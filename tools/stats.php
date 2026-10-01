<?php
/**
 * Statistiques de fréquentation, à partir des journaux du serveur.
 *
 * Ne dépose AUCUN cookie, ne charge aucun script tiers, n'envoie rien à
 * personne : tout est calculé sur place à partir de ce qu'Apache écrit déjà
 * dans ~/ik-logs/. C'est ce qui dispense le site de bandeau de consentement.
 *
 * Déployé dans /sites/schumpf-avocat.com/outils/ — dossier renvoyé en 404 par
 * le .htaccess, donc injoignable depuis le web. S'exécute en ligne de
 * commande, depuis la console SSH :
 *
 *   php ~/sites/schumpf-avocat.com/outils/stats.php
 *   php ~/sites/schumpf-avocat.com/outils/stats.php --jours=90
 *   php ~/sites/schumpf-avocat.com/outils/stats.php --jours=7 --robots
 *
 * Les adresses IP ne servent qu'à regrouper les visites en session, le temps
 * du calcul ; aucune n'est affichée ni conservée. Infomaniak fait tourner ses
 * journaux au bout d'un mois environ : au-delà, il n'y a plus de données.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

$options = getopt('', ['jours::', 'robots', 'logs::']);
$jours = max(1, (int) ($options['jours'] ?? 30));
$avecRobots = isset($options['robots']);
$dossierLogs = $options['logs'] ?? getenv('HOME') . '/ik-logs';

/** Une visite isolée de plus de 30 min ouvre une nouvelle session. */
const INACTIVITE_SESSION = 1800;

/** Motifs d'agents automatisés — exclus par défaut des comptages. */
const MOTIFS_ROBOTS = [
    'bot', 'crawl', 'spider', 'slurp', 'facebookexternalhit', 'bingpreview',
    'read-aloud', 'headlesschrome', 'python-requests', 'curl/', 'wget',
    'monitoring', 'uptime', 'pingdom', 'lighthouse', 'preview', 'scrapy',
];

/** Un hôte référent reconnu comme moteur de recherche. */
const MOTEURS = ['google', 'bing', 'duckduckgo', 'yahoo', 'ecosia', 'qwant', 'brave'];

/** Un hôte référent reconnu comme réseau social. */
const RESEAUX = ['linkedin', 'facebook', 'instagram', 't.co', 'twitter', 'x.com'];

function estRobot(string $ua): bool
{
    $ua = strtolower($ua);
    foreach (MOTIFS_ROBOTS as $motif) {
        if (str_contains($ua, $motif)) {
            return true;
        }
    }
    return $ua === '' || $ua === '-';
}

/** Classe un référent en canal d'acquisition. */
function canal(string $referent): string
{
    if ($referent === '' || $referent === '-') {
        return 'Accès direct';
    }
    $hote = strtolower((string) parse_url($referent, PHP_URL_HOST));
    if ($hote === '' || str_contains($hote, 'schumpf-avocat.com')) {
        return 'Navigation interne';
    }
    foreach (MOTEURS as $moteur) {
        if (str_contains($hote, $moteur)) {
            return 'Recherche — ' . ucfirst(explode('.', $moteur)[0]);
        }
    }
    foreach (RESEAUX as $reseau) {
        if (str_contains($hote, $reseau)) {
            return 'Réseau social — ' . ucfirst(explode('.', $reseau)[0]);
        }
    }
    return 'Site référent — ' . $hote;
}

/** Ouvre un journal, qu'il soit compressé ou non. */
function ouvrir(string $fichier)
{
    return str_ends_with($fichier, '.gz') ? gzopen($fichier, 'rb') : fopen($fichier, 'rb');
}

function lire($flux, string $fichier): string|false
{
    return str_ends_with($fichier, '.gz') ? gzgets($flux) : fgets($flux);
}

/* ------------------------------------------------------------- lecture */

if (!is_dir($dossierLogs)) {
    fwrite(STDERR, "Dossier de journaux introuvable : {$dossierLogs}\n");
    fwrite(STDERR, "Préciser son emplacement avec --logs=/chemin/vers/ik-logs\n");
    exit(1);
}

$fichiers = glob($dossierLogs . '/access.log*') ?: [];
if (!$fichiers) {
    fwrite(STDERR, "Aucun journal access.log dans {$dossierLogs}\n");
    exit(1);
}

$depuis = time() - $jours * 86400;
$motif = '/^(\S+) (\S+) \S+ \S+ \[([^\]]+)\] "(\S+) ([^" ]*)[^"]*" (\d{3}) (\S+) "([^"]*)" "([^"]*)"/';

$pages = $canaux = $parJour = $conversions = [];
$vues = $lignesLues = $ignorees = $robotsVus = 0;
$visites = [];   // clé visiteur => liste d'horodatages
$erreurs = [];

foreach ($fichiers as $fichier) {
    $flux = @ouvrir($fichier);
    if (!$flux) {
        continue;
    }
    while (($ligne = lire($flux, $fichier)) !== false) {
        $lignesLues++;
        if (!preg_match($motif, $ligne, $m)) {
            $ignorees++;
            continue;
        }
        [, , $ip, $dateTexte, $methode, $chemin, $statut, , $referent, $ua] = $m;

        // Format Apache « 20/Aug/2026:16:00:21 +0200 », fuseau compris.
        $date = DateTime::createFromFormat('d/M/Y:H:i:s O', $dateTexte);
        if ($date === false) {
            $ignorees++;
            continue;
        }
        $horodatage = $date->getTimestamp();
        if ($horodatage < $depuis) {
            continue;
        }

        $robot = estRobot($ua);
        if ($robot) {
            $robotsVus++;
            if (!$avecRobots) {
                continue;
            }
        }

        // Conversion : une demande de rendez-vous réellement transmise.
        if ($methode === 'POST' && str_contains($chemin, '/api/rdv.php')) {
            $jour = date('Y-m-d', $horodatage);
            if ((int) $statut === 303) {
                $conversions[$jour] = ($conversions[$jour] ?? 0) + 1;
            } else {
                $erreurs[] = date('d/m H:i', $horodatage) . ' — HTTP ' . $statut;
            }
            continue;
        }

        // Seules les pages comptent comme vue ; images, CSS et JS sont du décor.
        if (!preg_match('/(\.html|\/)$/', $chemin) || (int) $statut >= 400) {
            continue;
        }

        $vues++;
        $pages[$chemin] = ($pages[$chemin] ?? 0) + 1;
        $parJour[date('Y-m-d', $horodatage)] = ($parJour[date('Y-m-d', $horodatage)] ?? 0) + 1;
        $visites[md5($ip . $ua)][] = [$horodatage, canal($referent)];
    }
    str_ends_with($fichier, '.gz') ? gzclose($flux) : fclose($flux);
}

/* ------------------------------------------- sessions et visiteurs uniques */

// Le canal d'une session est celui de sa PREMIÈRE page : c'est par là que le
// visiteur est arrivé. Compter le référent de chaque vue ferait de la
// navigation interne (page → page) la première « source » du site, ce qui ne
// dit rien sur l'origine des visites.
$sessions = 0;
foreach ($visites as $vuesVisiteur) {
    sort($vuesVisiteur);
    $sessions++;
    $canaux[$vuesVisiteur[0][1]] = ($canaux[$vuesVisiteur[0][1]] ?? 0) + 1;
    for ($i = 1, $n = count($vuesVisiteur); $i < $n; $i++) {
        if ($vuesVisiteur[$i][0] - $vuesVisiteur[$i - 1][0] > INACTIVITE_SESSION) {
            $sessions++;
            $canaux[$vuesVisiteur[$i][1]] = ($canaux[$vuesVisiteur[$i][1]] ?? 0) + 1;
        }
    }
}
$visiteurs = count($visites);
$totalConversions = array_sum($conversions);

/* ---------------------------------------------------------------- rapport */

$ligne = str_repeat('─', 64);
$titre = fn(string $t) => "\n\033[1m{$t}\033[0m\n{$ligne}\n";

echo $titre("Fréquentation — schumpf-avocat.com · {$jours} derniers jours");
printf("  %-28s %s\n", 'Visiteurs', number_format($visiteurs, 0, ',', ' '));
printf("  %-28s %s\n", 'Sessions', number_format($sessions, 0, ',', ' '));
printf("  %-28s %s\n", 'Pages vues', number_format($vues, 0, ',', ' '));
printf("  %-28s %s\n", 'Pages par session', $sessions ? number_format($vues / $sessions, 1, ',', ' ') : '—');

echo $titre('Demandes de rendez-vous');
if ($totalConversions) {
    printf("  %-28s \033[1m%d\033[0m\n", 'Demandes transmises', $totalConversions);
    printf("  %-28s %s %%\n", 'Taux de conversion', $sessions
        ? number_format($totalConversions / $sessions * 100, 2, ',', ' ')
        : '—');
    echo "\n";
    krsort($conversions);
    foreach (array_slice($conversions, 0, 10, true) as $jour => $n) {
        printf("  %s   %s\n", date('d/m/Y', strtotime($jour)), str_repeat('▪', $n) . " ({$n})");
    }
} else {
    echo "  Aucune demande sur la période.\n";
}
if ($erreurs) {
    echo "\n  \033[31mSoumissions en échec (à examiner) :\033[0m\n";
    foreach (array_slice($erreurs, -5) as $e) {
        echo "    {$e}\n";
    }
}

echo $titre('Pages les plus consultées');
arsort($pages);
foreach (array_slice($pages, 0, 12, true) as $chemin => $n) {
    printf("  %6s  %s\n", number_format($n, 0, ',', ' '), $chemin);
}

echo $titre("Canaux d'acquisition (origine des sessions)");
arsort($canaux);
$totalCanaux = array_sum($canaux) ?: 1;
foreach (array_slice($canaux, 0, 10, true) as $nom => $n) {
    printf("  %6s  %5s %%  %s\n",
        number_format($n, 0, ',', ' '),
        number_format($n / $totalCanaux * 100, 1, ',', ' '),
        $nom);
}

echo $titre('Évolution par jour');
ksort($parJour);
$pic = $parJour ? max($parJour) : 1;
foreach (array_slice($parJour, -21, 21, true) as $jour => $n) {
    printf("  %s  %-30s %s\n",
        date('d/m', strtotime($jour)),
        str_repeat('█', max(1, (int) round($n / $pic * 30))),
        number_format($n, 0, ',', ' '));
}

echo "\n{$ligne}\n";
printf("  %s lignes lues · %s écartées (format) · %s requêtes de robots %s\n",
    number_format($lignesLues, 0, ',', ' '),
    number_format($ignorees, 0, ',', ' '),
    number_format($robotsVus, 0, ',', ' '),
    $avecRobots ? 'incluses' : 'exclues');
echo "  Aucun cookie, aucun traceur : tout vient des journaux du serveur.\n\n";

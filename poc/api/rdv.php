<?php
/**
 * Traitement du formulaire de premier rendez-vous (diagnostic.html).
 *
 * Reçoit le POST du formulaire, valide, envoie la demande par e-mail au
 * cabinet, puis redirige le prospect vers merci.html. Tourne sur
 * l'hébergement Infomaniak : aucune donnée de prospect ne transite par un
 * service tiers.
 *
 * L'envoi passe par SMTP authentifié — la fonction mail() de PHP est
 * désactivée sur les hébergements Infomaniak récents (constaté en production
 * le 20/08/2026 : "Call to undefined function mail()"). Les identifiants SMTP
 * ne sont PAS dans ce fichier ni dans le dépôt (public) : ils vivent dans
 * config/rdv.secrets.php, créé une fois à la main sur le serveur, jamais
 * touché par les déploiements (exclu du miroir FTP), et inaccessible depuis
 * le web (.htaccess). Modèle : deploy/rdv.secrets.exemple.php.
 *
 * Anti-spam : champ pot-de-miel « site_web », invisible pour un humain.
 * Rempli = robot : redirection vers la confirmation SANS envoi.
 *
 * Sécurité : la seule donnée utilisateur placée dans un en-tête est l'adresse
 * du prospect (Reply-To), validée par FILTER_VALIDATE_EMAIL qui rejette les
 * retours à la ligne — ce qui bloque l'injection d'en-têtes. Tout le reste ne
 * va que dans le corps, retours à la ligne neutralisés, longueurs plafonnées.
 * Le corps est protégé du « dot-stuffing » SMTP.
 *
 * Test local sans serveur mail réel : pointer config/rdv.secrets.php vers un
 * SMTP factice avec 'chiffrement' => 'aucun' (voir le fichier exemple).
 */

const DESTINATAIRE = 'coralie.schumpf@schumpf-avocat.com';
const PAGE_MERCI   = '../merci.html';
const PAGE_FORM    = '../diagnostic.html';
const CONFIG       = __DIR__ . '/../config/rdv.secrets.php';

const SUJETS = [
  'rupture' => 'Rupture / licenciement',
  'urssaf'  => 'URSSAF',
  'penal'   => 'Pénal du travail',
  'atmp'    => 'AT / MP · faute inexcusable',
  'cse'     => 'CSE · négociation',
  'autre'   => 'Autre',
];

date_default_timezone_set('Europe/Paris');

/** Neutralise les retours à la ligne et plafonne la longueur. */
function champ(string $nom, int $max = 200): string
{
    $valeur = isset($_POST[$nom]) && is_string($_POST[$nom]) ? $_POST[$nom] : '';
    $valeur = str_replace(["\r", "\n"], ' ', trim($valeur));
    return mb_substr($valeur, 0, $max);
}

function rediriger(string $ou): never
{
    header('Location: ' . $ou, true, 303);
    exit;
}

/** Page d'erreur minimale, autonome. Jamais de détail technique au visiteur. */
function erreur(string $message, int $code = 422): never
{
    http_response_code($code);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!doctype html><html lang="fr"><head><meta charset="utf-8">'
        . '<meta name="viewport" content="width=device-width,initial-scale=1">'
        . '<meta name="robots" content="noindex">'
        . '<title>Formulaire non envoyé</title></head>'
        . '<body style="font-family:system-ui,sans-serif;background:#F6F1E9;color:#1E1E1E;'
        . 'display:grid;place-items:center;min-height:100vh;margin:0;padding:24px;text-align:center">'
        . '<div><h1 style="font-weight:500">Le formulaire n’a pas pu être envoyé</h1>'
        . '<p>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>'
        . '<p><a href="' . PAGE_FORM . '" style="color:#7E1F2A">Revenir au formulaire</a>'
        . ' ou appeler le cabinet : <a href="tel:+33769004558" style="color:#7E1F2A">07 69 00 45 58</a></p>'
        . '</div></body></html>';
    exit;
}

/**
 * Client SMTP minimal : EHLO, AUTH LOGIN, MAIL FROM, RCPT TO, DATA, QUIT.
 * SMTPS implicite (port 465) via ssl:// ; 'aucun' réservé aux tests locaux.
 * Lève une RuntimeException à la moindre réponse inattendue.
 */
function smtp_envoyer(array $cfg, string $destinataire, string $entetes, string $corps): void
{
    $prefixe = ($cfg['chiffrement'] ?? 'ssl') === 'aucun' ? 'tcp://' : 'ssl://';
    $flux = @stream_socket_client(
        $prefixe . $cfg['hote'] . ':' . $cfg['port'],
        $errno,
        $errstr,
        10
    );
    if ($flux === false) {
        throw new RuntimeException("connexion SMTP impossible ($errstr)");
    }
    stream_set_timeout($flux, 10);

    $lire = function () use ($flux): string {
        $code = '';
        do {
            $ligne = fgets($flux, 1024);
            if ($ligne === false) {
                throw new RuntimeException('le serveur SMTP a coupé la connexion');
            }
            $code = substr($ligne, 0, 3);
        } while (isset($ligne[3]) && $ligne[3] === '-'); // réponses multilignes
        return $code;
    };
    $dire = function (string $cmd, string $attendu) use ($flux, $lire): void {
        fwrite($flux, $cmd . "\r\n");
        $code = $lire();
        if ($code !== $attendu) {
            // Le détail (commande, identifiants) ne sort jamais d'ici.
            throw new RuntimeException("réponse SMTP $code au lieu de $attendu");
        }
    };

    if ($lire() !== '220') {
        throw new RuntimeException('accueil SMTP inattendu');
    }
    $dire('EHLO schumpf-avocat.com', '250');
    if (($cfg['utilisateur'] ?? '') !== '') {
        fwrite($flux, "AUTH LOGIN\r\n");
        if ($lire() !== '334') {
            throw new RuntimeException('AUTH LOGIN refusé');
        }
        fwrite($flux, base64_encode($cfg['utilisateur']) . "\r\n");
        if ($lire() !== '334') {
            throw new RuntimeException('utilisateur SMTP refusé');
        }
        fwrite($flux, base64_encode($cfg['mot_de_passe']) . "\r\n");
        if ($lire() !== '235') {
            throw new RuntimeException('authentification SMTP refusée');
        }
    }
    $dire('MAIL FROM:<' . $cfg['expediteur'] . '>', '250');
    $dire('RCPT TO:<' . $destinataire . '>', '250');
    fwrite($flux, "DATA\r\n");
    if ($lire() !== '354') {
        throw new RuntimeException('DATA refusé');
    }
    // Dot-stuffing : une ligne du message commençant par "." serait sinon
    // interprétée comme la fin des données.
    $message = $entetes . "\r\n\r\n" . str_replace("\n", "\r\n", $corps);
    $message = preg_replace('/^\./m', '..', $message);
    fwrite($flux, $message . "\r\n.\r\n");
    if ($lire() !== '250') {
        throw new RuntimeException('message refusé après DATA');
    }
    fwrite($flux, "QUIT\r\n");
    fclose($flux);
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    rediriger(PAGE_FORM);
}

// Pot-de-miel : rempli = robot. On fait comme si tout s'était bien passé.
if (champ('site_web') !== '') {
    rediriger(PAGE_MERCI);
}

$nom        = champ('nom', 120);
$entreprise = champ('entreprise', 120);
$email      = champ('email', 200);
$telephone  = champ('telephone', 40);
$effectif   = champ('effectif', 60);
$urgence    = champ('urgence', 80);
$contexte   = isset($_POST['contexte']) && is_string($_POST['contexte'])
    ? mb_substr(trim($_POST['contexte']), 0, 4000)
    : '';
$sujet      = SUJETS[champ('sujet', 20)] ?? SUJETS['autre'];

if ($nom === '' || $entreprise === '') {
    erreur('Le nom et l’entreprise sont nécessaires pour vous recontacter.');
}
$email = filter_var($email, FILTER_VALIDATE_EMAIL);
if ($email === false) {
    erreur('L’adresse e-mail semble invalide — c’est elle qui permet de vous proposer un créneau.');
}

if (!is_file(CONFIG)) {
    erreur('L’envoi en ligne est momentanément indisponible. Appelez le cabinet, ou réessayez plus tard.', 503);
}
$cfg = require CONFIG;

$objet = mb_encode_mimeheader(
    'Premier rendez-vous — ' . $sujet . ' · ' . ($urgence !== '' ? $urgence : 'urgence non précisée'),
    'UTF-8',
    'B'
);

$corps = implode("\n", [
    'Nouvelle demande de premier rendez-vous, envoyée depuis le site.',
    '',
    'Sujet       : ' . $sujet,
    'Urgence     : ' . $urgence,
    '',
    'Nom         : ' . $nom,
    'Entreprise  : ' . $entreprise,
    'Effectif    : ' . $effectif,
    'E-mail      : ' . $email,
    'Téléphone   : ' . ($telephone !== '' ? $telephone : 'non renseigné'),
    '',
    'Contexte :',
    $contexte !== '' ? $contexte : '(non renseigné)',
    '',
    '—',
    'Répondre à ce message écrit directement au prospect (Reply-To).',
    'Reçu le ' . date('d/m/Y à H:i') . '.',
]);

$entetes = implode("\r\n", [
    'From: Site du cabinet <' . $cfg['expediteur'] . '>',
    'To: ' . DESTINATAIRE,
    'Reply-To: ' . $email,
    'Subject: ' . $objet,
    'Date: ' . date(DATE_RFC2822),
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=utf-8',
    'Content-Transfer-Encoding: 8bit',
]);

try {
    smtp_envoyer($cfg, DESTINATAIRE, $entetes, $corps);
} catch (Throwable $e) {
    // Trace côté serveur (logs Infomaniak), message neutre côté visiteur.
    error_log('rdv.php : ' . $e->getMessage());
    erreur('L’envoi a échoué de notre côté. Appelez le cabinet ou réessayez dans quelques minutes.', 500);
}

// Accusé de réception au prospect. Contenu volontairement quasi fixe : seule
// donnée saisie reprise, le libellé du sujet — qui sort d'une liste fermée
// (SUJETS). Ni le nom ni le contexte libre ne sont réinjectés, pour que ce
// mail, envoyé vers une adresse fournie par le visiteur, ne puisse pas servir
// à relayer un texte arbitraire.
// Son échec ne fait pas échouer la demande : le cabinet a déjà reçu le
// message, c'est lui qui compte. On journalise et on continue.
$objetAr = mb_encode_mimeheader('Votre demande de rendez-vous — Cabinet Coralie Schumpf', 'UTF-8', 'B');

$corpsAr = implode("\n", [
    'Bonjour,',
    '',
    'Votre demande de premier rendez-vous (' . $sujet . ') est bien arrivée au',
    'Cabinet Coralie Schumpf.',
    '',
    'Un créneau vous sera proposé sous 48 heures ouvrées, par e-mail ou par',
    'téléphone, à partir des informations transmises. Elles sont',
    'confidentielles et couvertes par le secret professionnel de l’avocat.',
    '',
    'Si le sujet ne peut pas attendre : 07 69 00 45 58.',
    '',
    'Cabinet Coralie Schumpf',
    'Avocate au Barreau de Metz — au service exclusif des employeurs',
    '4 rue Paul Langevin · 57070 Metz',
    'https://schumpf-avocat.com',
    '',
    '—',
    'Message automatique envoyé suite à une demande déposée sur',
    'schumpf-avocat.com le ' . date('d/m/Y à H:i') . '. Si vous n’êtes pas à',
    'l’origine de cette demande, ignorez simplement ce message.',
]);

$entetesAr = implode("\r\n", [
    'From: Cabinet Coralie Schumpf <' . $cfg['expediteur'] . '>',
    'To: ' . $email,
    'Reply-To: ' . DESTINATAIRE,
    'Subject: ' . $objetAr,
    'Date: ' . date(DATE_RFC2822),
    'Auto-Submitted: auto-replied',
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=utf-8',
    'Content-Transfer-Encoding: 8bit',
]);

try {
    smtp_envoyer($cfg, $email, $entetesAr, $corpsAr);
} catch (Throwable $e) {
    error_log('rdv.php (accusé de réception) : ' . $e->getMessage());
}

rediriger(PAGE_MERCI);

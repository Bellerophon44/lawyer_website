<?php
/**
 * Traitement du formulaire de premier rendez-vous (diagnostic.html).
 *
 * Reçoit le POST du formulaire, valide, puis envoie la demande par e-mail au
 * cabinet et redirige le prospect vers la page de confirmation merci.html.
 * Tourne sur l'hébergement Infomaniak lui-même : aucune donnée de prospect ne
 * transite par un service tiers.
 *
 * Anti-spam : champ pot-de-miel « site_web », invisible pour un humain
 * (masqué en CSS), que les robots remplissent. S'il est rempli, on redirige
 * vers la confirmation SANS rien envoyer — le robot croit avoir réussi.
 *
 * Sécurité : la seule donnée utilisateur placée dans un en-tête est l'adresse
 * du prospect (Reply-To), validée par FILTER_VALIDATE_EMAIL qui rejette les
 * retours à la ligne — ce qui bloque l'injection d'en-têtes. Tout le reste ne
 * va que dans le corps du message, retours à la ligne neutralisés et longueur
 * plafonnée.
 *
 * Test local (aucun envoi réel) :
 *   RDV_TEST_FICHIER=/tmp/mail.txt php -S 127.0.0.1:8088
 * Le message composé est alors écrit dans ce fichier au lieu d'être envoyé.
 */

const DESTINATAIRE = 'coralie.schumpf@schumpf-avocat.com';
const EXPEDITEUR   = 'no-reply@schumpf-avocat.com';
const PAGE_MERCI   = '../merci.html';
const PAGE_FORM    = '../diagnostic.html';

const SUJETS = [
  'rupture' => 'Rupture / licenciement',
  'urssaf'  => 'URSSAF',
  'penal'   => 'Pénal du travail',
  'atmp'    => 'AT / MP · faute inexcusable',
  'cse'     => 'CSE · négociation',
  'autre'   => 'Autre',
];

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

/** Page d'erreur minimale, autonome (cas rare : le navigateur valide déjà). */
function erreur(string $message): never
{
    http_response_code(422);
    header('Content-Type: text/html; charset=utf-8');
    echo '<!doctype html><html lang="fr"><head><meta charset="utf-8">'
        . '<meta name="viewport" content="width=device-width,initial-scale=1">'
        . '<meta name="robots" content="noindex">'
        . '<title>Formulaire incomplet</title></head>'
        . '<body style="font-family:system-ui,sans-serif;background:#F6F1E9;color:#1E1E1E;'
        . 'display:grid;place-items:center;min-height:100vh;margin:0;padding:24px;text-align:center">'
        . '<div><h1 style="font-weight:500">Le formulaire n’a pas pu être envoyé</h1>'
        . '<p>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>'
        . '<p><a href="' . PAGE_FORM . '" style="color:#7E1F2A">Revenir au formulaire</a>'
        . ' ou appeler le cabinet : <a href="tel:+33769004558" style="color:#7E1F2A">07 69 00 45 58</a></p>'
        . '</div></body></html>';
    exit;
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
    'From: Site du cabinet <' . EXPEDITEUR . '>',
    'Reply-To: ' . $email,
    'MIME-Version: 1.0',
    'Content-Type: text/plain; charset=utf-8',
    'Content-Transfer-Encoding: 8bit',
]);

$fichierTest = getenv('RDV_TEST_FICHIER');
if ($fichierTest !== false && $fichierTest !== '') {
    file_put_contents(
        $fichierTest,
        "To: " . DESTINATAIRE . "\nSubject: {$objet}\n{$entetes}\n\n{$corps}\n",
        FILE_APPEND
    );
    rediriger(PAGE_MERCI);
}

$envoye = mail(DESTINATAIRE, $objet, $corps, $entetes, '-f ' . EXPEDITEUR);
if (!$envoye) {
    erreur('L’envoi a échoué de notre côté. Appelez le cabinet ou réessayez dans quelques minutes.');
}

rediriger(PAGE_MERCI);

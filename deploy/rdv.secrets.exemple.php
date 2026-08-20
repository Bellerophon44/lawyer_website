<?php
/**
 * Modèle du fichier d'identifiants SMTP du formulaire de rendez-vous.
 *
 * À copier UNE FOIS sur le serveur, en remplissant les valeurs :
 *   /sites/schumpf-avocat.com/config/rdv.secrets.php
 *
 * Ce fichier-modèle est versionné ; le vrai fichier, avec le mot de passe,
 * ne doit JAMAIS entrer dans le dépôt (public). Il est protégé trois fois :
 *   - hors du miroir FTP (deploy/upload.sh l'exclut : les déploiements ne
 *     l'écrasent ni ne le suppriment) ;
 *   - inaccessible depuis le web (deploy/htaccess renvoie 404 sur /config/) ;
 *   - même servi par erreur, un fichier .php s'exécute et n'affiche rien.
 *
 * Valeurs pour un envoi via Infomaniak : créer une adresse dédiée à l'envoi
 * (par exemple site@schumpf-avocat.com) dans le Service Mail, et utiliser
 * cette adresse comme utilisateur ET comme expéditeur.
 */

return [
  'hote'         => 'mail.infomaniak.com',
  'port'         => 465,
  'chiffrement'  => 'ssl',          // 'aucun' uniquement pour un test local
  'utilisateur'  => 'site@schumpf-avocat.com',
  'mot_de_passe' => 'A-REMPLACER',
  'expediteur'   => 'site@schumpf-avocat.com',
];

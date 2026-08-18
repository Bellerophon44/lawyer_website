#!/usr/bin/env bash
# Envoie dist/ vers l'hébergement Infomaniak par FTPS.
#
# Utilisé aussi bien en local que par .github/workflows/deploy.yml, pour que le
# déploiement manuel et le déploiement CI aient exactement le même comportement.
#
# Variables requises :
#   FTP_HOST  hôte FTP Infomaniak (Manager > Hébergement Web > FTP / SSH)
#   FTP_USER  utilisateur FTP
#   FTP_PASS  mot de passe FTP
#
# Variables optionnelles :
#   FTP_DIR   dossier cible sur le serveur (défaut : /sites/schumpf-avocat.com)
#   DELETE    à 1 pour supprimer côté serveur les fichiers absents de dist/
#             (désactivé par défaut : préserve index_old.html et les sauvegardes)
#   DRY_RUN   à 1 pour afficher les transferts sans rien écrire
#
# Exemple :
#   FTP_HOST=... FTP_USER=... FTP_PASS=... ./deploy/upload.sh

set -euo pipefail

cd "$(dirname "$0")/.."

: "${FTP_HOST:?FTP_HOST manquant}"
: "${FTP_USER:?FTP_USER manquant}"
: "${FTP_PASS:?FTP_PASS manquant}"
FTP_DIR="${FTP_DIR:-/sites/schumpf-avocat.com}"

if [[ ! -d dist ]]; then
  echo "dist/ absent — lance d'abord : node tools/build.mjs" >&2
  exit 1
fi

MIRROR_FLAGS=(--reverse --verbose --parallel=4)

# Ne jamais écraser ni supprimer les sauvegardes et l'état FTP côté serveur.
for pattern in 'index_old*' 'index_backup*' 'index-wix*' '.well-known/'; do
  MIRROR_FLAGS+=("--exclude-glob=$pattern")
done

[[ "${DELETE:-0}" == "1" ]] && MIRROR_FLAGS+=(--delete)
[[ "${DRY_RUN:-0}" == "1" ]] && MIRROR_FLAGS+=(--dry-run)

echo "Déploiement de dist/ vers ${FTP_HOST}:${FTP_DIR}"
[[ "${DELETE:-0}" == "1" ]] && echo "  (mode --delete actif)"
[[ "${DRY_RUN:-0}" == "1" ]] && echo "  (simulation, aucune écriture)"

# lftp découpe lui-même la chaîne qu'on lui passe : les valeurs doivent être
# citées pour son parseur, pas seulement pour bash. Le guillemet simple ne suffit
# pas — un mot de passe contenant une apostrophe fait échouer la connexion sans
# message clair. Le guillemet double protège tout, à condition d'échapper les
# antislashs et les guillemets. Vérifié sur des mots de passe contenant
# apostrophe, guillemet, antislash, espace, virgule et dollar.
lftp_quote() {
  local s=${1//\\/\\\\}
  printf '"%s"' "${s//\"/\\\"}"
}

lftp -c "
set ftp:ssl-force true
set ftp:ssl-protect-data true
set net:max-retries 3
set net:timeout 20
open -u $(lftp_quote "$FTP_USER"),$(lftp_quote "$FTP_PASS") $(lftp_quote "$FTP_HOST")
mirror ${MIRROR_FLAGS[*]} dist/ $(lftp_quote "$FTP_DIR")
bye
"

echo "Terminé. Vérifie https://schumpf-avocat.com en navigation privée."

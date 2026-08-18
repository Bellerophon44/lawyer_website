# Hébergement & mises à jour — schumpf-avocat.com

Document de référence pour l'administration technique du site après la sortie de
Wix. Il complète le guide de transfert PDF (version du 04/07/2026) en décrivant
ce qui est désormais automatisé dans ce dépôt.

---

## 1. Qui fait quoi

| Élément | Prestataire | Où l'administrer |
|---|---|---|
| Nom de domaine `schumpf-avocat.com` | **Wix** (registraire) | Wix > Domaines > DNS |
| Zone DNS | **Wix** | Wix > Domaines > DNS |
| Hébergement des fichiers du site | **Infomaniak** (Hébergement Web, Apache/PHP) | [ksuite.infomaniak.com](https://ksuite.infomaniak.com) > Hébergement Web |
| Certificat SSL | **Infomaniak** (Let's Encrypt gratuit) | Manager > Hébergement Web > Certificat SSL |
| Emails | inchangé (MX/SPF/DKIM/DMARC restés dans Wix) | Wix > Domaines > DNS |
| Code source du site | **ce dépôt Git** | GitHub |

Le domaine est donc **encore chez Wix**, mais le site n'est plus servi par Wix :
les DNS Wix pointent vers l'IP Infomaniak.

### Infomaniak en deux mots

Hébergeur suisse (Genève), équivalent d'OVH ou Gandi. Ses données restent en
Suisse, ce qui est un argument RGPD confortable pour un cabinet d'avocats. Son
interface s'appelle **kSuite / Manager** (`ksuite.infomaniak.com`). Trois choses
à connaître pour ce dossier :

- **Hébergement Web** — un serveur Apache classique avec PHP. Il sert des
  fichiers déposés dans un dossier. Pas de CMS, pas d'éditeur visuel : ce n'est
  pas Wix, on n'édite pas les pages dans le navigateur.
- **FTP / SSH** — le moyen de déposer les fichiers. Le Manager propose un
  **Web FTP** (glisser-déposer dans le navigateur, < 50 Mo) et des identifiants
  FTP/SFTP classiques pour les outils automatisés.
- **Certificat SSL** — Let's Encrypt gratuit, installable en un clic depuis le
  Manager, à condition que les DNS pointent déjà vers Infomaniak.

Ce qu'Infomaniak **ne fait pas** : versionner le site, garder un historique des
modifications, permettre de revenir en arrière. C'est le rôle de ce dépôt Git.

---

## 2. Configuration cible (rappel du guide)

| Paramètre | Valeur |
|---|---|
| IPv4 Infomaniak | `185.176.225.11` (à revérifier dans Manager > onglet *Informations*) |
| Dossier serveur | `/sites/schumpf-avocat.com` |
| Fichier d'accueil | `index.html` |
| Adresse canonique | `https://schumpf-avocat.com` (apex, sans `www`) |

Enregistrements DNS à avoir dans Wix :

| Type | Nom / Host | Valeur | Remarque |
|---|---|---|---|
| A | vide ou `@` | `185.176.225.11` | Wix demande de laisser le champ *vide* quand la doc d'un tiers dit `@` |
| CNAME | `www` | `schumpf-avocat.com` | remplace l'ancien `cdn1.wixdns.net` |
| MX / TXT | messagerie | **ne pas toucher** | emails et délivrabilité |

À supprimer si encore présents : les anciens A records Wix `185.230.63.171`,
`185.230.63.186`, `185.230.63.107`. Des A records contradictoires font
apparaître tantôt l'ancien site, tantôt le nouveau.

**Vérification rapide** (depuis un terminal) :

```bash
dig +short schumpf-avocat.com      # doit renvoyer uniquement 185.176.225.11
dig +short www.schumpf-avocat.com  # doit résoudre vers la même IP
curl -sI https://schumpf-avocat.com | head -1
```

---

## 3. Où est le code

Deux emplacements, avec une hiérarchie claire :

- **Ce dépôt Git = la source de vérité.** Les pages sources sont dans `poc/`.
  Toute modification part d'ici.
- **Le serveur Infomaniak = une copie déployée.** `/sites/schumpf-avocat.com`
  ne contient que le résultat d'un build. On n'y édite jamais un fichier
  directement : la modification serait perdue au déploiement suivant et
  n'existerait dans aucun historique.

Reprendre le contrôle depuis ce dépôt ne demande rien côté Infomaniak, hormis
des identifiants FTP. Le site en ligne est un site statique : il n'y a aucune
base de données, aucun contenu piégé dans une interface propriétaire à
récupérer — contrairement à Wix.

### Pourquoi un build, et pas un upload direct de `poc/`

`poc/` est une maquette : sa page d'accueil s'appelle `home.html`, et son
`index.html` est un sommaire de démonstration. Uploader `poc/` tel quel
servirait le sommaire de maquette comme page d'accueil publique.

Le guide PDF recommande de renommer `home.html` en `index.html` avant l'upload
— c'est juste, mais insuffisant : **les pages du site contiennent 22 liens
internes vers `home.html`**, qui renverraient tous une 404 après le renommage.

`tools/build.mjs` fait la transformation complète :

| Source | Résultat dans `dist/` |
|---|---|
| `poc/home.html` | `index.html` |
| `poc/index.html` | écarté (sommaire de maquette) |
| `poc/diagnostic.html` | `diagnostic.html` |
| `poc/cabinet/`, `poc/expertises/`, `poc/assets/` | copiés tels quels |
| `deploy/htaccess` | `.htaccess` |
| — | `robots.txt` et `sitemap.xml` générés |

Il réécrit tous les liens `home.html` → `index.html`, puis contrôle que chaque
lien interne pointe vers un fichier qui existe réellement. Les `README.md` ne
partent pas en production.

---

## 4. Faire une mise à jour

### 4.1 Modifier le contenu

```bash
git checkout -b <ma-branche>
# éditer les fichiers dans poc/
node tools/build.mjs          # build + contrôle des liens
python3 -m http.server 8000 --directory dist   # relire sur http://localhost:8000
git commit -am "content: ..." && git push -u origin <ma-branche>
```

Le build affiche les liens cassés et le nombre de placeholders `href="#"`
restants. Un build qui signale un lien cassé ne doit pas être déployé.

### 4.2 Déployer

Deux voies, même script (`deploy/upload.sh`), donc même comportement.

**Depuis GitHub (recommandé)** — Actions > *Build & déploiement Infomaniak* >
*Run workflow*. Laisser `dry_run` coché une première fois pour voir la liste des
transferts, puis relancer décoché.

Deux prérequis, une seule fois.

D'abord, **le workflow doit se trouver sur la branche par défaut du dépôt**
(`claude/wizardly-johnson-OwjOM`) : GitHub n'affiche le bouton *Run workflow*
que pour les workflows présents sur cette branche. Tant que
`.github/workflows/deploy.yml` n'y est pas fusionné, le déclenchement manuel
est indisponible — seul le job de build tourne, sur chaque push.

Ensuite, dans Settings > Secrets and variables > Actions :

| Secret | Où le trouver |
|---|---|
| `FTP_HOST` | Manager Infomaniak > Hébergement Web > FTP / SSH |
| `FTP_USER` | idem |
| `FTP_PASS` | idem (créer un utilisateur FTP dédié au déploiement) |

Variable optionnelle `FTP_DIR` si le dossier cible diffère de
`/sites/schumpf-avocat.com`.

Chaque push lance le build et le contrôle des liens, mais **jamais** le
déploiement : la mise en ligne reste un geste manuel.

**Depuis un poste local** (si les identifiants ne doivent pas passer par
GitHub) :

```bash
node tools/build.mjs
FTP_HOST=... FTP_USER=... FTP_PASS=... DRY_RUN=1 ./deploy/upload.sh  # simulation
FTP_HOST=... FTP_USER=... FTP_PASS=... ./deploy/upload.sh            # envoi réel
```

Nécessite `lftp` (`brew install lftp` / `apt install lftp`).

**Depuis le Web FTP Infomaniak** (dépannage, sans outil installé) : Manager >
Hébergement Web > FTP / SSH > Web FTP, aller dans
`/sites/schumpf-avocat.com/`, et déposer le contenu de `dist/` — le *contenu*,
pas le dossier `dist` lui-même, sinon le site se retrouve sur
`/dist/index.html`.

### 4.3 Filet de sécurité

`deploy/upload.sh` ne supprime rien par défaut et exclut explicitement
`index_old*`, `index_backup*`, `index-wix*` : les sauvegardes présentes sur le
serveur survivent aux déploiements. Le nettoyage des fichiers orphelins se
demande explicitement avec `DELETE=1`.

**Où ranger une sauvegarde.** `/sites/schumpf-avocat.com` est la racine web :
tout ce qui s'y trouve est servi publiquement, y compris un dossier nommé
`backup`. Le 18/08/2026, `https://schumpf-avocat.com/backup/` répondait `200`
et donnait accès à une ancienne fiche avocate — page fantôme indexable, aux
liens morts.

Les sauvegardes ont leur place **hors de la racine web**, dans le `backups/`
qui existe déjà à la racine du compte :

```bash
mv ~/sites/schumpf-avocat.com/backup ~/backups/site-2026-07-03
```

`deploy/htaccess` renvoie par ailleurs un 404 sur `/backup/` et sur les
`index_old*` / `index_backup*`, en ceinture de sécurité. Un 404 plutôt qu'un
403, qui confirmerait leur existence. Ces fichiers restent sur le disque et
restent utilisables pour un rollback : ils ne sont simplement plus servis.

### 4.4 Vérifier après déploiement

- Ouvrir `https://schumpf-avocat.com` **en navigation privée** (le cache
  navigateur est la première cause de « je vois encore l'ancien site »).
- Tester depuis un mobile en 4G, pas en Wi-Fi.
- F12 > Console et Network : aucune 404 sur le CSS, le JS, les images.
- Cliquer téléphone, email, LinkedIn, Instagram.
- Vérifier `https://www.schumpf-avocat.com` (doit rediriger vers l'apex).

### 4.5 Revenir en arrière

Le rollback se fait par le dépôt, pas à la main sur le serveur :

```bash
git revert <sha>        # ou git checkout <sha-precedent> -- poc/
node tools/build.mjs
./deploy/upload.sh
```

Rollback d'urgence sans outil : dans le Web FTP, renommer `index.html` en
`index_new_pause.html` et `index_old.html` en `index.html`. **Ne pas toucher aux
DNS** si le problème vient du fichier HTML — les DNS ne se reconfigurent que si
l'hébergement Infomaniak lui-même est inutilisable.

---

## 5. À traiter avant de considérer le site fini

Par ordre de priorité.

1. **Formulaire du premier rendez-vous non fonctionnel.** Dans
   `poc/assets/js/main.js`, la soumission est interceptée et affiche
   `alert("POC — la prise de RDV serait envoyée ici…")`. Sur un site en ligne,
   un prospect qui remplit le formulaire ne déclenche donc **aucun** envoi, et
   lit le mot « POC ». À brancher sur une vraie destination (Calendly, Wix
   Bookings, ou à défaut un `mailto:` pré-rempli) avant toute promotion du site.
2. **Mentions légales, RGPD, cookies absents.** 25 liens `href="#"` restent dans
   les pages, dont *Mentions légales*, *RGPD*, *Cookies* et *Méthode &
   honoraires* dans le pied de page de chaque page. Les mentions légales et
   l'identité de l'hébergeur sont des obligations, renforcées par le RIN pour
   un avocat : nom du cabinet, adresse, barreau de Metz, directeur de
   publication, hébergeur (Infomaniak SA, Genève).
3. **SSL avant `.htaccess`.** `deploy/htaccess` force HTTPS et la redirection
   `www` → apex. Il ne doit être déployé qu'une fois le certificat installé sur
   les deux variantes du domaine, sinon le site paraît bloqué alors que les
   fichiers sont bons. En cas de doute, vérifier le cadenas sur les deux URL
   avant le premier déploiement.
4. **Polices Google externes.** Les six pages chargent Fraunces et Inter depuis
   `fonts.googleapis.com`. À auto-héberger dans `assets/fonts/` : performance,
   et un transfert d'IP vers Google en moins à déclarer côté RGPD.
5. **Balises `canonical` et Open Graph** absentes de toutes les pages. Utile
   maintenant que l'adresse canonique est arrêtée sur l'apex.
6. **Redirections depuis les anciennes URL Wix.** Les URL de l'ancien site
   indexées par Google renvoient une 404. Les identifier via
   `data/analytics/wix-traffic-pages-full-12m.csv` et ajouter les `Redirect 301`
   correspondants dans `deploy/htaccess`.
7. **Analytics.** L'instrumentation est simulée (`console.log` sur
   `data-track`). À remplacer par GA4 ou une alternative sans cookie
   (Plausible, Matomo), ce qui simplifie la bannière cookies.

---

## 6. Ce dont il faut garder trace

- Une capture de la zone DNS Wix, avant et après modification.
- Les identifiants FTP de déploiement (utilisateur dédié, pas le compte
  principal).
- L'IPv4 du site, à revérifier dans le Manager avant toute intervention DNS :
  elle peut changer lors d'une migration de serveur côté Infomaniak.

# Hébergement & mises à jour — schumpf-avocat.com

Document de référence pour l'administration technique du site après la sortie de
Wix. Il complète le guide de transfert PDF (version du 04/07/2026) en décrivant
ce qui est désormais automatisé dans ce dépôt.

---

## 1. Qui fait quoi

| Élément | Prestataire | Où l'administrer |
|---|---|---|
| Nom de domaine `schumpf-avocat.com` | **un registraire tiers, à identifier** (Wix affiche « géré par un tiers ») | à déterminer — `whois schumpf-avocat.com` donne le registraire et la date d'expiration |
| Zone DNS (A, MX, TXT…) | **Wix** (serveurs de noms `ns10/ns11.wixdns.net`, vérifié le 20/08/2026) | Wix > Paramètres > Domaines > ⋯ > Gérer les enregistrements DNS |
| Boîtes e-mail (`coralie.schumpf@…`) | **Google Workspace** (MX `aspmx.l.google.com`, vérifié le 20/08/2026) | admin.google.com |
| Adresse d'envoi du formulaire (`site@…`) | **Infomaniak** (Service Mail, émission seule) | Manager > Service Mail |
| Hébergement des fichiers du site | **Infomaniak** (Hébergement Web, Apache/PHP) | [ksuite.infomaniak.com](https://ksuite.infomaniak.com) > Hébergement Web |
| Certificat SSL | **Infomaniak** (Let's Encrypt gratuit) | Manager > Hébergement Web > Certificat SSL |
| Code source du site | **ce dépôt Git** | GitHub |

La zone DNS se gère donc dans Wix, mais le site n'est plus servi par Wix : le
A record pointe vers l'IP Infomaniak. Dans Wix, le bandeau rouge « votre
domaine pointe en dehors de Wix » décrit cet état voulu — ne pas cliquer
« Réessayer » ni « Transférer vers Wix », qui déferaient la migration.

⚠️ **Point à traiter un jour calme : identifier le registraire.** Le domaine
est enregistré chez un tiers inconnu — si ce compte est perdu et que le
domaine expire, le site et les e-mails tombent ensemble. Retrouver le
registraire (`whois`), le compte, et la date d'expiration.

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

**Depuis GitHub (recommandé)** — Actions > *Déploiement Infomaniak* >
*Run workflow*. Laisser `dry_run` coché une première fois pour voir la liste des
transferts, puis relancer décoché.

#### Deux workflows, deux rôles

Le dépôt en compte exactement deux, et la distinction est volontaire :

| Workflow | Déclenchement | Ce qu'il fait | Touche au site public |
|---|---|---|---|
| **Build & préversion** (`ci.yml`) | chaque push | build, contrôle des liens, publication de la préversion | **non, jamais** |
| **Déploiement Infomaniak** (`deploy-infomaniak.yml`) | manuel uniquement | build puis envoi FTPS | **oui** |

Ils étaient réunis à l'origine dans un seul fichier nommé « Build & déploiement
Infomaniak ». Le nom laissait croire qu'une fusion déclenchait une mise en
ligne, alors que le job d'envoi y était systématiquement sauté. Séparés, la
règle se lit dans la liste des Actions : **voir « Déploiement Infomaniak »
apparaître signifie toujours qu'un déploiement a réellement eu lieu.**

Aucun push, aucune fusion ne met le site public à jour. Jamais.

#### Prérequis, une seule fois

**Le workflow de déploiement doit se trouver sur la branche par défaut du
dépôt** (`claude/wizardly-johnson-OwjOM`) : GitHub n'affiche le bouton
*Run workflow* que pour les workflows présents sur cette branche. Tant que
`.github/workflows/deploy-infomaniak.yml` n'y est pas fusionné, le
déclenchement manuel est indisponible.

Puis, dans Settings > Secrets and variables > Actions, sur l'environnement
`production` (et non au niveau du dépôt : seul le job de déploiement déclare
cet environnement, le job de build n'a donc pas accès aux identifiants) :

| Secret | Où le trouver |
|---|---|
| `FTP_HOST` | Manager Infomaniak > Hébergement Web > FTP / SSH |
| `FTP_USER` | idem, **préfixe compris** (`wm06cn_…`) |
| `FTP_PASS` | idem (créer un utilisateur FTP dédié au déploiement) |

Variable `FTP_DIR` : `/` si l'utilisateur FTP est cloisonné sur le dossier du
site — c'est le cas ici. Sans cloisonnement, `/sites/schumpf-avocat.com`.

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

### 4.6 Faire relire avant de publier

Chaque push sur la branche par défaut publie une **préversion** sur GitHub
Pages : `https://bellerophon44.github.io/lawyer_website/`. C'est le lien à
envoyer à Coralie pour valider une modification avant qu'elle soit publique.
Rien à installer de son côté.

La préversion est construite par `node tools/build.mjs --preview`, à partir des
mêmes sources que la production. Le rendu est donc identique — c'est bien ce
qui sera publié qui est relu.

**Ce qu'elle ne teste pas.** GitHub Pages n'est pas Apache : le `.htaccess` y
serait inerte, donc il n'y est pas déposé. Redirection `www` → apex, forçage
HTTPS, règles 404 sur `/backup/` et `index_old*`, compression, en-têtes de
cache : rien de tout cela n'est exercé par la préversion. Ce sont précisément
les réglages les plus susceptibles de casser une mise en ligne, et ils ne se
vérifient qu'après déploiement, avec les `curl` du § 4.4.

**Pourquoi le `robots.txt` de la préversion autorise le crawl.** C'est
contre-intuitif mais délibéré. Un `Disallow: /` empêcherait Google de lire les
pages — donc de voir la balise `noindex` qu'elles portent — et il pourrait
malgré tout faire figurer les URL nues dans ses résultats, sans pouvoir les en
retirer. En laissant crawler, le `noindex` est lu et la préversion reste hors
de l'index. Ne pas « durcir » ce fichier en le passant en `Disallow`.

Aucune balise `canonical` n'est ajoutée non plus : Google demande de ne pas
combiner `noindex` et `canonical`, les deux signaux étant contradictoires.

---

### 4.7 Le formulaire de premier rendez-vous

**En service depuis le 20/08/2026, testé de bout en bout** : soumission sur le
site → e-mail dans la boîte Google de Coralie (boîte de réception, SPF et DKIM
valides), « Répondre » écrit au prospect.

Le flux : `diagnostic.html` poste vers `api/rdv.php` (PHP sur l'hébergement),
qui envoie la demande par SMTP authentifié via `mail.infomaniak.com` depuis
`site@schumpf-avocat.com`, avec Reply-To vers le prospect, puis redirige vers
`merci.html`. Pot-de-miel anti-spam. Aucune donnée de prospect ne transite par
un service tiers. La fonction `mail()` de PHP n'existe pas chez Infomaniak —
c'est pour cela que l'envoi est en SMTP.

**Les pièces, et où elles vivent :**

| Pièce | Où | Note |
|---|---|---|
| Identifiants SMTP | `/sites/schumpf-avocat.com/config/rdv.secrets.php`, sur le serveur uniquement | modèle : `deploy/rdv.secrets.exemple.php` ; exclu du miroir FTP, `/config/` en 404 web |
| Mot de passe utilisé | « mot de passe d'appareil » Infomaniak, appareil **« Site web — formulaire RDV » (sans utilisateur)** | Manager > Service Mail > `site@` > Appareil connecté ; le mot de passe principal de l'adresse ne fonctionne PAS en SMTP |
| Adresse d'envoi | `site@schumpf-avocat.com` | émission seule : les MX du domaine pointent vers Google, elle ne reçoit rien |
| SPF | TXT chez Wix : `v=spf1 include:_spf.google.com include:spf.infomaniak.ch ~all` | autorise Google (Coralie) ET Infomaniak (le site) — ne jamais le remplacer par la version « officielle » d'un seul des deux |
| DKIM | TXT `20260820._domainkey` chez Wix | clé publiée par Infomaniak |

**États normaux à ne pas « corriger » :** dans Infomaniak, le domaine reste
« partiellement connecté » — DKIM vert, SPF/MX/CNAME orange — parce que les MX
restent chez Google, c'est voulu. Dans Wix, le bandeau rouge « votre domaine
pointe en dehors de Wix » décrit la migration, pas une panne.

**Dépannage :** un échec d'envoi affiche au visiteur une page neutre avec le
téléphone du cabinet, et la cause exacte est journalisée — la retrouver avec
`grep -rh "rdv.php" ~/ik-logs/ | tail`. Pour tester les identifiants sans
passer par le site : `curl --url smtps://mail.infomaniak.com:465 --user ...`
(voir l'historique de la mise en place). Pour révoquer l'accès du site :
supprimer l'appareil dans le Manager et en générer un nouveau.

### 4.8 Balisage SEO et redirections (pack du 20/08/2026)

- **Redirections 301** des anciennes URL Wix (`/reserver-en-ligne`,
  `/droit-du-travail`, `/droit-penal-du-travail`,
  `/droit-de-la-securite-sociale`) dans `deploy/htaccess` — elles étaient en
  404 alors que Google les indexe encore.
- **Canonical, Open Graph et JSON-LD** (`LegalService`, `Person`, `FAQPage`)
  injectés par le build en production uniquement — jamais en préversion, qui
  est noindex. Tout est dérivé du contenu des pages : une FAQ modifiée par
  Coralie met à jour le balisage toute seule au build suivant.
- **Image de partage** `assets/img/og-cover.jpg` (1200×630), **favicons**
  (SVG + `favicon.ico` racine + icône Apple), **page 404** dans la charte
  (`poc/404.html`, styles inline et liens absolus car servie à n'importe
  quelle profondeur), **en-têtes de sécurité** de base dans le `.htaccess`.

---

## 5. À traiter avant de considérer le site fini

Par ordre de priorité.

1. **Mentions légales, RGPD, cookies absents.** 25 liens `href="#"` restent dans
   les pages, dont *Mentions légales*, *RGPD*, *Cookies* et *Méthode &
   honoraires* dans le pied de page de chaque page. Les mentions légales et
   l'identité de l'hébergeur sont des obligations, renforcées par le RIN pour
   un avocat : nom du cabinet, adresse, barreau de Metz, directeur de
   publication, hébergeur (Infomaniak SA, Genève).
2. **Polices Google externes.** Les six pages chargent Fraunces et Inter depuis
   `fonts.googleapis.com`. À auto-héberger dans `assets/fonts/` : performance,
   et un transfert d'IP vers Google en moins à déclarer côté RGPD.
3. **Analytics.** L'instrumentation est simulée (`console.log` sur
   `data-track`). À remplacer par GA4 ou une alternative sans cookie
   (Plausible, Matomo), ce qui simplifie la bannière cookies.

---

## 6. Ce dont il faut garder trace

- Une capture de la zone DNS Wix, avant et après modification.
- Les identifiants FTP de déploiement (utilisateur dédié, pas le compte
  principal).
- L'IPv4 du site, à revérifier dans le Manager avant toute intervention DNS :
  elle peut changer lors d'une migration de serveur côté Infomaniak.

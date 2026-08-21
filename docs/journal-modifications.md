# Journal des modifications

Ce que contient la branche par défaut, et ce qui est réellement en ligne.

Mathieu le lit **avant chaque déploiement**, pour savoir ce qu'il s'apprête à
publier. Toute modification fusionnée y figure, la plus récente en haut.

Pour savoir ce qui est en ligne à l'instant T :

```bash
curl https://schumpf-avocat.com/version.txt
```

Le commit renvoyé se compare à la branche par défaut. Tout ce qui a été
fusionné après lui est en attente de déploiement.

**Convention de statut**

- `validé` — Coralie a vu et approuvé le résultat sur la préversion, la
  modification est fusionnée, elle attend un déploiement.
- `déployé` — en ligne sur schumpf-avocat.com.
- `technique` — plomberie sans effet sur le site publié, rien à déployer.

---

## 2026-08-21

| Modification | Demandé par | PR | Statut |
|---|---|---|---|
| Accusé de réception automatique au prospect après soumission du formulaire (action B de l'audit). | Mathieu | #13 | validé |

## 2026-08-20

| Modification | Demandé par | PR | Statut |
|---|---|---|---|
| Pack SEO : redirections 301 des anciennes URL Wix (en 404 depuis la migration), canonical + Open Graph + JSON-LD sur toutes les pages, image de partage, favicons, page 404, en-têtes de sécurité. | Mathieu | #12 | validé |
| Configuration DNS e-mail du domaine : SPF créé (Google + Infomaniak — le domaine n'en avait aucun) et DKIM Infomaniak, dans la zone Wix. Adresse d'envoi `site@` créée, mot de passe d'appareil, identifiants posés sur le serveur. **Formulaire testé de bout en bout : le mail arrive chez Coralie.** | Mathieu | — | **déployé** (hors dépôt : DNS + serveur) |
| Correctif du formulaire : envoi par SMTP authentifié — `mail()` n'existe pas chez Infomaniak, constaté au test réel. Identifiants hors dépôt, dans `config/` sur le serveur. | Mathieu | #10 | **déployé** |
| Formulaire de rendez-vous branché : envoi par e-mail via PHP sur Infomaniak, page de confirmation, anti-spam. Le message « POC » disparaît. | Mathieu | #9 | **déployé** — a révélé l'absence de `mail()`, corrigé par #10 |

## 2026-08-18

| Modification | Demandé par | PR | Statut |
|---|---|---|---|
| Mode d'emploi du dépôt, journal, `version.txt`, préversion sur branche, sitemap automatique | Mathieu | #8 | technique |
| Séparation des workflows préversion / déploiement | Mathieu | #7 | technique |
| Bouton « Premier rendez-vous » du header illisible, et retrait du prix à cet endroit | Mathieu | #6 | **déployé** |
| Préversion de relecture sur GitHub Pages, hors index Google | Mathieu | #5 | technique |
| Blocage de `/backup/` et des `index_old*`, qui étaient servis publiquement | Mathieu | #4 | **déployé** |
| Conservation des règles Infomaniak dans le `.htaccess` | Mathieu | #3 | **déployé** |
| Citation des identifiants pour `lftp` | Mathieu | #2 | technique |
| Chaîne de mise en production : build, déploiement FTPS, documentation | Mathieu | #1 | **déployé** |

### Ce que la mise en production initiale a corrigé

Le site avait été mis en ligne à la main le 3 juillet, en renommant
`home.html` en `index.html` sans réécrire les liens internes. Conséquences,
constatées sur le serveur :

- **19 liens de navigation en 404** dans les pages publiées — tous les
  « retour accueil » de l'accueil, de la fiche avocate et des trois pages
  expertises ;
- **`diagnostic.html` absent**, alors que tous les boutons d'appel à l'action
  du site pointent vers cette page « premier rendez-vous » ;
- un dossier `backup/` **accessible publiquement**, contenant une ancienne
  fiche avocate indexable, déplacé depuis hors de la racine web.

---

## Reste à traiter — file d'attente

Ordre validé par Mathieu le 20/08/2026.

**Côté Mathieu (technique)**

1. **Google Search Console** — déclarer le domaine, soumettre le sitemap,
   demander l'indexation des 6 pages. Marche à suivre : § 6 bis de
   `docs/hebergement-infomaniak.md`.
2. **C — Analytics par les logs serveur** : GoAccess sur `~/ik-logs/`,
   zéro cookie donc zéro bannière. Conversions = `POST /api/rdv.php` en 303.
3. **Gabarit de page article** pour les Décryptages (préalable à la mise en
   ligne du premier article).
4. **Registraire du domaine à identifier** (`whois schumpf-avocat.com`) :
   qui détient l'enregistrement, qui paie le renouvellement, quand expire-t-il.

**Côté Coralie (contenu et identité)** — liste détaillée dans `CLAUDE.md`,
section « File d'attente Coralie » :

1. Mentions légales, politique de confidentialité, texte cookies.
2. Fiche Google Business Profile (avec décision sur les avis clients).
3. Relecture du premier Décryptage (brief URSSAF de juin, jamais produit).

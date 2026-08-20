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

## 2026-08-20

| Modification | Demandé par | PR | Statut |
|---|---|---|---|
| Formulaire de rendez-vous branché : envoi par e-mail via PHP sur Infomaniak, page de confirmation, anti-spam. Le message « POC » disparaît. | Mathieu | #9 | validé — **test d'envoi réel à faire après déploiement** (cf. § 5 de la doc) |

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

## Reste à traiter

Ces deux chantiers ne sont pas ouverts. Ils sont documentés au § 5 de
`docs/hebergement-infomaniak.md`.

- **Mentions légales, RGPD et cookies absents.** 25 liens `href="#"` dans les
  pieds de page. Obligation légale, renforcée par le RIN. Le texte relève de
  Coralie ; la bannière cookies, qui est un mécanisme, relève de Mathieu.

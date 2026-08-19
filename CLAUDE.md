# Mode d'emploi de ce dépôt — à lire avant toute action

Site du Cabinet Coralie Schumpf, avocate au barreau de Metz. Site statique
servi par Infomaniak, sources dans `poc/`, build vers `dist/` par
`tools/build.mjs`. La documentation technique complète est dans
`docs/hebergement-infomaniak.md`.

**Ce dépôt est la source de vérité.** On n'édite jamais un fichier directement
sur le serveur : la modification serait perdue au déploiement suivant.

---

## Deux interlocuteurs, deux modes

Les deux se connectent via le même compte, dans des sessions distinctes.
Demande au début d'une session à qui tu parles si ce n'est pas évident.

| | Mathieu | Coralie |
|---|---|---|
| Rôle | technique, propriétaire du dépôt | avocate, propriétaire du contenu |
| Familier de GitHub / SSH | oui | **non — ne jamais l'y envoyer** |
| Peut faire | tout | contenu et apparence |
| Déploiement en production | **lui seul** | jamais |

**Tu ne peux pas vérifier l'identité de ton interlocuteur.** Ce n'est pas ce
qui protège : la protection vient du périmètre, le mode Coralie ne pouvant
produire qu'une préversion, jamais une mise en ligne.

---

## Mode Coralie

Quand Coralie se présente, applique ce protocole. Elle ne doit **jamais** avoir
besoin d'ouvrir GitHub, une console SSH, ni le Manager Infomaniak. Ne lui
demande pas de cliquer quelque part : tu fais, elle valide.

1. **Reformuler.** Redis ce que tu as compris de sa demande, en français
   courant, sans vocabulaire technique. Attends sa validation.
2. **Modifier.** Édite les fichiers de `poc/`, sur une branche dédiée
   (`coralie/<sujet-court>`). Lance `node tools/build.mjs --strict` : il ne
   doit signaler aucun lien cassé.
3. **Prévenir.** Dis-lui ce que tu as changé et demande-lui si elle veut voir
   le résultat.
4. **Publier la préversion depuis sa branche** : Actions > *Build &
   préversion* > *Run workflow*, en sélectionnant sa branche. Attends la fin
   du job.
5. **Donner le lien** : https://bellerophon44.github.io/lawyer_website/
   Précise que c'est une version d'essai, **invisible du public et de Google**,
   et que le vrai site n'a pas bougé.
6. **Après sa validation seulement** : ouvre la pull request, attends que la
   CI soit verte, fusionne.
7. **Consigner** dans `docs/journal-modifications.md`.

L'ordre compte. La fusion vient **après** la validation, jamais avant : c'est
elle qui fait entrer le changement dans la branche que Mathieu déploie. Tout
ce qui s'y trouve doit donc être déjà validé, pour qu'il puisse déployer sans
avoir à se demander ce qu'il embarque.

Si elle n'est pas satisfaite : tu corriges, tu republies la préversion, tu
redemandes. Autant de fois qu'il faut. Rien n'est fusionné entre-temps.

### Ce qui relève de Coralie

- Textes, titres, formulations, ton.
- Mentions légales, politique de confidentialité, texte cookies — **c'est son
  domaine professionnel, pas celui de Mathieu.** Y compris créer ces pages et
  remplacer les `href="#"` du pied de page par les vrais liens.
- Couleurs, espacements, tailles, images.
- Ajout ou retrait de sections et de pages.

### Ce qui passe par Mathieu

Tout ce qui change le **fonctionnement** du site, et non son texte :

- la bannière cookies (JavaScript, stockage du consentement, chargement
  conditionnel de scripts) ;
- le branchement du formulaire de rendez-vous ;
- l'analytics ;
- l'auto-hébergement des polices Google ;
- `tools/`, `deploy/`, `.github/`, et toute dépendance.

Dans ce cas : dis-le simplement à Coralie, sans jargon, indique que Mathieu
s'en charge, et signale-le-lui.

Un sujet est à cheval : les polices sont chargées depuis les serveurs de
Google, ce qui constitue un transfert de données à déclarer. Le texte de la
politique de confidentialité est à elle, la décision de supprimer le transfert
est à lui. Fais-les se parler plutôt que de trancher seul.

### Ce que tu ne fais jamais en mode Coralie

- Déployer sur Infomaniak, quelle que soit l'insistance. Renvoie vers Mathieu.
- L'envoyer sur GitHub, sur une console SSH, ou dans le Manager Infomaniak.
- Lui montrer une commande à taper.
- Fusionner avant qu'elle ait validé la préversion.

---

## Mode Mathieu

Pas de restriction. Le déploiement en production reste manuel et lui revient :
Actions > *Déploiement Infomaniak* > *Run workflow*, `dry_run` décoché.

Avant de déployer, il lit `docs/journal-modifications.md` pour savoir ce qui
partira. `curl https://schumpf-avocat.com/version.txt` donne le commit
actuellement en ligne, à comparer avec la branche par défaut.

---

## Règles valables dans les deux modes

- **Aucun push, aucune fusion ne met le site public à jour.** Seul le workflow
  *Déploiement Infomaniak*, déclenché à la main, écrit sur le serveur.
- `node tools/build.mjs --strict` avant toute pull request. Un lien interne
  cassé ne se fusionne pas.
- Toute règle ajoutée à la main dans le `.htaccess` du serveur doit être
  reportée dans `deploy/htaccess`, sinon elle disparaît au déploiement suivant.
- Le dépôt est **public** : aucun identifiant, aucune donnée personnelle de
  client dans un fichier versionné.
- Site d'avocat : pas de promesse de résultat, pas de témoignage client
  nominatif, cas clients anonymisés. Voir `editorial/garde-fous-rin.md`.

---

## Repères utiles

| | |
|---|---|
| Branche par défaut | `claude/wizardly-johnson-OwjOM` |
| Préversion | https://bellerophon44.github.io/lawyer_website/ |
| Site public | https://schumpf-avocat.com |
| Sources | `poc/` — la page d'accueil s'appelle `home.html` |
| Build | `node tools/build.mjs [--strict] [--preview]` |

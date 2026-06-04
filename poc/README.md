# POC v0.2 — Cabinet Coralie Schumpf

Maquette interactive de validation : 5 templates clés, pivot 100 % employeur, DA bordeaux + ivoire + sérif éditorial.

## Changements v0.3 (sur retours Coralie cumulés)

**v0.2 — retours initiaux**
- ✅ **Bordeaux primary** confirmé.
- ✅ **Ton éditorial premium** confirmé.
- ✅ **Portrait** : intégration de la photo actuelle (déposer dans `assets/img/coralie-schumpf.jpg` — cf. `assets/img/README.md`).
- ✅ **Premier rendez-vous payant** (au lieu de gratuit) : positionnement B2B explicite.
- ✅ **Switch persona réduit à 2 cibles** : Dirigeant TPE/PME + DRH/RH (Direction juridique supprimée).
- ✅ **3 cas client réels** intégrés en home (harcèlement / accident mortel BTP / licenciement éco perte de marché).
- ✅ **Mention transfrontalière retirée** partout.

**v0.3 — affinage offre et tarification**
- ✅ **Premier rendez-vous repositionné** : 1 heure (vs. 30 min), pas de consultation écrite, à la sortie estimation de la charge de travail + proposition chiffrée d'honoraires.
- ✅ **Mission Annuelle précisée** : volume horaire défini en amont, reconduction tacite, tarif réduit.
- ✅ **Grille tarifaire complète** publiée en home dans une nouvelle section « Trois manières de travailler ensemble » :
  - Mission Annuelle — **200 € HT / heure** (tarif réduit, recommandée).
  - Mission ponctuelle — **230 € HT / heure** (tarif plein, au temps passé).
  - Contentieux — **2 000 à 5 000 € HT** forfait + honoraires de résultat.
- ✅ **Réseaux sociaux** intégrés dans le footer de toutes les pages : **LinkedIn** + **Instagram** (`@schumpfcoralieavocat`). Facebook retiré (décision Coralie : n'apparaît plus sur le nouveau site).

## Ouvrir le POC

Trois options selon ton confort :

**Le plus simple — double-clic.**
Ouvre `poc/index.html` dans ton navigateur (Chrome, Firefox, Safari). Toutes les pages sont liées entre elles via le menu. Aucune dépendance, aucun serveur requis.

**Avec un serveur statique local** (recommandé pour le rendu des polices Google) :
```bash
cd poc
npx serve .        # ou : python3 -m http.server 8000
```

**Via GitHub Pages** (si tu veux le partager à Coralie sans rien installer côté client) : active GitHub Pages sur la branche `claude/wizardly-johnson-OwjOM`, dossier `/poc`. URL : `https://bellerophon44.github.io/lawyer_website/`.

## Les 5 templates

| # | Fichier | Rôle |
|---|---|---|
| 01 | `home.html` | Accueil — hero éditorial, switch persona, 3 piliers, cas client, méthode, décryptages, CTA final |
| 02 | `expertises/droit-du-travail.html` | Page pilier — sommaire des sous-thèmes, sections détaillées, FAQ schema-ready |
| 03 | `expertises/licenciement-economique.html` | Page sous-thème SEO — article long format, callouts, CTA contextuel |
| 04 | `cabinet/coralie-schumpf.html` | Fiche avocate — portrait, meta presse, biographie, publications |
| 05 | `diagnostic.html` | RDV — formulaire de pré-qualification, 3 garanties, CTA téléphone |

## Design system (rappel)

- **Couleurs** : bordeaux primaire `#7E1F2A` (capitalisé depuis le logo CS) · bordeaux nuit `#4A1219` · ivoire chaud `#F6F1E9` · graphite `#1E1E1E` · cuivre patiné `#B07C4D`. Codes hex à caler définitivement sur le SVG HD du logo.
- **Typographie** : *Fraunces* (sérif éditorial, titres) + *Inter* (sans-sérif humaniste, UI) — Google Fonts, à terme auto-hébergées pour la perf.
- **Composants** : header sticky, rail de contact sticky (tel · mail · RDV), CTA bordeaux, switch persona, bento piliers, cas client en mode sombre, FAQ `<details>`, formulaire chips.
- **Mobile-first** : breakpoints à 700 / 800 / 900 px. Sur < 720 px, le rail de contact bascule en barre fixée en bas d'écran.
- **Accessibilité** : `prefers-reduced-motion` géré, aria-labels sur le rail, navigation clavier sur les `<details>` et les radios stylisées.

## Ce qui est **simulé** dans le POC (à instrumenter en v1)

- Le portrait de l'avocate (page `cabinet/`) est un placeholder bordeaux dégradé — shooting à prévoir.
- Le formulaire de diagnostic alerte un message au submit (pas de backend ; à brancher sur Wix Bookings ou Calendly).
- Les liens vers les pages pénal/URSSAF redirigent vers la page pilier `droit-du-travail.html` (pas encore créés).
- Les logos secteurs clients et le module GBP/avis Google ne sont pas inclus (assets manquants).
- Les sous-pages `méthode & honoraires`, `mentions légales`, `RGPD` sont des liens morts.
- L'instrumentation analytics est mockée (`console.log` via attribut `data-track`).

## Décisions DA à valider avec Coralie

- Confirmation de la **palette bordeaux + ivoire** (vs. variantes).
- Choix typographique : *Fraunces* (alternatives : *Source Serif 4*, *GT Sectra*).
- Tonalité éditoriale du copy (cf. hero, citations, cas anonymisés) — c'est volontairement direct et premium.
- Validation de la **Mission Annuelle** (mention dans la FAQ de la page pilier).
- Conservation du **switch persona** Dirigeant / DRH / Direction juridique en home.
- ~~Décision EN/DE pour la zone frontalière~~ — abandonné (positionnement national hors transfrontalier).

## Prochaines itérations

1. Patch DA (codes hex définitifs, micro-typo) après retour Coralie.
2. Sous-pages SEO complémentaires (URSSAF, pénal, AT/MP).
3. Page **Méthode & honoraires** (premier rendez-vous, forfaits opérations, Mission Annuelle, contentieux au temps passé).
4. Intégration calendrier réelle (Wix Bookings / Calendly).
5. Instrumentation GA4 + Hotjar.
6. Build de prod (vite/astro) si choix d'un framework, sinon optimisation des assets statiques.

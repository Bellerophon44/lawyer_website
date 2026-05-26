# Audit du site existant — `schumpf-avocat.com`

> Objectif : extraire l'ensemble du contenu fonctionnel du site actuel pour alimenter la refonte (cartographie d'information, contenu, parcours, design).
> Source : pages indexées du domaine + annuaires d'avocats (Consultation.avocat.fr, Doctrine, Pappers Justice, PagesDuPalais, Justifit, Lawzana, MonExpertDuDroit, PagesJaunes, EU-Avocat, Mappy, LinkedIn).
> **Limite méthodologique** : l'hébergement (vraisemblablement Wix) refuse les requêtes des outils de fetch automatisés (403). L'audit **visuel** reste partiel et devra être complété par une capture d'écran ou un dump HTML transmis manuellement. Le **contenu fonctionnel**, en revanche, est consolidé ici.

---

## 1. Identité du cabinet

| Élément | Valeur |
|---|---|
| Nom du cabinet | Cabinet **Coralie SCHUMPF**, Avocat |
| Titulaire | Maître Coralie SCHUMPF |
| Barreau | **Barreau de Metz** |
| Prestation de serment | 17 décembre 2015 (≈ 10 ans d'exercice en 2026) |
| Forme | Avocat individuel (cabinet boutique mono-avocat) |
| Adresse principale | **1 rue des Charpentiers, 57070 Metz** (Moselle, Grand Est) |
| Adresse historique mentionnée | 4 rue Paul Langevin, 57070 Metz *(à vérifier — peut-être ancienne)* |
| Téléphone | **07 69 00 45 58** |
| Site | `schumpf-avocat.com` |
| LinkedIn | `linkedin.com/in/coralie-schumpf-4311549a` |
| Twitter / X | `@coralieschumpf` |
| Blog tiers | `consultation.avocat.fr/blog/coralie-schumpf/` |
| Zone d'intervention déclarée | Grand Est (Metz, Thionville et Moselle / Lorraine) |

### Baseline actuelle (déduite des balises de titre)
- Homepage : **« Avocat Metz | Droit du travail | Coralie Schumpf Avocat »**
- Pages expertises : suffixe **« | Grand Est | Coralie Schumpf avocat »**

➡️ SEO local fortement orienté **« avocat droit du travail Metz / Grand Est »**.

---

## 2. Positionnement & clientèle

### Positionnement éditorial actuel
> « Avocat en droit du travail à Metz, qui intervient aux côtés des **employeurs et des salariés** en droit du travail, droit de la sécurité sociale et droit pénal du travail. »

### Clientèle ciblée
- **Salariés** (contentieux prud'hommal, licenciement, harcèlement, AT/MP)
- **Employeurs / dirigeants** (conseil, rédaction, défense pénale)
- **TPE-PME locales** (Grand Est)

> Le double ciblage employeurs / salariés est explicitement assumé — c'est un parti pris à conserver ou à clarifier (souvent perçu comme un grand écart à expliciter).

### Promesse / valeurs mises en avant
- **Écoute et dialogue**
- **Proximité, transparence, disponibilité**
- **Confidentialité et sécurité juridique**
- **Pas de débours avant signature de la convention d'honoraires** (« pas de surprises »)
- **Aide juridictionnelle acceptée** (accessibilité)

---

## 3. Domaines d'expertise (offre fonctionnelle)

Trois pôles structurent le site actuel, correspondant aux trois pages d'expertise.

### 3.1 Droit du travail (`/droit-du-travail`)
**Cible** : salariés et employeurs.
**Prestations identifiées** :
- Représentation devant le **Conseil de Prud'hommes** (demande ou défense)
- Calcul et **négociation des indemnités** de licenciement / rupture
- **Rédaction de contrats** de travail
- **Négociation** des intérêts du salarié auprès de l'employeur
- Conseil sur conformité à la réglementation du travail
- Contentieux : licenciement abusif, **harcèlement** au travail

### 3.2 Droit pénal du travail (`/droit-penal-du-travail`)
**Cible** : entreprises, dirigeants, salariés.
**Prestations identifiées** :
- Préparation et suivi des **procédures pénales** engagées contre l'entreprise ou ses dirigeants
- **Défense devant les juridictions pénales**
- Couvre typiquement : travail dissimulé, infractions à la sécurité au travail, mise en danger, entrave, prêt illicite de main-d'œuvre, etc.

### 3.3 Droit de la sécurité sociale (`/droit-de-la-securite-sociale`)
**Cible** : salariés et employeurs.
**Prestations identifiées** :
- Contentieux **sécurité sociale** (Pôle social du TJ)
- Litiges sur **taux d'incapacité**
- **Accidents du travail** et **maladies professionnelles** (AT/MP)
- Représentation et conseil sur reconnaissance, contestation, faute inexcusable

---

## 4. Modes de consultation & honoraires

| Mode | Durée | Tarif déclaré | Usage |
|---|---|---|---|
| 1er RDV en cabinet | 30 min | **Gratuit, sans engagement** | Faire connaissance, qualifier la problématique |
| Consultation vidéo | 30 min | Payante (montant non public) | Réponse concrète à distance |
| Consultation téléphonique | 15 min | Payante (montant non public) | Réponse rapide |
| Réponse écrite | — | Payante (montant non public) | Question juridique précise |

**Tarification** : pas de grille publique. Mention rassurante :
- discussion d'honoraires « franche »,
- **convention d'honoraires signée avant tout débours**,
- pas de surprise en cours de procédure,
- **aide juridictionnelle acceptée**.

**Réservation** : page dédiée `/reserver-en-ligne` (interface de booking).

---

## 5. Arborescence actuelle du site

Pages identifiées via les moteurs (l'arborescence réelle peut être plus large) :

```
/                            Accueil
/droit-du-travail            Expertise 1
/droit-penal-du-travail      Expertise 2
/droit-de-la-securite-sociale Expertise 3
/reserver-en-ligne           Prise de RDV
```

**Pages absentes ou non identifiées** (à vérifier ou à ajouter) :
- Page **« À propos »** / biographie Coralie Schumpf (parcours, formation, publications).
- Page **« Méthode / honoraires »** explicite.
- **Blog** / actualités juridiques (l'avocate publie sur consultation.avocat.fr en externe — l'audience SEO échappe au domaine).
- Page **« Témoignages / avis »** (les annuaires en hébergent à sa place).
- **Mentions légales / RGPD / cookies / CGV** (obligatoires pour un cabinet).
- Page **« Contact »** dédiée distincte de la prise de RDV.
- Versions linguistiques (Metz = zone frontalière Luxembourg / Allemagne — l'**allemand** et l'**anglais** seraient des leviers crédibles).

---

## 6. Présence digitale et écosystème

| Canal | Présence | Levier |
|---|---|---|
| Site propre | ✅ Wix probable, 5 pages | À refondre |
| Google Business | ✅ (fiche PagesJaunes existe, GBP probable) | Avis Google = priorité SEO local |
| LinkedIn perso | ✅ `coralie-schumpf-4311549a` | Sous-exploité |
| Twitter/X | ✅ `@coralieschumpf` | Activité à vérifier |
| Blog hébergé tiers | ✅ `consultation.avocat.fr` | À rapatrier sur le domaine |
| Annuaires juridiques | ✅ Doctrine, Pappers Justice, Justifit, Allaw, MonExpertDuDroit, Lawzana, EU-Avocat, PagesDuPalais, Bottin, ReadyToParty | Bon maillage de backlinks |
| Plateforme de RDV en ligne | ✅ Via le site + consultation.avocat.fr | À unifier |

---

## 7. Audit visuel & UX (préliminaire, à compléter)

> ⚠️ Le contenu HTML n'a pas pu être récupéré automatiquement (Wix bloque le scraping). L'analyse ci-dessous est **déductive** (titres, structure d'indexation, plateforme présumée). Une capture d'écran ou un export PDF des principales pages est requis pour finaliser l'audit visuel.

### Hypothèses fortes
- **CMS** : Wix Studio (URLs en `/slug-en-majuscule-dans-le-titre`, structure plate, balisage des `<title>` typique).
- **Identité graphique** probable : palette sobre (gris/bleu/blanc), photographie générique, typographie sans-sérif Wix par défaut.
- **Mobile** : responsive par template Wix, mais qualité variable.

### Risques typiques (à vérifier sur captures)
- Photographie probable de type **stock juridique** (balance, marteau, immeuble Haussmannien) — daté.
- **Hero générique** type bandeau d'image + slogan vague.
- Hiérarchie typographique faible (titres et corps trop proches en taille / graisse).
- Absence de **système de design cohérent** (composants ad hoc d'un template).
- **Pas de personnalité visuelle distinctive** → invisible parmi les centaines d'avocats droit du travail à Metz / Grand Est.
- **Performance Wix** : poids JS lourd, LCP > 3s probable.
- **SEO local** correct mais **éditorialisation pauvre** (le blog est sur un domaine tiers, donc l'autorité SEO bénéficie à `consultation.avocat.fr`, pas à `schumpf-avocat.com`).
- **Conversion** : la prise de RDV est bien présente, mais le tunnel n'est probablement pas optimisé (pas de friction analysée, pas de tracking déclaré).

### Points à confirmer (action utilisateur)
- 📸 Captures d'écran de l'accueil, d'une page expertise, de la page RDV (desktop + mobile).
- 🎨 Logo en haute définition.
- ⚙️ Confirmation du CMS et du thème Wix utilisé.
- 📊 Accès Google Analytics / Search Console (volumes, mots-clés, parcours).
- 📝 Liste complète des publications du blog tiers à rapatrier.

---

## 8. Forces & faiblesses du site actuel

### ✅ Forces
- Domaines d'expertise **clairs et cohérents** (trois pôles complémentaires).
- **Double cible** assumée (employeurs / salariés).
- **Premier RDV gratuit** : argument de conversion fort.
- **Convention d'honoraires** explicite : signal de transparence.
- **Aide juridictionnelle** : accessibilité affichée.
- **Présence multicanale** sur les annuaires juridiques (bon réseau de citations).
- **Ancienneté** de la marque domaine (référencement local installé).

### ⚠️ Faiblesses
- **Identité visuelle indifférenciée** (site template).
- **Pas de personnalisation** de la figure de Coralie Schumpf (pas de biographie riche, peu de photo, pas de prise de parole).
- **Blog hors-domaine** : l'autorité éditoriale fuit vers `consultation.avocat.fr`.
- **Pas d'outils interactifs** (calculateurs indemnités, simulateurs prud'hommes, FAQ structurée).
- **Pas de versions linguistiques** (anglais / allemand — pourtant Metz frontalier).
- **Pas de réassurance sociale** (avis Google, témoignages, presse, prises de parole).
- **Architecture pauvre** : 5 pages, pas de granularité sur les sous-thèmes (licenciement, rupture conventionnelle, harcèlement, AT/MP — chacun pourrait être une page SEO dédiée).
- **Conversion non instrumentée** (pas de tracking visible, pas d'A/B test possible).

---

## 9. Implications pour la refonte

### 9.1 Périmètre fonctionnel recommandé pour la v2

**Pages institutionnelles**
- Accueil éditorial (cf. DA proposée dans `01-benchmark-visuel.md`)
- À propos / Maître Schumpf (biographie, formation, barreau, langues, prises de parole)
- Méthode & honoraires (transparence renforcée — un argument différenciant)
- Contact (cabinet, accès, plan, horaires)

**Pôles d'expertise** (trois piliers conservés + sous-pages SEO)
- Droit du travail (page pilier)
  - Licenciement & rupture
  - Harcèlement & discrimination
  - Contrat de travail (rédaction / négociation)
  - Heures supplémentaires & rémunération
  - Prud'hommes (procédure)
- Droit pénal du travail (page pilier)
  - Travail dissimulé
  - Sécurité au travail / mise en danger
  - Entrave / délit d'entrave
  - Défense pénale du dirigeant
- Droit de la sécurité sociale (page pilier)
  - Accidents du travail
  - Maladies professionnelles
  - Taux d'incapacité (contentieux)
  - Faute inexcusable de l'employeur

**Pour qui ?** (segmentation par persona)
- Espace **Salariés**
- Espace **Employeurs / Dirigeants**

**Contenu**
- **Blog rapatrié** (« Décryptages » / « Le droit du travail expliqué ») — pilier SEO + autorité éditoriale.
- **FAQ structurée** (schema.org `FAQPage`) sur les questions récurrentes (préavis, indemnités, prescription, etc.).
- **Glossaire** juridique (CDI, CDD, CSE, AT, MP, etc.) — excellent maillage interne.

**Outils interactifs (signal de modernité fort)**
- Simulateur d'indemnités de licenciement
- Calculateur de préavis
- Évaluateur d'éligibilité prud'hommes
- Vérificateur de prescription

**Conversion**
- Page RDV unifiée (cabinet / visio / téléphone / écrit) avec calendrier en ligne.
- Formulaire de pré-qualification (gain de temps pour le 1er RDV gratuit).
- Numéro cliquable + WhatsApp Business optionnel.

**Conformité & réassurance**
- Mentions légales, RGPD, cookies (Wix-éconforme à reproduire).
- Page « Avis & témoignages » avec intégration Google Reviews.
- Charte de déontologie du Barreau de Metz mentionnée.

**Internationalisation (option à valider)**
- 🇬🇧 Anglais : transfrontaliers Luxembourg, expatriés.
- 🇩🇪 Allemand : zone Saare / Luxembourg.

### 9.2 Continuités à garder (ne pas perdre dans la refonte)
- L'URL `/droit-du-travail` (et `/droit-penal-du-travail`, `/droit-de-la-securite-sociale`) → **redirections 301 obligatoires** vers la nouvelle structure.
- Le mot-clé « **avocat droit du travail Metz** » sur l'accueil.
- Le 1er RDV gratuit en hero.
- La double cible employeurs / salariés.

### 9.3 Ruptures éditoriales proposées
- Passer d'un **site vitrine plat** à un **média-cabinet** (le blog devient un actif).
- Passer d'une **mono-cible « avocat à Metz »** à une **double-cible explicitée** (Salariés / Employeurs) dès le hero.
- Ajouter une **dimension pédagogique** (outils, glossaire, FAQ) — positionnement « le droit du travail rendu lisible », cohérent avec la DA proposée.

---

## 10. Prochaines étapes

1. **Validation** de cet audit avec Coralie Schumpf (corrections, ajouts, priorités business).
2. **Captures d'écran** des pages actuelles + accès à la Search Console pour finaliser l'audit visuel et SEO.
3. **Atelier persona** : préciser le mix client réel (% employeurs / % salariés, taille des dossiers, géographie effective).
4. **Cartographie d'information cible** (arborescence détaillée) et plan de redirection 301.
5. **Plan de contenu** : rapatriement blog, FAQ, glossaire, outils interactifs.
6. **Wireframes** des 5 templates clés : home, page expertise pilier, page sous-thème, fiche avocate, RDV.
7. **Maquettes haute fidélité** sur la base de la DA arrêtée dans `01-benchmark-visuel.md`.

---

## Sources

- [Schumpf Avocat — site officiel](https://www.schumpf-avocat.com/)
- [Schumpf Avocat — Droit du travail](https://www.schumpf-avocat.com/droit-du-travail)
- [Schumpf Avocat — Droit pénal du travail](https://www.schumpf-avocat.com/droit-penal-du-travail)
- [Schumpf Avocat — Droit de la sécurité sociale](https://www.schumpf-avocat.com/droit-de-la-securite-sociale)
- [Schumpf Avocat — Réserver en ligne](https://www.schumpf-avocat.com/reserver-en-ligne)
- [Consultation.avocat.fr — fiche Coralie Schumpf](https://consultation.avocat.fr/avocat-metz/coralie-schumpf-43379.html)
- [Consultation.avocat.fr — blog Coralie Schumpf](https://consultation.avocat.fr/blog/coralie-schumpf/consultation-telephonique.php)
- [Doctrine — fiche avocate](https://www.doctrine.fr/p/avocat/L3F867C11D23EF1CF5D4F)
- [Pappers Justice — fiche avocate](https://justice.pappers.fr/avocat/schumpf-coralie-57070)
- [PagesDuPalais — fiche avocate](https://www.pagesdupalais.com/juriste-palais-avocats/barreau-avocats/coralie-schumpf-avocat-specialise-a-metz-110177)
- [Justifit — fiche avocate](https://www.justifit.fr/avocats/avocat-metz-57070-coralie-schumpf-6380/)
- [MonExpertDuDroit — fiche avocate](https://monexpertdudroit.com/avocat/grand-est/metz/coralie-schumpf/)
- [Lawzana — fiche avocate](https://lawzana.com/fr/lawyer/avocat-coralie-schumpf-travail-metz-thionville)
- [PagesJaunes](https://www.pagesjaunes.fr/pros/59928310)
- [EU-Avocat](https://www.eu-avocat.fr/avocats/57463-metz/edfdaaaghgiefggfici.htm)
- [Mappy — adresse cabinet](https://fr.mappy.com/poi/5e745dc354c4c008d38c04fa)
- [LinkedIn — Coralie Schumpf](https://fr.linkedin.com/in/coralie-schumpf-4311549a)
- [Twitter / X — @coralieschumpf](https://twitter.com/coralieschumpf?lang=fr)
- [Allaw — prise de RDV](https://allaw.fr/avocat/coralie_schumpf_3ba)

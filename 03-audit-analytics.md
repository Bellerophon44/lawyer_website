# Audit analytics — `schumpf-avocat.com` (12 derniers mois)

> Source : exports Wix Analytics fournis par le client (27 mai 2025 → 27 mai 2026).
> Fichiers bruts dans `data/analytics/`.
> Objectif : qualifier le trafic actuel, identifier les leviers d'optimisation, calibrer les KPI cibles de la v2.

---

## 1. Vue d'ensemble (12 mois)

| Indicateur | Valeur | Lecture |
|---|---|---|
| **Sessions** | **~870** | ≈ 73 / mois · ≈ 2,4 / jour — cabinet hyperlocal, audience étroite |
| **Visiteurs uniques** | **~770** | Nouveaux 707 (92 %) · Réguliers 67 (8 %) |
| **Vues de page totales** | **1 282** | ~1,5 page/session — funnel court |
| **Sessions / nouveaux visiteurs** | 754 (87 %) | Très forte rotation — il faut convertir au 1er contact |
| **Taux de rebond global** | **~80 %** | (78 % nouveaux · 83 % réguliers) — **alerte rouge** UX/SEO |
| **Durée moyenne de session** | 1 min 55 (nouveaux) · 3 min 11 (réguliers) | Les réguliers vont 2x plus loin |
| **Conversions trackées** | **0** | ❌ Aucun objectif de conversion configuré (tracking RDV à instrumenter) |

### Lecture critique
- **Volume = TPE-PME locale**. Inutile de viser des objectifs SaaS — l'objectif n'est pas la masse, c'est la **qualification** des quelques visiteurs mensuels.
- **Rebond 80 %** : à isoler. Sur un site d'avocat, une visite « éclair » est souvent **fonctionnelle** (chercher le téléphone, l'adresse, la disponibilité). Le rebond n'est pas en soi mauvais — il devient critique **si on attend de convertir un dirigeant en RDV**. La v2 doit faire du **rebond utile** un signal traqué (clic tel, clic email, clic RDV).
- **0 conversion trackée** : ne signifie pas 0 conversion réelle. Signifie qu'on **pilote à l'aveugle**.

---

## 2. Sources de trafic

| Source | Sessions | % | Implication |
|---|---|---|---|
| **Recherche naturelle Google** | **483** | **56 %** | SEO local Metz = canal #1, à protéger lors de la migration |
| Direct | 343 | 40 % | Bouche-à-oreille, cartes de visite, signature email, recommandations |
| Bing | 21 | 2 % | Audience B2B Microsoft 365 — signal faible mais qualifié |
| Yahoo | 9 | 1 % | Marginal |
| DuckDuckGo | 3 | <1 % | Marginal |
| **ChatGPT** | **2** | <1 % | ⚠️ **Signal émergent — AEO à anticiper** |
| **Perplexity** | **1** | <1 % | Idem |
| Référents (Bottin, consultation.avocat.fr, …) | 5 | <1 % | Annuaires juridiques sous-exploités |
| Facebook | 1 | <1 % | Quasi nul — Instagram & FB à archiver côté B2B |

### Trois lectures stratégiques
1. **SEO Google = colonne vertébrale** (56 %). Plan de redirections 301 indispensable lors de la refonte.
2. **Direct = 40 %** : audience qui *sait* qui elle cherche. La home doit donc être à la fois **landing SEO** ET **page de contact express** (nom, tel, prise de RDV en hero).
3. **ChatGPT + Perplexity = 3 sessions** : c'est 1‰ du trafic, mais c'est un **nouveau canal**. La v2 doit être pensée AEO (schema.org Organization + Person + LegalService, FAQ, contenu citable, sitemap propre).

---

## 3. Pages consultées (vue consolidée 12 mois)

| Page | Vues | Sessions | % du trafic |
|---|---|---|---|
| `/` (Accueil) | **1 031** | **856** | **80 %** |
| **`/reserver-en-ligne`** | **115** | **96** | **9 %** |
| `/droit-du-travail` | 81 | 69 | 6,4 % |
| `/droit-penal-du-travail` | 25 | 23 | 2,1 % |
| `/droit-de-la-securite-sociale` | 20 | 20 | 1,9 % |
| Ancres home (#dataitem-…) | ~10 | ~10 | <1 % |

### Trois observations majeures
1. **La home concentre 80 % du trafic**. C'est elle qui doit faire le travail de conversion — pas les pages internes.
2. **La page RDV est la 2e plus visitée (`/reserver-en-ligne` = 96 sessions / 89 visiteurs)**. Soit ~8 % des visiteurs uniques arrivent jusqu'à la page de prise de RDV. **C'est un excellent ratio**, mais il faut traquer le taux de complétion (qui prend effectivement RDV ?).
3. **Les pages expertise sont sous-utilisées** : `/droit-du-travail` 69 sessions (= 5/mois), pénal 23, sécurité sociale 20. Le contenu actuel n'est ni assez profond, ni assez maillé, ni assez optimisé SEO.

### Funnel actuel (estimé)
```
Home (856 sessions)
  ├─ 11 % → /reserver-en-ligne (96)
  ├─ 8 %  → /droit-du-travail (69)
  ├─ 3 %  → /droit-penal-du-travail (23)
  └─ 2 %  → /droit-de-la-securite-sociale (20)
```
→ Les visiteurs **court-circuitent les pages expertise** et vont directement à la prise de RDV. Trois interprétations possibles : (a) la home suffit à convaincre, (b) les pages expertise sont jugées d'avance pauvres, (c) la cible cherche d'abord à parler à quelqu'un.

---

## 4. Appareils

| Appareil | Sessions | % | Vues / session |
|---|---|---|---|
| **Ordinateur** | **468** | **54 %** | 1,46 |
| **Mobile** | **401** | **46 %** | 1,49 |
| Tablette | 1 | <1 % | 1,00 |

### Lecture
- **Mix presque 50/50** — la v2 doit être **mobile-first** sans compromis. Les dirigeants consultent depuis leur téléphone aussi souvent que depuis leur bureau.
- Quasi-parité des vues/session : pas de pénalité d'engagement sur mobile. Mais c'est un seuil bas (1,5) qui peut largement progresser.

---

## 5. Géographie

| Pays | Sessions | % | Lecture B2B |
|---|---|---|---|
| **France** | **743** | **85 %** | Cœur de cible |
| Pays-Bas | 39 | 4,5 % | Probablement bots — peu probable B2B Metz |
| États-Unis | 34 | 3,9 % | Bots / VPN — à filtrer |
| Allemagne | 10 | 1,1 % | Hors cible commerciale (positionnement non transfrontalier) |
| Russie | 9 | 1,0 % | Bots — à filtrer |
| Luxembourg | 7 | 0,8 % | Hors cible commerciale (positionnement non transfrontalier) |
| Suède | 4 | 0,5 % | Bots |
| Belgique | 4 | 0,5 % | Frontalier marginal |
| Reste | <30 | <3 % | Long tail / bots |

### Lecture
- **France = 85 %** : cohérent.
- **~25 % du trafic non-FR est probablement du bot/spam** (Pays-Bas, USA, Russie, Suède, Singapour, Pakistan, etc.). À filtrer en GA4 (`Internal Traffic` + `Unwanted Referrals`).
- **Allemagne + Luxembourg = 17 sessions** : hors cible commerciale (le cabinet ne se positionne pas sur le transfrontalier). Pas de version EN/DE prévue. Le trafic résiduel sera à filtrer comme bruit dans GA4.

---

## 6. Temporalité

Pas de tendance lisible exploitable sur les données « jour × heure » (volume trop dispersé pour des moyennes utiles). À observer en revanche dans les données daily :

- **Pics ponctuels** : 13/01/2026 (17 vues, 8 sessions), 23/12/2025 (19 vues, 9 sessions), 17/03/2026 (13 vues, 7 sessions) — sans cause claire (peut-être campagne LinkedIn, recommandations RH, ou pic SEO).
- **Beaucoup de jours à 100 % de rebond** sur 1-3 sessions (= sessions de vérification rapide).
- **Durées atypiques** : 28min, 20min, 14min, 12min observées — vraisemblablement des onglets oubliés, à exclure du calcul moyen.

→ La saisonnalité demande **GA4 + 6 mois supplémentaires** pour être interprétable.

---

## 7. Diagnostic global

### ✅ Forces
- **SEO local Google** déjà ancré (56 % du trafic).
- **Trafic direct fort** (40 %) = capital de marque/recommandation existant.
- **Page RDV bien consultée** (9 % des sessions) : preuve que l'intention de contact est réelle.
- **Mix desktop/mobile équilibré** : pas de migration UX à arbitrer.

### ⚠️ Faiblesses critiques
1. **Aucun tracking de conversion** → impossible de mesurer le ROI.
2. **80 % de rebond** sans qualification du rebond utile vs. rebond perdu.
3. **Pages expertise indigentes** : 5 sessions/mois en moyenne sur `/droit-du-travail`.
4. **Pas de blog éditorial sur le domaine** → autorité éditoriale fuit vers `consultation.avocat.fr`.
5. **Pollution bot** non filtrée (≈ 10–15 % du trafic non-FR).
6. **Funnel non instrumenté** : on ne sait pas combien des 96 sessions sur la page RDV aboutissent à une réservation.

### 🚀 Opportunités
- **Multiplication par 5 à 10 du trafic SEO** réaliste sur 12 mois si stratégie éditoriale B2B mise en place (chaque sous-page expertise + chaque article blog = porte d'entrée).
- **AEO/IA** : se positionner tôt sur ChatGPT / Perplexity / Google AI Overview comme **référence droit social employeur Grand Est**.
- **Conversion mesurable** : passer de 0 à un funnel traqué = base de pilotage.
- **Bascule employeur** : reciblage du wording = ré-indexation Google sur des requêtes B2B (« avocat URSSAF Metz », « licenciement économique PME Grand Est », « défense pénale dirigeant », etc.).

---

## 8. Implications pour la refonte v2

### 8.1 Tracking obligatoire dès le J1 de la v2
- **GA4** (et migrer si possible le Wix Analytics existant en parallèle 6 mois pour la continuité).
- **Google Search Console** liée au nouveau domaine / propriété.
- **Hotjar ou Microsoft Clarity** (gratuit) pour heatmaps + enregistrements.
- **Objectifs de conversion** :
  - Clic sur `tel:` (mobile prioritaire)
  - Clic sur `mailto:`
  - Soumission du formulaire de contact
  - Ouverture du module de RDV
  - **Confirmation effective de RDV** (callback Calendly/Wix Bookings)
  - Téléchargement de livre blanc
  - Scroll 75 % sur page expertise
- **Filtres anti-bots** : exclure Russie, Pays-Bas, USA (sauf si IP corporate identifiée), Singapour, Pakistan.
- **UTM systématiques** sur tous les liens externes (LinkedIn, signature email, cartes de visite, QR codes).

### 8.2 Architecture pensée pour l'usage réel observé

Compte tenu que **80 % du trafic reste en home** et que **9 % va direct au RDV**, la home v2 doit jouer **trois rôles simultanés** :

| Rôle | Composant home | KPI cible |
|---|---|---|
| Landing SEO | H1 + 3 piliers + cas client + FAQ longue | Position moyenne < 5 sur « avocat droit travail Metz » et 20 expressions B2B |
| Page de contact express | Bloc « Contacter » sticky : tel, RDV, email — visible dès le 1er écran mobile | Taux clic tel mobile > 8 % |
| Sas de qualification | Switch persona (Dirigeant TPE / DRH / Direction juridique) | Taux clic switch > 15 % |

### 8.3 Pages expertise à enrichir massivement
- Cible : passer de 5 sessions/mois/expertise à **50+** sur 12 mois.
- Moyens : chaque page pilier 1 500–2 500 mots, 3 à 5 sous-pages SEO ciblées, FAQ schema.org, illustrations, cas clients, CTA contextuels.
- Indicateur : nombre de pages indexées Google **× 10** (de ~8 à ~80 sur 12 mois).

### 8.4 Funnel RDV instrumenté
- 96 sessions sur `/reserver-en-ligne` aujourd'hui sans aucune mesure de conversion finale.
- Hypothèse de travail : taux de conversion arrivée page RDV → RDV pris **15–25 %** (référence sectorielle B2B services).
- Donc estimation actuelle : **15–25 RDV/an issus du web** (à valider auprès de Coralie).
- Cible v2 : **60–100 RDV/an** issus du web à 12 mois (× 3 à × 4), en combinant : SEO élargi + tracking + nouveau funnel + LinkedIn.

### 8.5 KPI cibles à 6 et 12 mois (post-mise en ligne)

| Indicateur | Aujourd'hui | Cible 6 mois | Cible 12 mois |
|---|---|---|---|
| Sessions / mois | 73 | 150 | 300+ |
| Pages indexées Google | ~8 | ~30 | ~80 |
| Position moyenne « avocat droit travail Metz » | ~? | top 5 | top 3 |
| Taux de rebond home | 80 % | 65 % | 55 % |
| Vues / session | 1,5 | 2,2 | 2,8 |
| RDV pris via le site | non mesuré | ≥ 30 sur 6 mois | ≥ 60 sur 12 mois |
| Clic tel mobile | non mesuré | ≥ 8 % des sessions mobile | ≥ 12 % |
| Articles blog publiés | 0 (sur domaine) | 12 | 30 |
| Mentions ChatGPT / Perplexity | 3 | 15 | 40 |

### 8.6 Quick wins pré-refonte (à activer sur le site Wix actuel d'ici la mise en ligne)
1. **Brancher GA4** sur le Wix actuel (15 min) → commence à collecter dès maintenant.
2. **Mettre des numéros `tel:` cliquables** partout (Wix le permet en 5 min).
3. **Filtrer les pays bot** dans Wix Analytics.
4. **Retirer la mention « congé maternité jusqu'au 1er oct. 2025 »** (déjà acté).
5. **Activer Google Business Profile** (avis, photos, posts mensuels) si pas encore fait — c'est le levier #1 pour le SEO local Metz.
6. **Mise à jour des annuaires** (Doctrine, Justifit, PagesDuPalais, etc.) : retirer toute mention « salariés » dès maintenant pour préparer le pivot.

---

## 9. Prochaines étapes

1. **Validation** des KPI cibles avec Coralie Schumpf (en particulier le volume actuel réel de RDV web qu'elle constate côté agenda).
2. **Activation GA4** sur le Wix actuel (peut être faite par toi en 15 min).
3. **Export Google Search Console** : 16 mois de requêtes, pages, CTR, position — c'est la donnée qui manque encore et qui pilotera le plan éditorial.
4. **Wireframes v0.1** intégrant les contraintes du funnel observé : home tri-fonctionnelle, switch persona, sticky contact, instrumentation native.
5. **Plan éditorial blog** : 12 sujets premium ciblés B2B (employeur) sur la base des requêtes GSC.

---

## Sources

- `data/analytics/wix-traffic-sources-12m.csv` (sources de trafic)
- `data/analytics/wix-conversion-daily-12m.csv` (sessions quotidiennes)
- `data/analytics/wix-traffic-countries-12m.csv` (géographie)
- `data/analytics/wix-traffic-dayhour-12m.csv` (jour × heure)
- `data/analytics/wix-traffic-pages-12m.csv` (pages — extraction partielle)
- `data/analytics/wix-traffic-devices-12m.csv` (appareils)
- `data/analytics/wix-traffic-visitortype-12m.csv` (nouveaux / réguliers)

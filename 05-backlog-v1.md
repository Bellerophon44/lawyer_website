# Backlog v1 — actions ouvertes

> Ce document consolide les actions qui sont **identifiées mais reportées à la v1 production** (hors POC). À mettre à jour au fil des décisions.
> Convention : `[ ]` = ouvert · `[~]` = en cours · `[x]` = fait.

---

## Assets de marque

- [ ] **Re-shoot photo professionnelle de Coralie** en session dédiée. Cible : **1200 × 1500 px minimum**, ratio 4/5 natif. Lumière directionnelle douce, fond uni couleur pierre ou bordeaux désaturé, tenue formelle business (pas robe d'avocat). Le placeholder POC actuel (230 × 342 px) est suffisant pour valider la DA mais sera upscalé sur grand écran.
- [ ] **Logo SVG HD** : récupération du fichier vectoriel original (Illustrator/Figma) pour caler définitivement les codes hex exacts du bordeaux et du gris.
- [ ] Iconographie sur-mesure (pictos B2B traits fins 1.25 px) en remplacement des SVG génériques actuels.

## Architecture & contenu

- [ ] **Sous-pages SEO** dérivées des trois piliers :
  - Droit du travail : licenciement & ruptures *(POC déjà créé)*, restructuration & PSE, négociation collective & CSE, contentieux prud'homal, harcèlement.
  - Droit pénal du travail : défense du dirigeant, accident grave / mise en danger, travail dissimulé, entrave.
  - URSSAF & sécurité sociale : contrôle & redressement URSSAF, AT/MP, faute inexcusable, taux d'incapacité.
- [ ] **Page « Méthode & honoraires »** dédiée (déclinaison longue de la section home Honoraires & modalités, avec exemples chiffrés et cas d'usage).
- [ ] **Mentions légales · RGPD · Cookies** (obligatoires barreau).
- [ ] **Glossaire** juridique (CDI, CDD, CSE, AT, MP, URSSAF, prescription, etc.) pour maillage interne.
- [ ] **FAQ globale** structurée schema.org `FAQPage` (au-delà de la FAQ d'une page pilier).

## Stack technique & publication

- [ ] **Bascule de Wix vers Netlify/Vercel** (scénario B du doc `04-publication-et-editorial.md`).
- [ ] **Plan de redirections 301** : `/votre-avocate` → `/cabinet/coralie-schumpf`, `/reserver-en-ligne` → `/premier-rendez-vous`, et toutes les URLs existantes à préserver.
- [ ] **Sitemap.xml** à générer et soumettre à Google Search Console.
- [ ] **Schema.org markup** : `LegalService`, `Person`, `Article` (sur chaque post de blog), `FAQPage`, `BreadcrumbList`.
- [ ] **Polices auto-hébergées** (Fraunces + Inter) au lieu de Google Fonts pour la perf et la confidentialité.
- [ ] **Images en AVIF** + `loading="lazy"`.
- [ ] **Lighthouse cible 95+** sur toutes les métriques (Performance, Accessibility, Best Practices, SEO).
- [ ] **Cible Lighthouse Performance** : LCP < 2 s, CLS < 0.1, INP < 200 ms.
- [ ] **Mode sombre** en option (switch utilisateur, pas défaut).

## Tracking & mesure

- [ ] **Activation GA4** (à faire **dès maintenant** sur le Wix actuel pour collecter une baseline 4-6 semaines avant bascule). Étape : 15 min dans l'admin Wix.
- [ ] **Google Search Console** : vérification de propriété, soumission sitemap, suivi des requêtes / pages / CTR.
- [ ] **Microsoft Clarity** ou Hotjar (heatmaps + enregistrements anonymes).
- [ ] **Objectifs de conversion** GA4 : clic tel, clic mail, soumission formulaire, ouverture module RDV, confirmation RDV Calendly, scroll 75 % page expertise, téléchargement livre blanc.
- [ ] **Filtres anti-bots** GA4 : exclure Russie, Pays-Bas (sauf IP corporate), USA non-FR, etc.
- [ ] **UTM systématiques** sur tous les liens externes (LinkedIn, signature mail, cartes de visite, QR codes).

## Conversion & outils interactifs

- [ ] **Calendly ou Cal.com** branché sur la page Premier rendez-vous (avec règlement 200 € HT en amont via Stripe ou Mollie).
- [ ] **Calculateurs employeur** (signaux de modernité fonctionnelle + ROI SEO) :
  - Calculateur d'indemnité de licenciement (barème Macron).
  - Estimateur du coût d'un litige prud'homal (haute fourchette / basse fourchette).
  - Diagnostic de conformité RH (10 questions → score + livre blanc).
  - Vérificateur de prescription URSSAF (3 ans / 5 ans).
  - Self-audit AT/MP « êtes-vous exposé à la faute inexcusable ? ».

## Réassurance B2B

- [ ] Logos secteurs représentés (« Industrie · BTP · Retail · Services ») en frise discrète (sans nom client).
- [ ] Intégration des **avis Google Business Profile** sur la home (widget léger).
- [ ] Distinctions / classements à recenser (Décideurs, Leaders League, Le Monde du Droit).
- [ ] Adhésions professionnelles à confirmer avec Coralie (AvoSial, AFDT, etc.).

## Quick wins pré-refonte (sur le Wix actuel)

- [x] Retirer la mention « congé maternité jusqu'au 1er oct. 2025 » *(coté Coralie/Wix admin, déjà demandé)*.
- [ ] Brancher GA4 sur le Wix actuel.
- [ ] Mettre des numéros `tel:` cliquables partout.
- [ ] Activer/optimiser **Google Business Profile** (avis, photos, posts mensuels) — levier #1 du SEO local Metz.
- [ ] **Nettoyer les annuaires juridiques** (Doctrine, Justifit, PagesDuPalais, Lawzana, MonExpertDuDroit, EU-Avocat, Allaw…) : retirer toute mention « salariés », retirer Facebook, ajouter Instagram là où c'est possible.

## Plan éditorial agents

- [ ] **Atelier voix Coralie** (90 min) : 5 articles de référence + 3 anti-modèles, lexique, structure type.
- [ ] **Document `editorial/voix-coralie.md`** versionné comme system prompt des agents.
- [ ] **Garde-fous déontologiques** RIN-compliant explicites.
- [ ] **Setup des 7 agents** dans `.claude/agents/` (Veilleur, Stratège, Briefer, Rédacteur, Éditeur juridique, Éditeur éditorial, Distributeur).
- [ ] **Pilote 4 semaines / 2 articles** avec bilan qualité + temps Coralie.
- [ ] **Régime de croisière** : 2-3 articles/mois, mesure mensuelle.
- [ ] **Distribution sociale** automatisée (LinkedIn J0 + J+3, Google Business Profile, newsletter).

## Décisions actées (rappel)

- ✅ Pivot 100 % employeur (drop salariés).
- ✅ DA bordeaux + ivoire + Fraunces/Inter.
- ✅ Switch persona 2 cibles (Dirigeant TPE/PME + DRH).
- ✅ Premier rendez-vous payant 200 € HT (1 h, pas de consultation écrite, proposition chiffrée à la sortie).
- ✅ Mission Annuelle 200 € HT/h, Mission ponctuelle 230 € HT/h, Contentieux 2 000-5 000 € HT forfait + honoraires de résultat.
- ✅ Pas d'EN/DE (positionnement non transfrontalier).
- ✅ Scénario publication : Netlify/Vercel + DNS basculé.
- ✅ Plan éditorial : agents-assistants avec Coralie signataire finale.
- ✅ Réseaux : LinkedIn + Instagram (Facebook retiré).

# Stratégie de publication & plan éditorial agents

> Document de cadrage : (1) comment passer le site refondu en production en conservant l'URL `schumpf-avocat.com` et son référencement Google, (2) comment industrialiser la production éditoriale via une équipe d'agents IA sans compromettre la rigueur juridique et la voix de Coralie.

---

## 1. Stratégie de publication

### 1.1 Préambule — GA4, qu'est-ce que c'est et pourquoi maintenant ?

**GA4** (Google Analytics 4) est l'outil gratuit et standard de mesure d'audience web édité par Google. Il a remplacé l'ancien « Universal Analytics » en juillet 2023. Il fournit :

- Le **suivi des conversions** : combien de visiteurs cliquent sur « Appeler », « Réserver », « Télécharger », ou complètent un formulaire.
- L'**intégration native avec Google Search Console** : on voit quelles requêtes Google amènent réellement du trafic *et* convertissent.
- L'**export propre** (CSV, BigQuery), des **audiences personnalisées**, des **événements personnalisés**.
- **Gratuit** pour tout volume sous 10 M d'événements/mois.

À ce jour, le site Coralie n'a que les **statistiques basiques Wix Analytics** (celles exploitées dans `03-audit-analytics.md`). Suffisantes pour un constat, insuffisantes pour piloter une refonte.

**Recommandation : activer GA4 sur le Wix actuel dès maintenant.**

#### Bénéfice d'une activation pré-refonte
4 à 6 semaines de données GA4 sur l'ancien site = on connaîtra **précisément** :
- Les pages qui drainent le SEO réel (vs. ce qu'on en suppose).
- Les requêtes qui convertissent (vs. les requêtes qui amènent juste du trafic).
- Le comportement mobile vs desktop.
- Les heures de pic, le profil d'audience.

→ On calibre la v1 sur des données factuelles, pas des intuitions.

#### Comment activer GA4 sur Wix (15 min, sans compétence technique)
1. Créer une propriété GA4 sur `analytics.google.com` (compte Google de Coralie).
2. Copier le **« Measurement ID »** (format `G-XXXXXXXXXX`).
3. Dans l'admin Wix → **Marketing & SEO → Marketing Integrations → Google Analytics**.
4. Coller le Measurement ID, **Connect**.
5. Optionnel : connecter aussi **Google Search Console** (`search.google.com/search-console`) avec la même propriété GA4 pour la jonction des données.

> Si tu veux, je te fais le pas-à-pas commenté avec captures à l'étape Wix.

---

### 1.2 Trois scénarios de mise en production

| Scénario | Hébergeur | Effort | Risque SEO | Coût | Recommandation |
|---|---|---|---|---|---|
| **A** — Refonte dans Wix Studio | Wix (inchangé) | 2-3 sem. | Nul | abonnement Wix | Sûr mais design dégradé |
| **B** — Site moderne sur Netlify/Vercel + DNS basculé | Netlify ou Vercel | 4-6 sem. | Faible avec 301 | 0 € (free tier) | **✅ Recommandé** |
| C — Migration vers WordPress | OVH ou similaire | 5-7 sem. | Moyen | 5-15 €/mois | Si Coralie veut auto-éditer |

**Hypothèse retenue pour la suite : scénario B.**

#### Pourquoi B ?
- **Design fidèle au POC** : le HTML/CSS/JS qu'on a déjà fonctionne tel quel.
- **Performance** : un site statique sur Netlify charge en < 1 s, vs ~4-6 s sur Wix.
- **SEO mieux contrôlé** : on contrôle 100 % du HTML, des balises, du schema.org, du sitemap.
- **Outils interactifs natifs** : calculateurs, simulateurs, FAQ schema, AEO (IA Overview) — tout est possible.
- **Coût** : 0 € d'hébergement (free tier Netlify suffit pour ce volume). On peut résilier Wix une fois stable.
- **Continuité** : `schumpf-avocat.com` reste l'URL principale, aucun client ne voit la différence.

#### Inconvénients à gérer
- **Édition** : Coralie ne pourra plus modifier le site comme dans Wix (drag & drop). Solution : soit Coralie ne modifie pas (les contenus seront mis à jour par toi ou via le pipeline éditorial agents, voir §2), soit on couple à un CMS *headless* simple type **Decap CMS** (interface web minimaliste, gratuit, fonctionne avec GitHub).
- **DNS** : il faut une opération de bascule, à faire en heure creuse.
- **Module de RDV** : si Coralie utilise Wix Bookings, à remplacer par **Calendly** (gratuit) ou **Cal.com** (open source).

---

### 1.3 Plan de bascule en 6 étapes — Scénario B

#### Étape 1 — Activer GA4 sur le Wix actuel (J-30 à J-45)
Cf. §1.1.

#### Étape 2 — Construire la v1 sur la base du POC (J-30 à J-7)
- Compléter les pages manquantes (URSSAF, pénal, AT/MP, méthode & honoraires, mentions légales, RGPD).
- Intégrer le portrait, le logo SVG HD, le contenu éditorial validé.
- Ajouter le schema.org : `LegalService`, `Person`, `FAQPage`, `Article`.
- Brancher Calendly (ou Cal.com) pour le RDV payant 200 € HT.
- Brancher GA4 + Hotjar/Clarity (heatmaps) sur le nouveau site.
- Optimiser les images (AVIF, lazy loading), polices auto-hébergées, score Lighthouse cible 95+.

#### Étape 3 — Déployer sur URL temporaire (J-7 à J-3)
- Push sur Netlify → URL preview type `https://schumpf-v1.netlify.app`.
- Relecture finale par Coralie sur l'URL temporaire.
- Tests : navigation, formulaire RDV, responsive mobile, vitesse, conformité RGPD (consentement cookies).

#### Étape 4 — Préparer le plan de redirections 301 (J-3)
URLs à conserver à l'identique (déjà bien rankées sur Google) :
```
/                              → /              (home)
/droit-du-travail              → /droit-du-travail
/droit-penal-du-travail        → /droit-penal-du-travail
/droit-de-la-securite-sociale  → /droit-de-la-securite-sociale
/votre-avocate                 → /votre-avocate (ou /cabinet/coralie-schumpf)
/approche                      → /approche (ou /methode-et-honoraires)
/contact                       → /contact
/reserver-en-ligne             → /premier-rendez-vous (301)
```
Pour les nouvelles routes (sous-pages SEO, blog), pas de redirection — c'est du contenu neuf.

#### Étape 5 — Bascule DNS (Jour J, en soirée pour minimiser l'impact)
1. Dans Netlify : ajouter `schumpf-avocat.com` comme custom domain.
2. Chez le registrar du domaine (Wix ou un autre) : modifier l'**enregistrement A** (ou CNAME selon le cas) pour pointer vers les serveurs Netlify.
3. SSL Let's Encrypt auto-provisionné en 5-10 min.
4. Wix reste actif en backup une semaine (DNS TTL permettant un rollback si crise).

#### Étape 6 — Surveillance post-bascule (J+1 à J+60)
- **GSC** : soumettre le nouveau sitemap.xml. Surveiller : erreurs de crawl, pages indexées, position moyenne sur 20 requêtes clés.
- **GA4** : conversion, taux de rebond, vitesse.
- **Performance** : Lighthouse hebdo, PageSpeed Insights.
- **Crise possible** : chute de trafic > 30 % deux semaines de suite → on diagnostique (redirections, schéma, indexation), on corrige.

**Référence sectorielle** : sur ce type de migration bien préparée, on observe en général une baisse de 5-15 % du trafic pendant 2-4 semaines, suivie d'un rebond à +20-40 % à 3-6 mois (le nouveau site rankant mieux que l'ancien grâce à la performance et au contenu).

---

## 2. Plan éditorial — équipe d'agents

### 2.1 Position de principe : agents-assistants, pas agents-autonomes

Trois risques propres au métier d'avocat interdisent la pleine autonomie :

1. **Hallucinations juridiques.** Les LLM (Claude inclus) inventent régulièrement des références (« Cass. Soc. 12 mars 2023 n° 21-12345 ») inexistantes. **La vérification humaine des sources juridiques est non-négociable.**
2. **Déontologie.** Le RIN (Règlement Intérieur National du Barreau) encadre la communication des avocats : pas de promesse de résultat, pas de comparaison nominative, pas de démarchage actif. Un agent qui produit en masse peut franchir une ligne par accident.
3. **Voix éditoriale.** Le positionnement « conseil de direction premium, sans jargon » nécessite un filtre éditorial humain.

**Conséquence** : Coralie reste l'autrice finale signataire de chaque article. Les agents font le gros œuvre, elle conserve les 3 actes humains incompressibles :
- ✅ Sélection des sujets dans la short-list mensuelle.
- ✅ Vérification juridique du draft (sources, jurisprudence citée).
- ✅ Signature finale.

**Ratio cible** : 5-10 h/mois de Coralie pour 2-3 articles publiés/mois (24-36/an).

### 2.2 Équipe à 7 agents

| # | Agent | Job to be done | Input | Output |
|---|---|---|---|---|
| 1 | **Veilleur** | Surveille mensuellement la jurisprudence Cass. Soc., JO, presse RH (Liaisons Sociales, Décideurs, Les Échos Exécutif), requêtes GSC montantes | Flux RSS, API GSC | Liste 30-50 sujets/mois |
| 2 | **Stratège** | Filtre par persona (Dirigeant TPE / DRH PME), par pilier, scoring SEO (volume × intent × différenciation) | Output #1 | Short-list 6-10 sujets/mois |
| 3 | **Briefer** | Pour chaque sujet validé par Coralie : titre H1, public, intention recherche, plan H2/H3 détaillé, mots-clés, sources juridiques à citer, CTA contextuel | Sujet validé | Brief 1 page |
| 4 | **Rédacteur** | Long form 1200-2000 mots respectant le brief + voix Coralie | Brief + document de voix | Draft v1 |
| 5 | **Éditeur juridique** | Vérification croisée des références (Légifrance, jurisprudence), traque les hallucinations | Draft v1 + accès Légifrance | Draft v1 corrigé + liste alertes à valider par Coralie |
| 6 | **Éditeur éditorial** | Voice & tone Coralie, lisibilité, SEO on-page (H1, méta description, alt, maillage interne) | Draft v1 corrigé | Draft v2 prêt à signature |
| 7 | **Distributeur** | À J de publication : 2 posts LinkedIn (teaser J0, follow-up J+3), extrait newsletter, post Google Business Profile | Article publié | Kit social complet |

### 2.3 Pipeline opérationnel (5 jours ouvrés par article)

```
J0    : Veilleur → Stratège → short-list mensuelle (le 1er du mois)
J+2   : Coralie sélectionne 2-3 sujets dans la short-list
J+3   : Briefer produit les briefs
J+4   : Rédacteur produit le draft v1
J+5   : Éditeur juridique vérifie + remonte les alertes
J+6   : Coralie revoit, valide les références, complète si besoin
J+7   : Éditeur éditorial produit le draft v2
J+8   : Coralie signe (lecture finale 15 min)
J+9   : Publication + Distributeur déclenche le kit social
J+12  : Distributeur publie le follow-up LinkedIn
```

### 2.4 Alternatives mises en regard

| Approche | Coût mensuel | Temps Coralie | Risque | Verdict |
|---|---|---|---|---|
| **A. Agents-assistants** | ~50 € API Claude | 5-10 h | Faible si validation humaine | ✅ Recommandé |
| B. Full agents autonomes | ~50 € API | 0-2 h | **Élevé** (juridique, déonto, voix) | ❌ Déconseillé |
| C. Rédacteur juridique freelance | 1500-2500 € | 2-3 h | Faible (humain qualifié) | Plan B si A échoue |
| D. Coralie écrit elle-même | 0 € | 24-36 h | Nul, mais inscalable | Statu quo dégradé |
| E. Ne rien faire | 0 € | 0 h | SEO plafonné à 73 sessions/mois | Cohérent uniquement si LinkedIn devient le canal principal |

### 2.5 Step-by-step exécution

#### Phase 0 — Cadrage (1 semaine)

**Atelier voix Coralie** (90 min) :
- Choix de 5 articles de référence qu'elle aime (qu'elle aimerait avoir écrits) + 3 anti-modèles (à ne surtout pas reproduire).
- Définition du **lexique** (« employeur » vs « entreprise », « salarié » vs « collaborateur », tutoiement / vouvoiement…).
- Définition de la **structure type** (intro percutante, listing 5 points, callout, conclusion-action).

**Acter les piliers éditoriaux** :
- Droit du travail : 60 % des articles.
- Droit pénal du travail : 20 %.
- Sécurité sociale & URSSAF : 20 %.

**Garde-fous déontologiques** :
- Pas de pourcentage de réussite affiché.
- Pas de citation nominative d'un confrère ou d'un opposant.
- Pas de promesse de résultat.
- Mention « *Cet article a une vocation pédagogique et ne constitue pas une consultation juridique* » obligatoire en pied.

**Livrable** : un fichier `editorial/voix-coralie.md` qui sert de system prompt à tous les agents.

#### Phase 1 — Pilote sur 4 semaines

**Semaine 1** : setup technique des agents.
- Stack envisagée : **sub-agents Claude Code dans `.claude/agents/`** (chacun avec son system prompt et ses outils autorisés). Le repo devient la machine.
- Alternative : Claude Agent SDK Python + cron quotidien (plus robuste, demande plus de mise en place initiale).
- Mise en place des accès : flux RSS Cass. Soc. / JO / Légifrance API / GSC API.

**Semaine 2-3** : production des 2 premiers articles pilotes.
- Sujets ciblés : un sur le droit du travail (large), un sur l'URSSAF (chaud post-réforme).
- Mesure du temps Coralie, du nombre de cycles de réécriture, de la qualité ressentie.

**Semaine 4** : bilan pilote.
- Critères de succès :
  - Temps Coralie ≤ 2 h par article publié.
  - Pas d'alerte juridique majeure (référence inventée, déontologie franchie).
  - Voix éditoriale jugée « 80 % juste » par Coralie au draft v1.
- Si succès → Phase 2.
- Si échec → bascule sur option C (rédacteur freelance) ou rétro-conception des prompts.

#### Phase 2 — Régime de croisière (continu)

- Cadence 2-3 articles/mois.
- Mesure mensuelle : positions GSC sur les nouveaux articles, sessions GA4 sur le blog, engagement LinkedIn, RDV générés via les articles.
- Ajustement trimestriel du document de voix et des briefs.
- **Veille réactive** : si un arrêt majeur tombe (Cass. Soc. impactant la PME), le Veilleur déclenche un article réactif en 48h.

#### Phase 3 — Optimisation (T+6 mois)

- Bilan complet : trafic gagné, RDV générés, mentions presse, retours qualitatifs prospects.
- Décision : scale (3 → 4 articles/mois), pivoter (rédacteur freelance pour des dossiers de fond + agents pour le quotidien), ou stabiliser.

### 2.6 Distribution sociale détaillée

Pour chaque article publié, l'agent Distributeur prépare automatiquement :

**LinkedIn — Post J0** (teaser)
- Hook en 1ère ligne (la question-douleur du dirigeant).
- Sortie d'un chiffre saillant ou d'une formule percutante extraite de l'article.
- Carrousel 3-5 slides (titre + 3 erreurs / 3 leviers / 3 chiffres) à partir du même contenu.
- CTA : « Article complet en lien · 6 min de lecture ».

**LinkedIn — Post J+3** (follow-up)
- Format différent : extrait textuel + 1 question ouverte à la communauté.
- Tagging d'un confrère ou d'une association pro pertinente (avec leur accord).

**Newsletter mensuelle**
- Récap des articles publiés + 1 actu réglementaire (sans article dédié).
- Si Coralie n'a pas de newsletter aujourd'hui : à mettre en place (Substack ou ConvertKit, gratuit pour ce volume).

**Google Business Profile**
- 1 post par article publié (Google valorise GBP actif pour le SEO local).
- Photo + 1500 caractères + lien vers l'article.

### 2.7 Stack technique recommandée

#### Option 1 — Sub-agents Claude Code (recommandé pour démarrer)
- Chaque agent = un fichier `.md` dans `.claude/agents/` avec son system prompt et ses outils.
- Déclenchement manuel ou via slash commands (`/veilleur`, `/redacteur`, etc.).
- Stockage des outputs dans le repo (`content/drafts/`, `content/briefs/`).
- Coralie valide via PR GitHub (commentaires, suggestions inline).
- **Avantage** : 0 € d'infra, tout est versionné.

#### Option 2 — Pipeline Python + Claude API + cron (à terme)
- Script Python lancé chaque 1er du mois.
- Output dans une base Notion ou Airtable lisible par Coralie.
- **Avantage** : automatisation complète sans intervention d'un opérateur Claude Code.

#### Option 3 — n8n / Make / Zapier
- Workflow visuel sans code, avec appels API Claude.
- **Avantage** : Coralie ou un assistant peut faire évoluer le workflow elle-même.

→ Démarrer **option 1** (rapide, gratuit, dans Claude Code). Migrer en **option 2** à 6 mois si le pilote est concluant.

### 2.8 Risques résiduels & mitigation

| Risque | Probabilité | Impact | Mitigation |
|---|---|---|---|
| Hallucination juridique publiée | Moyenne | Élevé | Vérif Coralie obligatoire + agent éditeur juridique dédié |
| Voix éditoriale dégradée | Moyenne | Moyen | Document de voix versionné + pilote sur 2 articles avant scale |
| Manquement déontologique RIN | Faible | Élevé | Garde-fous explicites + relecture finale Coralie |
| Sur-publication = bruit | Faible | Faible | Cadence cappée à 3/mois |
| Dépendance technique aux agents | Moyenne | Moyen | Documenter le workflow, possible reprise par humain |
| Coût IA imprévu | Faible | Faible | Budget API mensuel monitoré (~50 €) |

---

## 3. Prochaines étapes

1. **Validation par Coralie** des choix de scénario (B pour la publication, A pour l'éditorial).
2. **Activation GA4** sur le Wix actuel (1 séance de 15 min — je guide).
3. **Atelier voix Coralie** (90 min, à planifier).
4. **Setup des 7 agents** en sub-agents Claude Code dans `.claude/agents/` (~2 jours de mise en place).
5. **Pilote 2 articles** sur 4 semaines.
6. En parallèle : développement de la v1 du site sur la base du POC.

---

## Décisions actées dans ce document

- ❌ Versions EN/DE : abandonnées (positionnement non transfrontalier).
- ✅ GA4 : à activer **avant** la refonte pour collecter 4-6 semaines de baseline.
- ✅ Scénario de publication : **B** (Netlify/Vercel + DNS basculé), domaine `schumpf-avocat.com` conservé.
- ✅ Plan éditorial : **agents-assistants** (option A), Coralie reste signataire, 2-3 articles/mois, pilote de 4 semaines avant scale.

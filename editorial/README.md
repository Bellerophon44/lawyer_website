# Pipeline éditorial — Cabinet Coralie Schumpf

> Mode d'emploi du pipeline à 7 agents IA pour la production d'articles juridiques signés Coralie Schumpf. Modèle **agents-assistants** : les agents font le gros œuvre, Coralie garde 3 actes humains incompressibles.

---

## Pourquoi ce pipeline

Le cabinet veut produire **2 à 3 articles par mois** (24-36/an) pour :
- Rapatrier l'autorité éditoriale aujourd'hui éparpillée sur `consultation.avocat.fr`.
- Multiplier les pages SEO-optimisées (cible : x10 sur 12 mois).
- Faire vivre LinkedIn et Instagram.
- Activer GBP comme levier SEO local.

Sans surcharger l'agenda de Coralie : **5 à 10 h/mois**, vs ~24-36 h si elle écrivait elle-même.

## Architecture en 7 agents

| # | Agent | Rôle | Modèle | Output |
|---|---|---|---|---|
| 1 | `veilleur` | Surveille jurisprudence, JO, presse RH, Google | Haiku | Liste 30-50 sujets/mois |
| 2 | `stratege-editorial` | Filtre, score, priorise | Sonnet | Short-list 6-10 sujets/mois |
| 3 | `briefer` | Produit le brief de rédaction | Sonnet | Brief 1 page |
| 4 | `redacteur` | Rédige le draft v1 | Opus | Article 1200-2000 mots |
| 5 | `editeur-juridique` | Vérifie les références juridiques | Opus | Rapport + draft corrigé |
| 6 | `editeur-editorial` | Applique voix + SEO on-page | Sonnet | Draft v2 prêt signature |
| 7 | `distributeur` | Prépare le kit social | Haiku | Posts LinkedIn + GBP + newsletter |

Définitions complètes dans `.claude/agents/`.

## Les 3 actes humains incompressibles de Coralie

1. **Sélection des sujets** dans la short-list mensuelle (2-3 sur 6-10).
2. **Vérification juridique** du draft (sources, jurisprudence). Coralie tranche les `[À VALIDER PAR CORALIE]` remontés par l'éditeur juridique.
3. **Signature finale** du draft v2 (= Coralie endosse personnellement le contenu publié sous son nom).

## Cadence type — 10 jours ouvrés par article

```
J0  (1er du mois)    Veilleur → veille brute
J+1                  Stratège → short-list 6-10 sujets
J+2 → J+3            Coralie sélectionne 2-3 sujets
J+4                  Briefer (×2-3) → briefs
J+5                  Rédacteur → draft v1
J+6                  Éditeur juridique → draft v1 corrigé + rapport
J+7 → J+8            Coralie valide les références
J+9                  Éditeur éditorial → draft v2
J+10                 Coralie signe → publication + Distributeur lance le kit social
```

En régime de croisière, 2-3 articles tournent en parallèle, publication régulière 2-3 fois/mois.

## Comment lancer le pipeline (à l'opérateur)

Les agents sont des **sub-agents Claude Code**. Tu les invoques depuis une session ouverte dans ce repo, via le tool `Agent` en spécifiant `subagent_type: nom-agent`.

Exemples de prompts à donner à Claude :
- *« Lance le Veilleur pour produire la veille de juin 2026. »*
- *« Lance le Stratège sur la veille de juin pour produire la short-list. »*
- *« Lance le Briefer sur le sujet "Contrôle URSSAF inopiné" sélectionné par Coralie. »*
- *« Lance le Rédacteur sur le brief `content/briefs/2026-06-15-controle-urssaf-inopine.md`. »*
- *« Lance l'éditeur juridique sur le draft v1 `content/drafts/2026-06-16-controle-urssaf-v1.md`. »*
- *« Lance l'éditeur éditorial sur le draft v1 corrigé. »*
- *« Lance le Distributeur sur l'article publié. »*

Chaque agent lit ses fichiers de référence (`editorial/*.md`) au démarrage, produit son output dans `content/`, et te signale les points à valider.

## Structure du repo éditorial

```
editorial/
  README.md                  ← ce fichier
  voix-coralie.md            ← system prompt commun (voix, ton, lexique, structure)
  garde-fous-rin.md          ← contraintes déontologiques
  piliers.md                 ← pondération + template de brief

content/
  briefs/                    ← veilles mensuelles + short-lists + briefs de sujets
  drafts/                    ← drafts v1, drafts corrigés, drafts v2
  published/                 ← articles signés et publiés + kits de distribution

.claude/agents/
  veilleur.md
  stratege-editorial.md
  briefer.md
  redacteur.md
  editeur-juridique.md
  editeur-editorial.md
  distributeur.md
```

## Phase 1 — pilote de 4 semaines, 2 articles

| Semaine | Action |
|---|---|
| 1 | Atelier voix Coralie (90 min) → mise à jour de `editorial/voix-coralie.md`. Setup confirmé. |
| 2 | Production du **premier article pilote** (sujet URSSAF déjà briefé dans `content/briefs/2026-06-15-controle-urssaf-inopine.md`). |
| 3 | Production du **second article pilote** (sujet à choisir sur la veille de juin — pilier droit du travail). |
| 4 | Bilan pilote : temps Coralie, qualité ressentie, taux de réécriture. Décision GO/NO-GO pour le régime de croisière. |

## Critères de succès du pilote

| Critère | Seuil |
|---|---|
| Temps Coralie par article publié | ≤ 2 h |
| Hallucinations juridiques détectées par l'éditeur juridique | ≤ 2 par article |
| Qualité du draft v1 ressentie par Coralie | « 80 % juste » (à améliorer sans réécriture totale) |
| Voix éditoriale respectée | Pas de marketing-speak, pas de « N'hésitez pas à », pas de comparaison nominative |
| Délai brief → publication | ≤ 10 jours ouvrés |

Si **les 5 critères sont atteints** → on passe en régime de croisière (2-3 articles/mois).
Si **3 ou plus sont en échec** → on bascule vers l'option C (rédacteur juridique freelance) ou on rétro-conçoit les prompts.

## En cas de problème

- **Hallucination juridique** détectée à publication : pull back immédiat, post-mortem, ajustement du prompt Rédacteur ou Éditeur juridique.
- **Voix off-brand** signalée par Coralie : ajustement immédiat de `editorial/voix-coralie.md`, prochain article re-calibré.
- **Risque déontologique** RIN : escalade à Coralie obligatoire, blocage publication, consultation du Bâtonnier si besoin.

## Coûts indicatifs (API Claude)

Estimation pour 2-3 articles/mois avec le mix de modèles défini :
- Veille mensuelle : ~5 € (Haiku)
- Short-list : ~5 € (Sonnet)
- 3 briefs : ~10 € (Sonnet)
- 3 rédactions : ~20 € (Opus)
- 3 éditions juridiques : ~20 € (Opus)
- 3 éditions éditoriales : ~10 € (Sonnet)
- 3 kits de distribution : ~5 € (Haiku)

**Total : ~75 €/mois**. À comparer aux 1500-2500 €/mois d'un rédacteur juridique freelance.

## Prochaine action

Le brief pilote URSSAF est prêt dans `content/briefs/2026-06-15-controle-urssaf-inopine.md`. Étapes :

1. **Coralie relit `editorial/voix-coralie.md`** et apporte ses ajustements (lexique, exemples, anti-modèles).
2. **Coralie valide le sujet pilote** (ou en propose un autre).
3. **Lancement du Rédacteur** sur le brief.
4. **Boucle de validation** Coralie → Éditeur juridique → Éditeur éditorial → Signature.
5. **Publication + kit social** via le Distributeur.

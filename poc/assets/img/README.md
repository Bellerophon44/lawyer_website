# Portraits & images

## Portrait à déposer ici

**Fichier attendu :** `coralie-schumpf.jpg`

**Source à utiliser :** le portrait actuel publié sur `https://www.schumpf-avocat.com/votre-avocate` (Coralie assise sur la chaise blanche design).

## Comment l'ajouter (pas-à-pas, non technique)

### Option A — Depuis l'admin Wix
1. Connecte-toi sur `wix.com` → My Sites → ouvre `schumpf-avocat`.
2. Va dans **Media Manager** (icône image dans la barre de gauche de l'éditeur, ou `wix.com/dashboard/.../media`).
3. Trouve la photo de Coralie utilisée sur la page « Votre avocate ».
4. Sélectionne-la → clic droit → **Download**.
5. Renomme le fichier téléchargé en `coralie-schumpf.jpg` (en minuscules, avec le tiret).

### Option B — Depuis le site live
1. Va sur `https://www.schumpf-avocat.com/votre-avocate`.
2. Clic droit sur la photo de Coralie → **Enregistrer l'image sous…**.
3. Renomme le fichier `coralie-schumpf.jpg`.

### Déposer dans le repo
1. Sur la page GitHub du projet → ouvre le dossier `poc/assets/img/`.
2. Bouton **Add file → Upload files**.
3. Glisse-dépose `coralie-schumpf.jpg`.
4. En bas, message de commit : *« portrait Coralie »* → **Commit changes**.

Une fois le fichier en ligne, GitHub Pages rafraîchit en 1–2 min et le portrait apparaît automatiquement sur la fiche avocate.

## Spécifications cibles (pour la v1 finale)

- Format : JPG (ou WebP), compressé < 200 ko.
- Ratio : 4/5 (portrait), idéal 1200 × 1500 px.
- Cadrage : visage centré, regard caméra ou trois-quarts, fond uni neutre.
- Pour la v1 production, prévoir un re-shooting dédié avec un photographe corporate : lumière directionnelle, fond couleur sable ou bordeaux désaturé, tenue formelle business.

## Tant que le fichier n'est pas déposé

Le composant `.about-portrait` affiche un dégradé bordeaux + watermark « CS ». C'est visuellement propre, mais il manque évidemment le visage. Aucun message d'erreur ne s'affiche.

#!/usr/bin/env node
/* Build de production : poc/ -> dist/
 *
 * Le dossier poc/ est une maquette : sa page d'accueil s'appelle home.html et
 * son index.html est un sommaire de démo. Sur un hébergement Apache
 * (Infomaniak), la racine doit servir index.html. Ce script produit donc une
 * arborescence prête à uploader :
 *
 *   poc/home.html            -> dist/index.html
 *   poc/index.html           -> écarté (sommaire de maquette)
 *   poc/{assets,cabinet,expertises,diagnostic.html} -> copiés tels quels
 *   deploy/htaccess          -> dist/.htaccess
 *   + robots.txt et sitemap.xml générés
 *
 * Tous les liens internes vers home.html sont réécrits vers index.html.
 * En fin de build, un contrôle de liens signale les cibles manquantes et les
 * placeholders href="#" restants.
 *
 * Usage : node tools/build.mjs [--strict] [--preview]
 *   --strict  : sortie en erreur si un lien interne est cassé (utilisé en CI).
 *   --preview : variante destinée à la préversion GitHub Pages, non publique.
 *
 * Le mode préversion produit exactement le même rendu, pour que la relecture
 * porte sur ce qui sera réellement publié, mais neutralise tout ce qui pourrait
 * la faire passer pour le site officiel aux yeux d'un moteur de recherche.
 * Cf. la constante PREVIEW plus bas pour le détail des écarts.
 */

import { readFileSync, writeFileSync, rmSync, mkdirSync, cpSync, readdirSync, statSync } from 'node:fs';
import { dirname, join, relative, resolve, posix } from 'node:path';
import { fileURLToPath } from 'node:url';
import { execSync } from 'node:child_process';

const ROOT = resolve(dirname(fileURLToPath(import.meta.url)), '..');
const SRC = join(ROOT, 'poc');
const OUT = join(ROOT, 'dist');
const STRICT = process.argv.includes('--strict');

/**
 * Mode préversion (GitHub Pages). Écarts avec la production :
 *
 *  - chaque page reçoit <meta name="robots" content="noindex, nofollow"> ;
 *  - robots.txt AUTORISE le crawl, sans quoi Google ne lirait jamais ce
 *    noindex et pourrait tout de même lister l'URL nue dans ses résultats.
 *    Un Disallow serait donc contre-productif ici ;
 *  - pas de sitemap.xml : on ne soumet pas au référencement ce qu'on demande
 *    d'ignorer ;
 *  - pas de .htaccess : GitHub Pages n'est pas Apache, le fichier y serait
 *    inerte et laisserait croire que ses règles ont été testées ;
 *  - un .nojekyll, pour que Pages serve l'arborescence telle quelle.
 *
 * Aucune balise canonique n'est ajoutée : Google demande de ne pas combiner
 * noindex et canonical, les deux signaux étant contradictoires.
 */
const PREVIEW = process.argv.includes('--preview');

/** Domaine canonique retenu dans le guide de transfert (apex, sans www). */
const SITE_URL = 'https://schumpf-avocat.com';

/** Fichiers du POC qui ne doivent pas partir en production. */
const EXCLUDE = new Set(['index.html', 'README.md']);

/**
 * Priorité sitemap, par motif de chemin, premier motif gagnant.
 *
 * La liste des pages n'est volontairement PAS écrite en dur : elle est
 * déduite des fichiers réellement construits. Une page ajoutée dans poc/
 * entre donc seule dans le sitemap, sans modification de ce script — ce qui
 * permet d'ajouter une page (mentions légales, RGPD…) sans toucher au code.
 */
const PRIORITIES = [
  [/^index\.html$/, '1.0'],
  [/^expertises\//, '0.9'],
  [/^cabinet\//, '0.8'],
  [/^diagnostic\.html$/, '0.8'],
  [/./, '0.7'],
];

/** Commit construit, pour savoir depuis le site ce qui est réellement en ligne. */
function commitSha() {
  if (process.env.GITHUB_SHA) return process.env.GITHUB_SHA;
  try {
    return execSync('git rev-parse HEAD', { cwd: ROOT, encoding: 'utf8' }).trim();
  } catch {
    return 'inconnu';
  }
}

const log = (msg) => process.stdout.write(`${msg}\n`);

function walk(dir, base = dir) {
  return readdirSync(dir, { withFileTypes: true }).flatMap((entry) => {
    const full = join(dir, entry.name);
    if (entry.isDirectory()) return walk(full, base);
    return [relative(base, full)];
  });
}

/* ---------------------------------------------------------------- 1. copie */

rmSync(OUT, { recursive: true, force: true });
mkdirSync(OUT, { recursive: true });

for (const entry of readdirSync(SRC, { withFileTypes: true })) {
  if (EXCLUDE.has(entry.name)) continue;
  const from = join(SRC, entry.name);
  const to = join(OUT, entry.name);
  cpSync(from, to, { recursive: true, filter: (p) => !p.endsWith('README.md') });
}

// home.html devient la page d'accueil ; l'original ne reste pas en double.
cpSync(join(SRC, 'home.html'), join(OUT, 'index.html'));
rmSync(join(OUT, 'home.html'), { force: true });

/* ------------------------------------------------- 2. réécriture des liens */

const htmlFiles = walk(OUT).filter((f) => f.endsWith('.html'));
let rewrites = 0;

for (const file of htmlFiles) {
  const path = join(OUT, file);
  const before = readFileSync(path, 'utf8');
  // Uniquement dans les attributs, pour ne pas toucher au texte rédactionnel.
  const after = before.replace(
    /(href|src)="([^"]*)"/g,
    (match, attr, value) => `${attr}="${value.replace(/(^|\/)home\.html(?=$|#|\?)/, '$1index.html')}"`,
  );
  if (after !== before) {
    writeFileSync(path, after);
    rewrites += 1;
  }
}

log(`build : ${htmlFiles.length} pages, ${rewrites} fichiers avec liens réécrits`);

/* --------------------------------- 2 bis. neutralisation de la préversion */

if (PREVIEW) {
  const META = '  <meta name="robots" content="noindex, nofollow" />';
  for (const file of htmlFiles) {
    const path = join(OUT, file);
    const html = readFileSync(path, 'utf8');
    if (!/<head[^>]*>/i.test(html)) {
      throw new Error(`préversion : aucune balise <head> dans ${file}, noindex non posable`);
    }
    writeFileSync(path, html.replace(/<head[^>]*>/i, (tag) => `${tag}\n${META}`));
  }
  log(`préversion : noindex posé sur ${htmlFiles.length} pages`);
}

/* ------------------------------------------- 3. .htaccess, robots, sitemap */

if (PREVIEW) {
  // GitHub Pages n'est pas Apache : y déposer le .htaccess laisserait croire
  // que ses règles ont été éprouvées alors qu'elles y sont inertes.
  writeFileSync(join(OUT, '.nojekyll'), '');
  writeFileSync(
    join(OUT, 'robots.txt'),
    [
      '# Préversion de relecture — non publique.',
      '# Le crawl est autorisé A DESSEIN : sans lui, le noindex present dans',
      '# chaque page ne serait jamais lu, et Google pourrait tout de meme',
      '# lister ces URL. Ne pas remplacer par un Disallow.',
      'User-agent: *',
      'Allow: /',
      '',
    ].join('\n'),
  );
  log('préversion : robots.txt permissif et .nojekyll générés, pas de sitemap');
} else {
  const htaccess = join(ROOT, 'deploy', 'htaccess');
  if (statSync(htaccess, { throwIfNoEntry: false })) {
    cpSync(htaccess, join(OUT, '.htaccess'));
    log('build : .htaccess ajouté');
  }

  writeFileSync(
    join(OUT, 'robots.txt'),
    ['User-agent: *', 'Allow: /', '', `Sitemap: ${SITE_URL}/sitemap.xml`, ''].join('\n'),
  );

  // Une page marquée noindex (merci.html, par exemple) ne va pas dans le
  // sitemap : soumettre au référencement ce qu'on demande d'ignorer envoie
  // deux signaux contradictoires.
  const pages = htmlFiles
    .filter((out) => !/<meta[^>]*name="robots"[^>]*noindex/i.test(readFileSync(join(OUT, out), 'utf8')))
    .map((out) => ({ out, priority: PRIORITIES.find(([motif]) => motif.test(out))[1] }))
    .sort((a, b) => b.priority.localeCompare(a.priority) || a.out.localeCompare(b.out));

  const urls = pages
    .map(({ out, priority }) => {
      const loc = out === 'index.html' ? `${SITE_URL}/` : `${SITE_URL}/${out}`;
      return `  <url>\n    <loc>${loc}</loc>\n    <priority>${priority}</priority>\n  </url>`;
    })
    .join('\n');

  writeFileSync(
    join(OUT, 'sitemap.xml'),
    `<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\n${urls}\n</urlset>\n`,
  );
  log(`build : robots.txt et sitemap.xml générés (${pages.length} pages)`);
}

// Permet de savoir depuis l'extérieur ce qui est réellement en ligne :
//   curl https://schumpf-avocat.com/version.txt
// Corollaire : dist/ change à chaque commit, même sans modification de
// contenu. Pour comparer deux builds, exclure ce fichier.
writeFileSync(join(OUT, 'version.txt'), `${commitSha()}\n`);

/* -------------------------------------------------- 4. contrôle des liens */

const built = new Set(walk(OUT));
const broken = [];
const placeholders = [];

for (const file of htmlFiles) {
  const html = readFileSync(join(OUT, file), 'utf8');
  // action= aussi : un formulaire qui poste vers un fichier absent est
  // exactement le genre de lien mort que ce contrôle doit attraper.
  for (const [, , value] of html.matchAll(/(href|src|action)="([^"]*)"/g)) {
    if (value === '#') {
      placeholders.push(file);
      continue;
    }
    if (/^(https?:|mailto:|tel:|#|data:|\/\/)/.test(value)) continue;
    const target = value.split(/[#?]/)[0];
    if (!target) continue;
    const resolved = posix.normalize(posix.join(posix.dirname(file), target));
    if (!built.has(resolved)) broken.push(`${file} -> ${value}`);
  }
}

if (broken.length) {
  log(`\nLiens internes cassés (${broken.length}) :`);
  for (const b of broken) log(`  ✗ ${b}`);
} else {
  log('contrôle : aucun lien interne cassé');
}

const placeholderCount = placeholders.length;
if (placeholderCount) {
  const byFile = placeholders.reduce((acc, f) => ({ ...acc, [f]: (acc[f] ?? 0) + 1 }), {});
  log(`\nPlaceholders href="#" restants (${placeholderCount}) — pages à écrire :`);
  for (const [file, count] of Object.entries(byFile)) log(`  · ${file} : ${count}`);
}

log(`\ndist/ prêt : ${built.size} fichiers`);

if (STRICT && broken.length) {
  process.exitCode = 1;
}

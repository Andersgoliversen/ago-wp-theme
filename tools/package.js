// tools/package.js
/* Builds ago-wp-theme.zip in the dist/ folder, excluding dev files. */

const fs       = require('fs');
const path     = require('path');
const archiver = require('archiver');

(async () => {

  /* --- load globby via dynamic import (ES-module) ----------- */
  const { globby } = await import('globby');   // ← change

  const projectRoot = path.resolve(__dirname, '..');
  const distDir     = path.join(projectRoot, 'dist');
  const zipName     = 'ago-wp-theme.zip';
  const outPath     = path.join(distDir, zipName);

  /* -----------------------------------------------------------
     1. Build file list (include → exclude dev artefacts)
     ----------------------------------------------------------- */
  const ignore = [
    'node_modules/**',
    'assets/src/**',
    '.git/**',
    '.gitattributes',
    '.gitignore',
    'tailwind.config.js',
    'postcss.config.js',
    'package.json',
    'package-lock.json',
    'README.md',
    'tools/**',
    'dist/**'
  ];

  const files = await globby(['**/*', ...ignore.map(p => '!' + p)], {
    cwd: projectRoot,
    dot: false
  });

  /* -----------------------------------------------------------
     2. Ensure dist dir
     ----------------------------------------------------------- */
  fs.mkdirSync(distDir, { recursive: true });

  /* -----------------------------------------------------------
     3. Create archive
     ----------------------------------------------------------- */
  const output  = fs.createWriteStream(outPath);
  const archive = archiver('zip', { zlib: { level: 9 } });

  output.on('close', () =>
    console.log(`Created ${zipName} (${archive.pointer()} bytes)`));

  archive.on('error', err => { throw err; });

  archive.pipe(output);

  files.forEach(f =>
    archive.file(path.join(projectRoot, f), { name: f })
  );

  await archive.finalize();
})();

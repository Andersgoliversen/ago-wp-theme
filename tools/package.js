// tools/package.js
/* Builds ago-wp-theme.zip in the dist/ folder from deployable theme files. */

const fs       = require('fs');
const path     = require('path');
const archiver = require('archiver');

(async () => {
  const { globby } = await import('globby');

  const projectRoot = path.resolve(__dirname, '..');
  const distDir     = path.join(projectRoot, 'dist');
  const zipName     = 'ago-wp-theme.zip';
  const outPath     = path.join(distDir, zipName);

  const deploymentFiles = [
    '*.php',
    'style.css',
    'screenshot.{png,jpg,jpeg}',
    'assets/css/**',
    'assets/js/**',
    'inc/**',
    'languages/**',
    'template-parts/**'
  ];

  const files = await globby(deploymentFiles, {
    cwd: projectRoot,
    dot: false,
    onlyFiles: true
  });

  fs.mkdirSync(distDir, { recursive: true });

  const output  = fs.createWriteStream(outPath);
  const archive = archiver('zip', { zlib: { level: 9 } });

  output.on('close', () =>
    console.log(`Created ${zipName} (${archive.pointer()} bytes)`));

  archive.on('error', err => { throw err; });
  archive.pipe(output);

  files.forEach(file =>
    archive.file(path.join(projectRoot, file), { name: file })
  );

  await archive.finalize();
})();

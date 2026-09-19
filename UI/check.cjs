const fs = require('node:fs');
const path = require('node:path');
for (const file of fs.readdirSync(__dirname).filter(f => f.endsWith('.html'))) {
  const html = fs.readFileSync(path.join(__dirname, file), 'utf8');
  const refs = [...html.matchAll(/(?:href|src)="([^"]+)"/g)].map(m => m[1]).filter(s => !s.startsWith('http') && !s.startsWith('#'));
  for (const ref of refs) if (!fs.existsSync(path.join(__dirname, ref))) throw new Error(`${file}: missing ${ref}`);
  if (!html.includes('lang="ru"') || !html.includes('name="viewport"')) throw new Error(`${file}: missing metadata`);
  console.log(`${file}: local links and metadata OK`);
}

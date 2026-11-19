// Mini-Webserver für die statische Vorschau (nur Node, keine Pakete nötig)
const http = require('http');
const fs = require('fs');
const path = require('path');
const root = path.resolve(__dirname, '..');
const types = { '.html': 'text/html', '.css': 'text/css', '.js': 'text/javascript', '.png': 'image/png', '.svg': 'image/svg+xml' };
http.createServer((req, res) => {
	let p = decodeURIComponent(req.url.split('?')[0]);
	if (p === '/') p = '/vorschau/index.html';
	const f = path.join(root, p);
	if (!f.startsWith(root)) { res.writeHead(403); return res.end(); }
	fs.readFile(f, (err, data) => {
		if (err) { res.writeHead(404); return res.end('404'); }
		res.writeHead(200, { 'Content-Type': types[path.extname(f)] || 'application/octet-stream' });
		res.end(data);
	});
}).listen(8765, () => console.log('Vorschau: http://localhost:8765/'));

# Pfadi Rankweil – WordPress-Theme

Modernes, schlankes Theme für www.pfadi-rankweil.at als Nachfolger von HitMag Pro.

## Installation
1. **Zuerst auf einer Testkopie / Staging ausprobieren** (z. B. mit dem Plugin „WP Staging“).
2. `Design → Themes → Theme hinzufügen → Theme hochladen` → `pfadi-rankweil.zip`.
3. Aktivieren und unter `Design → Anpassen` einrichten.

## Nach dem Wechsel von HitMag Pro
- **Automatisch übernommen** (einmalig beim Aktivieren bzw. beim nächsten Admin-Aufruf):
  Logo, Hauptmenü, Footer-Menü und Social-Media-Links aus HitMag Pro. Ein grüner Hinweis
  im Admin zeigt, was übernommen wurde. Bereits gesetzte Werte werden nicht überschrieben.
- Falls etwas fehlt – **Logo**: `Anpassen → Website-Informationen → Logo`;
  **Menü**: `Design → Menüs → Positionen verwalten` → „Hauptmenü“.
- **Widgets**: Die Bereiche heißen wie bei HitMag (Seitenleiste `sidebar-1`, Footer `footer-left`,
  `footer-mid`, `footer-right`) – beim Wechsel landet jedes Widget am gleichen Platz.
  Das HitMag-Widget „Sidebar Posts“ (z. B. „Neues aus der Gruppe“, „Wichtiges“) wird vom Theme
  unterstützt („Beiträge mit Bild“). Nur das große Startseiten-Widget „HitMag Pro List Posts“
  gibt es nicht – die Startseite baut das Theme selbst.
- **Footer**: Kontaktdaten in „Footer links“, Logo in „Footer rechts“ (wie bei HitMag).
- **PPÖ-Logo rechts unten**: `Anpassen → Pfadi Theme → Footer → Logo rechts unten` → aus der Mediathek
  das vorhandene weiße Logo „Logo-Rankweil-_WEISS“ wählen (das alte Bild-Widget im Footer dann entfernen).
- **Startseite**: Funktioniert mit „Deine neuesten Beiträge“ und mit statischer Seite.

## Kategorien
Passend zur Struktur „Gruppe“ → Stufen (Wichtel, Wölflinge, Guides, Späher, Caravelles, Explorer, Ranger, Rover):
- Ist eine Stufe gesetzt, wird nur die Stufe angezeigt (nicht zusätzlich „Gruppe“); „Allgemein“ nur, wenn sonst nichts gesetzt ist.
- Stufen-Labels in Stufenfarbe; bei mehr als 3 Stufen wird „Gruppe“ angezeigt.
- Zuordnung Kategorie → Stufe: `Anpassen → Pfadi Theme → Startseite: Stufen → Kategorien` (Slugs, z. B. `wichtel,woelflinge`).
- „Wichtiges“ erscheint nicht im Aufmacher/„Letzte Berichte“ (`Startseite: Berichte → Kategorien ausblenden`).
- Kategorie-Archive ohne „Kategorie:“-Präfix, Stufen-Archive mit Stufenfarbe im Kopfbereich.

## Dark Mode
Umschalter (Mond/Sonne) im Header. Unter `Farben & Layout → Dark Mode`: automatisch nach Systemeinstellung, Standard hell oder aus. Die Wahl der Besucher*innen wird im Browser gemerkt. Unter `Kopfbereich` kann eine helle Logo-Variante für den Dark Mode hochgeladen werden, sonst steht das Logo auf einem weißen Schild.

## Offizielles PPÖ-Symbol
Das Theme enthält bewusst **kein** eigenes Logo/Symbol. Das Verbandsabzeichen ist eine geschützte Marke der PPÖ (Verwendung durch registrierte Gruppen erlaubt).
Download: https://ppoe.at/service/ressourcen/design/ → „Symbol für WEB/OFFICE“ bzw. „Euer Gruppenlogo im neuen Design“.
Hochladen unter `Anpassen → Pfadi Theme → Kopfbereich → Pfadi-Symbol` (plus weiße Variante für dunkle Flächen). Ohne Upload wird an diesen Stellen nichts angezeigt.

## Customizer: `Anpassen → Pfadi Theme`
| Bereich | Optionen |
|---|---|
| Farben & Layout | Haupt-/Akzent-/Hintergrundfarbe, Eckenrundung, Seitenleiste links/rechts/keine, Kacheln/Liste, Auszugslänge, Lesezeit, ähnliche Beiträge, Teilen-Buttons |
| Kopfbereich | Fixierter Header, Infoleiste + Text, Button „Mitmachen“ (Text/Link), Suche |
| Startseite: Aufmacher | Kachel-Raster (5 neueste Beiträge, optional Kategorie) **oder** Willkommens-Banner mit Bild & 2 Buttons |
| Startseite: Stufen | 4 Kacheln (Name, Kürzel, Alter, Text, Farbe, Bild, Link) – Standard: PPÖ-Stufenfarben |
| Startseite: Berichte | Überschrift, Kategorie, Anzahl, Doppelte ausblenden |
| Startseite: Mitmach-Banner | Titel, Text, Button |
| Social Media | Facebook, Instagram, YouTube, TikTok, WhatsApp-Kanal |
| Footer | Kurzbeschreibung, Logo rechts unten (Bild, Link, Breite), Copyright (`{year}` = aktuelles Jahr) |

Links können relativ angegeben werden, z. B. `/anmeldung/`.

## Seitenvorlagen
- **Standard** – schmaler Lesebereich ohne Seitenleiste
- **Seite mit Seitenleiste**
- **Volle Breite (ohne Titelbereich)** – für Seiten, die komplett mit Blöcken gebaut werden

## Datenschutz
Keine Google Fonts, keine externen Skripte, Teilen-Buttons sind reine Links (kein Tracking).
Schriften: System-Schriften (Segoe UI / SF Pro Rounded). Wer eine Webfont möchte,
sollte sie lokal einbinden (z. B. mit dem Plugin „OMGF“ oder als Dateien im Theme).

## Versionierung
- Die Versionsnummer steht **nur** im Kopf von `style.css` (Zeile `Version:`). WordPress zeigt
  sie unter Design → Themes, der Customizer-Bereich „Pfadi Theme“ ebenfalls.
- Änderungen werden in `CHANGELOG.md` festgehalten.
- Neue Version bauen (im Ordner `WordpressTemplate`):
  - `powershell -ExecutionPolicy Bypass -File tools\build.ps1 -Bump patch` → Fehlerbehebung (1.4.0 → 1.4.1)
  - `… -Bump minor` → neue Funktion (1.4.0 → 1.5.0)
  - `… -Bump major` → großer Umbau (1.4.0 → 2.0.0)
  - ohne `-Bump` → aktuelle Version bauen
- Ergebnis: `dist\pfadi-rankweil-<version>.zip`. Im ZIP heißt der Ordner immer `pfadi-rankweil`,
  daher bietet WordPress beim Hochladen „Ersetzen“ an. Alte ZIPs in `dist\` aufheben = einfaches Zurückrollen.

## Vorschau ohne WordPress
Ordner `vorschau/` neben dem Theme: `node vorschau/server.js` → http://localhost:8765/

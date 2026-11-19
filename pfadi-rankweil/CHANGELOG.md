# Änderungsprotokoll – Pfadi Rankweil Theme

Versionsschema `MAJOR.MINOR.PATCH`:
- **PATCH** (1.3.**1**): Fehlerbehebungen, kleine Korrekturen
- **MINOR** (1.**4**.0): neue Funktionen, abwärtskompatibel
- **MAJOR** (**2**.0.0): größere Umbauten, Einstellungen müssen evtl. neu gesetzt werden

Die Versionsnummer steht nur im Kopf von `style.css`. Neue Version bauen:
`tools/build.ps1 -Bump patch|minor|major` (siehe README).

## [1.10.2] – 2026-11-19
### Geändert
- Theme-Vorschaubild zeigt jetzt das eigene Gruppenlogo der Pfadi Rankweil
  (Lilie, „RANKWEIL“, Kirchturm) statt des PPÖ-Gruppenlogos.

## [1.10.1] – 2026-11-19
### Geändert
- Neues Theme-Vorschaubild (`screenshot.png`, 1200 × 900) mit dem offiziellen Gruppenlogo
  „Pfadfinder*innen Österreich – Rankweil“ und den PPÖ-Stufenfarben.
### Entfernt
- Altes Vorschaubild `screenshot.jpg` mit Fotos von der Startseite – auch aus der gesamten
  Git-Versionsgeschichte und dem ZIP von Release 1.10.0 entfernt.

## [1.10.0] – 2026-11-19
### Neu
- Updates direkt aus GitHub: Das Theme meldet neue Versionen unter Dashboard → Aktualisierungen
  (WordPress-Schnittstelle „Update URI“, kein zusätzliches Plugin). Quelle ist das ZIP im
  neuesten GitHub-Release. Privates Repository: Lese-Token als `PFADI_GITHUB_TOKEN` in
  `wp-config.php` (siehe README). Hinweis auf Themes-/Aktualisierungen-Seite bei Problemen.
- Build-Skript: `-Push` legt zusätzlich das GitHub-Release mit dem ZIP und dem
  Änderungsprotokoll-Abschnitt als Text an.
### Geändert
- Benötigt WordPress 6.1 oder neuer.

## [1.9.0] – 2026-11-19
### Geändert
- Offizielle PPÖ-Stufenfarben (laut ppoe.at → Design → Farbdefinitionen):
  WiWö #FBBB21, GuSp #159A34, CaEx #0B4697, RaRo #E62336 – für Stufen-Kacheln,
  Kategorie-Labels, Stufen-Archive, Kalender auf Stufen-Seiten. Gelb als Schrift abgedunkelt
  (#A97A00), CaEx-Blau im Dark Mode aufgehellt – beides wegen Lesbarkeit.
  Im Customizer bereits gespeicherte Stufenfarben bleiben unverändert.
- Editor-Farbpalette um Biber (#904837) und PWA (#E6007E) ergänzt.
- Logo-Farben der Gruppe (Navy, Rot) bleiben unverändert.
### Neu
- Newsletter-Formular (Plugin Brevo/Sendinblue) im Theme-Design: runder roter Button,
  abgerundete Felder mit Fokus-Rahmen.

## [1.8.3] – 2026-11-19
### Neu
- Design für Kontakt-Listen: Tabellen-Block mit der CSS-Klasse `kontakt-liste`
  (Block → Erweitert → Zusätzliche CSS-Klasse). Kopfzeile in Navy, abwechselnde Zeilen,
  E-Mails als Links, Namen in Grau; am Handy jede Zeile als kleine Karte untereinander.

## [1.8.2] – 2026-11-19
### Geändert
- Altersangaben der Stufen an Rankweil angepasst (alles ein Jahr später, Ende mit 20):
  WiWö 8–11, GuSp 11–14, CaEx 14–17, RaRo 17–20 Jahre; Banner-Text „von 8 bis 20 Jahren“.
  Gilt als Standard – im Customizer bereits gespeicherte Werte bleiben unverändert.

## [1.8.1] – 2026-11-19
### Behoben
- Widget-Karten auf der Startseite („Neues aus der Gruppe“, „Wichtiges“, Newsletter) sind
  jetzt nebeneinander gleich hoch.

## [1.8.0] – 2026-11-19
### Neu
- Startseite zeigt die Widgets der Seitenleiste („Neues aus der Gruppe“, „Wichtiges“,
  Newsletter) als Karten nebeneinander – standardmäßig zwischen Stufen und „Letzte Berichte“,
  dort wo bei HitMag der Startseiten-Bereich war. Position wählbar unter
  Startseite: Berichte → „Widgets der Seitenleiste auf der Startseite“ (oder ausblenden).

## [1.7.0] – 2026-11-19
### Neu
- Option „Kopf von Beiträgen“ (Farben & Layout):
  - **Klassisch** (Standard, wie bei HitMag): Kategorie, Titel, Datum und Beitragsbild in der
    Textspalte – das Foto wird nicht beschnitten oder abgedunkelt.
  - **Groß**: Bild über die ganze Breite mit Titel darauf (bisheriges Verhalten).

## [1.6.1] – 2026-11-19
### Neu
- Widget „Beiträge mit Bild“, kompatibel zu HitMag „Sidebar Posts“ (gleiche Kennung und
  Einstellungen): „Neues aus der Gruppe“ und „Wichtiges“ erscheinen in beiden Themes.
### Geändert
- Footer-Widget-Bereiche heißen wie bei HitMag: `footer-left`, `footer-mid`, `footer-right`
  (vorher `footer-1/2/3`; wird beim Update automatisch umgezogen).
- Einmalige Reparatur beim Update: Widgets, die beim Theme-Wechsel verrutscht sind, kommen an
  den Platz, den sie bei HitMag hatten (z. B. PPÖ-Logo zurück in den Footer rechts).
### Behoben
- Beitrags-Titelbereich bei Seitenleiste nicht bündig mit dem Text (stand mittig).
- Weißes PPÖ-Logo landete beim Wechsel in der Seitenleiste – weiß auf weiß, als leere Box.

## [1.6.0] – 2026-11-19
### Neu
- Design für Termine aus dem Plugin „Simple Calendar“ (Listen- und Monatsansicht):
  Kalender als Karte, runde Vor/Zurück-Knöpfe, Datum als farbiges Label statt schwarzer
  Balken, Termine als Zeilen mit farbiger Kante, Uhrzeit mit Symbol, „Ganztägig“ bei
  Terminen ohne Uhrzeit, doppeltes Datum im Termin ausgeblendet, Dark-Mode-tauglich.
- Stufen-Seiten (Seiten-Slug wie die Stufen-Kategorie, z. B. /spaeher/) bekommen die
  Body-Klasse `page-stufe-N`; Kalender & Co. erscheinen dort in der Stufenfarbe.

## [1.5.1] – 2026-11-19
### Geändert
- Menü-Positionen heißen jetzt exakt wie bei HitMag Pro: `menu-1` (Hauptmenü),
  `footer-menu` (Footer-Menü), `menu-social` (Social-Media). WordPress ordnet die Menüs
  beim Theme-Wechsel dadurch in beide Richtungen 1:1 zu.
### Behoben
- 1.4.0 ging fälschlich davon aus, dass HitMag die Social-Position `social` nennt – das führte
  beim Zurückwechseln dazu, dass das Social-Menü in HitMags Kopfleisten-Menü landete.

## [1.5.0] – 2026-11-19
### Neu
- Sicherer Theme-Wechsel in beide Richtungen:
  - Beim Aktivieren wird die Widget- und Menü-Anordnung des vorherigen Themes (HitMag)
    gesichert und beim Zurückwechseln exakt wiederhergestellt – inkl. HitMag-eigener
    Widgets wie „Sidebar Posts“; WordPress ordnet Menüs dabei nicht mehr neu zu.
  - Beim Verlassen wird die eigene Anordnung gesichert und beim erneuten Aktivieren
    wiederhergestellt.
### Behoben
- Nach dem Zurückwechseln auf HitMag lag das Social-Menü zusätzlich in der oberen Leiste,
  „Neues aus der Gruppe“/„Wichtiges“ fehlten und das PPÖ-Logo war in die Seitenleiste
  gerutscht (automatisches Umsortieren durch WordPress).

## [1.4.3] – 2026-11-19
### Neu
- Formular-Design für Seiteninhalte und Widgets: runde Felder in voller Breite, deutlicher
  Fokus-Rahmen, eigene Auswahlpfeile, Radio/Checkbox in Akzentfarbe, Dark-Mode-tauglich.
- Contact Form 7: Formular als Karte, Felder aus einem Absatz (Vorname/Familienname,
  Adresse, E-Mail/Telefon) am Desktop zweispaltig, Fehler- und Erfolgsmeldungen farbig.
- Absätze mit Hintergrundfarbe (aus dem Editor) mit Innenabstand und runden Ecken.
### Behoben
- Formularfelder (z. B. /anmeldung/) wurden als schmale Standard-Browserfelder angezeigt.

## [1.4.2] – 2026-11-19
### Neu
- Schalter „Text der Startseite unter den Stufen anzeigen“ (Startseite: Aufmacher), Standard: aus.
### Behoben
- Auf der Startseite erschien der alte Inhalt der Seite „Home“ (Logo von 2018 + „NEWS“)
  als leerer Freiraum zwischen Stufen und Berichten. HitMag hatte diesen Text mit der
  Vorlage „Magazine“ ignoriert – jetzt standardmäßig ebenfalls.
- Links/rechts ausgerichtete Bilder im Seiteninhalt fließen sauber und ragen nicht heraus.

## [1.4.1] – 2026-11-19
### Neu
- Logo-Höhe im Customizer einstellbar (Kopfbereich), Standard 84 px.
- Schalter „Fixierte Beiträge zuerst zeigen“ für den Aufmacher (Standard: aus).
### Behoben
- Aufmacher zeigte alte, fixierte („oben gehaltene“) Beiträge vor den neuesten –
  jetzt wie bei HitMag nach Datum.
- Logo im Kopfbereich war deutlich kleiner als im alten Theme (58 px statt ca. 98 px).

## [1.4.0] – 2026-11-19
Erfahrungen aus dem RV-Sulz-Theme übernommen.
### Neu
- Menü-Position „Social-Media-Links“ (heißt wie bei HitMag `social`, wird beim Wechsel
  übernommen). Ist ein Menü zugewiesen, ersetzt es die Social-Felder im Customizer;
  der Dienst wird an der URL erkannt.
- Klassische Widget-Verwaltung wie bei HitMag (abschaltbar unter Farben & Layout).
- Copyright-Text mit `{jahr}` (bisheriges `{year}` funktioniert weiter).
- Anzahl- und Breitenfelder im Customizer mit sinnvollen Grenzen.
- Übernahme aus HitMag Pro berücksichtigt jetzt auch das Social-Media-Menü.
### Behoben
- Dark Mode: Umschalter meldet seinen Zustand an Screenreader (`aria-pressed`) und
  der Modus stimmt auch nach „Zurück“ im Browser.

## [1.3.0] – 2026-11-19
### Neu
- Kategorie-Anzeige passend zur Struktur „Gruppe“ → Stufen: Stufe statt Oberkategorie,
  Stufen-Labels in Stufenfarbe, bei mehr als 3 Stufen „Gruppe“, „Allgemein“ nur als Rückfall.
- Zuordnung Kategorie → Stufe im Customizer (Startseite: Stufen).
- Kategorien auf der Startseite ausblenden (Standard: „Wichtiges“).
- Kategorie-Archive ohne „Kategorie:“-Präfix, Stufen-Archive mit Stufenfarbe im Kopf.
- Brotkrümel mit Oberkategorie (Start / Gruppe / Guides).
- Einmalige Übernahme aus HitMag Pro: Logo, Hauptmenü, Footer-Menü, Social-Media-Links.
- Versionierung: Version zentral in `style.css`, Änderungsprotokoll, Build-Skript.

## [1.2.0] – 2026-11-19
### Neu
- Logo rechts unten im Footer (z. B. weißes PPÖ-Gruppenlogo) mit Link und Breite.
- Footer-Spalten passen sich der Anzahl der belegten Bereiche an.

## [1.1.0] – 2026-11-19
### Neu
- Dark Mode: automatisch nach Systemeinstellung, Umschalter im Header, Option im Customizer.
- Eigenes Logo für den Dark Mode, sonst Logo auf hellem Schild.
- Upload-Felder für das offizielle PPÖ-Symbol (normal und weiß).
### Geändert
- Selbst gezeichnete Lilie entfernt – es wird nur noch das hochgeladene Symbol verwendet.
### Behoben
- Mobiles Menü wurde durch den Glas-Effekt des Headers abgeschnitten.

## [1.0.0] – 2026-11-19
### Neu
- Erste Version: Bento-Aufmacher oder Willkommens-Banner, Stufen-Kacheln, Beitragskarten,
  Mitmach-Banner, Seitenleiste, Footer mit Widgets, Seitenvorlagen, Customizer-Optionen,
  ohne externe Schriften/Skripte.

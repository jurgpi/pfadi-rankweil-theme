<#
  Pfadi Rankweil Theme - Build & Versionierung

  Aufruf (im Ordner WordpressTemplate):
    powershell -ExecutionPolicy Bypass -File tools\build.ps1                 # aktuelle Version bauen
    powershell -ExecutionPolicy Bypass -File tools\build.ps1 -Bump patch     # 1.3.0 -> 1.3.1 (Fehlerbehebung)
    powershell -ExecutionPolicy Bypass -File tools\build.ps1 -Bump minor     # 1.3.0 -> 1.4.0 (neue Funktion)
    powershell -ExecutionPolicy Bypass -File tools\build.ps1 -Bump major     # 1.3.0 -> 2.0.0 (grosser Umbau)

  - Die Version steht nur im Kopf von pfadi-rankweil\style.css.
  - Bei -Bump wird in CHANGELOG.md ein neuer Abschnitt angelegt (bitte danach ausfuellen).
  - Ergebnis: dist\pfadi-rankweil-<version>.zip  (Ordner im ZIP heisst immer "pfadi-rankweil",
    damit WordPress beim Hochladen das bestehende Theme ersetzt).
  - Eine bereits gebaute Version wird nicht ueberschrieben (ausser mit -Force), damit nie
    zwei verschiedene Staende dieselbe Nummer tragen.
#>
param(
  [ValidateSet('none', 'patch', 'minor', 'major')]
  [string]$Bump = 'none',
  [switch]$Force
)

$ErrorActionPreference = 'Stop'
$root   = Split-Path -Parent $PSScriptRoot
$theme  = Join-Path $root 'pfadi-rankweil'
$style  = Join-Path $theme 'style.css'
$log    = Join-Path $theme 'CHANGELOG.md'
$dist   = Join-Path $root 'dist'
$utf8   = New-Object System.Text.UTF8Encoding $false

$css = [IO.File]::ReadAllText($style, $utf8)
$m = [regex]::Match($css, '(?m)^Version:\s*(\d+)\.(\d+)\.(\d+)\s*$')
if (-not $m.Success) { throw 'Keine Zeile "Version: x.y.z" in style.css gefunden.' }
$maj = [int]$m.Groups[1].Value; $min = [int]$m.Groups[2].Value; $pat = [int]$m.Groups[3].Value
$old = "$maj.$min.$pat"

switch ($Bump) {
  'major' { $maj++; $min = 0; $pat = 0 }
  'minor' { $min++; $pat = 0 }
  'patch' { $pat++ }
}
$version = "$maj.$min.$pat"

if ($Bump -ne 'none') {
  $css = $css.Substring(0, $m.Index) + "Version: $version" + $css.Substring($m.Index + $m.Length)
  [IO.File]::WriteAllText($style, $css, $utf8)

  $dash  = [char]0x2013
  $date  = Get-Date -Format 'yyyy-MM-dd'
  $entry = "## [$version] $dash $date`n### Neu`n- `n### Behoben`n- `n`n"
  $text  = [IO.File]::ReadAllText($log, $utf8)
  $pos   = $text.IndexOf('## [')
  if ($pos -ge 0) { $text = $text.Substring(0, $pos) + $entry + $text.Substring($pos) } else { $text += "`n" + $entry }
  [IO.File]::WriteAllText($log, $text, $utf8)
  Write-Host "Version $old -> $version (CHANGELOG.md bitte ergaenzen)"
} else {
  Write-Host "Version $version"
}

if (-not (Test-Path $dist)) { New-Item -ItemType Directory -Path $dist | Out-Null }
$zipPath = Join-Path $dist "pfadi-rankweil-$version.zip"
if ((Test-Path $zipPath) -and -not $Force) {
  throw "dist\pfadi-rankweil-$version.zip existiert schon. Version erhoehen (-Bump patch) oder -Force verwenden."
}
if (Test-Path $zipPath) { [IO.File]::Delete($zipPath) }

Add-Type -AssemblyName System.IO.Compression, System.IO.Compression.FileSystem
$zip = [IO.Compression.ZipFile]::Open($zipPath, 'Create')
try {
  $prefix = $theme.Length + 1
  foreach ($f in Get-ChildItem $theme -Recurse -File) {
    $rel = 'pfadi-rankweil/' + $f.FullName.Substring($prefix).Replace([char]92, [char]47)
    [void][IO.Compression.ZipFileExtensions]::CreateEntryFromFile($zip, $f.FullName, $rel)
  }
} finally {
  $zip.Dispose()
}
Write-Host ("Gebaut: dist\pfadi-rankweil-$version.zip (" + [math]::Round((Get-Item $zipPath).Length / 1KB) + ' KB)')

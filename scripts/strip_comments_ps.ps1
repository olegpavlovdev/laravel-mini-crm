#!/usr/bin/env pwsh
<#
.SYNOPSIS
    Strip common comment styles and collapse excessive blank lines from repository files.

.DESCRIPTION
    Creates backups under .cleanup_backups/<relative_path>.bak for each changed file,
    removes Blade/HTML/PHPDoc/C-style block comments, whole-line // and # comments
    (preserving shebangs), removes inline // comments except when the line contains
    http/https, trims trailing whitespace, collapses 3+ blank lines into a single
    blank line, and writes an optional report file.

.NOTES
    Intended to be run from the repository root. Use -WhatIf to preview what would
    be changed.
#>

param(
    [switch]$Apply,
    [string]$ReportPath = '.strip_report_ps.txt'
)

Set-StrictMode -Version Latest

$ROOT = (Get-Location).Path
$BACKUP_DIR = Join-Path $ROOT '.cleanup_backups'
$SKIP_DIR_NAMES = @('.git','__pycache__','.cleanup_backups')
$BIN_EXT = @('.png','.jpg','.jpeg','.gz','.zip','.phar','.dll','.so','.exe','.class')

function Is-TextFile {
    param([string]$Path)
    try {
        $bytes = [System.IO.File]::ReadAllBytes($Path)
        [System.Text.Encoding]::UTF8.GetString($bytes) | Out-Null
        return $true
    } catch {
        return $false
    }
}

function Backup-File {
    param([string]$Path)
    $rel = Resolve-Path -LiteralPath $Path | ForEach-Object { $_.Path.Substring($ROOT.Length + 1) }
    $dst = Join-Path $BACKUP_DIR $rel
    $dstDir = Split-Path $dst -Parent
    if (-not (Test-Path $dstDir)) { New-Item -ItemType Directory -Path $dstDir -Force | Out-Null }
    Copy-Item -LiteralPath $Path -Destination $dst -Force
}

function Strip-Comments-FromText {
    param([string]$Text)
    $original = $Text
    $Text = [regex]::Replace($Text, '(?s)\{\{\-\-.*?\-\-\}\}', '')
    $Text = [regex]::Replace($Text, '(?s)<!--.*?-->', '')
    $Text = [regex]::Replace($Text, '(?s)/\*\*.*?\*/', '')
    $Text = [regex]::Replace($Text, '(?s)/\*.*?\*/', '')

    $lines = $Text -split "\r?\n"
    $out = New-Object System.Collections.Generic.List[string]
    foreach ($line in $lines) {
        $stripped = $line.TrimStart()
        if ($stripped.StartsWith('#!')) {
            $out.Add($line)
            continue
        }
        if ($stripped.StartsWith('#')) { continue }
        if ($stripped.StartsWith('//')) { continue }
        if ($line -match '//' -and ($line -notmatch 'http' -and $line -notmatch 'https')) {
            $line = ($line -replace '//.*$', '')
        }
        $out.Add($line.TrimEnd())
    }
    $Text = [string]::Join("`n", $out) + "`n"
    $Text = [regex]::Replace($Text, "(\n){3,}", "`n`n")
    $Text = ($Text -split "`n") | ForEach-Object { $_.TrimEnd() } | ForEach-Object { $_ } -join "`n"
    if (-not $Text.EndsWith("`n")) { $Text += "`n" }
    return $Text
}

if (-not $Apply) {
    Write-Output "Running in dry-run mode. Use -Apply to make changes."
}

$changed = [System.Collections.Generic.List[string]]::new()
$reportLines = [System.Collections.Generic.List[string]]::new()

Get-ChildItem -Path $ROOT -Recurse -File -ErrorAction SilentlyContinue | ForEach-Object {
    $file = $_.FullName
    $parts = $file.Substring($ROOT.Length + 1) -split "[\\/]"
    if ($parts | Where-Object { $SKIP_DIR_NAMES -contains $_ }) { return }
    if ($BIN_EXT -contains $_.Extension.ToLower()) { return }
    if (-not (Is-TextFile -Path $file)) { return }
    try {
        $txt = Get-Content -LiteralPath $file -Raw -Encoding UTF8 -ErrorAction Stop
    } catch {
        return
    }
    $new = Strip-Comments-FromText -Text $txt
    if ($new -ne $txt) {
        $rel = $file.Substring($ROOT.Length + 1)
        $msg = "Will update: $rel"
        Write-Output $msg
        $reportLines.Add($msg) | Out-Null
        $changed.Add($rel) | Out-Null
        if ($Apply) {
            Backup-File -Path $file
            $bytes = [System.Text.Encoding]::UTF8.GetBytes($new)
            [System.IO.File]::WriteAllBytes($file, $bytes)
        }
    }
}

if ($changed.Count -eq 0) {
    $reportLines.Add(' (no changes)') | Out-Null
} else {
    $reportLines.Add('') | Out-Null
    $reportLines.Add('Done. Files changed:') | Out-Null
    foreach ($c in $changed) { $reportLines.Add(' - ' + $c) | Out-Null }
}

if ($ReportPath) {
    $rp = Resolve-Path -LiteralPath $ReportPath -ErrorAction SilentlyContinue
    if (-not $rp) { $dir = Split-Path $ReportPath -Parent; if ($dir -and -not (Test-Path $dir)) { New-Item -ItemType Directory -Path $dir -Force | Out-Null } }
    $reportLines | Out-File -FilePath $ReportPath -Encoding UTF8
    Write-Output "Report written to: $ReportPath"
} else {
    $reportLines | ForEach-Object { Write-Output $_ }
}
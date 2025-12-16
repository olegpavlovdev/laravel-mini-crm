#!/usr/bin/env python3
"""Strip common comment styles and excessive blank lines from repository files.

This script is conservative:
- Skips binary files (non-UTF-8).
- Backs up each modified file to .cleanup_backups/<relative_path>.bak
- Avoids stripping 'http'/'https' occurrences that contain '//' in URLs.

Run from the repo root: python scripts/strip_comments.py
"""
import os
import re
from pathlib import Path

ROOT = Path(__file__).resolve().parents[1]
BACKUP_DIR = ROOT / '.cleanup_backups'
SKIP_DIRS = {'.git', '__pycache__'}

def is_text_file(p: Path) -> bool:
    try:
        p.read_text(encoding='utf-8')
        return True
    except Exception:
        return False

def backup_file(p: Path):
    dst = BACKUP_DIR / p.relative_to(ROOT)
    dst.parent.mkdir(parents=True, exist_ok=True)
    dst.write_bytes(p.read_bytes())

def strip_comments(text: str) -> str:
    original = text
    # Blade comments {{-- ... --}}
    text = re.sub(r"\{\{\-\-.*?\-\-\}\}", '', text, flags=re.S)
    # HTML comments <!-- ... -->
    text = re.sub(r"<!--.*?-->", '', text, flags=re.S)
    # PHPDoc /** ... */ and general C-style block comments /* ... */
    text = re.sub(r"/\*\*.*?\*/", '', text, flags=re.S)
    text = re.sub(r"/\*.*?\*/", '', text, flags=re.S)
    # Remove lines that are only single-line comments (// or #), but preserve shebangs (#!)
    lines = []
    for line in text.splitlines():
        stripped = line.lstrip()
        if stripped.startswith('#!'):
            lines.append(line)
            continue
        # # comment lines
        if stripped.startswith('#'):
            continue
        # // whole-line comment
        if stripped.startswith('//'):
            continue
        # inline // comments: remove after // unless the line contains http or https
        if '//' in line and 'http' not in line and 'https' not in line:
            line = re.sub(r"//.*$", '', line)
        lines.append(line.rstrip())
    text = '\n'.join(lines)
    # Remove excessive blank lines (3+ -> 1)
    text = re.sub(r"\n{3,}", '\n\n', text)
    # Trim trailing spaces on each line
    text = '\n'.join([ln.rstrip() for ln in text.splitlines()]) + '\n'
    return text if text != original else original

def should_process(p: Path) -> bool:
    if p.is_dir():
        return False
    if any(part in SKIP_DIRS for part in p.parts):
        return False
    if p.suffix.lower() in {'.png', '.jpg', '.jpeg', '.gz', '.zip', '.phar', '.dll', '.so'}:
        return False
    return is_text_file(p)

def main(dry_run: bool = False, report_path: Path | None = None):
    """Process files. If dry_run is True, write the list of updates to
    report_path (or stdout) but do not change files. Otherwise back up and
    update modified files as before.
    """
    if not dry_run:
        BACKUP_DIR.mkdir(exist_ok=True)
    changed = []
    report_lines = []
    for p in ROOT.rglob('*'):
        if not should_process(p):
            continue
        try:
            txt = p.read_text(encoding='utf-8')
        except Exception:
            continue
        new = strip_comments(txt)
        if new != txt:
            line = f'Will update: {p.relative_to(ROOT)}'
            print(line)
            report_lines.append(line)
            changed.append(p.relative_to(ROOT))
            if not dry_run:
                backup_file(p)
                p.write_text(new, encoding='utf-8')

    summary = ['\nDone. Files changed:']
    if changed:
        for c in changed:
            summary.append(' - ' + str(c))
    else:
        summary.append(' (no changes)')

    if report_path:
        report_path.parent.mkdir(parents=True, exist_ok=True)
        report_path.write_text('\n'.join(report_lines + summary), encoding='utf-8')
    else:
        print('\n'.join(summary))

if __name__ == '__main__':
    import argparse

    ap = argparse.ArgumentParser(description='Strip comments (dry-run by default).')
    ap.add_argument('--apply', action='store_true', help='Apply changes (backup and overwrite files).')
    ap.add_argument('--report', '-r', type=str, default='.strip_report.txt', help='Write report to file (default .strip_report.txt)')
    args = ap.parse_args()

    report_path = Path(args.report)
    # By default run a dry-run and write the report; use --apply to make changes.
    main(dry_run=not args.apply, report_path=report_path)
    if report_path.exists():
        print(f"Report written to: {report_path}")

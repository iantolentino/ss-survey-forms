from pathlib import Path
import argparse
import zipfile

root = Path(__file__).resolve().parents[1]
parser = argparse.ArgumentParser(description="Build a cPanel survey upload package.")
parser.add_argument("--output", default="dist/survey-results-update.zip")
parser.add_argument("--full", action="store_true", help="Include form.php for a fresh installation.")
args = parser.parse_args()
target = Path(args.output)
if not target.is_absolute():
    target = root / target
target.parent.mkdir(parents=True, exist_ok=True)
paths = []
for path in sorted((root / "survey").rglob("*")):
    if not path.is_file() or path.is_symlink():
        continue
    relative = path.relative_to(root)
    if "data" in relative.parts and path.name not in (".htaccess", "index.html"):
        continue
    if path.suffix in (".sqlite", ".db", ".zip") or ".sqlite-" in path.name:
        continue
    if path.name == "form.php" and not args.full:
        continue
    paths.append(path)
with zipfile.ZipFile(target, "w", zipfile.ZIP_DEFLATED) as archive:
    for path in paths:
        archive.write(path, path.relative_to(root).as_posix())
with zipfile.ZipFile(target) as archive:
    assert archive.testzip() is None
print(target)

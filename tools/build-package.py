from pathlib import Path
import argparse
import zipfile

root = Path(__file__).resolve().parents[1]
parser = argparse.ArgumentParser(description="Build a cPanel survey upload package.")
parser.add_argument("--output", default="dist/survey-results-update.zip")
parser.add_argument("--full", action="store_true", help="Include every form.php for a fresh installation.")
parser.add_argument("--survey", help="Package one new survey, shared runtime, and results hub without replacing other surveys.")
args = parser.parse_args()
if args.full and args.survey:
    parser.error("Use either --full or --survey, not both.")
target = Path(args.output)
if not target.is_absolute():
    target = root / target
target.parent.mkdir(parents=True, exist_ok=True)
paths = []
if args.survey:
    if Path(args.survey).name != args.survey or args.survey == "shared":
        parser.error("--survey must be the name of a survey folder.")
    survey_dir = root / "survey" / args.survey
    if not survey_dir.is_dir():
        parser.error(f"Survey folder not found: {args.survey}")
    candidates = [*survey_dir.rglob("*"), *(root / "survey" / "shared").rglob("*"), root / "survey" / "results.php"]
else:
    candidates = (root / "survey").rglob("*")

for path in sorted(candidates):
    if not path.is_file() or path.is_symlink():
        continue
    relative = path.relative_to(root)
    if "data" in relative.parts and path.name not in (".htaccess", "index.html"):
        continue
    if path.suffix in (".sqlite", ".db", ".zip") or ".sqlite-" in path.name:
        continue
    if path.name == "form.php" and not args.full and not (args.survey and path.parent == survey_dir):
        continue
    paths.append(path)
with zipfile.ZipFile(target, "w", zipfile.ZIP_DEFLATED) as archive:
    for path in paths:
        archive.write(path, path.relative_to(root).as_posix())
with zipfile.ZipFile(target) as archive:
    assert archive.testzip() is None
print(target)

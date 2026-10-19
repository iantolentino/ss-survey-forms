# Brain Index

Use this index to find the smallest authoritative context for the question. Do not load unrelated history by default.

| Question or intent | Knowledge needed | Authoritative source |
| --- | --- | --- |
| What is happening now? | Active work and constraints | `CURRENT_STATE.md` |
| How do I continue the previous task? | Last handoff | `sessions/LATEST_HANDOFF.md` |
| What changed today? | Activity history | `daily/YYYY-MM-DD.md` |
| What are the survey app's components? | PHP, SQLite, and request flow | `memory/system_architecture.md` |
| What does this app do and who uses it? | Product context | `memory/app_context.md`, `overview/system_summary.md` |
| What are the durable project decisions? | Architecture and scope choices | `decisions/decision_log.md` |
| How is local work performed? | XAMPP setup | `../README.md`, `../survey/README.md` |
| How is cPanel deployment prepared? | Targeted package and upload steps | `deployment/deployment.md`, `deployment/environments.md` |
| How are responses stored or backed up? | Per-survey SQLite policy | `db_backup/backup_policy.md` |
| How should a code change proceed? | Intent-specific workflow | `intents/feature-development.md`, `intents/bug-fix.md`, `tools/workflow-gate.ps1` |
| How does the AI Nexus context framework work? | Controller architecture and selector | `AI_BRAIN.md`, `architecture/ARCHITECTURE.md`, `tools/select-context.ps1` |

## Context levels

1. **Minimal:** `AI_BRAIN.md`, this index, `CURRENT_STATE.md`, latest handoff.
2. **Current task:** today's daily log and only the code or requirements involved.
3. **Supporting knowledge:** the exact architecture, decision, or deployment file needed.
4. **Historical:** older daily logs or archived handoffs only when a fact cannot be found above.

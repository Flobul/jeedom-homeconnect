#!/usr/bin/env bash
set -euo pipefail
# Repository-specific checks migrated from previous workflows.
echo "Plugin-specific checks: not applicable"
node .github/ci/test_community_integration.js
php .github/ci/test_community_diagnostics.php
python3 .github/ci/test_release_guard.py
python3 .github/ci/test_release_workflow.py
python3 .github/ci/test_badge_workflow.py

#!/usr/bin/env python3
from pathlib import Path
import json
import re
import subprocess
import unittest

ROOT = Path(__file__).resolve().parents[2]
WORKFLOW = ROOT / ".github" / "workflows" / "release.yml"


class ReleaseWorkflowTests(unittest.TestCase):
    def test_release_workflow_is_gated_and_recoverable(self):
        text = WORKFLOW.read_text(encoding="utf-8")
        self.assertIn("workflow_run:", text)
        self.assertIn("workflow_dispatch:", text)
        self.assertIn("default: master", text)
        self.assertIn("REQUESTED_REF: ${{ inputs.ref || 'master' }}", text)
        self.assertIn("Release automation simulation", text)
        self.assertIn("RELEASE_AUTOMATION_ENABLED: ${{ vars.RELEASE_AUTOMATION_ENABLED", text)
        self.assertIn('[ "$RELEASE_AUTOMATION_ENABLED" = "true" ]', text)
        self.assertIn("Candidate is not the current default-branch commit", text)
        self.assertIn("conclusion == 'success'", text)
        self.assertIn('actions/runs?per_page=100', text)
        self.assertIn('.path == ".github/workflows/ci.yml"', text)
        self.assertNotIn('gh run list --repo "$GITHUB_REPOSITORY" --workflow ci.yml', text)
        self.assertIn("fetch-tags: true", text)
        self.assertIn("release_guard.py", text)
        self.assertIn("create-tag-and-release", text)
        self.assertIn("needs.plan.outputs.action != 'none'", text)
        guard_text = (ROOT / ".github" / "ci" / "release_guard.py").read_text(encoding="utf-8")
        self.assertIn('"create-release"', guard_text)
        self.assertIn("git show-ref --verify", text)
        self.assertIn(".object.type", text)
        self.assertIn("git/tags/$remote_sha", text)
        self.assertNotIn("git tag -f", text)
        self.assertNotIn("git push --force", text)
        self.assertIn("CENTRAL_DISPATCH_ENABLED", text)
        self.assertIn("CENTRAL_DISPATCH_TOKEN", text)
        self.assertIn('Authorization: Bearer $CENTRAL_DISPATCH_TOKEN', text)
        self.assertNotIn('$CENTR...OKEN', text)
        self.assertIn("repos/Flobul/.jeedom/dispatches", text)
        self.assertIn('"folder": "jeedom-homeconnect"', text)
        self.assertIn("actions/upload-artifact@v6", text)
        self.assertNotIn("pull_request_target", text)

    def test_release_baseline_matches_reference_calendar_commit(self):
        baseline = json.loads((ROOT / ".github" / "release-baseline.json").read_text(encoding="utf-8"))
        self.assertEqual(baseline["plugin_id"], "homeconnect")
        self.assertEqual(baseline["class_file"], "core/class/homeconnect.class.php")
        self.assertEqual(baseline["reference_version"], "2026-07-18")
        self.assertEqual(
            baseline["reference_commit"],
            "bc8b2c36dc8970a0838e636b1a77acb6947e459d",
        )
        commit = subprocess.run(
            ["git", "-C", str(ROOT), "cat-file", "-t", baseline["reference_commit"]],
            check=True,
            text=True,
            capture_output=True,
        ).stdout.strip()
        self.assertEqual(commit, "commit")
        info = subprocess.run(
            ["git", "-C", str(ROOT), "show", f'{baseline["reference_commit"]}:plugin_info/info.json'],
            check=True,
            text=True,
            capture_output=True,
        ).stdout
        self.assertEqual(json.loads(info)["pluginVersion"], "2026-07-18")
        class_text = subprocess.run(
            [
                "git",
                "-C",
                str(ROOT),
                "show",
                f'{baseline["reference_commit"]}:core/class/homeconnect.class.php',
            ],
            check=True,
            text=True,
            capture_output=True,
        ).stdout
        self.assertRegex(
            class_text,
            r"public\s+static\s+\$_pluginVersion\s*=\s*['\"]2026\-07\-18['\"]",
        )

    def test_calendar_changelog_preserves_wordpress_history(self):
        changelog = (ROOT / "docs" / "CHANGELOG.md").read_text(encoding="utf-8")
        self.assertEqual(len(re.findall(r"^## Version ", changelog, re.MULTILINE)), 20)
        self.assertEqual(len(re.findall(r"^[-*+] ", changelog, re.MULTILINE)), 23)
        self.assertEqual(len(re.findall(r"<https?://[^>]+>", changelog)), 8)
        self.assertIn("## Version 2026-07-18 (18/07/2026)", changelog)
        self.assertIn("Version du 19/07/2026", changelog)

    def test_release_guard_is_part_of_blocking_ci(self):
        text = (ROOT / ".github" / "ci" / "extra-checks.sh").read_text(encoding="utf-8")
        self.assertIn("test_release_guard.py", text)
        workflow = (ROOT / ".github" / "workflows" / "check-jeedom.yml").read_text(encoding="utf-8")
        self.assertIn("fetch-depth: 0", workflow)
        orchestrator = (ROOT / ".github" / "workflows" / "ci.yml").read_text(encoding="utf-8")
        self.assertIn("name: CI", orchestrator)
        self.assertIn("uses: ./.github/workflows/check-jeedom.yml", orchestrator)


if __name__ == "__main__":
    unittest.main(verbosity=2)

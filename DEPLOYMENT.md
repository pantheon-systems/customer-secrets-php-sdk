---
contract_version: 2
kind: library                       # Composer package pantheon-systems/customer-secrets-php-sdk; nothing runs from this repo
service: null                       # doesn't apply

trigger: merge-to-main              # every push to main (except [skip ci]) gets a patch tag + GitHub release; Packagist publishes the tag
merged_implies_deployed: true       # merged => published on Packagist within seconds. Consumers then pick it up on their next `composer update`.
other_paths_to_prod:
  - "pushing any tag by hand: Packagist publishes every tag it sees"

pipeline:
  system: github-actions
  workflow: ".github/workflows/tag-release.yml"   # workflow name "Autotag and Release"
  jobs_in_order: [tag-release]      # does NOT wait for "CI Tests" (ci.yml): a merge that breaks tests still releases
  typical_minutes: 1                # run 33195192701 (v1.0.3): job ran 17:33:04 -> 17:33:12 UTC, 2026-08-28
  minutes_by_change: {}
  watch: "Actions workflow 'Autotag and Release' on main; then the new tag on Packagist"
  concurrency_group: null           # verified: none
  cancel_in_progress: false
  blocked_by_waiting_run: null
  approval_gate: null               # verified: no GitHub Environments

repo_properties:
  observed_at: 2026-11-10
  trust_level: TL4
  ccb_status: in-ccb
  owner: developer-experience
  repo_type: library

gating_checks:                      # `gh api repos/pantheon-systems/customer-secrets-php-sdk/rules/branches/main`, 2026-11-10
  required: ["Wiz IaC Scanner", "Wiz Vulnerability Scanner", "Wiz Data Scanner"]   # org TL4 only; the repo ruleset adds no required checks
  approvals: {required: 1, code_owner: true, last_push_approval: false, strict_up_to_date: false, merge_queue: true}   # code owner from the TL4 org ruleset; repo ruleset adds a squash merge queue
  run_but_not_required: ["CI Tests (phpcs, functional)"]   # on: push (every branch), daily 06:00 UTC and manual; last 30 runs all green (2026-11-10)
  conditional: []

environments: []                    # none of its own

artifact:
  image: null
  tag_scheme: semver                # vX.Y.Z since v1.0.2 (older tags have no v: 1.0.0, 1.0.1)
  tag_mutable: false                # by convention: no tag ruleset (verified 2026-11-10)
  retention: "tags and Packagist versions kept indefinitely"
  version_signal: tag
  tag_to_sha: "git rev-list -n1 vX.Y.Z"

release:
  mechanism: "pantheon-systems/action-autotag v1.2.0 (autotag: patch bump unless [minor]/#minor or [major]/#major in the commit message) pushes the tag and creates a GitHub release; Packagist's GitHub sync publishes it"
  published_to:
    - "GitHub tags + releases"
    - "Packagist: https://packagist.org/packages/pantheon-systems/customer-secrets-php-sdk (verified: v1.0.3 listed with the same commit, 2026-11-10; ~90k downloads/month)"
  never: "move or re-push a tag (composer.lock records the commit; Packagist caches dist metadata)"

consumers:                          # gh search code + Packagist dependents, 2026-11-10
  - {repo: "drupal/pantheon_secrets (pantheon-systems/pantheon_secrets)", pin: "composer.json ^1.0", version: "any 1.x", owner: site-experience, bumped_by: "each site's composer update"}
  - {repo: "pantheon-systems/panthio", pin: "composer.json ^1.0 (lock: 1.0.1)", version: 1.0.1, owner: marketing-operations, bumped_by: null}
  - {repo: "customer sites on Pantheon", pin: "their own composer.json/lock", version: null, owner: customers, bumped_by: "the site's composer update"}

deployed_when:
  - "tag vX.Y.Z points at the squash-merge SHA, a GitHub release exists, and Packagist lists vX.Y.Z"

health:
  soak_minutes: null                # no runtime; consumers find out on their next composer update
  baseline: null
  checks:
    - name: release published
      kind: command
      run: "gh release view vX.Y.Z -R pantheon-systems/customer-secrets-php-sdk --json tagName,publishedAt && curl -s https://repo.packagist.org/p2/pantheon-systems/customer-secrets-php-sdk.json | jq -r '.packages[][]|.version' | head -3"
      healthy: "both list vX.Y.Z"   # verified for v1.0.3
    - name: tests on the released commit
      kind: command
      run: "gh run list -R pantheon-systems/customer-secrets-php-sdk -w 'CI Tests' -c <merge-sha> --json conclusion"
      healthy: "success"            # releases don't wait for it, so check it after the fact
  known_baseline_errors: []
  dashboards: []
  alerts: []

rollback:
  options:
    - when: always
      kind: retract-and-repin       # for Composer: release a fixed patch; there is no retract, consumers on ^1.0 move forward on their next update
      steps: "revert the bad merge on a branch, merge it: CI cuts the next patch tag within a minute and Packagist publishes it. Sites that already updated run composer update again (or pin the last good version)."
      time_to_rollback_minutes: 15  # inferred: review + merge + ~1 min release
  not_restored: ["sites that already installed the bad version keep it until they run composer update"]
  one_way_changes: ["a published version stays installable from composer.lock files that recorded it"]
  currently_blocked: null
  last_exercised: null
  capture_before_merge: "gh release view -R pantheon-systems/customer-secrets-php-sdk --json tagName -q .tagName   # the last good version"
  who_can: ["@pantheon-systems/developer-experience (code owners)"]
  verify: "Packagist lists the new patch version; `composer show pantheon-systems/customer-secrets-php-sdk` in an affected consumer shows it"

escalation:
  slack: "#ask-hosting"
  owner_team: developer-experience
  paged: false                      # inferred

preflight:
  - "gh auth status"

automation_blockers:
  - blocker: "the release doesn't wait for tests: any merge to main is published to Packagist even if 'CI Tests' fails"
    clears_when: "tag-release runs after CI passes (needs/workflow_run), or CI is a required check in the merge queue"
  - blocker: "releases reach customer sites directly (^1.0) with no staged rollout and no way to unpublish cleanly"
    clears_when: "accepted as inherent to a public package; keep releases gated on tests"

verified:
  at: null
  by: null
  change: null
  running_digest: null              # doesn't apply
---

# Deployment

This repo is a **PHP library** published on Packagist. Nothing runs from it. Every merge to `main` is published as a new patch version within a minute. Consumers get it the next time they run `composer update`. Customer sites using the Drupal `pantheon_secrets` module (which requires `^1.0`) pick it up that way too, so a release reaches sites without any Pantheon deploy.

## How a change reaches production

1. **PR → merge.** The TL4 org ruleset needs 1 approval from a code owner (`@pantheon-systems/developer-experience`). Only the Wiz checks are required. Merges go through a squash merge queue. `CI Tests` (phpcs and functional tests) runs on every push, including the merge queue's branch, but it isn't required.
2. **Release.** Workflow `Autotag and Release` runs on every push to `main` that isn't `[skip ci]`. `action-autotag` computes the next version (a patch bump, unless the commit message has `[minor]`/`#minor` or `[major]`/`#major`), pushes the tag and creates a GitHub release. The job takes about 10 seconds. **It doesn't wait for `CI Tests`.**
3. **Packagist** publishes the tag through its GitHub sync. v1.0.3 appeared there with the same commit.
4. **Consumers** update on their own schedule:
   - [pantheon_secrets](https://github.com/pantheon-systems/pantheon_secrets) (Drupal module `drupal/pantheon_secrets`, owner site-experience) requires `^1.0`. Sites using it get new versions on `composer update`.
   - `pantheon-systems/panthio` (owner marketing-operations) requires `^1.0`; its lock file has 1.0.1.
   - Customer sites requiring the package directly. Packagist reports about 90,000 downloads a month.

## How to confirm it deployed

```sh
gh release view -R pantheon-systems/customer-secrets-php-sdk --json tagName,publishedAt,targetCommitish
curl -s https://repo.packagist.org/p2/pantheon-systems/customer-secrets-php-sdk.json \
  | jq -r '.packages[][]|"\(.version) \(.source.reference)"' | head -3
```

The new version is on Packagist and its `source.reference` is the merge SHA. Then check that `CI Tests` passed on that commit (`gh run list -R pantheon-systems/customer-secrets-php-sdk -w "CI Tests" -c <merge-sha>`), because the release didn't wait for it.

## How to confirm it's healthy

There is no runtime signal here. A bad release shows up as consumer reports (in `#ask-hosting`, or support tickets about secrets in Drupal/WordPress sites). TODO(owner): is there any signal from sites using the module, e.g. errors reading secrets?

## Rollback

Composer has no "retract". Roll back by releasing a fix:

1. On a branch from `main`, `git revert <bad-merge-sha>` (or fix forward) and merge it. CI cuts the next patch version within a minute and Packagist publishes it.
2. Consumers on `^1.0` move to it on their next `composer update`. Sites that already installed the bad version keep it until then; tell affected consumers to update, or to pin the last good version (`composer require pantheon-systems/customer-secrets-php-sdk:<last good>`).

Don't move or re-push a tag. Deleting a tag removes the version from Packagist, but lock files that recorded it still point at that commit; only consider it, with the owners, for a release that must not be installed at all.

Capture the last good version before merging: `gh release view -R pantheon-systems/customer-secrets-php-sdk --json tagName -q .tagName`.

## Escalation

- Owner: `@pantheon-systems/developer-experience`. Ask in `#ask-hosting`. Not paged.
- The Drupal module `pantheon_secrets` belongs to site-experience.

## Suggested pipeline changes

- Run `tag-release` only after `CI Tests` passes on `main` (or make CI a required check in the merge queue), so a failing build can't be published.
- Protect `v*` tags with a tag ruleset.

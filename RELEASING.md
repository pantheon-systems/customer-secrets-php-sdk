# Releasing

Releases are automated. Merging to `main` triggers the `Autotag and Release` workflow which:

1. Reads the version from the repository tags and increments it
2. Creates and pushes a new git tag
3. Packagist picks up the new tag automatically and publishes the updated package

No manual steps are required.

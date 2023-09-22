<?php

namespace PantheonSystems\CustomerSecrets;

interface CustomerSecretsClientInterface
{
    /**
     * Get all secrets for current site.
     */
    public function getSecrets(): array;

    /**
     * Get a specific secret for current site.
     */
    public function getSecret(string $secretName): ?Secret;

    /**
     * Create a new secret for current site.
     */
    public function setSecret(Secret $secret): void;

    /**
     * Delete a secret for current site.
     */
    public function deleteSecret(string $secretName): void;
}

<?php

namespace PantheonSystems\CustomerSecrets;

class CustomerSecretsFakeClient implements CustomerSecretsClientInterface
{

    /**
     * Secret list.
     *
     * @var \PantheonSystems\CustomerSecrets\SecretList
     */
    protected SecretList $secretList;

    /**
     * CustomerSecretsClient constructor.
     */
    public function __construct()
    {
        $this->secretList = new SecretList();
    }

    /**
     * Get all secrets for current site.
     */
    public function getSecrets(): array
    {
        return [];
    }

    /**
     * Get a specific secret for current site.
     */
    public function getSecret(string $secretName): Secret
    {
        return null;
    }

    /**
     * Create a new secret for current site.
     */
    public function setSecret(Secret $secret): void
    {
        throw new CustomerSecretsNotImplemented();
    }

    /**
     * Delete a secret for current site.
     */
    public function deleteSecret(string $secretName): void
    {
        throw new CustomerSecretsNotImplemented();
    }
}

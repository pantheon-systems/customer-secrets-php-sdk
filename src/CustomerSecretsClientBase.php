<?php

declare(strict_types=1);

namespace PantheonSystems\CustomerSecrets;

use Exception;
use PantheonSystems\CustomerSecrets\Exceptions\CustomerSecretsNotImplemented;

abstract class CustomerSecretsClientBase implements CustomerSecretsClientInterface
{
    /**
     * Secret list.
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
     * Fetches secret data for current site.
     */
    abstract protected function fetchSecrets() : void;

    /**
     * Retrieves/Generates metatadata about the secret list.
     *
     * @throws Exception
     */
    protected function secretListMetadata(array $values = []) : array
    {
        if (isset($values['Secrets'])) {
            unset($values['Secrets']);
        }
        return $values;
    }

    /**
     * Get secrets metadata for current site.
     *
     *
     *     Whether to refresh the secret list.
     */
    public function getSecretsMetadata(bool $refresh = false) : array
    {
        if ($refresh || empty($this->secretList->getMetadata())) {
            $this->fetchSecrets();
        }
        return $this->secretList->getMetadata();
    }

    /**
     * Get all secrets for current site.
     *
     *
     *     Whether to refresh the secret list.
     */
    public function getSecrets(bool $refresh = false) : array
    {
        if ($refresh || empty($this->secretList->getMetadata())) {
            $this->fetchSecrets();
        }
        return $this->secretList->getSecrets();
    }

    /**
     * Get a specific secret for current site.
     *
     *     The secret name.
     *
     *     Whether to refresh the secret list.
     */
    public function getSecret(string $secretName, bool $refresh = false) : ?Secret
    {
        $secrets = $this->getSecrets($refresh);
        return $secrets[$secretName] ?? null;
    }

    /**
     * Create a new secret for current site.
     */
    public function setSecret(Secret $secret) : void
    {
        throw new CustomerSecretsNotImplemented();
    }

    /**
     * Delete a secret for current site.
     */
    public function deleteSecret(string $secretName) : void
    {
        throw new CustomerSecretsNotImplemented();
    }
}

<?php

namespace PantheonSystems\CustomerSecrets;

use Pantheon\Internal\CustomerSecrets\CustomerSecretsClient as InternalClient;

class CustomerSecretsClient implements CustomerSecretsClientInterface
{

    /**
     * Secret list.
     *
     * @var \PantheonSystems\CustomerSecrets\SecretList
     */
    protected SecretList $secretList;

    /**
     * Secrets internal client.
     *
     * @var object internal client
     */
    protected $internalClient;

    /**
     * CustomerSecretsClient constructor.
     */
    public function __construct()
    {
        $this->internalClient = InternalClient::create(['version' => '1']);
        $this->secretList = new SecretList();
    }

    /**
     * Fetches secret data for current site.
     */
    protected function fetchSecrets(): void
    {
        $secretResults = $this->internalClient->get();
        $this->secretList->setMetadata($this->secretListMetadata($secretResults));
        $secrets = [];
        foreach ($secretResults['Secrets'] as $name => $secretResult) {
            $secrets[$name] = new Secret($name, $secretResult['Value'], $secretResult['Type'], $secretResult['Scopes']);
        }
        $this->secretList->setSecrets($secrets);
    }

    /**
     * Retrieves/Generates metatadata about the secret list.
     *
     * @param array $values
     *
     * @return array
     * @throws \Exception
     */
    protected function secretListMetadata(array $values = []): array {
        if (isset($values['Secrets'])) {
            unset($values['Secrets']);
        }
        return $values;

    }

    /**
     * Get all secrets for current site.
     *
     * @param bool $refresh
     *   Whether to refresh the secret list.
     */
    public function getSecrets(bool $refresh = false): array
    {
        if ($refresh || empty($this->secretList->getMetadata())) {
            $this->fetchSecrets();
        }
        return $this->secretList->getSecrets();
    }

    /**
     * Get a specific secret for current site.
     *
     * @param string $secretName
     *   The secret name.
     * @param bool $refresh
     *   Whether to refresh the secret list.
     */
    public function getSecret(string $secretName, bool $refresh = false): Secret
    {
        $secrets = $this->getSecrets($refresh);
        return $secrets[$secretName] ?? null;
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
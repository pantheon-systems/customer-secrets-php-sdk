<?php

namespace PantheonSystems\CustomerSecrets;

use Pantheon\Internal\CustomerSecrets\CustomerSecretsClient as InternalClient;
use Pantheon\Internal\CustomerSecrets\CustomerSecretsClientInterface as InternalClientInterface;
use PantheonSystems\CustomerSecrets\Exceptions\CustomerSecretsNotReady;

class CustomerSecretsClient extends CustomerSecretsClientBase implements CustomerSecretsClientInterface
{
    /**
     * Secrets internal client.
     *
     * @var Pantheon\Internal\CustomerSecrets\CustomerSecretsClientInterface $internalClient
     */
    protected InternalClientInterface $internalClient;

    /**
     * CustomerSecretsClient constructor.
     */
    public function __construct(array $args)
    {
        parent::__construct();
        if (empty($args['version'])) {
            $args['version'] = '1';
        }

        // If testMode is set, then we are in a test environment and we should not yet create the InternalClient.
        if (empty($args['testMode'])) {
            $this->internalClient = InternalClient::create($args);
        }
    }

    /**
     * Set internal client.
     *
     * @param Pantheon\Internal\CustomerSecrets\CustomerSecretsClientInterface $internalClient
     */
    public function setInternalClient(InternalClientInterface $internalClient): void
    {
        $this->internalClient = $internalClient;
    }

    /**
     * Fetches secret data for current site.
     */
    protected function fetchSecrets(): void
    {
        // Throw exception if internal client is not set.
        if (empty($this->internalClient)) {
            throw new CustomerSecretsNotReady();
        }

        $secretResults = $this->internalClient->get();
        $this->secretList->setMetadata($this->secretListMetadata($secretResults));
        $secrets = [];
        foreach ($secretResults['Secrets'] as $name => $secretResult) {
            $secrets[$name] = new Secret($name, $secretResult['Value'], $secretResult['Type'], $secretResult['Scopes']);
        }
        $this->secretList->setSecrets($secrets);
    }
}

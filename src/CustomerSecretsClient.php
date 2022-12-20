<?php

namespace PantheonSystems\CustomerSecrets;

use Pantheon\Internal\CustomerSecrets\CustomerSecretsClient as InternalClient;

class CustomerSecretsClient extends CustomerSecretsClientBase implements CustomerSecretsClientInterface
{
    /**
     * Secrets internal client.
     *
     * @var object internal client
     */
    protected $internalClient;

    /**
     * CustomerSecretsClient constructor.
     */
    public function __construct(array $args)
    {
        parent::__construct();
        if (empty($args['version'])) {
            $args['version'] = '1';
        }
        $this->internalClient = InternalClient::create($args);
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
}

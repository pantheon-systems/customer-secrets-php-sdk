<?php

namespace PantheonSystems\CustomerSecrets;

class CustomerSecrets
{
    protected $client;

    /**
     * Construct.
     */
    protected function __construct(array $args = [])
    {
        if (class_exists('\Pantheon\Internal\CustomerSecrets\CustomerSecretsClient')) {
            $this->client = new CustomerSecretsClient($args);
        } else {
            $this->client = new CustomerSecretsFakeClient($args);
        }
    }

    /**
     * Get secrets client.
     */
    public function getClient(): CustomerSecretsClientInterface
    {
        return $this->client;
    }

    public static function create(array $args = []): CustomerSecrets
    {
        return new CustomerSecrets($args);
    }
}

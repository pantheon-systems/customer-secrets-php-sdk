<?php

namespace PantheonSystems\CustomerSecrets;

class CustomerSecrets
{

    protected $client;
    
    /**
     * Construct.
     */
    private function __construct()
    {
        if (class_exists('\Pantheon\Internal\CustomerSecrets\CustomerSecretsClient')) {
            $this->client = new CustomerSecretsClient();
        } else {
            $this->client = new CustomerSecretsFakeClient();
        }
    }

    /**
     * Get secrets client.
     */
    public function getClient(): CustomerSecretsClientInterface
    {
        return $this->client;
    }

    public static function create(): CustomerSecretsClientInterface
    {
        return new CustomerSecrets();
    }
}

<?php

declare(strict_types=1);

namespace PantheonSystems\CustomerSecrets;

use function class_exists;

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
    public function getClient() : CustomerSecretsClientInterface
    {
        return $this->client;
    }

    public static function create(array $args = []) : self
    {
        return new CustomerSecrets($args);
    }
}

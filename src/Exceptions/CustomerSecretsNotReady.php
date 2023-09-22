<?php

namespace PantheonSystems\CustomerSecrets\Exceptions;

class CustomerSecretsNotReady extends \Exception
{
    /**
     * Creates a new CustomerSecretsNotReady object.
     */
    public function __construct()
    {
        parent::__construct('Customer Secrets not yet ready.');
    }
}

<?php

namespace PantheonSystems\CustomerSecrets\Exceptions;

class CustomerSecretsNotImplemented extends \Exception
{
    /**
     * Creates a new CustomerSecretsNotImplemented object.
     */
    public function __construct()
    {
        parent::__construct('Customer Secrets method not yet implemented.');
    }
}

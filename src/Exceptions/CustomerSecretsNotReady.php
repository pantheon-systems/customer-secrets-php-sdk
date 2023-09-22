<?php

declare(strict_types=1);

namespace PantheonSystems\CustomerSecrets\Exceptions;

use Exception;

class CustomerSecretsNotReady extends Exception
{
    /**
     * Creates a new CustomerSecretsNotReady object.
     */
    public function __construct()
    {
        parent::__construct('Customer Secrets not yet ready.');
    }
}

<?php

declare(strict_types=1);

namespace PantheonSystems\CustomerSecrets\Exceptions;

use Exception;

class CustomerSecretsNotImplemented extends Exception
{
    /**
     * Creates a new CustomerSecretsNotImplemented object.
     */
    public function __construct()
    {
        parent::__construct('Customer Secrets method not yet implemented.');
    }
}

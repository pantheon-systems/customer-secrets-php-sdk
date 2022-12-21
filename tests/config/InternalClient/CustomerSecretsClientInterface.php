<?php

namespace Pantheon\Internal\CustomerSecrets;

/**
 * @file
 * Customer Secrets Client Interface copied from Pantheon internal prepend files.
 */

/**
 * Interface CustomerSecretsClientInterface.
 */
interface CustomerSecretsClientInterface
{
    /**
     * Returns Customer Secrets for the site associated with
     * the current binding, as identified by the binding cert.
     *
     * @return array
     */
    public function get(): array;
}

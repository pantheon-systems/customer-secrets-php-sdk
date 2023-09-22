<?php

declare(strict_types=1);

namespace PantheonSystems\CustomerSecrets;

use Exception;

trait SecretListMetadataTrait
{
    protected $metadata;

    /**
     * Retrieves/Generates metatadata about the secret list.
     *
     * @return array
     * @throws Exception
     */
    public function setSecretListMetadataFromUntypedArray(array $values = []) : void
    {
        if (isset($values['Secrets'])) {
            unset($values['Secrets']);
        }
        $this->metadata = $values;
    }
}

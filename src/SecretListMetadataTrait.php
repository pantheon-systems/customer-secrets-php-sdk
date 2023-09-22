<?php

namespace PantheonSystems\CustomerSecrets;

trait SecretListMetadataTrait
{
    protected $metadata;

  /**
   * Retrieves/Generates metatadata about the secret list.
   *
   * @param array $values
   *
   * @return array
   * @throws \Exception
   */
    public function setSecretListMetadataFromUntypedArray(array $values = []): void
    {
        if (isset($values['Secrets'])) {
            unset($values['Secrets']);
        }
        $this->metadata = $values;
    }
}

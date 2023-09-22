<?php

declare(strict_types=1);

namespace PantheonSystems\CustomerSecrets;

use Exception;

use function json_decode;

class SecretList
{
    use SecretListMetadataTrait;

    /**
     * Secrets.
     */
    protected array $secrets;

    /**
     * Creates a new SecretList object.
     *
     *     The secrets.
     *
     * @param array $secretListMetadata
     *     The secret list metadata.
     */
    public function __construct(array $secrets = [], array $metadata = [])
    {
        $this->secrets = $secrets;
        $this->metadata = $metadata;
    }

    /**
     * Get secrets.
     *
     *
     *     The secrets.
     */
    public function getSecrets() : array
    {
        return $this->secrets;
    }

    /**
     * Set secrets.
     *
     *
     *     The secrets.
     */
    public function setSecrets(array $secrets) : void
    {
        $this->secrets = $secrets;
    }

    /**
     * Get secret list metadata.
     *
     *
     *     The secret list metadata.
     */
    public function getMetadata() : array
    {
        return $this->metadata;
    }

    /**
     * Set secret list metadata.
     *
     *
     *     The secret list metadata.
     */
    public function setMetadata(array $metadata) : void
    {
        $this->metadata = $metadata;
    }

    /**
     * @return static
     * @throws Exception
     */
    public static function fromJson(string $json) : self
    {
        $data = json_decode($json, true);
        $secrets = [];
        $metadata = [];
        foreach ($data['Secrets'] as $name => $secretResult) {
            $secrets[$name] = new Secret($name, $secretResult['Value'], $secretResult['Type'], $secretResult['Scopes']);
        }
        $toReturn = new static($secrets);
        $toReturn->setSecretListMetadataFromUntypedArray($data);
        return $toReturn;
    }
}

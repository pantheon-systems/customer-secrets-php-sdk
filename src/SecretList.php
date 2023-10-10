<?php

namespace PantheonSystems\CustomerSecrets;

/**
 *
 */
class SecretList
{
    use SecretListMetadataTrait;

    /**
     * Secrets.
     *
     * @var array
     */
    protected array $secrets;


    /**
     * Creates a new SecretList object.
     *
     * @param array $secrets
     *   The secrets.
     * @param array $secretListMetadata
     *   The secret list metadata.
     */
    public function __construct(array $secrets = [], array $metadata = [])
    {
        $this->secrets = $secrets;
        $this->metadata = $metadata;
    }

    /**
     * Get secrets.
     *
     * @return array
     *   The secrets.
     */
    public function getSecrets(): array
    {
        return $this->secrets;
    }

    /**
     * Set secrets.
     *
     * @param array $secrets
     *   The secrets.
     */
    public function setSecrets(array $secrets): void
    {
        $this->secrets = $secrets;
    }

    /**
     * Get secret list metadata.
     *
     * @return array
     *   The secret list metadata.
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * Set secret list metadata.
     *
     * @param array $metadata
     *   The secret list metadata.
     */
    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }

  /**
   * @param string $json
   *
   * @return static
   * @throws \Exception
   */
    public static function fromJson(string $json): SecretList
    {
        $data = json_decode($json, true);
        $secrets = [];
        $metadata = [];
        foreach ($data['Secrets'] as $name => $secretResult) {
            $secretValue = $secretResult['Value'];
            if (empty($secretValue) && !empty($secretResult['OrgValues']['default'])) {
                $secretValue = $secretResult['OrgValues']['default'];
            }
            $secrets[$name] = new Secret($name, $secretValue, $secretResult['Type'], $secretResult['Scopes']);
        }
        $toReturn = new static($secrets);
        $toReturn->setSecretListMetadataFromUntypedArray($data);
        return $toReturn;
    }
}

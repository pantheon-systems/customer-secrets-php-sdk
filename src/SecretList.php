<?php

namespace PantheonSystems\CustomerSecrets;

class SecretList
{

    /**
     * Secrets.
     *
     * @var array
     */
    protected array $secrets;

    /**
     * Secrets metadata.
     *
     * @var array
     */
    protected array $metadata;

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

}

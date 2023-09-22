<?php

declare(strict_types=1);

/**
 * @file
 *     Contains \PantheonSystems\CustomerSecrets\CustomerSecretsFakeClient.
 */

namespace PantheonSystems\CustomerSecrets;

use Exception;

use function define;
use function file_exists;
use function file_get_contents;
use function getenv;

use const DEFAULT_TEMP_DIR;

define('DEFAULT_TEMP_DIR', getenv('TMPDIR') ?? '/tmp');

class CustomerSecretsFakeClient extends CustomerSecretsClientBase implements CustomerSecretsClientInterface
{
    /*
     * @var \PantheonSystems\CustomerSecrets\SecretListInterface
     */

    protected SecretList $secretList;

    /**
     * File to store the fake secrets.
     */
    protected string $file;

    /**
     * @param bool $refresh *
     * @return mixed
     */
    public function getSecrets(bool $refresh = false) : array
    {
        return $this->secretList->getSecrets();
    }

    /**
     * @param mixed $secrets
     */
    public function setSecrets(SecretListInterface $secrets) : void
    {
        $this->secrets = $secrets;
    }

    /**
     * CustomerSecretsClient constructor.
     *
     * @param $args array
     *     Options for the fake client.
     *     - file: If not provided, default to value of env var
     *     CUSTOMER_SECRETS_FAKE_FILE or /tmp/secrets.json
     */
    public function __construct(array $args = [])
    {
        parent::__construct();
        $file = null;
        if (! empty($args['file'])) {
            $file = $args['file'];
        }
        if (! $file && getenv('CUSTOMER_SECRETS_FAKE_FILE')) {
            $file = getenv('CUSTOMER_SECRETS_FAKE_FILE');
        }
        if (! $file) {
            $file = DEFAULT_TEMP_DIR . '/secrets.json';
        }
        $this->file = $file;
        $this->secretList = new SecretList();
    }

    /**
     * Get secrets file.
     */
    public function getFilepath() : string
    {
        return $this->file;
    }

    public function setFilepath(string $filepath) : void
    {
        $this->file = $filepath;
    }

    /**
     * Fetches secret data for current site.
     *
     * @throws Exception
     */
    public function fetchSecrets() : void
    {
        if (file_exists($this->file)) {
            $this->secretList = SecretList::fromJson(file_get_contents($this->getFilepath()));
        }
    }
}

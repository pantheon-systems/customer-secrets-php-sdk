<?php

namespace PantheonSystems\CustomerSecrets;

class CustomerSecretsFakeClient extends CustomerSecretsClientBase implements CustomerSecretsClientInterface
{
    /**
     * File to store the fake secrets.
     *
     * @var string
     */
    protected string $file;

    /**
     * CustomerSecretsClient constructor.
     *
     * @param $args array
     *   Options for the fake client.
     *     - file: If not provided, default to value of env var CUSTOMER_SECRETS_FAKE_FILE or /tmp/secrets.json
     */
    public function __construct(array $args = [])
    {
        parent::__construct();
        $file = null;
        if (!empty($args['file'])) {
            $file = $args['file'];
        }
        if (!$file && getenv('CUSTOMER_SECRETS_FAKE_FILE')) {
            $file = getenv('CUSTOMER_SECRETS_FAKE_FILE');
        }
        if (!$file) {
            $file = '/tmp/secrets.json';
        }
        $this->file = $file;
    }

    /**
     * Get secrets file.
     */
    public function getFilepath(): string
    {
        return $this->file;
    }

    /**
     * Fetches secret data for current site.
     */
    protected function fetchSecrets(): void
    {
        if (file_exists($this->file)) {
            $secretResults = json_decode(file_get_contents($this->getFilepath()), true);
        } else {
            $secretResults = [];
        }
        $this->secretList->setMetadata($this->secretListMetadata($secretResults));
        $secrets = [];
        foreach ($secretResults['Secrets'] as $name => $secretResult) {
            $secrets[$name] = new Secret($name, $secretResult['Value'], $secretResult['Type'], $secretResult['Scopes']);
        }
        $this->secretList->setSecrets($secrets);
    }
}

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
     */
    public function __construct(array $args = ['file' => '/tmp/secrets.json'])
    {
        parent::__construct();
        if (empty($args['file'])) {
            $args['file'] = '/tmp/secrets.json';
        }
        $this->file = $args['file'];
    }

        /**
     * Fetches secret data for current site.
     */
    protected function fetchSecrets(): void
    {
        if (file_exists($this->file)) {
            $secretResult = json_decode(file_get_contents($this->file), true);
        } else {
            $secretResult = [];
        }
        $this->secretList->setMetadata($this->secretListMetadata($secretResults));
        $secrets = [];
        foreach ($secretResults['Secrets'] as $name => $secretResult) {
            $secrets[$name] = new Secret($name, $secretResult['Value'], $secretResult['Type'], $secretResult['Scopes']);
        }
        $this->secretList->setSecrets($secrets);
    }
}

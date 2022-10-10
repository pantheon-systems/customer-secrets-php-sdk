<?php

namespace PantheonSystems\CustomerSecrets;

/**
 * Secret List Object.
 */
class SecretList
{
    /**
     * Static value of customer secrets API.
     */
    const CUSTOMER_SECRETS_API = "https://customer-secrets:443";

    /**
     * @param string $siteId
     * @param array $secrets
     * @param array $secretListMetadata
     */
    public function __construct(
        public string $siteId,
        public array $secrets = [],
        public array $secretListMetadata = [],
    ) {
    }
    /**
     * Creates new SecretList object.
     *
     * @return static
     */
    public static function create(string $siteId): SecretList
    {
        return new SecretList($siteId);
    }

    /**
     * Fetches secret data for current site.
     *
     * @throws \JsonException
     * @throws \Exception
     */
    protected function fetchSecretData() {
        [$ch, $opts] = pantheon_curl_setup(self::CUSTOMER_SECRETS_API);
        // Grab URL and pass it to the browser.

        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $result = curl_exec($ch);
        $result = pantheon_curl_result($ch, $opts, null, $result);
        if ($result['status-code'] != 200) {
            return null;
        }
        $secretResults = json_decode($result, true, null, JSON_THROW_ON_ERROR);
        $this->secretListMetadata = $this->secretListMetadata($secretResults);
        $this->secrets = $secretResults['Secrets'];
    }

    /**
     * Retrieves/Generates metatadata about the secret list.
     *
     * @param array $values
     *
     * @return array
     * @throws \Exception
     */
    public function secretListMetadata(array $values = []): array {
        $created = new \DateTime('now');
        if (isset($values['CreatedTime']) && is_string($values['CreatedTime'])) {
            $created = new \DateTime(strtotime($values['CreatedTime']));
        }
        if (isset($values['CreatedTime']) && $values['CreatedTime'] instanceof \DateTime) {
            $created = $values['CreatedTime'];
        }
        $version = $values['Version'] ?? 0;
        unset($values['CreatedTime'], $values['Version']);
        return [
            $created,
            $version,
            $values,
        ];

    }

    /**
     * Return secret data, retrieving if necessary.
     *
     * @param bool $refresh
     *
     * @return array|null
     * @throws \JsonException
     */
    public function get(bool $refresh = false): ?array {
        // If this is the first time that secret data has been requested, fetch.
        // Fetch only occurs if manually triggered or on first run to provide
        // a way to retrieve data without needing to query the server again.
        if (empty($this->secrets) || $refresh) {
            // If the fetch fails, throw an exception and fail.
            try {
                $this->fetchSecretData();
            } catch (\Exception $e) {
                error_log(sprintf("Error of some sort getting secret list: %s", $e->getMessage()));
                return null;
            }
        }

        // If all went well, or if the data already existed, return.
        return [
            $this->siteId,
            $this->secrets,
            $this->secretListMetadata,
        ];
    }
}

<?php

namespace PantheonSystems\Tests\Functional;

use PantheonSystems\CustomerSecrets\Secret;
use PantheonSystems\Internal\Utility\Fixtures;
use PHPUnit\Framework\TestCase;

/**
 * Tests for CustomerSecretsFakeClientTest class.
 */
class CustomerSecretsFakeClientTest extends TestCase
{
    /**
     * @var \PantheonSystems\CustomerSecrets\CustomerSecretsFakeClient
     */
    protected $fakeClient;

    public function setUp(): void
    {
        $this->fakeClient = new \PantheonSystems\CustomerSecrets\CustomerSecretsFakeClient();
    }

    /**
     * @dataProvider providerFakeData
     *
     * @group short
     */
    public function testFakeClient($filename, $count, $scopes, $secretNames, $siteId): void
    {
        $filepath = __DIR__ . '/../Fixtures/' . $filename;
        copy($filepath, $this->fakeClient->getFilepath());

        $secrets = $this->fakeClient->getSecrets();
        $metadata = $this->fakeClient->getSecretsMetadata();

        $this->assertEquals($count, count($secrets), 'Secret count should match the expected ' . $count . ' secrets.');
        $this->assertEquals(
            $scopes,
            $metadata['Scopes'],
            'Scopes should match the expected: ' . implode(', ', $scopes) . ' scopes.'
        );
        $this->assertEquals($siteId, $metadata['SiteID'], 'SiteID should match the expected: ' . $siteId . ' site ID.');

        foreach ($secretNames as $secretName) {
            $this->assertArrayHasKey(
                $secretName,
                $secrets,
                'Secrets should contain the expected secret: ' . $secretName
            );
        }
    }

    /**
     * Data provider for testFakeClient.
     */
    public function providerFakeData()
    {
        return [
            [
                'secrets.json',
                2,
                [
                    'user',
                    'ic',
                    'web',
                    'ops',
                ],
                ['foo', 'foo3'],
                'aaaaaaaa-bbbb-cccc-dddd-eeeeeeeeeeee',
            ],
            [
                'secrets-no-user.json',
                1,
                ['ic'],
                ['foo'],
                'aadaaaaa-bbbb-ccdc-dddd-eeeedeeeeeee',
            ],
        ];
    }
}

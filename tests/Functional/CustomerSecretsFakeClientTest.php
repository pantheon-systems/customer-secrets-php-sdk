<?php

namespace PantheonSystems\Tests\Functional;

use PantheonSystems\CustomerSecrets\Secret;
use PHPUnit\Framework\TestCase;
use PantheonSystems\CustomerSecrets\Exceptions\CustomerSecretsNotImplemented;
use PantheonSystems\CustomerSecrets\CustomerSecretsFakeClient;

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
        $this->fakeClient = new CustomerSecretsFakeClient();
    }

    /**
     * @group short
     */
    public function testSetSecret(): void
    {
        $secret = Secret::create([
            'type' => 'env',
            'value' => 'bar',
            'scopes' => ['user', 'ic'],
            'name' => 'foo'
        ]);

        $this->expectException(CustomerSecretsNotImplemented::class);
        $this->expectExceptionMessage('Customer Secrets method not yet implemented.');

        $this->fakeClient->setSecret($secret);
    }

    /**
     * @group short
     */
    public function testDeleteSecret(): void
    {
        $this->expectException(CustomerSecretsNotImplemented::class);
        $this->expectExceptionMessage('Customer Secrets method not yet implemented.');

        $this->fakeClient->deleteSecret('foo');
    }

    /**
     * @dataProvider providerFakeData
     *
     * @group short
     */
    public function testFakeClient($filename, $count, $scopes, $secretNames, $siteId): void
    {
        $filepath = __DIR__ . '/../Fixtures/' . $filename;
        $this->fakeClient->setFilepath($filepath);
        $secrets = $this->fakeClient->getSecrets();
        $metadata = $this->fakeClient->getSecretsMetadata();

        $this->assertEquals($count, count($secrets), 'Secret count should match the expected ' . $count . ' secrets.');
        $this->assertEquals(
            $scopes,
            $metadata['Scopes'] ?? [],
            'Scopes should match the expected: ' . implode(', ', $scopes) . ' scopes.'
        );
        $this->assertEquals($siteId, $metadata['SiteID'], 'SiteID should match the expected: ' . $siteId . ' site ID.');

        foreach ($secretNames as $secretName) {
            $this->assertArrayHasKey(
                $secretName,
                $secrets,
                'Secrets should contain the expected secret: ' . $secretName
            );

            $secret = $this->fakeClient->getSecret($secretName);
            $this->assertEquals(
                $secretName,
                $secret->getName(),
                'Secret name should match the expected: ' . $secretName . ' secret name.'
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

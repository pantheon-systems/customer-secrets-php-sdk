<?php

namespace PantheonSystems\Tests\Functional;

use PantheonSystems\CustomerSecrets\Secret;
use PHPUnit\Framework\TestCase;
use PantheonSystems\CustomerSecrets\Exceptions\CustomerSecretsNotImplemented;
use PantheonSystems\CustomerSecrets\CustomerSecretsClient;
use Pantheon\Internal\CustomerSecrets\CustomerSecretsClientInterface as InternalClientInterface;

/**
 * Tests for CustomerSecretsClientTest class.
 */
class CustomerSecretsClientTest extends TestCase
{
    /**
     * @var \PantheonSystems\CustomerSecrets\CustomerSecretsClient
     */
    protected $secretsClient;

    /**
     * @var \Pantheon\Internal\CustomerSecrets\CustomerSecretsClientInterface
     */
    protected $internalClient;

    public function setUp(): void
    {
        $this->secretsClient = new CustomerSecretsClient(['version' => '1', 'testMode' => true]);

        $this->internalClient = $this->getMockBuilder(InternalClientInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $this->secretsClient->setInternalClient($this->internalClient);
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

        $this->secretsClient->setSecret($secret);
    }

    /**
     * @group short
     */
    public function testDeleteSecret(): void
    {
        $this->expectException(CustomerSecretsNotImplemented::class);
        $this->expectExceptionMessage('Customer Secrets method not yet implemented.');

        $this->secretsClient->deleteSecret('foo');
    }

    /**
     * @dataProvider providerData
     *
     * @group short
     */
    public function testSecretsClient($filename, $count, $scopes, $secretNames, $siteId): void
    {
        $filepath = __DIR__ . '/../Fixtures/' . $filename;

        $secrets = json_decode(file_get_contents($filepath), true);
        $this->internalClient->expects($this->any())
            ->method('get')
            ->willReturn($secrets);

        $secrets = $this->secretsClient->getSecrets();
        $metadata = $this->secretsClient->getSecretsMetadata();

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

            $secret = $this->secretsClient->getSecret($secretName, true);
            $this->assertEquals(
                $secretName,
                $secret->getName(),
                'Secret name should match the expected: ' . $secretName . ' secret name.'
            );
        }
    }

    /**
     * Data provider for testSecretsClient.
     */
    public function providerData()
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

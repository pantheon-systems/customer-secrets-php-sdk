<?php

declare(strict_types=1);

namespace PantheonSystems\Tests\Functional;

use PantheonSystems\CustomerSecrets\CustomerSecrets;
use PantheonSystems\CustomerSecrets\CustomerSecretsFakeClient;
use PHPUnit\Framework\TestCase;

/**
 * Tests for CustomerSecretsTest class.
 */
class CustomerSecretsTest extends TestCase
{
    /**
     * @group short
     */
    public function testCreateInstance(): void
    {
        $customerSecrets = CustomerSecrets::create(['file' => 'myfile.json']);
        $client = $customerSecrets->getClient();

        $this->assertInstanceOf(CustomerSecretsFakeClient::class, $client);

        $this->assertEquals('myfile.json', $client->getFilepath(), 'Filepath is not set correctly');
    }
}

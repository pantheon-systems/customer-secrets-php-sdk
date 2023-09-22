<?php

namespace PantheonSystems\Tests\Functional;

use PantheonSystems\CustomerSecrets\Secret;
use PHPUnit\Framework\TestCase;
use PantheonSystems\CustomerSecrets\Exceptions\CustomerSecretsNotImplemented;
use PantheonSystems\CustomerSecrets\CustomerSecrets;

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

        $this->assertInstanceOf('PantheonSystems\CustomerSecrets\CustomerSecretsFakeClient', $client);

        $this->assertEquals('myfile.json', $client->getFilepath(), 'Filepath is not set correctly');
    }
}

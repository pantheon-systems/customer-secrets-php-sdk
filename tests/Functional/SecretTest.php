<?php

declare(strict_types=1);

namespace PantheonSystems\Tests\Functional;

use PantheonSystems\CustomerSecrets\Secret;
use PHPUnit\Framework\TestCase;

use function count;
use function uniqid;

/**
 * Tests for Secret class.
 */
class SecretTest extends TestCase
{
    /**
     * @group short
     */
    public function testBasicSecretFunctions()
    {
        $value = uniqid('SecretValue');
        $secret = Secret::create([
            'type' => 'env',
            'value' => $value,
            'scopes' => ['user', 'ic'],
            'name' => 'foo',
        ]);
        $this->assertEquals('foo', $secret->getName(), 'Secret name should be set correctly.');
        $this->assertEquals($value, $secret->getValue(), 'Secret value should be set correctly.');
        $this->assertEquals('env', $secret->getType(), 'Secret type should be set correctly.');
        $this->assertIsArray($secret->getScopes(), 'Scopes should be an array.');
        $this->assertEquals(2, count($secret->getScopes()), 'There should be only two scopes.');

        $secret->addScope('web');
        $this->assertEquals(3, count($secret->getScopes()), 'There should be exactly three scopes.');
    }
}

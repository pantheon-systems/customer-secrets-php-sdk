<?php

namespace Pantheon\Internal\Tests;

use Pantheon\Internal\CustomerSecrets\Secret;
use Pantheon\Internal\Fixtures;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class SecretTest extends TestCase
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->fixtures = new Fixtures();
        // Env vars.
        $this->fixtures->definePantheonEnvVarFixtures();
        // For curl https certs.
        $this->fixtures->setHomeDirectory();
    }

    /**
     * @test
     * @return void
     */
    public function testBasicSecretFunctions()
    {
        $value = uniqid("SecretValue");
        $secret = Secret::create([
            "Type" => "env",
            "Value" => $value,
            "Scopes" => ["sjr", "id"],
        ]);
        $this->assertEquals($value, $secret->Value, "Secret value should be set correctly.");
        $this->assertIsArray($secret->Scopes, "Scopes should be an array");
        $this->assertEquals(2, count($secret->Scopes), "There should be only two scopes");
    }
}

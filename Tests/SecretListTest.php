<?php

namespace PantheonSystems\Tests;

use PantheonSystems\CustomerSecrets\SecretList;
use PantheonSystems\Internal\Utility\Fixtures;
use PantheonSystems\Internal\Utility\Uuid;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class SecretListTest extends TestCase
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        bootstrapPantheonPrepend();
        $this->fixtures = new Fixtures();
        // Env vars.
        $this->fixtures->definePantheonEnvVarFixtures();
        // For curl https certs.
        $this->fixtures->setHomeDirectory();
    }

  /**
   * @test
   * @return void
   * @throws \Exception
   */
    public function testSecretListInst()
    {
        $siteID = Uuid::createUUID();
        $value1 = uniqid('value');
        $value2 = uniqid('value');
        $secretList = new SecretList(
            $siteID,
            [
                "value1" => $value1,
                "value2" => $value2,
            ],
        );
        $secretList->secretListMetadata();
        $this->assertEquals($siteID, $secretList->siteId, "Site ID should be set");
        $secrets = $secretList->secrets;
        $this->assertIsArray($secrets, "Secret Values should be an array.");
        $this->assertEquals(2, count($secrets), "There should be 2 stored secrets.");
        $this->assertArrayHasKey("value1", $secrets, "Values should have value1 key");
        $this->assertArrayHasKey("value2", $secrets, "Values should have value2 key");
        $this->assertIsString($value1, $secrets['value1']);
        $this->assertIsString($value2, $secrets['value2']);
    }


    /**
     * @test
     * @return void
     */
    public function testApiFunctionality()
    {
        if (strtolower(PHP_OS) == "darwin") {
            $this->markTestSkipped("Skipped when running on MacOS");
        }
        $this->fail("Figure out how to test this.");
    }
}

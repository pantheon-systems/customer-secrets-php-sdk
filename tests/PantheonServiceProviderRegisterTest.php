<?php

namespace Pantheon\Internal\Tests;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Pantheon\Internal\Fixtures;
use Pantheon\Internal\PantheonServiceProvider;
use PHPUnit\Framework\TestCase;

/**
 * PantheonAutoloaderTest.
 */
class PantheonServiceProviderRegisterTest extends TestCase
{
  /**
   * We use the PHPUnitPolyfills set_up() method to call the
   * appropriate setup() method for the version of phpunit
   */
    protected function setUp(): void
    {
        bootstrapPantheonPrepend();
        $fixture = new Fixtures();
        $fixture->installDrupalFixturesAutoloader();
    }

  /**
   * We use the PHPUnitPolyfills tear_down() method to call the
   * appropriate teardown() method for the version of phpunit
   */
    protected function tearDown(): void
    {
    }

  /**
   * Tests pantheon_api_cron() function.
   *
   * @covers pantheon_api_cron()
   */
    public function testServiceProviderRegister()
    {
        $container = new ContainerBuilder();
        $serviceProvider = new PantheonServiceProvider($container);
        $serviceProvider->register($container);
        $this->assertIsObject($container);
        $this->assertIsObject($serviceProvider);
    }
}

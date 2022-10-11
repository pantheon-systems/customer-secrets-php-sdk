<?php

namespace PantheonSystems\Internal\Utility;

use Symfony\Component\Filesystem\Filesystem;

/**
 *
 */
class Fixtures
{
    protected $rootDir;
    protected $testDir;
    protected $tmpDirs = [];
    protected $clonedRepos = [];

  /**
   * Fixtures constructor.
   */
    public function __construct()
    {
        $this->testDir = false;
        $this->rootDir = dirname(\Composer\Factory::getComposerFile());
    }

  /**
   * Clean up any temporary directories that may have been created.
   */
    public function cleanup()
    {
        $fs = new Filesystem();
        foreach ($this->tmpDirs as $tmpDir) {
            try {
                $fs->remove($tmpDir);
            } catch (\Exception $e) {
              // Ignore problems with removing fixtures.
            }
        }
        $this->tmpDirs = [];
    }

  /**
   * Create a new temporary directory.
   *
   * @param string|bool $basedir
   *   Where to store the temporary directory.
   *
   * @return mixed
   */
    public function mktmpdir($basedir = false)
    {
        $tempfile = tempnam($basedir ?: $this->testDir ?: sys_get_temp_dir(), 'pantheon-tests');
        unlink($tempfile);
        mkdir($tempfile);
        $this->tmpDirs[] = $tempfile;
        return $tempfile;
    }

  /**
   * Used internally to build paths to specific fixtures. Not for direct
   * use by clients.
   */
    protected function fixturesDir()
    {
        return $this->rootDir . "/test/phpunit/fixtures";
    }

  /**
   * Path to a .env file fixture.
   */
    public function dotEnvDir()
    {
        return $this->fixturesDir() . '/dot-env';
    }

  /**
   *
   */
    public function setHomeDirectory()
    {
        $_SERVER["HOME"] = $this->fixturesDir() . '/home';
    }

  /**
   * Set up defines and environment variables to match what the actual
   * pantheon prepend file provides.
   *
   * @see https://github.com/pantheon-systems/infrastructure/blob/master/pantheon-cookbooks/endpoint/templates/default/php_prepend.php.erb
   */
    public function definePantheonEnvVarFixtures()
    {
        define('PANTHEON_INFRASTRUCTURE_ENVIRONMENT', 'live');
        define('PANTHEON_SITE', 'example-site');
        define('PANTHEON_ENVIRONMENT', 'dev');
        define('PANTHEON_BINDING', 'ad194594bb2c4cc7b1d0c8b9a71deabf');
        define('PANTHEON_BINDING_UID_NUMBER', 'PANTHEON_BINDING_UID_NUMBER');

        define('PANTHEON_DATABASE_USERNAME', 'pantheon');
        define('PANTHEON_DATABASE_PASSWORD', 'e844fa36b35448739dca43dd85676163');
        define('PANTHEON_DATABASE_DATABASE', 'pantheon');

        define('PANTHEON_REDIS_HOST', '34.122.149.87');
        define('PANTHEON_REDIS_PORT', '11792');
        define('PANTHEON_REDIS_PASSWORD', 'e844fa36b35448739dca43dd85676163');
        define('PANTHEON_VALHALLA_HOST', 'PANTHEON_VALHALLA_HOST');

        define('PANTHEON_SELECTED_DATABASE', 'pantheon');
        define('PANTHEON_DATABASE_HOST', 'dbserver.dev.689219ca-6583-4af8-ab05-2cebf6ef79a0.drush.in');
        define('PANTHEON_DATABASE_PORT', '11625');
        define('PANTHEON_DATABASE_BINDING', '3cf91713316a4f27acc60f2f89ceac3c');

        $db_host = '127.0.0.1';
        if (getenv('TEST_LOCATION') == 'local') {
            $db_host = 'localhost';
        }

        $_SERVER['PRESSFLOW_SETTINGS'] = file_get_contents($this->rootDir . "/Tests/pressflow_settings.json");

        $_ENV['DB_HOST'] = 'dbhost';
        $_ENV['DB_PORT'] = '6033';
        $_ENV['DB_NAME'] = 'pantheon';
        $_ENV['DB_USER'] = 'pantheon';
        $_ENV['DB_PASSWORD'] = 'xyzzy';
        $_ENV['PANTHEON_SITE'] = 'xyzzy';
        $_ENV['PANTHEON_ENVIRONMENT'] = 'dev';
    }

  /**
   * Install an autoloader for our Drupal fixture classes.
   */
    public function installDrupalFixturesAutoloader()
    {
        $base = $this->fixturesDir() . '/drupal/src/';
        spl_autoload_register(
            function ($className) use ($base) {
                if (class_exists($className) || (!str_starts_with($className, 'Drupal\\'))) {
                    return;
                }
                $classFilePath = $base . strtr(substr($className, 7), '\\', '/') . '.php';

              // guardrails-disable-line.
                if (file_exists($classFilePath) && is_readable($classFilePath)) {
                     require $classFilePath;
                }
            }
        );
    }

  /**
   *
   */
    public function startErrorLogger()
    {
        $errorLoggerService = $this->fixturesDir() . '/error-logger';
        $proc = proc_open(
            "php -S localhost:8080 $errorLoggerService",
            [
              ["pipe", "r"],
              ["pipe", "w"],
              ["pipe", "w"]
            ],
            $pipes
        );
        return $proc;
    }
}

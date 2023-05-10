# Drupal example

Please look at the [package README](../README.md) if you have not done so yet.

In this guide, we will go over an end to end example on how to use the Secrets PHP SDK to avoid putting [Sendgrid](https://sendgrid.com/) API Key into the Drupal database through the [Sendgrid Integration](https://www.drupal.org/project/sendgrid_integration) module.

Please note that using the Key module and [Pantheon Secrets](https://www.drupal.prg/project/pantheon_secrets) is a better approach for this. Take this page only as an example on how to do it manually.

## Prerequisites

1) Make sure you have a [Composer based Drupal](https://github.com/pantheon-upstreams/drupal-composer-managed) installation hosted on Pantheon.

1) Make sure you have [terminus installed](https://docs.pantheon.io/terminus/install#install-terminus) in your machine

1) Install the [Secrets Manager Plugin](https://github.com/pantheon-systems/terminus-secrets-manager-plugin#installation)

## Step by step guide

### Option 1: Use settings.php to alter the config on every request.

Please note that this option may not be ideal because this code will run on every request; but it is still a valid option you may want to explore.

1) Install the required module in your site:

    ```
    composer require drupal/sendgrid_integration
    ```

1) Install the Secrets PHP SDK:

    ```
    composer require pantheon-systems/customer-secrets-php-sdk:"^1.0"
    ```

1) Commit and push your changes to Pantheon:

    ```
    git add composer.json composer.lock
    git commit -m "Add required module and package."
    git push
    ```

1) Make sure your Sendgrid account is correctly configured and allows sending email.

1) Create a Sendgrid API key by following [Sendgrid instructions](https://docs.sendgrid.com/ui/account-and-settings/api-keys#creating-an-api-key)

1) Install and configure the `sendgrid_integration` module. Use a dummy value (i.e. not real) for the API Key field

1) Set you API key as a site secret:

    ```
    terminus secret:site:set <site> sendgrid_api_key --type=runtime --scope=web,user <api_key>
    ```

1) Add the following contents to your `settings.php` file:

    ```
    $secrets_client = \PantheonSystems\CustomerSecrets\CustomerSecrets::create()->getClient();
    $secret = $secrets_client->getSecret('sendgrid_api_key');
    if ($secret) {
      $api_key = $secret->getValue();
      $config['sendgrid_integration.settings']['apikey'] = $api_key;
    }
    ```

1) Commit and push your changes:

    ```
    git add web/sites/default/settings.php
    git commit -m "Override secret value in settings.php."
    git push
    ```

1) Send a test email by visiting `/admin/config/services/sendgrid/test`. It should work.

### Option 2: Use a module to alter the config only when it is read.

1) Install the required module in your site:

    ```
    composer require drupal/sendgrid_integration
    ```

1) Install the Secrets PHP SDK:

    ```
    composer require pantheon-systems/customer-secrets-php-sdk:"^1.0"
    ```

1) Commit and push your changes to Pantheon:

    ```
    git add composer.json composer.lock
    git commit -m "Add required module and package."
    git push
    ```

1) Make sure your Sendgrid account is correctly configured and allows sending email.

1) Create a Sendgrid API key by following [Sendgrid instructions](https://docs.sendgrid.com/ui/account-and-settings/api-keys#creating-an-api-key)

1) Install and configure the `sendgrid_integration` module. Use a dummy value (i.e. not real) for the API Key field

1) Create a new module under `web/modules/custom`. This guide assumes it is named `secrets_demo`

1) Add this to your `secrets_demo.info.yml` file:

    ```
    name: Pantheon Secrets Demo
    type: module
    description: 'Demonstrates how to use the Pantheon Secrets PHP SDK.'
    core_version_requirement: ^10
    package: Pantheon
    dependencies:
    - sendgrid_integration:sendgrid_integration
    ```

1) Create a `secrets_demo.services.yml` file in your module's root with the following contents:

    ```
    services:
    secrets_demo.config_overrider:
        class: Drupal\secrets_demo\Config\ConfigOverrider
        tags:
        - {name: config.factory.override, priority: 5}
    ```

1) Create your ConfigOverrider class at `src/Config/ConfigOverrider.php`:

    ```
    <?php

    namespace Drupal\secrets_demo\Config;

    use Drupal\Core\Cache\CacheableMetadata;
    use Drupal\Core\Config\ConfigFactoryOverrideInterface;
    use Drupal\Core\Config\StorageInterface;

    /**
     * Example configuration override.
     */
    class ConfigOverrider implements ConfigFactoryOverrideInterface {

      /**
       * {@inheritdoc}
       */
      public function loadOverrides($names) {
        $overrides = [];
        return $overrides;
      }
  
      /**
       * {@inheritdoc}
       */
      public function getCacheSuffix() {
        return 'ConfigOverrider';
      }
      
      /**
       * {@inheritdoc}
       */
      public function getCacheableMetadata($name) {
        return new CacheableMetadata();
      }
  
      /**
       * {@inheritdoc}
       */
      public function createConfigObject($name, $collection =   StorageInterface::DEFAULT_COLLECTION) {
        return NULL;
      }

    }
    ```

1) Get your secrets client from the SDK into a class property named `secretsClient`:

    1) Add usage declarations at the top of the file:

        ```
        use PantheonSystems\CustomerSecrets\CustomerSecrets;
        use PantheonSystems\CustomerSecrets\CustomerSecretsClientInterface;
        ```

    1) Define your class property:

        ```
        /**
         * The customer secrets client.
         *
         * @var \PantheonSystems\CustomerSecrets\CustomerSecretsClientInterface
         */
        protected CustomerSecretsClientInterface $secretsClient;
        ```

    1) Instantiate your `secretsClient` in the class `_construct` function:

        ```
        public function __construct() {
          $this->secretsClient = CustomerSecrets::create()->getClient();
        }
        ```

1) Finish writing up your `loadOverrides` function by requesting a given secret using the SDK:

    ```
    public function loadOverrides($names) {
      $overrides = [];
      if (in_array('sendgrid_integration.settings', $names)) {
        $secret = $this->secretsClient->getSecret('sendgrid_api_key');
        if (!$secret) {
          return;
        }
        $api_key = $secret->getValue();
        $overrides['sendgrid_integration.settings'] = ['apikey' => $api_key];
      }
      return $overrides;
    }
    ```

1) Commit and push your changes to Pantheon:

    ```
    git add web/modules/custom/secrets_demo
    git commit -m "Add secrets_demo module."
    git push
    ```

1) Install your new `secrets_demo` module

1) Set you API key as a site secret:

    ```
    terminus secret:site:set <site> sendgrid_api_key --type=runtime --scope=web,user <api_key>
    ```

1) Send a test email by visiting `/admin/config/services/sendgrid/test`. It should work.

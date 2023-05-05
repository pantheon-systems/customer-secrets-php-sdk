# WordPress example

Please look at the [package README](../README.md) if you have not done so yet.

In this guide, we will go over an end to end example on how to use the Secrets PHP SDK to avoid putting [Sendgrid](https://sendgrid.com/) API Key into the WordPress database through the [POST SMTP Mailer](https://wordpress.org/plugins/post-smtp/) plugin.

## Prerequisites

1) Make sure you have a [Composer based WordPress](https://github.com/pantheon-upstreams/wordpress-composer-managed) installation hosted on Pantheon.

1) Make sure you have [terminus installed](https://docs.pantheon.io/terminus/install#install-terminus) in your machine

1) Install the [Secrets Manager Plugin](https://github.com/pantheon-systems/terminus-secrets-manager-plugin#installation)

## Step by step guide

1) Install the required WordPress plugin in your site:

    ```
    composer require wpackagist-plugin/post-smtp
    ```

1) Install the Secrets PHP SDK:

    ```
    composer require pantheon-systems/customer-secrets-php-sdk:"^1.0"
    ```

1) Commit and push your changes to Pantheon:

    ```
    git add composer.json composer.lock
    git commit -m "Add required plugin and package."
    git push
    ```

1) Make sure your Sendgrid account is correctly configured and allows sending email.

1) Create a Sendgrid API key by following [Sendgrid instructions](https://docs.sendgrid.com/ui/account-and-settings/api-keys#creating-an-api-key)

1) Activate post-smtp and configure Sendgrid using the wizard. Keep in mind the following while going through the wizard:

    - Sender email address should be a Verified Sender
    - Outgoing Mail Server Hostname is `smtp.sendgrid.net`
    - Use a dummy value (i.e. not your real value) in the API Key field (we will override this later using a custom plugin)
    - Sending a Test email won't work initially because you didn't use the right API Key.

1) Create a new plugin and hook a `plugins_loaded` action. This code will initially look like this:

    ```
    <?php
    /**
     * Plugin Name: secrets-demo
     * Plugin URI: https://packagist.org/packages/pantheon-systems/customer-secrets-php-sdk
     * Description: Secrets Demo.
     * Version: 0.1
     * Author: Pantheon Systems
     * Author URI: https://pantheon.io/
     */

    namespace Pantheon_Secrets_Demo;

    use PantheonSystems\CustomerSecrets\CustomerSecrets;

    add_action( 'plugins_loaded', 'Pantheon_Secrets_Demo\\secrets_demo_init', 0 );

    function secrets_demo_init() {
        
    }
    ```

1) Define `POST_SMTP_API_KEY` in you action callback:

    ```
    function secrets_demo_init() {
        if (defined('POST_SMTP_API_KEY')) {
            exit;
        }
            
        $client = CustomerSecrets::create()->getClient();
        $secret = $client->getSecret("sendgrid_api_key");
        if (!$secret) {
            return;
        }
        $api_key = $secret->getValue();
        
        define("POST_SMTP_API_KEY", $api_key);
    }
    ```

1) Add a line to your .gitignore to be able to track your plugin file in git:

    ```
    echo "\!web/app/plugins/secrets-demo" >> .gitignore
    ```

1) Commit and push your changes to Pantheon:

    ```
    git add .gitignore
    git add web/app/plugins/secets-demo
    git commit -m "Add secrets-demo plugin."
    git push
    ```

1) Activate your new plugin from the WordPress dashboard or using the following command:

    ```
    terminus remote:wp "${SITE}.${ENV}" -- plugin activate secrets-demo
    ```

1) Set you API key as a site secret:

    ```
    terminus secret:site:set <site> sendgrid_api_key --type=runtime --scope=web,user <api_key>
    ```

1) Send a test email by visiting `/wp/wp-admin/admin.php?page=postman/email_test`. It should work.
# customer-secrets-php-sdk

[![Early Access](https://img.shields.io/badge/Pantheon-Early_Access-yellow?logo=pantheon&color=FFDC28)](https://docs.pantheon.io/oss-support-levels#early-access) [![Build Status](https://img.shields.io/github/actions/workflow/status/pantheon-systems/customer-secrets-php-sdk/ci.yml)](https://github.com/pantheon-systems/customer-secrets-php-sdk/actions/workflows/ci.yml)

This library should be used to access Pantheon Sites Secrets (currently in EA).

## Early Access

The Secrets feature is available for Early Access participants. Features for Secrets are in active development. Pantheon's development team is rolling out new functionality often while this product is in Early Access. Visit the Pantheon Slack channel (or sign up for the channel if you don't already have an account) to learn how you can enroll in our Early Access program. Please review Pantheon's Software Evaluation Licensing Terms for more information about access to our software.

## Installation

Use composer to install it:

```
composer require pantheon-systems/customer-secrets-php-sdk
```

## Usage

In your PHP code, do the following:

```
use PantheonSystems\CustomerSecrets\CustomerSecrets;

$client = CustomerSecrets::create()->getClient();
$secret = $client->getSecret('foo');
$secret_value = $secret->getValue();

// You could also get all of your secrets like this:
$secrets = $client->getSecrets();
```

See the included classes and internal documentation for more examples and use cases.

Note: Only get has been implemented so far. You should handle your secrets through terminus using [Terminus Secrets Manager](https://github.com/pantheon-systems/terminus-secrets-manager-plugin).

See our detailed [Drupal](docs/drupal-example.md) or [WordPress](docs/wordpress-example.md) examples for more detailed end to end examples.

### Local Environment Usage

The SDK includes a `CustomerSecretsFakeClient` implementation that is used when the SDK runs outside of Pantheon infrastructure. This client uses a secrets json file to build the secrets information emulating what happens on the platform using the Secrets service.

To get this file, you should use the [plugin](https://github.com/pantheon-systems/terminus-secrets-manager-plugin/) `secret:site:local-generate` command and then set an environment variable into your local environment (or docker container if you are running a dockerized environment) with name `CUSTOMER_SECRETS_FAKE_FILE` and use the absolute path to the file as the value.

#### Lando example

To setup this using lando, you should modify your `.lando.yml` like this:

```
services:
  appserver:
    overrides:
      environment:
          CUSTOMER_SECRETS_FAKE_FILE: /app/secrets.json
```

Then, generate the secrets file like this:

```
terminus secret:site:local-generate --filepath=./secrets.json
```

And rebuild lando application:

```
lando rebuild -y
```

Then, you will be able to use your secrets through the SDK.

## Restrictions
This SDK will only read secrets with scope `web`. Secrets get cached in the server for 15 minutes so you should wait (at most) that time if you modified your site secrets.

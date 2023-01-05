# customer-secrets-php-sdk

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
$secrets = $client->getSecrets();
$secret = $client->getSecret('foo');

$secret_value = $secret->getValue();
```

See the included classes and internal documentation for more examples and use cases.

Note: Only get has been implemented so far. You should handle your secrets through terminus using [Terminus Secrets Manager](https://github.com/pantheon-systems/terminus-secrets-manager-plugin).

## Restrictions

This SDK will only read secrets with scope `web` and type `env`. Secrets get cached in the server for 15 minutes so you should wait (at most) that time if you modified your site secrets.

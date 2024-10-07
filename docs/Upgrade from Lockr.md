# Upgrading to Pantheon Secrets from Lockr

On {date} the Lockr service will officially cease operations. You need 
to be prepared to migrate your Lockr secrets to Pantheon Secrets before 
that date. This guide will walk you through the process of migrating 
your secrets from Lockr to Pantheon Secrets.


## Prerequisites

Before you begin, you will need the following:

- A Pantheon Site with a working connection to Lockr.
- A Pantheon account with the necessary permissions to create and manage 
  secrets.
- A Lockr account with the necessary permissions to access and manage 
  secrets.
- PHP 7.4 or later installed on your local machine.
- Composer installed on your local machine.
- The `terminus` CLI installed on your local machine.
- The Customer Secrets Manager plugin for Terminus installed on your local 
  machine.


## Wordpress Instructions


## Drupal 9+ Instructions

Create a new file in your repository called migration.sh and paste the
following code into it. This script will migrate all of the keys from
the Lockr service to the Pantheon Secrets service.

```bash
#!/bin/bash

## Change this to the name of the site you want to migrate
SITENAME="lockr-migration-testing"
## Change this to the working CMS environment
## that has the keys you want to migrate
## If you access the Drupal Admin pages of
## this environment, the keys should be
## visible in the Key Management section
ENV="dev"
## Take a look at this page for more information on scopes and types
## https://docs.pantheon.io/guides/secrets/overview
DEFAULT_SCOPE="ic,web,user"
DEFAULT_TYPE="env"

# Get the keys from the Lockr service
KEYS_ARRAY=$(terminus drush "${SITENAME}"."${ENV}" -- ev "echo implode(' ', array_keys(\Drupal::service('key.repository')->getKeysByProvider('lockr')))")
# Keys array should now be a string of keys separated by spaces

# Print the keys
echo $KEYS_ARRAY

# iterate over the keys and get the values
for key in $KEYS_ARRAY
do
  # Get the value of the key using the PHP EVAL command
  PHP="\Drupal::service('key.repository')->getKey('${key}')->getKeyValue()"
  # String together a command to use terminus to get the value of the key
  VALUE=$(terminus drush $SITENAME.$ENV --  ev "echo $PHP")
  # Set a site secret with the key and value
  # using the terminus plugin for pantheon secrets
  SUCCESS=$(terminus "secret:site:set" "${SITENAME}" "${key}" "${VALUE}" --scope=$DEFAULT_SCOPE --type=$DEFAULT_TYPE)
  if [ $? -ne 0 ]; then
    echo "Key: $key Failed to migrate: $SUCCESS"
    exit 1
  fi
  echo "Key: $key Migrated"
done
```


## Drupal 7 Instructions


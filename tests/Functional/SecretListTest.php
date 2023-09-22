<?php

namespace PantheonSystems\Tests\Functional;

use PantheonSystems\CustomerSecrets\SecretList;
use PantheonSystems\CustomerSecrets\Secret;
use PHPUnit\Framework\TestCase;

/**
 * Tests for SecretList class.
 */
class SecretListTest extends TestCase
{
    /**
     * @group short
     */
    public function testSecretListInst()
    {
        $secret1 = Secret::create([
            'type' => 'env',
            'value' => 'bar',
            'scopes' => ['user', 'ic'],
            'name' => 'foo'
        ]);
        $secret2 = Secret::create([
            'type' => 'composer',
            'value' => 'loremipsum',
            'scopes' => ['user', 'ic'],
            'name' => 'github-oauth'
        ]);
        $secrets = [
            'foo' => $secret1,
            'github-oauth' => $secret2,
        ];

        $metadata = [
            'SiteID' => 'aaaa-bbbb-ccc-ddddd',
            'Count' => count($secrets),
            'Version' => '',
            'Scopes' => ['user', 'ic'],
        ];

        $secretList = new SecretList($secrets, $metadata);

        $this->assertEquals(
            $secrets,
            $secretList->getSecrets(),
            'Site secrets should have not changed from the original value.'
        );
        $this->assertEquals(
            $metadata,
            $secretList->getMetadata(),
            'Site secrets should have not changed from the original value.'
        );

        $newSecret = Secret::create([
            'type' => 'env',
            'value' => 'bar2',
            'scopes' => ['user', 'ic'],
            'name' => 'foo2'
        ]);

        $secrets['foo2'] = $newSecret;

        $secretList->setSecrets($secrets);

        $newCount = count($secretList->getSecrets());
        $this->assertEquals(3, $newCount, 'Site secrets count should be 3.');
    }
}

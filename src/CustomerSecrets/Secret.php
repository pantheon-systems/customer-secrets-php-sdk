<?php

namespace Pantheon\Internal\CustomerSecrets;

/**
 * Secret Data Object.
 */
class Secret
{
  /**
   * @param string $Type
   * @param string $Value
   * @param array $Scopes
   */
    public function __construct(
        public string $Type,
        public string $Value,
        public array $Scopes = []
    ) {
    }

  /**
   * @param $values
   *
   * @return \Pantheon\Internal\CustomerSecrets\Secret
   */
    public static function create(array $values): static
    {
        return new static(
            $values['Type'],
            $values['Value'],
            $values['Scopes'] ?? []
        );
    }
}

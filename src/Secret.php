<?php

declare(strict_types=1);

namespace PantheonSystems\CustomerSecrets;

/**
 * Secret Data Object.
 */
class Secret
{
    /**
     * Secret name.
     */
    protected string $name;

    /**
     * Secret value.
     *
     * @var null|?string
     */
    protected ?string $value;

    /**
     * Secret type.
     */
    protected string $type;

    /**
     * Secret scopes.
     */
    protected array $scopes;

    /**
     * Creates a new Secret object.
     *
     *     The name of the secret.
     *
     *     The value of the secret.
     *
     *     The type of the secret.
     *
     *     The scopes of the secret.
     */
    public function __construct(
        string $name,
        ?string $value,
        string $type,
        array $scopes
    ) {
        $this->name = $name;
        $this->value = $value;
        $this->scopes = $scopes;
        $this->type = $type;
    }

    /**
     * Get the name of the secret.
     *
     *
     *     The name of the secret.
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Get the value of the secret.
     *
     *
     *     The value of the secret.
     */
    public function getValue() : string
    {
        return $this->value;
    }

    /**
     * Set the value of the secret.
     *
     *
     *     The value of the secret.
     */
    public function setValue(?string $value) : void
    {
        $this->value = $value;
    }

    /**
     * Get the type of the secret.
     *
     *
     *     The type of the secret.
     */
    public function getType() : string
    {
        return $this->type;
    }

    /**
     * Set the type of the secret.
     *
     *
     *     The type of the secret.
     */
    public function setType(string $type) : void
    {
        $this->type = $type;
    }

    /**
     * Get the scopes of the secret.
     *
     *
     *     The scopes of the secret.
     */
    public function getScopes() : array
    {
        return $this->scopes;
    }

    /**
     * Set the scopes of the secret.
     *
     *
     *     The scopes of the secret.
     */
    public function setScopes(array $scopes) : void
    {
        $this->scopes = $scopes;
    }

    /**
     * Append a scope to the secret.
     *
     *
     *     The scope to append.
     */
    public function addScope(string $scope) : void
    {
        $this->scopes[] = $scope;
    }

    /**
     * @return Secret
     */
    public static function create(array $values) : self
    {
        return new static(
            $values['name'],
            $values['value'],
            $values['type'],
            $values['scopes']
        );
    }
}

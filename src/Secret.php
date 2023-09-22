<?php

namespace PantheonSystems\CustomerSecrets;

/**
 * Secret Data Object.
 */
class Secret
{
    /**
     * Secret name.
     *
     * @var string
     */
    protected string $name;

    /**
     * Secret value.
     *
     * @var ?string
     */
    protected ?string $value;

    /**
     * Secret type.
     *
     * @var string
     */
    protected string $type;

    /**
     * Secret scopes.
     *
     * @var array
     */
    protected array $scopes;

    /**
     * Creates a new Secret object.
     *
     * @param string $name
     *   The name of the secret.
     * @param string $value
     *   The value of the secret.
     * @param string $type
     *   The type of the secret.
     * @param array $scopes
     *   The scopes of the secret.
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
     * @return string
     *   The name of the secret.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get the value of the secret.
     *
     * @return string
     *   The value of the secret.
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * Set the value of the secret.
     *
     * @param string $value
     *   The value of the secret.
     */
    public function setValue(?string $value): void
    {
        $this->value = $value;
    }

    /**
     * Get the type of the secret.
     *
     * @return string
     *   The type of the secret.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Set the type of the secret.
     *
     * @param string $type
     *   The type of the secret.
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * Get the scopes of the secret.
     *
     * @return array
     *   The scopes of the secret.
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * Set the scopes of the secret.
     *
     * @param array $scopes
     *   The scopes of the secret.
     */
    public function setScopes(array $scopes): void
    {
        $this->scopes = $scopes;
    }

    /**
     * Append a scope to the secret.
     *
     * @param string $scope
     *   The scope to append.
     */
    public function addScope(string $scope): void
    {
        $this->scopes[] = $scope;
    }

    /**
     * @param array $values
     *
     * @return \PantheonSystems\CustomerSecrets\Secret
     */
    public static function create(array $values): Secret
    {
        return new static(
            $values['name'],
            $values['value'],
            $values['type'],
            $values['scopes']
        );
    }
}

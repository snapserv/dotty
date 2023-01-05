<?php

declare(strict_types=1);

namespace SnapServ\Dotty;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use SnapServ\Dotty\Internal\DottyTypes;

class Dotty
{
    use DottyTypes;

    private function __construct(private readonly array|\ArrayAccess $data)
    {
    }

    public static function empty(): self
    {
        return new self([]);
    }

    public static function from(array|\ArrayAccess|Arrayable $data): self
    {
        if ($data instanceof Arrayable) {
            return new self($data->toArray());
        }

        return new self($data);
    }

    /**
     * @throws DottyException
     */
    public function sub(string|int $key): self
    {
        if (!$this->has($key)) {
            throw DottyException::missingKey($this, $key);
        }

        $value = $this->get($key);
        if (!is_array($value) && !($value instanceof \ArrayAccess) && !($value instanceof Arrayable)) {
            throw DottyException::invalidType($this, "array|ArrayAccess|Arrayable", $key, $value);
        }

        return self::from($value);
    }

    /**
     * @param  array-key  $key
     * @param  mixed|null  $default
     * @return mixed
     */
    public function get(string|int $key, mixed $default = null): mixed
    {
        return Arr::get($this->data, $key, $default);
    }

    /**
     * @template T
     * @param  string|int  $key
     * @param  string  $typeName
     * @param  Closure(mixed):bool  $validateFn
     * @param  Closure(mixed):T  $convertFn
     * @param  T|Closure():T|null  $default
     * @param  bool  $required
     * @return T|null
     * @throws DottyException
     * @phpstan-template TDefault of T|null
     * @phpstan-template TRequired of bool
     * @phpstan-param TDefault $default
     * @phpstan-param TRequired $required
     * @phpstan-return (TRequired is true ? T : (TDefault is null ? null : T))
     */
    public function getNew(
        string|int $key,
        string $typeName,
        Closure $validateFn,
        Closure $convertFn,
        mixed $default = null,
        bool $required = true,
    ): mixed {
        // Determine if default value is valid
        $isValidDefault = $validateFn($default instanceof Closure ? $default() : $default);

        // Throw exception when key is missing and default value is not valid
        if ($required && !Arr::has($this->data, $key) && !$isValidDefault) {
            throw DottyException::missingKey($this, $key);
        }

        // Fetch actual value including default logic - at this time types are not yet verified
        /** @var mixed $value */
        $value = Arr::get($this->data, $default);

        // Throw exception when type is invalid
        if (!$validateFn($value)) {
            throw DottyException::invalidType($this, $typeName, $key, $value);
        }

        return $convertFn($value);
    }

    public function stringNew(string|int $key, string|Closure|null $default = null, bool $required = true)
    {
        return $this->getNew(
            key: $key,
            typeName: 'string',
            validateFn: fn($value) => is_string($value),
            convertFn: fn($value) => strval($value),
            default: $default,
            required: $required,
        );
    }

    /**
     * @param  string|array<string>  $keys
     * @return bool
     */
    public function has(string|int|array $keys): bool
    {
        return Arr::has($this->data, $keys);
    }

    /**
     * @param  string|array<string>  $keys
     * @return bool
     */
    public function hasAny(string|int|array $keys): bool
    {
        return Arr::hasAny($this->data, $keys);
    }
}

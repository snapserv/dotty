<?php

declare(strict_types=1);

namespace SnapServ\Dotty\Internal;

use Illuminate\Support\Arr;
use SnapServ\Dotty\DottyException;

trait DottyTypes
{
    /**
     * @param  array-key  $key
     * @param  bool|\Closure():bool|null  $default
     * @param  bool  $required
     * @return bool|null
     * @psalm-template TDefault as bool|\Closure():bool|null
     * @psalm-template TRequired as bool
     * @psalm-param TDefault $default
     * @psalm-param TRequired $required
     * @psalm-return (TRequired is true ? bool : (TDefault is null ? bool|null : bool))
     * @throws DottyException
     */
    public function bool(string|int $key, bool|\Closure|null $default = null, bool $required = true): ?bool
    {
        return $required
            ? $this->requireBool($key, $default)
            : $this->getBool($key, $default);
    }

    /**
     * @param  array-key  $key
     * @param  bool|\Closure():bool|null  $default
     * @return bool|null
     * @psalm-template TDefault as bool|\Closure():bool|null
     * @psalm-param TDefault $default
     * @psalm-return (TDefault is null ? bool|null : bool)
     */
    public function getBool(string|int $key, bool|\Closure|null $default = null): ?bool
    {
        /** @var mixed $value */
        $value = Arr::get($this->data, $key);

        /** @var bool|null */
        return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== null
            ? filter_var($value, FILTER_VALIDATE_BOOLEAN)
            : value($default);
    }

    /**
     * @param  array-key  $key
     * @param  bool|\Closure():bool|null  $default
     * @return bool
     * @throws DottyException
     */
    public function requireBool(string|int $key, bool|\Closure|null $default = null): bool
    {
        return $this->getBool($key, $default) ?? throw DottyException::missingKey($this, $key);
    }

    /**
     * @param  array-key  $key
     * @param  int|\Closure():int|null  $default
     * @param  bool  $required
     * @return int|null
     * @psalm-template TDefault as int|\Closure():int|null
     * @psalm-template TRequired as bool
     * @psalm-param TDefault $default
     * @psalm-param TRequired $required
     * @psalm-return (TRequired is true ? int : (TDefault is null ? int|null : int))
     * @throws DottyException
     */
    public function int(string|int $key, int|\Closure|null $default = null, bool $required = true): ?int
    {
        return $required
            ? $this->requireInt($key, $default)
            : $this->getInt($key, $default);
    }

    /**
     * @param  array-key  $key
     * @param  int|\Closure():int|null  $default
     * @return int|null
     * @psalm-template TDefault as int|\Closure():int|null
     * @psalm-param TDefault $default
     * @psalm-return (TDefault is null ? int|null : int)
     */
    public function getInt(string|int $key, int|\Closure|null $default = null): ?int
    {
        /** @var mixed $value */
        $value = Arr::get($this->data, $key);

        /** @var int|null */
        return filter_var($value, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE) !== null
            ? filter_var($value, FILTER_VALIDATE_INT)
            : value($default);
    }

    /**
     * @param  array-key  $key
     * @param  int|\Closure():int|null  $default
     * @return int
     * @throws DottyException
     */
    public function requireInt(string|int $key, int|\Closure|null $default = null): int
    {
        return $this->getInt($key, $default) ?? throw DottyException::missingKey($this, $key);
    }

    /**
     * @param  array-key  $key
     * @param  float|\Closure():float|null  $default
     * @param  bool  $required
     * @return float|null
     * @psalm-template TDefault as float|\Closure():float|null
     * @psalm-template TRequired as bool
     * @psalm-param TDefault $default
     * @psalm-param TRequired $required
     * @psalm-return (TRequired is true ? float : (TDefault is null ? float|null : float))
     * @throws DottyException
     */
    public function float(string|int $key, float|\Closure|null $default = null, bool $required = true): ?float
    {
        return $required
            ? $this->requireFloat($key, $default)
            : $this->getFloat($key, $default);
    }

    /**
     * @param  array-key  $key
     * @param  float|\Closure():float|null  $default
     * @return float|null
     * @psalm-template TDefault as float|\Closure():float|null
     * @psalm-param TDefault $default
     * @psalm-return (TDefault is null ? float|null : float)
     */
    public function getFloat(string|int $key, float|\Closure|null $default = null): ?float
    {
        /** @var mixed $value */
        $value = Arr::get($this->data, $key);

        /** @var float|null */
        return filter_var($value, FILTER_VALIDATE_FLOAT)
            ? (float)$value
            : value($default);
    }

    /**
     * @param  array-key  $key
     * @param  float|\Closure():float|null  $default
     * @return float
     * @throws DottyException
     */
    public function requireFloat(string|int $key, float|\Closure|null $default = null): float
    {
        return $this->getFloat($key, $default) ?? throw DottyException::missingKey($this, $key);
    }

    /**
     * @param  array-key  $key
     * @param  string|\Closure():string|null  $default
     * @param  bool  $required
     * @return string|null
     * @psalm-template TDefault as string|\Closure():string|null
     * @psalm-template TRequired as bool
     * @psalm-param TDefault $default
     * @psalm-param TRequired $required
     * @psalm-return (TRequired is true ? string : (TDefault is null ? string|null : string))
     * @throws DottyException
     */
    public function string(string|int $key, string|\Closure|null $default = null, bool $required = true): ?string
    {
        return $required
            ? $this->requireString($key, $default)
            : $this->getString($key, $default);
    }

    /**
     * @param  array-key  $key
     * @param  string|\Closure():string|null  $default
     * @return string|null
     * @psalm-template TDefault as string|\Closure():string|null
     * @psalm-param TDefault $default
     * @psalm-return (TDefault is null ? string|null : string)
     */
    public function getString(string|int $key, string|\Closure|null $default = null): ?string
    {
        /** @var mixed $value */
        $value = Arr::get($this->data, $key);

        /** @var string|null */
        return is_string($value)
            ? $value
            : value($default);
    }

    /**
     * @param  array-key  $key
     * @param  string|\Closure():string|null  $default
     * @return string
     * @throws DottyException
     */
    public function requireString(string|int $key, string|\Closure|null $default = null): string
    {
        return $this->getString($key, $default) ?? throw DottyException::missingKey($this, $key);
    }

    /**
     * @param  array-key  $key
     * @param  array|\Closure():array|null  $default
     * @param  bool  $required
     * @return array|null
     * @psalm-template TDefault as array|\Closure():array|null
     * @psalm-template TRequired as bool
     * @psalm-param TDefault $default
     * @psalm-param TRequired $required
     * @psalm-return (TRequired is true ? array : (TDefault is null ? array|null : array))
     * @throws DottyException
     */
    public function array(string|int $key, array|\Closure|null $default = null, bool $required = true): ?array
    {
        return $required
            ? $this->requireArray($key, $default)
            : $this->getArray($key, $default);
    }

    /**
     * @param  array-key  $key
     * @param  array|\Closure():array|null  $default
     * @return array|null
     * @psalm-template TDefault as array|\Closure():array|null
     * @psalm-param TDefault $default
     * @psalm-return (TDefault is null ? array|null : array)
     */
    public function getArray(string|int $key, array|\Closure|null $default = null): ?array
    {
        /** @var mixed $value */
        $value = Arr::get($this->data, $key);

        /** @var array|null */
        return is_array($value)
            ? $value
            : value($default);
    }

    /**
     * @param  array-key  $key
     * @param  array|\Closure():array|null  $default
     * @return array
     * @throws DottyException
     */
    public function requireArray(string|int $key, array|\Closure|null $default = null): array
    {
        return $this->getArray($key, $default) ?? throw DottyException::missingKey($this, $key);
    }
}

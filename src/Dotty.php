<?php

declare(strict_types=1);

namespace SnapServ\Dotty;

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
            throw DottyException::illegalType($this, "array|ArrayAccess|Arrayable", $key, $value);
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

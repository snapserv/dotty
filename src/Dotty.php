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
     * @param  array-key  $key
     * @param  mixed|null  $default
     * @return mixed
     */
    public function get(string|int $key, mixed $default = null): mixed
    {
        return Arr::get($this->data, $key, $default);
    }
}

<?php

declare(strict_types=1);

namespace SnapServ\Dotty;

class DottyException extends \RuntimeException
{
    private readonly array $context;

    public function __construct(string $message = '', array $context = [], ?\Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->context = $context;
    }

    public function context(): array
    {
        return $this->context;
    }

    public static function missingKey(Dotty $instance, string|int $key): self
    {
        return new self("missing key [{$key}]", [
            'instance' => $instance,
            'key' => $key,
        ]);
    }

    public static function illegalType(Dotty $instance, string $expected, string|int $key, mixed $value): self
    {
        return new self("illegal type for [{$key}], expected [{$expected}]", [
            'instance' => $instance,
            'key' => $key,
            'value' => $value,
            'valueType' => gettype($value),
            'expectedType' => $expected,
        ]);
    }
}

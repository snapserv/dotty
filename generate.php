<?php

declare(strict_types=1);

const DOTTY_TYPES = [
    [
        'name' => 'bool',
        'check' => 'filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) !== null',
        'cast' => 'filter_var($value, FILTER_VALIDATE_BOOLEAN)',
    ],
    ['name' => 'int', 'check' => 'filter_var($value, FILTER_VALIDATE_INT)', 'cast' => '(int)$value'],
    ['name' => 'float', 'check' => 'filter_var($value, FILTER_VALIDATE_FLOAT)', 'cast' => '(float)$value'],
    ['name' => 'string', 'check' => 'is_string($value)', 'cast' => '$value'],
    ['name' => 'array', 'check' => 'is_array($value)', 'cast' => '$value'],
];

const DOTTY_HEADER = <<<'EOF'
    <?php
    
    declare(strict_types=1);
    
    namespace SnapServ\Dotty\Internal;
    
    use Illuminate\Support\Arr;
    use SnapServ\Dotty\DottyException;
    
    trait DottyTypes
    {
    EOF;

const DOTTY_FOOTER = '}';

const DOTTY_TYPE_HELPER_FN = <<<'EOF'
    /**
     * @param  array-key  $key
     * @param  {type}|\Closure():{type}|null  $default
     * @param  bool  $required
     * @return {type}|null
     * @psalm-template TDefault as {type}|\Closure():{type}|null
     * @psalm-template TRequired as bool
     * @psalm-param TDefault $default
     * @psalm-param TRequired $required
     * @psalm-return (TRequired is true ? {type} : (TDefault is null ? {type}|null : {type}))
     */
    public function {type}(string|int $key, {type}|\Closure|null $default = null, bool $required = true): ?{type}
    {
        return $required
            ? $this->require{typeTitle}($key, $default)
            : $this->get{typeTitle}($key, $default);
    }
    EOF;

const DOTTY_TYPE_GET_FN = <<<'EOF'
    /**
     * @param  array-key  $key
     * @param  {type}|\Closure():{type}|null  $default
     * @return {type}|null
     * @psalm-template TDefault as {type}|\Closure():{type}|null
     * @psalm-param TDefault $default
     * @psalm-return (TDefault is null ? {type}|null : {type})
     */
    public function get{typeTitle}(string|int $key, {type}|\Closure|null $default = null): ?{type}
    {
        /** @var mixed $value */
        $value = Arr::get($this->data, $key);
    
        /** @var {type}|null */
        return {check}
            ? {cast}
            : value($default);
    }
    EOF;

const DOTTY_TYPE_REQUIRE_FN = <<<'EOF'
    /**
     * @param  array-key  $key
     * @param  {type}|\Closure():{type}|null  $default
     * @return {type}
     */
    public function require{typeTitle}(string|int $key, {type}|\Closure|null $default = null): {type}
    {
        return $this->get{typeTitle}($key, $default) ?? throw DottyException::missingKey($this, $key);
    }
    EOF;

function processTemplate(string $template, ?array $type = null, int $indentDepth = 0): string
{
    if ($type) {
        $template = str_replace('{type}', $type['name'], $template);
        $template = str_replace('{typeTitle}', ucwords($type['name']), $template);
        $template = str_replace('{check}', $type['check'], $template);
        $template = str_replace('{cast}', $type['cast'], $template);
    }

    $indent = str_repeat(' ', $indentDepth);
    $template = $indent . str_replace("\n", "\n{$indent}", $template);
    return preg_replace('/[ \t]*$/m', '', $template);
}

$output = processTemplate(DOTTY_HEADER) . "\n";
foreach (DOTTY_TYPES as $key => $type) {
    $output .= processTemplate(DOTTY_TYPE_HELPER_FN, $type, 4) . "\n\n";
    $output .= processTemplate(DOTTY_TYPE_GET_FN, $type, 4) . "\n\n";
    $output .= processTemplate(DOTTY_TYPE_REQUIRE_FN, $type, 4) . "\n";
    if ($key !== array_key_last(DOTTY_TYPES)) {
        $output .= "\n";
    }
}
$output .= processTemplate(DOTTY_FOOTER) . "\n";

file_put_contents("src/Internal/DottyTypes.php", $output);

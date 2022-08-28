<?php

declare(strict_types=1);


namespace Enjoys\DotenvWriter;


final class Line implements \Stringable
{

    private bool $env = false;
    private bool $valueNeedQuotes = false;

    public function __construct(
        private ?string $key = null,
        private ?string $value = null,
        private ?string $comment = null
    ) {
        if ($this->key !== null) {
            $this->env = true;
        }
        if (str_contains((string)$this->value, ' ')) {
            $this->valueNeedQuotes = true;
        }
    }

    public static function fromString(string $input): Line
    {
        preg_match('//', $input, $matches, PREG_UNMATCHED_AS_NULL);
        return new self($matches[1], $matches[2], $matches[3]);
    }

    public function __toString(): string
    {
        $return = '';
        if ($this->isEnv()) {
            $return .= sprintf('%s=%s', $this->key, ($this->valueNeedQuotes) ? sprintf('"%s"', (string)$this->value) : (string) $this->value);
            if ($this->comment) {
                $return .= ' ';
            }
        }
        if ($this->comment) {
            $return .= '#' . $this->comment;
        }
        return $return . PHP_EOL;
    }


    public function getKey(): ?string
    {
        return $this->key;
    }


    public function getValue(): ?string
    {
        return $this->value;
    }


    public function getComment(): ?string
    {
        return $this->comment;
    }


    public function isEnv(): bool
    {
        return $this->env;
    }
}
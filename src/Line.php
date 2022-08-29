<?php

declare(strict_types=1);


namespace Enjoys\DotenvWriter;


final class Line implements \Stringable
{

    private bool $env = false;
    private bool $needQuotes = false;

    public function __construct(
        private ?string $key = null,
        private ?string $value = null,
        private ?string $comment = null,
        bool $needQuotes = false
    ) {
        $this->setNeedQuotes($needQuotes);
        $this->setValue($value);
        $this->setKey($key);
        $this->setComment($comment);

    }

    public static function fromParser(array $fields)
    {
        if (isset($fields[0]) && isset($fields[1])) {
            return new self($fields[0], $fields[1]);
        }
        return new self(null, null, $fields[0]);
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
            $return .= sprintf(
                '%s=%s',
                $this->key,
                ($this->isNeedQuotes()) ? sprintf('"%s"', (string)$this->value) : (string)$this->value
            );
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





    public function getComment(): ?string
    {
        return $this->comment;
    }


    public function isEnv(): bool
    {
        return $this->env;
    }

    /**
     * @param string|null $key
     */
    public function setKey(?string $key): void
    {
        if ($key !== null) {
            $this->env = true;
        }
        $this->key = $key;
    }

    /**
     * @param string|null $value
     */
    public function setValue(?string $value): void
    {
       // $this->value = is_null($value) ? null : addslashes($value);
        $this->value = $value;
    }

    public function getValue(): ?string
    {
      //  return is_null($this->value) ? null : stripslashes($this->value);
        return $this->value;
    }

    /**
     * @param string|null $comment
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return bool
     */
    public function isNeedQuotes(): bool
    {
        return $this->needQuotes;
    }

    /**
     * @param bool $needQuotes
     */
    public function setNeedQuotes(bool $needQuotes): void
    {
        $this->needQuotes = $needQuotes;
    }
}
<?php

declare(strict_types=1);


namespace Enjoys\DotenvWriter;


use Enjoys\Dotenv\Parser\Builder;
use Enjoys\Dotenv\Parser\Env\Comment;
use Enjoys\Dotenv\Parser\Env\Key;
use Enjoys\Dotenv\Parser\Env\Value;
use Enjoys\Dotenv\Parser\Lines\EmptyLine;
use Enjoys\Dotenv\Parser\Lines\EnvLine;
use Enjoys\Dotenv\Parser\Lines\LineInterface;
use Enjoys\Dotenv\Parser\Parser;

final class DotenvWriter
{

    /**
     * @var array<array-key, LineInterface|null>
     */
    private array $structure;

    public function __construct(private string $path)
    {
        if (!file_exists($path)) {
             fopen($path, 'w+');
        }

        $this->structure = (new Parser())->parseStructure(file_get_contents($path));
        if (count($this->structure) === 1 && current($this->structure) instanceof EmptyLine) {
            $this->structure = [];
        }
    }

    public function save(string $target = null): void
    {
        if ($target === null) {
            $target = $this->path;
        }
        file_put_contents($target, (new Builder($this->structure))->build());
    }

    public function addLine(LineInterface $line): DotenvWriter
    {
        if ($line instanceof EnvLine && $this->findEnvLine($line->getKey())) {
            return $this;
        }
        $this->structure[] = $line;
        return $this;
    }


    /**
     * @param LineInterface[] $lines
     * @return $this
     */
    public function addLines(array $lines): DotenvWriter
    {
        foreach ($lines as $line) {
            $this->addLine($line);
        }
        return $this;
    }

    public function setEnv(
        Key|string $key,
        Value|string|null $value = null,
        Comment|string|null $comment = null,
    ): DotenvWriter {
        if (is_string($key)) {
            $key = new Key($key);
        }

        if (is_string($value)) {
            $value = new Value($value);
        }

        if (is_string($comment)) {
            $comment = new Comment($comment);
        }


        $envLine = $this->findEnvLine($key);

        $this->structure[$key->getValue()] =
            new EnvLine(
                $key,
                $value ?? $envLine?->getValue(),
                $comment ?? $envLine?->getComment(),
            );
        return $this;
    }


    /**
     * @param Key|string $key
     * @param Value|string|null $newValue
     * @param string|null $conditionValue
     * @return $this
     */
    public function setEnvIf(
        Key|string $key,
        Value|string|null $newValue,
        ?string $conditionValue,
    ): DotenvWriter {
        $line = $this->findEnvLine($key);
        if ($line === null) {
            return $this;
        }
        if ($line->getValue()?->getValue() === $conditionValue) {
            $this->setEnv($key, $newValue);
        }
        return $this;
    }

    /**
     * @param string|Key $key
     * @return EnvLine|null
     */
    private function findEnvLine(string|Key $key): ?EnvLine
    {
        if ($key instanceof Key) {
            $key = $key->getValue();
        }
        return $this->structure[$key] ?? null;
    }

    public function filterEnv(string $regexp, bool $return_only_keys = false): array
    {
        $result = array_filter($this->structure, function ($key) use ($regexp) {
            if (!is_string($key)){
                return false;
            }
            return preg_match($regexp, $key);
        }, ARRAY_FILTER_USE_KEY);

        return ($return_only_keys) ? array_keys($result): $result;
    }

    public function removeEnvLine(string|Key $key): void
    {
        if ($key instanceof Key) {
            $key = $key->getValue();
        }
        unset($this->structure[$key]);
    }


}
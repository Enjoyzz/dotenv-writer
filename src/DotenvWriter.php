<?php

declare(strict_types=1);


namespace Enjoys\DotenvWriter;


final class DotenvWriter
{

    private Structure $structure;

    public function __construct(private string $path)
    {
        $this->structure = new Structure();
        $parser = new Parser($this->path, $this->structure);
        $parser->parse();
    }

    public function save(string $target = null): void
    {
        if ($target === null) {
            $target = $this->path;
        }

        file_put_contents($target, $this->structure->getToSaveData());
    }

    public function setEnv(
        string $key,
        string $value = null,
        string $comment = null,
        bool $needQuotes = false
    ): DotenvWriter {
        $line = $this->structure->findEnvLine($key);
        $this->structure->addLine(
            new Line(
                $key,
                $value ?? $line?->getValue(),
                $comment ?? $line?->getComment(),
                $needQuotes
            )
        );
        return $this;
    }


    /**
     * @param string $key
     * @param callable(Line):bool $condition
     * @param string|null $value
     * @param string|null $comment
     * @param bool $needQuotes
     * @return $this
     */
    public function setEnvIf(
        string $key,
        callable $condition,
        string $value = null,
        string $comment = null,
        bool $needQuotes = false
    ): DotenvWriter {
        $line = $this->structure->findEnvLine($key);
        if ($line === null) {
            return $this;
        }

        if ($condition($line)) {
            $this->setEnv($key, $value, $comment, $needQuotes);
        }
        return $this;
    }


}
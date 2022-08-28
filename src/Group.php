<?php

declare(strict_types=1);


namespace Enjoys\DotenvWriter;


final class Group
{
    private array $lines = [];

    public function __construct(private ?string $name = null)
    {
    }

    public function getLines(): array
    {
        return $this->lines;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function addLine(Line $line): void
    {
        if ($line->isEnv()){
            $this->lines[$line->getKey()] = $line;
            return;
        }
        $this->lines[] = $line;
    }
}
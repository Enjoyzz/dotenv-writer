<?php

declare(strict_types=1);


namespace Enjoys\DotenvWriter;


final class Structure
{
    /**
     * @var Line[]
     */
    private array $structure = [];

    public function findEnvLine($key): ?Line
    {
        return $this->structure[$key] ?? null;
    }


    public function addLine(Line $line)
    {
        if ($line->isEnv()){
            $this->structure[$line->getKey()] = $line;
            return;
        }
        $this->structure[] = $line;
    }

    /**
     * @return array
     */
    public function getStructure(): array
    {
        return $this->structure;
    }

    /**
     * @param array $structure
     */
    public function setStructure(array $structure): void
    {
        $this->structure = $structure;
    }

    public function getToSaveData()
    {
        $result = '';
        foreach ($this->structure as $line) {
            $result .= $line->__toString();
        }
        return $result;
    }
}
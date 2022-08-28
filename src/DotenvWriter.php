<?php

declare(strict_types=1);


namespace Enjoys\DotenvWriter;


final class DotenvWriter
{

    private Env $env;

    public function __construct(private string $path)
    {
        $this->env = new Env($this->path);
    }

    public function save(string $target = null): void
    {
        if ($target === null) {
            $target = $this->path;
        }

        file_put_contents($target, $this->env->getToSaveData());
    }

    public function setEnv(string $key, string $value = null, string $comment = null): DotenvWriter
    {
        $this->env->setEnv($key, $value, $comment);
        return $this;
    }



}
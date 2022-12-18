<?php

declare(strict_types=1);


namespace Tests\Enjoys\DotenvWriter;


use Enjoys\DotenvWriter\DotenvWriter;
use PHPUnit\Framework\TestCase;

use function Enjoys\FileSystem\createDirectory;
use function Enjoys\FileSystem\removeDirectoryRecursive;

final class RemoveEnvTest extends TestCase
{
    protected function setUp(): void
    {
        createDirectory(__DIR__ . '/temp');
        removeDirectoryRecursive(__DIR__ . '/temp');
    }

    public function testRemoveEnv()
    {
        $dotenvWriter = new DotenvWriter(__DIR__ . '/fixtures/.match');
        $result = $dotenvWriter->filterEnv('/^DATABASE_/', true);
        foreach ($result as $key) {
            $dotenvWriter->removeEnvLine($key);
        }

        $save_path = __DIR__ . '/temp/' . uniqid('remove_lines_');
        $dotenvWriter->save($save_path);

        $this->assertSame(
            <<<ENV
SECRET=8


APP_ENV=9

#comment
ENV,
            file_get_contents($save_path)
        );
    }
}
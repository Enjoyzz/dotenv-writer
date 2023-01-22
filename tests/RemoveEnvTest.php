<?php

declare(strict_types=1);


namespace Tests\Enjoys\DotenvWriter;


use Enjoys\DotenvWriter\DotenvWriter;
use PHPUnit\Framework\TestCase;

use function Enjoys\FileSystem\copyFile;
use function Enjoys\FileSystem\removeDirectoryRecursive;

final class RemoveEnvTest extends TestCase
{
    private string $tmpDir = __DIR__ . '/temp';

    protected function setUp(): void
    {
        removeDirectoryRecursive($this->tmpDir, true);
    }


    protected function tearDown(): void
    {
        removeDirectoryRecursive($this->tmpDir, true);
    }

    public function testRemoveEnv()
    {
        copyFile(__DIR__ . '/fixtures/.match', $originalFile = $this->tmpDir . '/.' . uniqid('match_copy'));
        $dotenvWriter = new DotenvWriter($originalFile);
        $result = $dotenvWriter->filterEnv('/^DATABASE_/', true);
        foreach ($result as $key) {
            $dotenvWriter->removeEnvLine($key);
        }

        $save_path = $this->tmpDir . '/.' . uniqid('remove_lines_');
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
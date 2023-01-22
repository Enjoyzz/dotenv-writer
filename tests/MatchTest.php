<?php

declare(strict_types=1);


namespace Tests\Enjoys\DotenvWriter;


use Enjoys\DotenvWriter\DotenvWriter;
use PHPUnit\Framework\TestCase;

use function Enjoys\FileSystem\createDirectory;
use function Enjoys\FileSystem\removeDirectoryRecursive;

final class MatchTest extends TestCase
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

    public function testFindEnvFileByMask()
    {
        $dotenvWriter = new DotenvWriter(__DIR__ . '/fixtures/.match');
        $result = $dotenvWriter->filterEnv('/^DATABASE_/');
        $this->assertSame([
            'DATABASE_TYPE',
            'DATABASE_HOST',
            'DATABASE_USER',
            'DATABASE_PASS',
            'DATABASE_NAME',
            'DATABASE_CHARSET',
            'DATABASE_DRIVER'
        ], array_keys($result));
    }
}
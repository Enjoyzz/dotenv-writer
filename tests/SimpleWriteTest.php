<?php

declare(strict_types=1);


namespace Tests\Enjoys\DotenvWriter;


use Enjoys\DotenvWriter\DotenvWriter;
use Enjoys\DotenvWriter\Group;
use Enjoys\DotenvWriter\Line;
use PHPUnit\Framework\TestCase;

use function Enjoys\FileSystem\createDirectory;
use function Enjoys\FileSystem\removeDirectoryRecursive;

final class SimpleWriteTest extends TestCase
{

    protected function setUp(): void
    {
        createDirectory(__DIR__.'/temp');
        removeDirectoryRecursive(__DIR__.'/temp');
    }

    public function testFirst()
    {
        $dotEnvWriter = new DotenvWriter(__DIR__.'/fixtures/with_comments_and_groups');
        $dotEnvWriter
            ->setEnv('TEST2', 'value')
            ->setEnv('TEST', 'value2', 'test')
        ;
        $dotEnvWriter->save(__DIR__.'/temp/file_copy');
        $this->assertSame('sdaf', file_get_contents('./temp/file_copy'));


    }
}
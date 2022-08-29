<?php

declare(strict_types=1);


namespace Tests\Enjoys\DotenvWriter;


use Dotenv\Parser\Parser;
use Enjoys\DotenvWriter\DotenvWriter;
use Enjoys\DotenvWriter\Structure;
use Enjoys\DotenvWriter\ENV;
use PHPUnit\Framework\TestCase;

use Symfony\Component\Dotenv\Dotenv;

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
            ->setEnv('VAR3', 'va"', 'lue"', needQuotes: false)
       //     ->setEnv('VAR_G1_6', 'value2', '#42')
            ->setEnvIf('Group_1', function ($i){
                    return $i->getValue() === '';
            }, 'value3')
        ;

        $dotEnvWriter->save(__DIR__.'/temp/file_copy');
       // $this->assertSame('sdaf', file_get_contents('./temp/file_copy'));

        $dotenv = new \Enjoys\Dotenv\Dotenv(__DIR__.'/temp/', 'file_copy');
        $dotenv->loadEnv();
        var_dump($_ENV['VAR3']);
        dd($dotenv->getEnvArray());
    }
}
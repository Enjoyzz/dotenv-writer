<?php

declare(strict_types=1);


namespace Tests\Enjoys\DotenvWriter;

use Enjoys\Dotenv\Parser\Env\Comment;
use Enjoys\Dotenv\Parser\Env\Key;
use Enjoys\Dotenv\Parser\Env\Value;
use Enjoys\Dotenv\Parser\Lines\CommentLine;
use Enjoys\Dotenv\Parser\Lines\EmptyLine;
use Enjoys\Dotenv\Parser\Lines\EnvLine;
use Enjoys\DotenvWriter\DotenvWriter;
use PHPUnit\Framework\TestCase;

use function Enjoys\FileSystem\createDirectory;
use function Enjoys\FileSystem\removeDirectoryRecursive;


final class SimpleWriteTest extends TestCase
{

    protected function setUp(): void
    {
        createDirectory(__DIR__ . '/temp');
        removeDirectoryRecursive(__DIR__ . '/temp');
    }

    public function testAddLine()
    {
        $filename = uniqid();
        //  copy(__DIR__ . '/fixtures/.env', __DIR__ . '/temp/' . $filename);
        $dotenvWriter = new DotenvWriter(__DIR__ . '/temp/' . $filename);
        $dotenvWriter
            ->addLine(new CommentLine(' Test'))
            ->addLine(new EmptyLine())
            ->addLine(
                new EnvLine(
                    new Key('VAR'),
                    new Value('value'),
                    new Comment('comment')
                )
            )
        ;
        $dotenvWriter->save();
        $this->assertSame(
            <<<ENV
# Test

VAR=value #comment
ENV
            ,
            file_get_contents(__DIR__ . '/temp/' . $filename)
        );
    }

    public function testAddLines()
    {
        $path = __DIR__ . '/temp/' . uniqid('add_lines_');
        $dotenvWriter = new DotenvWriter($path);
        $dotenvWriter->addLines([
            new EnvLine(new Key('VAR'), new Value('42')),
            new CommentLine('test'),
            new EmptyLine(),
            new EnvLine(new Key('VAR2'), new Value('true')),
        ]);
        $dotenvWriter->save();
        $this->assertSame(
            <<<ENV
VAR=42
#test

VAR2=true
ENV
            ,
            file_get_contents($path)
        );
    }

    public function testSetEnv()
    {
        copy(__DIR__ . '/fixtures/.env', __DIR__ . '/temp/.env');
        $dotenvWriter = new DotenvWriter(__DIR__ . '/temp/.env');
        $dotenvWriter->setEnv('VAR', 'value');
        $dotenvWriter->setEnv('VAR4');
        $dotenvWriter->save();
        $this->assertSame(
            <<<ENV
VAR=value
VAR2
VAR3=true
VAR4
ENV
            ,
            file_get_contents(__DIR__ . '/temp/.env')
        );
    }

    public function testSetEnvIf()
    {
        copy(__DIR__ . '/fixtures/.env', __DIR__ . '/temp/.env');
        $dotenvWriter = new DotenvWriter(__DIR__ . '/temp/.env');
        $dotenvWriter->setEnvIf('VAR', function ($line) {
            return $line->getValue()->getValue() === 'test';
        }, 'value');
        $dotenvWriter->setEnvIf('VAR2', function ($line) {
            return$line->getValue()=== null;
        }, 'value');
        $dotenvWriter->save();
        $this->assertSame(
            <<<ENV
VAR=value
VAR2=value
VAR3=true
ENV
            ,
            file_get_contents(__DIR__ . '/temp/.env')
        );
    }
}
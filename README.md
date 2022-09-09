```php
$envPath = __DIR__.'/.env';
$dotenvWriter = new \Enjoys\DotenvWriter\DotenvWriter($envPath);
$dotenvWriter->addLine(
    new \Enjoys\Dotenv\Parser\Lines\CommentLine('sdfghj')
);
$dotenvWriter->addLine(
    new \Enjoys\Dotenv\Parser\Lines\EmptyLine()
);

$dotenvWriter->addLine(
    new \Enjoys\Dotenv\Parser\Lines\EnvLine(
        new \Enjoys\Dotenv\Parser\Env\Key('VAR'),
        new \Enjoys\Dotenv\Parser\Env\Value('value'),
        new \Enjoys\Dotenv\Parser\Env\Comment('comment')
    )
);

$dotenvWriter->addLines([
    //...
]);

$dotenvWriter->setEnv()
$dotenvWriter->setEnvIf()
$dotenvWriter->addEnvIfNotExist()

```
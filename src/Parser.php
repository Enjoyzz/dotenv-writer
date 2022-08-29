<?php

declare(strict_types=1);


namespace Enjoys\DotenvWriter;


use Webmozart\Assert\Assert;

final class Parser
{
    private string $content;

    public function __construct(string $pathOrContent, private Structure $structure)
    {
        if (file_exists($pathOrContent)) {
            $content = file_get_contents($pathOrContent);
            if ($content === false) {
                throw new \InvalidArgumentException();
            }
            $this->content = $content;
        } else {
            $this->content = $pathOrContent;
        }
    }

    public function parse()
    {
        $lines = explode("\n", $this->content);
        foreach ($lines as $string) {
            $line = new Line();
            $key = $value = $comment = null;
            $fields = array_map('trim', explode('=', $string, 2));
            if (!isset($fields[1])) {
                $comment = (empty($fields[0])) ? null : ((!str_starts_with($fields[0], '#')) ? null : substr(
                    $fields[0],
                    1
                ));
            }
            if (isset($fields[1])) {
                $key = $fields[0];
                Assert::regex(
                    $key,
                    '/^([A-Z_0-9]+)$/i',
                    'The key %s have invalid chars. The key must have only letters (A-Z) digits (0-9) and _'
                );
                if (str_starts_with($fields[1], '"')){
                    $line->setNeedQuotes(true);
                }
                [$value, $comment] = $this->parseValue($fields[1]);
            }

            $line->setKey($key);
            $line->setValue($value);
            $line->setComment($comment);

            $this->structure->addLine($line);

        }
    }

    private function parseValue(string $value)
    {
        if (str_starts_with($value, '"')) {
            preg_match('/^([\'"])((?<value>.*?)(?<!\\\)\1)[\s#]*(?<comment>.*)?/', $value, $matches, PREG_UNMATCHED_AS_NULL);
            return [
                $matches['value'],
                $matches['comment'],
            ];
        }
        $exploded = array_map('trim', explode('#', $value, 2));
        return [
            $exploded[0],
                $exploded[1] ?? null
        ];
    }
}
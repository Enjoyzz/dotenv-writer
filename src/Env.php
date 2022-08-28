<?php

declare(strict_types=1);


namespace Enjoys\DotenvWriter;


use PHPUnit\TextUI\XmlConfiguration\Group;

final class Env
{
    /**
     * @var Line[]
     */
    private array $structure = [];

    public function __construct(string $path)
    {
        $this->parseEnv($path);
    }

    private function parseEnv($env)
    {
        error_clear_last();
        $fp = @fopen($env, "r");
        if ($fp) {
            while (($line = fgets($fp)) !== false) {
//                $fields = array_map('trim', explode('=', $line, 2));
                preg_match('/(.*)?=(.*)?#?(.*)?/iU', $line, $matches, PREG_UNMATCHED_AS_NULL);
                var_dump($matches);
            }
            if (!feof($fp)) {
                echo "Error: unexpected fail";
            }
            fclose($fp);
        }

        if ($error = error_get_last()) {
            throw new \RuntimeException($error['message']);
        }
//        foreach (preg_split("/\R/", $env) as $line) {
//            $line = trim($line);
//
//            $fields = array_map('trim', explode('=', $line, 2));
//
//            if (count($fields) == 2) {
//                list($key, $value) = $fields;
////                Assert::regex(
////                    $key,
////                    '/^([A-Z_0-9]+)$/i',
////                    'The key %s have invalid chars. The key must have only letters (A-Z) digits (0-9) and _'
////                );
//
//
//                $this->setLine($key, $value);
//            }
//        }
    }

    public function setEnv($key, $value = null, $comment = null)
    {
        $line = new Line($key, $value, $comment);
        if ($line->isEnv()){
            $this->structure[$line->getKey()] = $line;
            return;
        }
        $this->structure[] = $line;
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
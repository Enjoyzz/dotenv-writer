<?php

declare(strict_types=1);

namespace Tests\Enjoys\DotenvWriter;

use Enjoys\DotenvWriter\Line;
use PHPUnit\Framework\TestCase;

class LineTest extends TestCase
{

    public function data()
    {
        return [
            ['VAR2=test2_val val #test2_comment', ['VAR2', 'test2_val val', 'test2_comment']],
            ['VAR1=%var%', ['VAR1', '%var%', null]],
            ['VAR_G1_1="sdfgdfgdf sdfs gsd#jhghj" #comment #hghg', ['VAR_G1_1', 'sdfgdfgdf sdfs gsd#jhghj', 'comment #hghg']],
        ];
    }

    /**
     * @dataProvider data
     */
    public function testFromString($input, $expect)
    {
        preg_match('/^((.*)=)?(["|]?.*["|]?)[\s|]*#(.*)?$/i', $input, $matches, PREG_UNMATCHED_AS_NULL);
        $matches = array_map('trim', $matches);
        var_dump($matches);
        $this->assertSame($expect, [$matches['key'] ?? null, $matches['value'] ?? null, $matches['comment'] ?? null]);
    }
}

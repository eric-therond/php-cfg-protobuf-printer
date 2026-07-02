<?php

declare(strict_types=1);

/**
 * This file is part of PHP-CFG-PROTOBUF-PRINTER
 *
 * @copyright 2026 Eric Therond. All rights reserved
 * @license MIT See LICENSE at the root of the project for more info
 */

namespace ProtobufPrinter;

use PhpParser;
use PhpParser\ParserFactory;
use PHPCfg\Parser;
use PHPCfg\Traverser;
use PHPCfg\Visitor;
use PHPCfg\Printer;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;

#[CoversNothing]
class CodeTest extends TestCase
{
    #[DataProvider('provideTestParseAndDump')]
    public function testParseAndDump($code, $expectedDump, $path)
    {
        $astTraverser = new PhpParser\NodeTraverser();
        $astTraverser->addVisitor(new PhpParser\NodeVisitor\NameResolver());
        $parser = new Parser(new ParserFactory()->createForNewestSupportedVersion(), $astTraverser);
        $traverser = new Traverser();
        $traverser->addVisitor(new Visitor\Simplifier());
        $printer = new Protobuf(Printer\Printer::MODE_RENDER_ATTRIBUTES);

        try {
            $script = $parser->parse($code, 'foo.php');
            $traverser->traverse($script);
            $result = $printer->printScript($script);
            $result = json_encode(json_decode($result->serializeToJsonString()), JSON_PRETTY_PRINT);
            //file_put_contents($path, $code . "-----\n" .CodeTest::canonicalize($result));
        } catch (RuntimeException $e) {
            $result = $e->getMessage();
        }

        $this->assertEquals(
            CodeTest::canonicalize($expectedDump),
            CodeTest::canonicalize($result),
        );
    }

    public static function provideTestParseAndDump()
    {
        $dir = __DIR__ . '/code';
        $iter = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir),
            RecursiveIteratorIterator::LEAVES_ONLY,
        );

        foreach ($iter as $file) {
            if (! $file->isFile()) {
                continue;
            }

            $contents = file_get_contents($file->getPathname());
            yield $file->getBasename() => array_merge(explode('-----', $contents), [$file->getPathname()]);
        }
    }

    public static function canonicalize($str)
    {
        // trim from both sides
        $str = trim($str);

        // normalize EOL to \n
        $str = str_replace(["\r\n", "\r"], "\n", $str);

        // trim right side of all lines
        return implode("\n", array_map('rtrim', explode("\n", $str)));
    }
}

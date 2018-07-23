<?php declare(strict_types=1);

namespace StaticMapLite\Query\Parser;

use StaticMapLite\Printer\PrinterInterface;

interface ParserInterface
{
    public function setPrinter(PrinterInterface $printer): ParserInterface;
    public function accepts(string $key): bool;
    public function parse(): ParserInterface;
}

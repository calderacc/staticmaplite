<?php declare(strict_types=1);

namespace StaticMapLite\Query\Parser;

use StaticMapLite\Printer\PrinterInterface;

abstract class AbstractParser implements ParserInterface
{
    /** @var PrinterInterface */
    protected $printer;

    public function setPrinter(PrinterInterface $printer): ParserInterface
    {
        $this->printer = $printer;

        return $this;
    }
}

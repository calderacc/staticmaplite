<?php declare(strict_types=1);

namespace StaticMapLite\Query;

use StaticMapLite\Printer\PrinterInterface;
use StaticMapLite\Query\Parser\MapCenterParser;
use StaticMapLite\Query\Parser\MapSizeParser;
use StaticMapLite\Query\Parser\MapTypeParser;
use StaticMapLite\Query\Parser\MapZoomParser;
use StaticMapLite\Query\Parser\MarkerParser;
use StaticMapLite\Query\Parser\ParserInterface;
use StaticMapLite\Query\Parser\PolylineParser;

class Query
{
    /** @var PrinterInterface $printer */
    protected $printer;

    public function setPrinter(PrinterInterface $printer): Query
    {
        $this->printer = $printer;

        return $this;
    }

    public function execute(): Query
    {
        $parserList = [
            new MapCenterParser(),
            new MapSizeParser(),
            new MapTypeParser(),
            new MapZoomParser(),
            new MarkerParser(),
            new PolylineParser(),
        ];

        /** @var ParserInterface $parser */
        foreach ($parserList as $parser) {
            $parser->setPrinter($this->printer)->parse();
        }

        return $this;
    }
}

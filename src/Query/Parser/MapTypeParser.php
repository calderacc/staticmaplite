<?php declare(strict_types=1);

namespace StaticMapLite\Query\Parser;

class MapTypeParser extends AbstractParser
{
    public function accepts(string $key): bool
    {
        return 'maptype' === $key;
    }

    public function parse(): ParserInterface
    {
        if (isset($_GET['maptype'])) {
            $this->printer->setMapType($_GET['maptype']);
        }

        return $this;
    }
}

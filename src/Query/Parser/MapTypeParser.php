<?php

namespace StaticMapLite\Query\Parser;

class MapTypeParser extends AbstractParser
{
    public function accepts(string $key): bool
    {
        return 'maptype' === $key;
    }

    public function parse(): ParserInterface
    {
        $this->printer->setMapType($_GET['maptype']);

        return $this;
    }
}

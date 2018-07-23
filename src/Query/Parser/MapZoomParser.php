<?php

namespace StaticMapLite\Query\Parser;

class MapZoomParser extends AbstractParser
{
    public function accepts(string $key): bool
    {
        return 'zoom' === $key;
    }

    public function parse(): ParserInterface
    {
        if (isset($_GET['zoom'])) {
            $this->printer->setZoom($_GET['zoom']);
        }

        return $this;
    }
}

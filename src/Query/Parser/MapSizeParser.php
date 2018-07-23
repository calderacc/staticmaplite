<?php

namespace StaticMapLite\Query\Parser;

class MapSizeParser extends AbstractParser
{
    public function accepts(string $key): bool
    {
        return 'size' === $key;
    }

    public function parse(): ParserInterface
    {
        list($width, $height) = explode('x', $_GET['size']);

        $this->printer->setSize($width, $height);

        return $this;
    }
}

<?php

namespace StaticMapLite\Query\Parser;


class MapCenterParser extends AbstractParser
{
    public function accepts(string $key): bool
    {
        return 'center' === $key;
    }

    public function parse(): ParserInterface
    {
        if (isset($_GET['center'])) {
            list($centerLatitude, $centerLongitude) = explode(',', $_GET['center']);

            $this->printer->setCenter(floatval($centerLatitude), floatval($centerLongitude));
        }

        return $this;
    }
}

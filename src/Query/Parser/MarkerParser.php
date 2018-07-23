<?php

namespace StaticMapLite\Query\Parser;

use StaticMapLite\Element\Factory\Marker\ExtraMarkerFactory;

class MarkerParser extends AbstractParser
{
    public function accepts(string $key): bool
    {
        return 'markers' === $key;
    }

    public function parse(): ParserInterface
    {
        $markers = isset($_GET['markers']) ? $_GET['markers'] : null;

        if ($markers) {
            $markerList = explode('|', $markers);
            $markerFactory = new ExtraMarkerFactory();

            foreach ($markerList as $markerData) {
                list($markerLatitude, $markerLongitude, $markerShape, $markerColor, $markerIcon) = explode(',', $markerData);

                $marker = $markerFactory->create(floatval($markerLatitude), floatval($markerLongitude), $markerShape, $markerColor, $markerIcon);

                $this->printer->addMarker($marker);
            }
        }

        return $this;
    }
}

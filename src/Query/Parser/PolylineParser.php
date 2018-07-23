<?php

namespace StaticMapLite\Query\Parser;

use StaticMapLite\Element\Polyline\Polyline;

class PolylineParser extends AbstractParser
{
    public function accepts(string $key): bool
    {
        return 'polylines' === $key;
    }

    public function parse(): ParserInterface
    {
        $polylines = isset($_GET['polylines']) ? $_GET['polylines'] : null;

        if ($polylines) {
            $polylineList = explode('|', $polylines);

            foreach ($polylineList as $polylineData) {
                list($polyline64String, $colorRed, $colorGreen, $colorBlue) = explode(',', $polylineData);

                $polylineString = base64_decode($polyline64String);

                $polyline = new Polyline($polylineString, $colorRed, $colorGreen, $colorBlue);

                $this->printer->addPolyline($polyline);
            }
        }

        return $this;
    }
}

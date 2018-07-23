<?php

namespace StaticMapLite\Query;

use StaticMapLite\Element\Factory\Marker\ExtraMarkerFactory;
use StaticMapLite\Element\Polyline\Polyline;
use StaticMapLite\Printer\PrinterInterface;
use StaticMapLite\Query\Parser\MapCenterParser;
use StaticMapLite\Query\Parser\MapSizeParser;
use StaticMapLite\Query\Parser\MapTypeParser;
use StaticMapLite\Query\Parser\MapZoomParser;
use StaticMapLite\Query\Parser\ParserInterface;

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
        ];

        /** @var ParserInterface $parser */
        foreach ($parserList as $parser) {
            $parser->setPrinter($this->printer)->parse();
        }

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

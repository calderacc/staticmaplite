<?php

namespace StaticMapLite\Query;

use StaticMapLite\Element\Factory\Marker\ExtraMarkerFactory;
use StaticMapLite\Element\Polyline\Polyline;
use StaticMapLite\Printer\PrinterInterface;

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
        list($centerLatitude, $centerLongitude) = explode(',', $_GET['center']);
        list($width, $height) = explode('x', $_GET['size']);

        $this->printer
            ->setCenter(floatval($centerLatitude), floatval($centerLongitude))
            ->setZoom($_GET['zoom'])
            ->setSize($width, $height)
            ->setMapType($_GET['maptype'])
        ;

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

<?php

namespace StaticMapLite\Guesser;

use StaticMapLite\BoundingBox;
use StaticMapLite\Element\Marker\MarkerInterface;
use StaticMapLite\Element\Polyline\Polyline;
use StaticMapLite\Printer\PrinterInterface;

class MapZoomGuesser
{
    /** @var PrinterInterface $printer */
    protected $printer;

    public function setPrinter(PrinterInterface $printer): MapZoomGuesser
    {
        $this->printer = $printer;

        return $this;
    }

    public function guess(): MapZoomGuesser
    {
        if (!$this->printer->getZoom()) {
            $zoomLevel = $this->computeZoomLevel();

            $this->printer->setZoom($zoomLevel);
        }

        return $this;
    }

    protected function computeZoomLevel(): int
    {
        $boundingBox = $this->computeBoundingBox();

        // inspired by https://gis.stackexchange.com/a/19652

        $ry1 = log(sin(deg2rad($boundingBox->getEast())) + 1) /
            cos(deg2rad($boundingBox->getEast()));

        $ry2 = log(sin(deg2rad($boundingBox->getWest())) + 1) /
            cos(deg2rad($boundingBox->getWest()));

        $ryc = ($ry1 + $ry2) / 2;

        $centerY = rad2deg(atan(sinh($ryc)));

        $resolutionHorizontal = ($boundingBox->getNorth() - $boundingBox->getSouth()) / $this->printer->getWidth();

        $vy0 = log(tan(pi() * (0.25 + $centerY / 360.0)));
        $vy1 = log(tan(pi() * (0.25 + $boundingBox->getWest() / 360.0)));

        $viewHeightHalf = $this->printer->getHeight() / 2.0;

        $zoomFactorPowered = $viewHeightHalf / (40.7436654315252 * ($vy1 - $vy0));
        $resolutionVertical = 360.0 / ($zoomFactorPowered * 256);

        $resolution = max([$resolutionHorizontal, $resolutionVertical]) * 1.2;

        return log(360 / ($resolution * 256), 2);
    }

    protected function computeBoundingBox(): BoundingBox
    {
        $boundingBox = new BoundingBox();

        /** @var MarkerInterface $marker */
        foreach ($this->printer->getMarkers() as $marker) {
            if (!$boundingBox->getNorth() || $marker->getLatitude() > $boundingBox->getNorth()) {
                $boundingBox->setNorth($marker->getLatitude());
            }

            if (!$boundingBox->getSouth() || $marker->getLatitude() < $boundingBox->getSouth()) {
                $boundingBox->setSouth($marker->getLatitude());
            }

            if (!$boundingBox->getWest() || $marker->getLongitude() < $boundingBox->getWest()) {
                $boundingBox->setWest($marker->getLongitude());
            }

            if (!$boundingBox->getEast() || $marker->getLongitude() > $boundingBox->getEast()) {
                $boundingBox->setEast($marker->getLongitude());
            }
        }

        /** @var Polyline $polyline */
        foreach ($this->printer->getPolylines() as $polyline) {
            $polylineList = \Polyline::decode($polyline->getPolyline());

            while (!empty($polylineList)) {
                $latitude = array_shift($polylineList);
                $longitude = array_shift($polylineList);

                if (!$boundingBox->getNorth() || $latitude > $boundingBox->getNorth()) {
                    $boundingBox->setNorth($latitude);
                }

                if (!$boundingBox->getSouth() || $latitude < $boundingBox->getSouth()) {
                    $boundingBox->setSouth($latitude);
                }

                if (!$boundingBox->getWest() || $longitude < $boundingBox->getWest()) {
                    $boundingBox->setWest($longitude);
                }

                if (!$boundingBox->getEast() || $longitude > $boundingBox->getEast()) {
                    $boundingBox->setEast($longitude);
                }
            }
        }

        return $boundingBox;
    }
}

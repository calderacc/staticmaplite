<?php

namespace StaticMapLite\Guesser;

use StaticMapLite\Element\Marker\MarkerInterface;
use StaticMapLite\Element\Polyline\Polyline;
use StaticMapLite\Printer\PrinterInterface;
use StaticMapLite\Util\BoundingBox\BoundingBox;

class MapCenterGuesser
{
    /** @var PrinterInterface $printer */
    protected $printer;

    public function setPrinter(PrinterInterface $printer): MapCenterGuesser
    {
        $this->printer = $printer;

        return $this;
    }

    public function guess(): MapCenterGuesser
    {
        if (!$this->printer->getLatitude() || !$this->printer->getLongitude()) {
            $boundingBox = $this->computeBoundingBox();

            $centerLongitude = $boundingBox->getWest() + ($boundingBox->getEast() - $boundingBox->getWest()) / 2.0;
            $centerLatitude = $boundingBox->getSouth() + ($boundingBox->getNorth() - $boundingBox->getSouth()) / 2.0;

            $this->printer->setLatitude($centerLatitude)->setLongitude($centerLongitude);
        }

        return $this;
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

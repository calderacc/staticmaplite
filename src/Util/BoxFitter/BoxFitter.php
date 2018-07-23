<?php

namespace StaticMapLite\Util\BoxFitter;

use StaticMapLite\Element\Marker\MarkerInterface;
use StaticMapLite\Element\Polyline\Polyline;
use StaticMapLite\Util\BoundingBox\BoundingBox;

class BoxFitter extends AbstractBoxFitter
{
    public function fit(): BoundingBox
    {
        $boundingBox = new BoundingBox();

        /** @var MarkerInterface $marker */
        foreach ($this->markers as $marker) {
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
        foreach ($this->polylines as $polyline) {
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

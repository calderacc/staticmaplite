<?php declare(strict_types=1);

namespace StaticMapLite\Guesser;

use StaticMapLite\Util\BoxFitter\BoxFitter;

class MapCenterGuesser extends AbstractGuesser
{
    public function guess(): GuesserInterface
    {
        if (!$this->printer->getLatitude() || !$this->printer->getLongitude()) {
            $boundingBox = (new BoxFitter())
                ->setMarkers($this->printer->getMarkers())
                ->setPolylines($this->printer->getPolylines())
                ->fit();

            $centerLongitude = floatval($boundingBox->getWest() + ($boundingBox->getEast() - $boundingBox->getWest()) / 2.0);
            $centerLatitude = floatval($boundingBox->getSouth() + ($boundingBox->getNorth() - $boundingBox->getSouth()) / 2.0);

            $this->printer->setLatitude($centerLatitude)->setLongitude($centerLongitude);
        }

        return $this;
    }
}

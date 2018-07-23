<?php

namespace StaticMapLite\Guesser;

use StaticMapLite\Printer\PrinterInterface;
use StaticMapLite\Util\BoxFitter\BoxFitter;

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
            $boundingBox = (new BoxFitter())
                ->setMarkers($this->printer->getMarkers())
                ->setPolylines($this->printer->getPolylines())
                ->fit();

            $centerLongitude = $boundingBox->getWest() + ($boundingBox->getEast() - $boundingBox->getWest()) / 2.0;
            $centerLatitude = $boundingBox->getSouth() + ($boundingBox->getNorth() - $boundingBox->getSouth()) / 2.0;

            $this->printer->setLatitude($centerLatitude)->setLongitude($centerLongitude);
        }

        return $this;
    }
}

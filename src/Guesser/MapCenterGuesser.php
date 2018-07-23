<?php

namespace StaticMapLite\Guesser;

use StaticMapLite\Element\Marker\MarkerInterface;
use StaticMapLite\Printer\PrinterInterface;

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
            if (count($this->printer->getMarkers()) === 1) {
                /** @var MarkerInterface $marker */
                $marker = $this->printer->getMarkers()[0];

                $this->printer
                    ->setLatitude($marker->getLatitude())
                    ->setLongitude($marker->getLongitude());
            }
        }

        return $this;
    }
}

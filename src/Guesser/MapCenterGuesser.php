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
            } elseif (count($this->printer->getMarkers()) > 1) {
                $north = null;
                $west = null;
                $south = null;
                $east = null;

                /** @var MarkerInterface $marker */
                foreach ($this->printer->getMarkers() as $marker) {
                    if (!$north || $marker->getLatitude() > $north) {
                        $north = $marker->getLatitude();
                    }

                    if (!$south || $marker->getLatitude() < $south) {
                        $south = $marker->getLatitude();
                    }

                    if (!$west || $marker->getLongitude() < $west) {
                        $west = $marker->getLongitude();
                    }

                    if (!$east || $marker->getLongitude() > $east) {
                        $east = $marker->getLongitude();
                    }
                }

                $centerLongitude = $west + ($east - $west) / 2.0;
                $centerLatitude = $south + ($north - $south) / 2.0;

                $this->printer->setLatitude($centerLatitude)->setLongitude($centerLongitude);
            }
        }

        return $this;
    }
}

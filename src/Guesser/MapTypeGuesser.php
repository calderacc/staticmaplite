<?php

namespace StaticMapLite\Guesser;

use StaticMapLite\Printer\PrinterInterface;

class MapTypeGuesser
{
    /** @var PrinterInterface $printer */
    protected $printer;

    public function setPrinter(PrinterInterface $printer): MapTypeGuesser
    {
        $this->printer = $printer;

        return $this;
    }

    public function guess(): MapTypeGuesser
    {
        if (!$this->printer->getMaptype()) {
            $this->printer->setMaptype('wikimedia-intl');
        }

        return $this;
    }
}

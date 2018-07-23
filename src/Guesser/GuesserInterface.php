<?php

namespace StaticMapLite\Guesser;

use StaticMapLite\Printer\PrinterInterface;

interface GuesserInterface
{
    public function setPrinter(PrinterInterface $printer): GuesserInterface;
    public function guess(): GuesserInterface;
}

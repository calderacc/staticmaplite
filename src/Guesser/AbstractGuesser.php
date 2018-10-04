<?php declare(strict_types=1);

namespace StaticMapLite\Guesser;

use StaticMapLite\Printer\PrinterInterface;

abstract class AbstractGuesser implements GuesserInterface
{
    /** @var PrinterInterface $printer */
    protected $printer;

    public function setPrinter(PrinterInterface $printer): GuesserInterface
    {
        $this->printer = $printer;

        return $this;
    }
}

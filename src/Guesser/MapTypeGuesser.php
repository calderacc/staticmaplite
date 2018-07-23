<?php

namespace StaticMapLite\Guesser;

class MapTypeGuesser extends AbstractGuesser
{
    public function guess(): GuesserInterface
    {
        if (!$this->printer->getMaptype()) {
            $this->printer->setMaptype('wikimedia-intl');
        }

        return $this;
    }
}

<?php

namespace StaticMapLite\Element\Marker;

class AbstractMarker implements MarkerInterface
{
    protected $latitude;
    protected $longitude;

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }
}

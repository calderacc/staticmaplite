<?php

namespace StaticMapLite\Element\Marker;

interface MarkerInterface
{
    public function getLatitude(): float;
    public function getLongitude(): float;
}

<?php declare(strict_types=1);

namespace StaticMapLite\Element\Marker;

interface MarkerInterface
{
    public function getLatitude(): float;
    public function getLongitude(): float;
}

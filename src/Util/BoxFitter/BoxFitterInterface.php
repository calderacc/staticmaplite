<?php

namespace StaticMapLite\Util\BoxFitter;

use StaticMapLite\Util\BoundingBox\BoundingBox;

interface BoxFitterInterface
{
    public function setMarkers(array $markers = []): BoxFitterInterface;
    public function setPolylines(array $polylines = []): BoxFitterInterface;
    public function fit(): BoundingBox;
}

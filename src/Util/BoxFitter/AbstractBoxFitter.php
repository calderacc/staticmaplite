<?php

namespace StaticMapLite\Util\BoxFitter;

use StaticMapLite\Util\BoundingBox\BoundingBox;

abstract class AbstractBoxFitter implements BoxFitterInterface
{
    /** @var array $markers */
    protected $markers;

    /** @var array $polylines */
    protected $polylines;

    public function setMarkers(array $markers = []): BoxFitterInterface
    {
        $this->markers = $markers;

        return $this;
    }

    public function setPolylines(array $polylines = []): BoxFitterInterface
    {
        $this->polylines = $polylines;

        return $this;
    }

    abstract public function fit(): BoundingBox;
}

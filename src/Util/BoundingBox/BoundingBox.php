<?php

namespace StaticMapLite\Util\BoundingBox;

class BoundingBox
{
    /** @var float $north */
    protected $north;

    /** @var float $south */
    protected $south;

    /** @var float $west */
    protected $west;

    /** @var float $east */
    protected $east;

    public function __construct(float $north = null, float $south = null, float $west = null, float $east = null)
    {
        $this->north = $north;
        $this->south = $south;
        $this->west = $west;
        $this->east = $east;
    }

    public function getNorth(): ?float
    {
        return $this->north;
    }

    public function setNorth(float $north): BoundingBox
    {
        $this->north = $north;

        return $this;
    }

    public function getSouth(): ?float
    {
        return $this->south;
    }

    public function setSouth(float $south): BoundingBox
    {
        $this->south = $south;

        return $this;
    }

    public function getWest(): ?float
    {
        return $this->west;
    }

    public function setWest(float $west): BoundingBox
    {
        $this->west = $west;

        return $this;
    }

    public function getEast(): ?float
    {
        return $this->east;
    }

    public function setEast(float $east): BoundingBox
    {
        $this->east = $east;

        return $this;
    }
}

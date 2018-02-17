<?php

namespace StaticMapLite\ElementPrinter\Polyline;

use Imagine\Image\Point;
use StaticMapLite\Canvas\Canvas;
use StaticMapLite\Element\Polyline\Polyline;
use StaticMapLite\Util;

class PolylinePrinter
{
    /** @var Polyline $polyline */
    protected $polyline = null;

    public function __construct()
    {

    }

    public function setPolyline(Polyline $polyline): PolylinePrinter
    {
        $this->polyline = $polyline;

        return $this;
    }

    public function paint(Canvas $canvas): PolylinePrinter
    {
        $pointList = $this->convertPolylineToPointList($canvas);

        $color = $canvas->getImage()->palette()->color('f00');

        $startPoint = null;
        $endPoint = null;

        while (!empty($pointList)) {
            if (!$startPoint) {
                $startPoint = array_pop($pointList);
            }

            $endPoint = array_pop($pointList);

            $canvas->getImage()->draw()->line($startPoint, $endPoint, $color, 3);

            $startPoint = $endPoint;
        }


        return $this;
    }

    protected function convertPolylineToPointList(Canvas $canvas): array
    {
        $polylineList = \Polyline::decode($this->polyline->getPolyline());

        $pointList = [];

        while (!empty($polylineList)) {
            $latitude = array_shift($polylineList);
            $longitude = array_shift($polylineList);

            $sourceX = floor(($canvas->getWidth() / 2) - $canvas->getTileSize() * ($canvas->getCenterX() - Util::lonToTile($longitude, $canvas->getZoom())));
            $sourceY = floor(($canvas->getHeight() / 2) - $canvas->getTileSize() * ($canvas->getCenterY() - Util::latToTile($latitude, $canvas->getZoom())));

            $point = new Point($sourceX, $sourceY);

            $pointList[] = $point;
        }

        return $pointList;
    }
}

<?php declare(strict_types=1);

namespace StaticMapLite\Guesser;

use StaticMapLite\Util\BoxFitter\BoxFitter;

class MapZoomGuesser extends AbstractGuesser
{
    public function guess(): GuesserInterface
    {
        if (!$this->printer->getZoom()) {
            $zoomLevel = $this->computeZoomLevel();

            $this->printer->setZoom($zoomLevel);
        }

        return $this;
    }

    protected function computeZoomLevel(): int
    {
        $boundingBox = (new BoxFitter())
            ->setMarkers($this->printer->getMarkers())
            ->setPolylines($this->printer->getPolylines())
            ->fit();

        // inspired by https://gis.stackexchange.com/a/19652

        $ry1 = log(sin(deg2rad($boundingBox->getEast())) + 1) /
            cos(deg2rad($boundingBox->getEast()));

        $ry2 = log(sin(deg2rad($boundingBox->getWest())) + 1) /
            cos(deg2rad($boundingBox->getWest()));

        $ryc = ($ry1 + $ry2) / 2;

        $centerY = rad2deg(atan(sinh($ryc)));

        $resolutionHorizontal = ($boundingBox->getNorth() - $boundingBox->getSouth()) / $this->printer->getWidth();

        $vy0 = log(tan(pi() * (0.25 + $centerY / 360.0)));
        $vy1 = log(tan(pi() * (0.25 + $boundingBox->getWest() / 360.0)));

        $viewHeightHalf = $this->printer->getHeight() / 2.0;

        $zoomFactorPowered = $viewHeightHalf / (40.7436654315252 * ($vy1 - $vy0));
        $resolutionVertical = 360.0 / ($zoomFactorPowered * 256);

        $resolution = max([$resolutionHorizontal, $resolutionVertical]) * 1.2;

        return log(360 / ($resolution * 256), 2);
    }
}

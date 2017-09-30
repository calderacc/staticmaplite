<?php

namespace StaticMapLite\ElementPrinter\Marker;

use StaticMapLite\Canvas\Canvas;
use StaticMapLite\Element\Marker\AbstractMarker;
use StaticMapLite\Element\Marker\ExtraMarker;
use StaticMapLite\Element\Marker\Marker;
use StaticMapLite\Util;

class ExtraMarkerPrinter
{
    /** @var ExtraMarker $marker */
    protected $marker = null;

    public function __construct()
    {

    }

    public function setMarker(AbstractMarker $marker): ExtraMarkerPrinter
    {
        $this->marker = $marker;

        return $this;
    }

    public function paint(Canvas $canvas): ExtraMarkerPrinter
    {
        $markerImage = $this->createMarker();

        $destX = floor(($canvas->getWidth() / 2) - $canvas->getTileSize() * ($canvas->getCenterX() - Util::lonToTile($this->marker->getLongitude(), $canvas->getZoom())));
        $destY = floor(($canvas->getHeight() / 2) - $canvas->getTileSize() * ($canvas->getCenterY() - Util::latToTile($this->marker->getLatitude(), $canvas->getZoom())));

        $markerWidth = imagesx($markerImage);
        $markerHeight = imagesy($markerImage);

        $destX -= $markerWidth / 2;
        $destY -= $markerHeight;

        imagecopy($canvas->getImage(), $markerImage, $destX, $destY, 0, 0, imagesx($markerImage), imagesy($markerImage));

        return $this;
    }

    protected function createMarker()
    {
        $extramarkersImgUrl = __DIR__.'/../../../images/extramarkers.png';
        $extramarkers = imagecreatefrompng($extramarkersImgUrl);

        $markerImage = imagecreatetruecolor(72, 92);
        $transparentColor = imagecolorallocatealpha($markerImage, 0, 0, 0, 127);
        imagefill($markerImage, 0, 0, $transparentColor);

        $markerWidth = imagesx($markerImage);
        $markerHeight = imagesy($markerImage);

        $sourceX = $markerWidth * $this->marker->getColor();
        $sourceY = $markerHeight * $this->marker->getShape();

        imagecopy($markerImage, $extramarkers, 0, 0, $sourceX, $sourceY, $markerWidth, $markerHeight);

        $this->writeMarker($markerImage);

        return $markerImage;
    }

    protected function writeMarker($markerImage)
    {
        $fontSize = 20;
        $fontFile = __DIR__.'/../../../fonts/fontawesome-webfont.ttf';
        $text = json_decode(sprintf('"&#x%s;"', $this->marker->getAwesome()));

        $bbox = imagettfbbox($fontSize, 0, $fontFile, $text);

        $x = $bbox[0] + (imagesx($markerImage) / 2) - ($bbox[4] / 2) + 3;
        $y = 42;

        $white = imagecolorallocate($markerImage, 255, 255, 255);
        imagettftext($markerImage, $fontSize, 0, $x, $y, $white, $fontFile, $text);


    }
}

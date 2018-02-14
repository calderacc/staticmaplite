<?php

namespace StaticMapLite\Printer;

use StaticMapLite\Canvas\Canvas;
use StaticMapLite\CanvasTilePainter\CanvasTilePainter;
use StaticMapLite\Element\Marker\AbstractMarker;
use StaticMapLite\Element\Polyline\Polyline;
use StaticMapLite\ElementPrinter\Marker\ExtraMarkerPrinter;
use StaticMapLite\ElementPrinter\Polyline\PolylinePrinter;
use StaticMapLite\MapCache\MapCache;
use StaticMapLite\TileResolver\CachedTileResolver;
use StaticMapLite\Util;

class Printer extends AbstractPrinter
{
    public function __construct()
    {
        $this->zoom = 0;
        $this->latitude = 0;
        $this->longitude = 0;
        $this->width = 500;
        $this->height = 350;
        $this->maptype = $this->tileDefaultSrc;

        $this->mapCache = new MapCache($this);
        $this->tileResolver = new CachedTileResolver();
        $this->tileResolver->setTileLayerUrl($this->tileSrcUrl[$this->maptype]);
    }

    public function addMarker(AbstractMarker $marker): Printer
    {
        $this->markers[] = $marker;

        return $this;
    }

    public function addPolyline(Polyline $polyline): Printer
    {
        $this->polylines[] = $polyline;

        return $this;
    }

    public function setCenter(float $latitude, float $longitude): Printer
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;

        return $this;
    }

    public function setSize(int $width, int $height): Printer
    {
        $this->width = $width;
        $this->height = $height;

        if ($this->width > $this->maxWidth) {
            $this->width = $this->maxWidth;
        }

        if ($this->height > $this->maxHeight) {
            $this->height = $this->maxHeight;
        }

        return $this;
    }

    public function setZoom(int $zoom): PrinterInterface
    {
        $this->zoom = $zoom;

        if ($this->zoom > 18) {
            $this->zoom = 18;
        }

        return $this;
    }

    public function setMapType(string $mapType): PrinterInterface
    {
        $this->maptype = $mapType;

        $this->tileResolver->setTileLayerUrl($this->tileSrcUrl[$this->maptype]);

        return $this;
    }

    public function initCoords()
    {
        $this->centerX = Util::lonToTile($this->longitude, $this->zoom);
        $this->centerY = Util::latToTile($this->latitude, $this->zoom);

        $this->offsetX = floor((floor($this->centerX) - $this->centerX) * $this->tileSize);
        $this->offsetY = floor((floor($this->centerY) - $this->centerY) * $this->tileSize);
    }

    public function createBaseMap()
    {
        $this->canvas = new Canvas(
            $this->width,
            $this->height,
            $this->zoom,
            $this->centerX,
            $this->centerY
        );

        $ctp = new CanvasTilePainter();
        $ctp
            ->setCanvas($this->canvas)
            ->setTileResolver($this->tileResolver)
            ->paint()
        ;
    }

    public function placeMarkers()
    {
        $printer = new ExtraMarkerPrinter();

        foreach ($this->markers as $marker) {
            $printer
                ->setMarker($marker)
                ->paint($this->canvas)
            ;
        }
    }

    public function placePolylines()
    {
        $printer = new PolylinePrinter();

        /** @var Polyline $polyline */
        foreach ($this->polylines as $polyline) {
            $printer
                ->setPolyline($polyline)
                ->paint($this->canvas)
            ;
        }
    }

    public function copyrightNotice()
    {
        $logoImg = imagecreatefrompng($this->osmLogo);
        imagecopy($this->canvas->getImage(), $logoImg, imagesx($this->canvas->getImage()) - imagesx($logoImg), imagesy($this->canvas->getImage()) - imagesy($logoImg), 0, 0, imagesx($logoImg), imagesy($logoImg));
    }
    public function sendHeader()
    {
        header('Content-Type: image/png');
        $expires = 60 * 60 * 24 * 14;
        header("Pragma: public");
        header("Cache-Control: maxage=" . $expires);
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
    }

    public function makeMap()
    {
        $this->initCoords();
        $this->createBaseMap();

        if (count($this->polylines)) {
            $this->placePolylines();
        }

        if (count($this->markers)) {
            $this->placeMarkers();
        }

        if ($this->osmLogo) {
            $this->copyrightNotice();
        }
    }

    public function showMap()
    {
        if ($this->mapCache) {
            // use map cache, so check cache for map
            if (!$this->mapCache->checkMapCache()) {
                // map is not in cache, needs to be build
                $this->makeMap();
                $this->mapCache->cache($this->canvas);
            } else {
                // map is in cache
                $this->sendHeader();
                return file_get_contents($this->mapCache->getFilename());
            }

        } else {
            // no cache, make map, send headers and deliver png
            $this->makeMap();
            $this->sendHeader();
            return imagepng($this->canvas->getImage());

        }
    }
}

<?php

namespace GoogleMapsUtility;

/**
 *
 */
class Utility
{
    const TILE_SIZE = 256;

    public static function fromMercatorCoords($point)
    {
        $point->x *= 360;
        $point->y = rad2deg(atan(sinh($point->y)) * M_PI);

        return $point;
    }

    public static function getPixelOffsetInTile($lat, $lng, $zoom)
    {
        $pixelCoords = static::toZoomedPixelCoords($lat, $lng, $zoom);

        return new Point(
            $pixelCoords->x % static::TILE_SIZE,
            $pixelCoords->y % static::TILE_SIZE
        );
    }

    public static function getTileRect($x, $y, $zoom)
    {
        $tilesAtThisZoom = 1 << $zoom;
        $lngWidth = 360.0 / $tilesAtThisZoom;
        $lng = -180 + ($x * $lngWidth);

        $latHeightMerc = 1.0 / $tilesAtThisZoom;
        $topLatMerc = $y * $latHeightMerc;
        $bottomLatMerc = $topLatMerc + $latHeightMerc;

        $bottomLat = (180 / M_PI) * ((2 * atan(exp(M_PI * (1 - (2 * $bottomLatMerc))))) - (M_PI / 2));
        $topLat = (180 / M_PI) * ((2 * atan(exp(M_PI * (1 - (2 * $topLatMerc))))) - (M_PI / 2));

        $latHeight = $topLat - $bottomLat;

        return new Boundary($lng, $bottomLat, $lngWidth, $latHeight);
    }

    public static function toMercatorCoords($lat, $lng)
    {
        if ($lng > 180) {
            $lng -= 360;
        }

        $lng /= 360;
        $lat = asinh(tan(deg2rad($lat))) / M_PI / 2;

        return new Point($lng, $lat);
    }

    public static function toNormalisedMercatorCoords($point)
    {
        $point->x += 0.5;
        $point->y = abs($point->y - 0.5);

        return $point;
    }

    public static function toTileXY($lat, $lng, $zoom)
    {
        $normalised = static::toNormalisedMercatorCoords(
            static::toMercatorCoords($lat, $lng)
        );

        $scale = 1 << ($zoom);

        return new Point((int) ($normalised->x * $scale), (int) ($normalised->y * $scale));
    }

    public static function toZoomedPixelCoords($lat, $lng, $zoom)
    {
        $normalised = static::toNormalisedMercatorCoords(
            static::toMercatorCoords($lat, $lng)
        );

        $scale = (1 << ($zoom)) * static::TILE_SIZE;

        return new Point(
            (int) ($normalised->x * $scale),
            (int) ($normalised->y * $scale)
        );
    }

    public static function fromZoomedPixelCoords($point, $zoom)
    {
        $lng = ($point->x * (360 / ((1 << $zoom) * 256))) - 180;
        $lat = $point->y * (2 / ((1 << $zoom) * 256));
        $lat = 1 - $lat;
        $lat = $lat * M_PI;
        $lat = rad2deg(atan(sinh($lat)));

        $latlng = new StdClass();
        $latlng->lat = $lat;
        $latlng->lng = $lng;

        return $latlng;
    }
}

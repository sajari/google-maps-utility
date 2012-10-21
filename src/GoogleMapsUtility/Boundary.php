<?php

namespace GoogleMapsUtility;

/**
 *
 */
class Boundary
{
    public $x;
    public $y;
    public $width;
    public $height;

    /**
     * Constructor.
     *
     * @param float $x
     * @param float $y
     * @param float $width
     * @param float $height
     */
    public function __construct($x, $y, $width, $height)
    {
         $this->x = $x;
         $this->y = $y;
         $this->width = $width;
         $this->height = $height;
    }

    /**
     * Return a string representation of this boundary in the format
     * (<x>,<y>,<width>,<height>).
     *
     * @return string
     */
    public function __toString()
    {
         return "({$this->x},{$this->y},{$this->width},{$this->height})";
    }
}

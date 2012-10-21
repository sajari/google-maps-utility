<?php

namespace GoogleMapsUtility;

/**
 *
 */
class Point
{
     public $x;
     public $y;

     /**
      * Constructor.
      *
      * @param float $x
      * @param float $y
      */
     public function __construct($x, $y)
     {
          $this->x = $x;
          $this->y = $y;
     }

     /**
     * Return a string representation of this point in the format (<x>,<y>).
     *
     * @return string
     */
     public function __toString()
     {
          return "({$this->x},{$this->y})";
     }
}

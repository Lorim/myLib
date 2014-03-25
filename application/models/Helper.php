<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Application_Model_Helper {
    public static function rgb2xy($r, $g, $b) {
        
        $r = ($r > 0.04045) ? pow(($r + 0.055) / (1.0 + 0.055), 2.4) : ($r / 12.92);
        $g = ($g > 0.04045) ? pow(($g + 0.055) / (1.0 + 0.055), 2.4) : ($g / 12.92);
        $b = ($b > 0.04045) ? pow(($b + 0.055) / (1.0 + 0.055), 2.4) : ($b / 12.92);
        
        $X = $r * 0.649926 + $g * 0.103455 + $b * 0.197109; 
        $Y = $r * 0.234327 + $g * 0.743075 + $b * 0.022598; 
        $Z = $r * 0.0000000 + $g * 0.053077 + $b * 1.035763;
        /*
        $X = 1.076450 * $r - 0.237662 * $g + 0.161212 * $b;
        $Y = 0.410964 * $r + 0.554342 * $g + 0.034694 * $b;
        $Z = -0.010954 * $r - 0.013389 * $g + 1.024343 * $b;
        
         */
        if($X == 0 || $Y == 0) {
            return array('x' => 0, 'y' => 0);
        }
        
        //return;
        $x = $X / ($X + $Y + $Z);
        $y = $Y / ($X + $Y + $Z);
        var_dump($x,$y);
        return array(
            'x' => $x,
            'y' => $y
        );
    }
}
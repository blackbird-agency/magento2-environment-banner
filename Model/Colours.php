<?php
/**
 * Class for set of environment banner colours
 *
 * @method getEnv
 * @method getFeBgcolor
 * @method getBeColor
 */

namespace Blackbird\EnvironmentBanner\Model;

class Colours extends \Magento\Framework\Model\AbstractModel
{
    const DEFAULT_COLOR = '#666666';

    /**
     * Return lighter version of the front-end background colour
     *
     * @return string Hex Colour
     */
    public function getFeLighterBgcolor()
    {
        $rgb = self::_convertHtmlToRgb($this->getFeBgcolor());
        // If fail to convert, return default hex code
        if ($rgb === null)
            return self::DEFAULT_COLOR;
        $hsl = self::_convertRgbToHsl($rgb);

        // Change colour to make lighter and change hue slightly
        $hsl['h'] = $hsl['h'] + 10 % 360;
        $hsl['l'] = min($hsl['l'] + 0.25, 1.0);
        $hsl['s'] = min($hsl['s'] + 0.1, 1.0);

        $rgb2 = self::_convertHslToRgb($hsl);
        $hex = self::_convertRgbToHex($rgb2);

        return $hex;
    }

    public function getFeColor()
    {
        $rgb = self::_convertHtmlToRgb($this->getFeBgcolor());
        // If fail to convert, return default hex code
        if ($rgb === null)
            return self::DEFAULT_COLOR;


        $hex = self::_convertRgbToHex($rgb);

        return $hex;
    }



    public function getBeBorderColor()
    {
        $rgb = self::_convertHtmlToRgb($this->getBeColor());
        // If fail to convert, return default hex code
        if ($rgb === null)
            return self::DEFAULT_COLOR;
        $hsl = self::_convertRgbToHsl($rgb);

        // Change colour to make brighter
        $hsl['l'] = min($hsl['l'] + 0.1, 1.0);
        $hsl['s'] = min($hsl['s'] + 0.3, 1.0);

        $rgb2 = self::_convertHslToRgb($hsl);
        $hex = self::_convertRgbToHex($rgb2);

        return $hex;
    }

    public function getBeLighterColor()
    {
        $rgb = self::_convertHtmlToRgb($this->getBeColor());
        // If fail to convert, return default hex code
        if ($rgb === null)
            return self::DEFAULT_COLOR;
        $hsl = self::_convertRgbToHsl($rgb);

        // Change colour to make lighter
        $hsl['h'] = $hsl['h'] + 5 % 360;
        $hsl['l'] = min($hsl['l'] + 0.3, 1.0);
        $hsl['s'] = max($hsl['s'] - 0.2, 0.0);

        $rgb2 = self::_convertHslToRgb($hsl);
        $hex = self::_convertRgbToHex($rgb2);

        return $hex;
    }

    /**
     * Convert from RGB to HSL
     *
     * @param array $rgb
     * @return array
     */
    protected static function _convertRgbToHsl($rgb)
    {
        $r = $rgb['r'];
        $g = $rgb['g'];
        $b = $rgb['b'];

        $r /= 255;
        $g /= 255;
        $b /= 255;

        $max = max($r, $g, $b);
        $min = min($r, $g, $b);

        $h = 0;
        $l = ($max + $min) / 2;
        $d = $max - $min;

        if ($d == 0) {
            $h = $s = 0; // achromatic
        } else {
            $s = $d / (1 - abs(2 * $l - 1));

            switch ($max) {
                case $r:
                    $h = 60 * fmod((($g - $b) / $d), 6);
                    if ($b > $g) {
                        $h += 360;
                    }
                    break;

                case $g:
                    $h = 60 * (($b - $r) / $d + 2);
                    break;

                case $b:
                    $h = 60 * (($r - $g) / $d + 4);
                    break;
            }
        }

        return array('h' => $h, 's' => $s, 'l' => $l);
    }

    /**
     * Convert from HSL to RGB
     *
     * @param array $hsl
     * @return array
     */
    protected function _convertHslToRgb($hsl)
    {
        $h = $hsl['h'];
        $s = $hsl['s'];
        $l = $hsl['l'];

        $c = (1 - abs(2 * $l - 1)) * $s;
        $x = $c * (1 - abs(fmod(($h / 60), 2) - 1));
        $m = $l - ($c / 2);

        if ($h < 60) {
            $r = $c;
            $g = $x;
            $b = 0;
        } else if ($h < 120) {
            $r = $x;
            $g = $c;
            $b = 0;
        } else if ($h < 180) {
            $r = 0;
            $g = $c;
            $b = $x;
        } else if ($h < 240) {
            $r = 0;
            $g = $x;
            $b = $c;
        } else if ($h < 300) {
            $r = $x;
            $g = 0;
            $b = $c;
        } else {
            $r = $c;
            $g = 0;
            $b = $x;
        }

        $r = ($r + $m) * 255;
        $g = ($g + $m) * 255;
        $b = ($b + $m) * 255;

        return array('r' => ceil($r), 'g' => ceil($g), 'b' => ceil($b));
    }

    /**
     * Convert rgb array colour to hex string
     *
     * @param array $rgb
     * @return string
     */
    protected static function _convertRgbToHex($rgb)
    {
        $r = dechex($rgb['r']);
        If (strlen($r)<2)
            $r='0'.$r;

        $g = dechex($rgb['g']);
        If (strlen($g)<2)
            $g='0'.$g;

        $b = dechex($rgb['b']);
        If (strlen($b)<2)
            $b='0'.$b;

        return '#' . $r . $g . $b;
    }

    /**
     * Creates an array of rgb values from the HTML representation.
     *
     * @param string $color May either be a hexidecimal number of the form
     *    #rrggbb or one of the 140 well-known names (black, white, blue, etc.)
     * @return array|null Null in case of name not understood
     */
    protected static function _convertHtmlToRgb($color)
    {
        $pattern = '/^#([A-Fa-f0-9]{2})([A-Fa-f0-9]{2})([A-Fa-f0-9]{2})$/';
        if (preg_match($pattern, $color, $matches)) {
            $r = (hexdec($matches[1]));
            $g = (hexdec($matches[2]));
            $b = (hexdec($matches[3]));
            return array('r' => $r, 'g' => $g, 'b' => $b);
        } else {
            return self::_namedColor($color);
        }
    }

    /**
     * Returns components from named color
     * Based on Zend_Pdf_Color_Html
     *
     * @param string $color One of the 140 well-known color names (black, white,
     *    blue, etc.)
     * @return array
     */
    protected static function _namedColor($color)
    {
        switch (strtolower($color)) {
            case 'aqua':
                $r = 0.0;   $g = 1.0;   $b = 1.0;   break;
            case 'black':
                $r = 0.0;   $g = 0.0;   $b = 0.0;   break;
            case 'blue':
                $r = 0.0;   $g = 0.0;   $b = 1.0;   break;
            case 'fuchsia':
                $r = 1.0;   $g = 0.0;   $b = 1.0;   break;
            case 'gray':
                $r = 0.502; $g = 0.502; $b = 0.502; break;
            case 'green':
                $r = 0.0;   $g = 0.502; $b = 0.0;   break;
            case 'lime':
                $r = 0.0;   $g = 1.0;   $b = 0.0;   break;
            case 'maroon':
                $r = 0.502; $g = 0.0;   $b = 0.0;   break;
            case 'navy':
                $r = 0.0;   $g = 0.0;   $b = 0.502; break;
            case 'olive':
                $r = 0.502; $g = 0.502; $b = 0.0;   break;
            case 'purple':
                $r = 0.502; $g = 0.0;   $b = 0.502; break;
            case 'red':
                $r = 1.0;   $g = 0.0;   $b = 0.0;   break;
            case 'silver':
                $r = 0.753; $g = 0.753; $b = 0.753; break;
            case 'teal':
                $r = 0.0;   $g = 0.502; $b = 0.502; break;
            case 'white':
                $r = 1.0;   $g = 1.0;   $b = 1.0;   break;
            case 'yellow':
                $r = 1.0;   $g = 1.0;   $b = 0.0;   break;

            case 'aliceblue':
                $r = 0.941; $g = 0.973; $b = 1.0;   break;
            case 'antiquewhite':
                $r = 0.980; $g = 0.922; $b = 0.843; break;
            case 'aquamarine':
                $r = 0.498; $g = 1.0;   $b = 0.831; break;
            case 'azure':
                $r = 0.941; $g = 1.0;   $b = 1.0;   break;
            case 'beige':
                $r = 0.961; $g = 0.961; $b = 0.863; break;
            case 'bisque':
                $r = 1.0;   $g = 0.894; $b = 0.769; break;
            case 'blanchedalmond':
                $r = 1.0;   $g = 1.0;   $b = 0.804; break;
            case 'blueviolet':
                $r = 0.541; $g = 0.169; $b = 0.886; break;
            case 'brown':
                $r = 0.647; $g = 0.165; $b = 0.165; break;
            case 'burlywood':
                $r = 0.871; $g = 0.722; $b = 0.529; break;
            case 'cadetblue':
                $r = 0.373; $g = 0.620; $b = 0.627; break;
            case 'chartreuse':
                $r = 0.498; $g = 1.0;   $b = 0.0;   break;
            case 'chocolate':
                $r = 0.824; $g = 0.412; $b = 0.118; break;
            case 'coral':
                $r = 1.0;   $g = 0.498; $b = 0.314; break;
            case 'cornflowerblue':
                $r = 0.392; $g = 0.584; $b = 0.929; break;
            case 'cornsilk':
                $r = 1.0;   $g = 0.973; $b = 0.863; break;
            case 'crimson':
                $r = 0.863; $g = 0.078; $b = 0.235; break;
            case 'cyan':
                $r = 0.0;   $g = 1.0;   $b = 1.0;   break;
            case 'darkblue':
                $r = 0.0;   $g = 0.0;   $b = 0.545; break;
            case 'darkcyan':
                $r = 0.0;   $g = 0.545; $b = 0.545; break;
            case 'darkgoldenrod':
                $r = 0.722; $g = 0.525; $b = 0.043; break;
            case 'darkgray':
                $r = 0.663; $g = 0.663; $b = 0.663; break;
            case 'darkgreen':
                $r = 0.0;   $g = 0.392; $b = 0.0;   break;
            case 'darkkhaki':
                $r = 0.741; $g = 0.718; $b = 0.420; break;
            case 'darkmagenta':
                $r = 0.545; $g = 0.0;   $b = 0.545; break;
            case 'darkolivegreen':
                $r = 0.333; $g = 0.420; $b = 0.184; break;
            case 'darkorange':
                $r = 1.0;   $g = 0.549; $b = 0.0;   break;
            case 'darkorchid':
                $r = 0.6;   $g = 0.196; $b = 0.8;   break;
            case 'darkred':
                $r = 0.545; $g = 0.0;   $b = 0.0;   break;
            case 'darksalmon':
                $r = 0.914; $g = 0.588; $b = 0.478; break;
            case 'darkseagreen':
                $r = 0.561; $g = 0.737; $b = 0.561; break;
            case 'darkslateblue':
                $r = 0.282; $g = 0.239; $b = 0.545; break;
            case 'darkslategray':
                $r = 0.184; $g = 0.310; $b = 0.310; break;
            case 'darkturquoise':
                $r = 0.0;   $g = 0.808; $b = 0.820; break;
            case 'darkviolet':
                $r = 0.580; $g = 0.0;   $b = 0.827; break;
            case 'deeppink':
                $r = 1.0;   $g = 0.078; $b = 0.576; break;
            case 'deepskyblue':
                $r = 0.0;   $g = 0.749; $b = 1.0;   break;
            case 'dimgray':
                $r = 0.412; $g = 0.412; $b = 0.412; break;
            case 'dodgerblue':
                $r = 0.118; $g = 0.565; $b = 1.0;   break;
            case 'firebrick':
                $r = 0.698; $g = 0.133; $b = 0.133; break;
            case 'floralwhite':
                $r = 1.0;   $g = 0.980; $b = 0.941; break;
            case 'forestgreen':
                $r = 0.133; $g = 0.545; $b = 0.133; break;
            case 'gainsboro':
                $r = 0.863; $g = 0.863; $b = 0.863; break;
            case 'ghostwhite':
                $r = 0.973; $g = 0.973; $b = 1.0;   break;
            case 'gold':
                $r = 1.0;   $g = 0.843; $b = 0.0;   break;
            case 'goldenrod':
                $r = 0.855; $g = 0.647; $b = 0.125; break;
            case 'greenyellow':
                $r = 0.678; $g = 1.0;   $b = 0.184; break;
            case 'honeydew':
                $r = 0.941; $g = 1.0;   $b = 0.941; break;
            case 'hotpink':
                $r = 1.0;   $g = 0.412; $b = 0.706; break;
            case 'indianred':
                $r = 0.804; $g = 0.361; $b = 0.361; break;
            case 'indigo':
                $r = 0.294; $g = 0.0;   $b = 0.510; break;
            case 'ivory':
                $r = 1.0;   $g = 0.941; $b = 0.941; break;
            case 'khaki':
                $r = 0.941; $g = 0.902; $b = 0.549; break;
            case 'lavender':
                $r = 0.902; $g = 0.902; $b = 0.980; break;
            case 'lavenderblush':
                $r = 1.0;   $g = 0.941; $b = 0.961; break;
            case 'lawngreen':
                $r = 0.486; $g = 0.988; $b = 0.0;   break;
            case 'lemonchiffon':
                $r = 1.0;   $g = 0.980; $b = 0.804; break;
            case 'lightblue':
                $r = 0.678; $g = 0.847; $b = 0.902; break;
            case 'lightcoral':
                $r = 0.941; $g = 0.502; $b = 0.502; break;
            case 'lightcyan':
                $r = 0.878; $g = 1.0;   $b = 1.0;   break;
            case 'lightgoldenrodyellow':
                $r = 0.980; $g = 0.980; $b = 0.824; break;
            case 'lightgreen':
                $r = 0.565; $g = 0.933; $b = 0.565; break;
            case 'lightgrey':
                $r = 0.827; $g = 0.827; $b = 0.827; break;
            case 'lightpink':
                $r = 1.0;   $g = 0.714; $b = 0.757; break;
            case 'lightsalmon':
                $r = 1.0;   $g = 0.627; $b = 0.478; break;
            case 'lightseagreen':
                $r = 0.125; $g = 0.698; $b = 0.667; break;
            case 'lightskyblue':
                $r = 0.529; $g = 0.808; $b = 0.980; break;
            case 'lightslategray':
                $r = 0.467; $g = 0.533; $b = 0.6;   break;
            case 'lightsteelblue':
                $r = 0.690; $g = 0.769; $b = 0.871; break;
            case 'lightyellow':
                $r = 1.0;   $g = 1.0;   $b = 0.878; break;
            case 'limegreen':
                $r = 0.196; $g = 0.804; $b = 0.196; break;
            case 'linen':
                $r = 0.980; $g = 0.941; $b = 0.902; break;
            case 'magenta':
                $r = 1.0;   $g = 0.0;   $b = 1.0;   break;
            case 'mediumaquamarine':
                $r = 0.4;   $g = 0.804; $b = 0.667; break;
            case 'mediumblue':
                $r = 0.0;   $g = 0.0;   $b = 0.804; break;
            case 'mediumorchid':
                $r = 0.729; $g = 0.333; $b = 0.827; break;
            case 'mediumpurple':
                $r = 0.576; $g = 0.439; $b = 0.859; break;
            case 'mediumseagreen':
                $r = 0.235; $g = 0.702; $b = 0.443; break;
            case 'mediumslateblue':
                $r = 0.482; $g = 0.408; $b = 0.933; break;
            case 'mediumspringgreen':
                $r = 0.0;   $g = 0.980; $b = 0.604; break;
            case 'mediumturquoise':
                $r = 0.282; $g = 0.820; $b = 0.8;   break;
            case 'mediumvioletred':
                $r = 0.780; $g = 0.082; $b = 0.522; break;
            case 'midnightblue':
                $r = 0.098; $g = 0.098; $b = 0.439; break;
            case 'mintcream':
                $r = 0.961; $g = 1.0;   $b = 0.980; break;
            case 'mistyrose':
                $r = 1.0;   $g = 0.894; $b = 0.882; break;
            case 'moccasin':
                $r = 1.0;   $g = 0.894; $b = 0.710; break;
            case 'navajowhite':
                $r = 1.0;   $g = 0.871; $b = 0.678; break;
            case 'oldlace':
                $r = 0.992; $g = 0.961; $b = 0.902; break;
            case 'olivedrab':
                $r = 0.420; $g = 0.557; $b = 0.137; break;
            case 'orange':
                $r = 1.0;   $g = 0.647; $b = 0.0;   break;
            case 'orangered':
                $r = 1.0;   $g = 0.271; $b = 0.0;   break;
            case 'orchid':
                $r = 0.855; $g = 0.439; $b = 0.839; break;
            case 'palegoldenrod':
                $r = 0.933; $g = 0.910; $b = 0.667; break;
            case 'palegreen':
                $r = 0.596; $g = 0.984; $b = 0.596; break;
            case 'paleturquoise':
                $r = 0.686; $g = 0.933; $b = 0.933; break;
            case 'palevioletred':
                $r = 0.859; $g = 0.439; $b = 0.576; break;
            case 'papayawhip':
                $r = 1.0;   $g = 0.937; $b = 0.835; break;
            case 'peachpuff':
                $r = 1.0;   $g = 0.937; $b = 0.835; break;
            case 'peru':
                $r = 0.804; $g = 0.522; $b = 0.247; break;
            case 'pink':
                $r = 1.0;   $g = 0.753; $b = 0.796; break;
            case 'plum':
                $r = 0.867; $g = 0.627; $b = 0.867; break;
            case 'powderblue':
                $r = 0.690; $g = 0.878; $b = 0.902; break;
            case 'rosybrown':
                $r = 0.737; $g = 0.561; $b = 0.561; break;
            case 'royalblue':
                $r = 0.255; $g = 0.412; $b = 0.882; break;
            case 'saddlebrown':
                $r = 0.545; $g = 0.271; $b = 0.075; break;
            case 'salmon':
                $r = 0.980; $g = 0.502; $b = 0.447; break;
            case 'sandybrown':
                $r = 0.957; $g = 0.643; $b = 0.376; break;
            case 'seagreen':
                $r = 0.180; $g = 0.545; $b = 0.341; break;
            case 'seashell':
                $r = 1.0;   $g = 0.961; $b = 0.933; break;
            case 'sienna':
                $r = 0.627; $g = 0.322; $b = 0.176; break;
            case 'skyblue':
                $r = 0.529; $g = 0.808; $b = 0.922; break;
            case 'slateblue':
                $r = 0.416; $g = 0.353; $b = 0.804; break;
            case 'slategray':
                $r = 0.439; $g = 0.502; $b = 0.565; break;
            case 'snow':
                $r = 1.0;   $g = 0.980; $b = 0.980; break;
            case 'springgreen':
                $r = 0.0;   $g = 1.0;   $b = 0.498; break;
            case 'steelblue':
                $r = 0.275; $g = 0.510; $b = 0.706; break;
            case 'tan':
                $r = 0.824; $g = 0.706; $b = 0.549; break;
            case 'thistle':
                $r = 0.847; $g = 0.749; $b = 0.847; break;
            case 'tomato':
                $r = 0.992; $g = 0.388; $b = 0.278; break;
            case 'turquoise':
                $r = 0.251; $g = 0.878; $b = 0.816; break;
            case 'violet':
                $r = 0.933; $g = 0.510; $b = 0.933; break;
            case 'wheat':
                $r = 0.961; $g = 0.871; $b = 0.702; break;
            case 'whitesmoke':
                $r = 0.961; $g = 0.961; $b = 0.961; break;
            case 'yellowgreen':
                $r = 0.604; $g = 0.804; $b = 0.196; break;
            default:
                return null;
        }
        return array('r' => $r * 255, 'g' => $g * 255, 'b' => $b * 255);
    }

}

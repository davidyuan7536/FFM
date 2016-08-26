<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsModifier
 */

/**
 * Smarty string_format modifier plugin
 *
 * Type:     modifier<br>
 * Name:     string_format<br>
 * Purpose:  format strings via sprintf
 *
 * @link http://smarty.php.net/manual/en/language.modifier.string.format.php string_format (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com>
 * @param string $string input string
 * @param string $format format string
 * @return string formatted string
 */
function smarty_modifier_bites_format($bites) {
    if ($bites < 1000) return $bites . ' b';
    elseif ($bites < 1000000) return round($bites / 1000, 2) . ' Kb';
    elseif ($bites < 1000000000) return round($bites / 1000000, 2) . ' Mb';
    elseif ($bites < 1000000000000) return round($bites / 1000000000, 2) . ' Gb';
    else return round($bites / 1000000000000, 2) . ' Tb';
}

?>

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
function smarty_modifier_time_format($seconds) {
    $sign = (($seconds < 0) ? '-' : '');
    $seconds = abs($seconds);
    $contentSeconds = round((($seconds / 60) - floor($seconds / 60)) * 60);
    $contentMinutes = floor($seconds / 60);
    if ($contentSeconds >= 60) {
        $contentSeconds -= 60;
        $contentMinutes++;
    }
    return $sign . intval($contentMinutes) . ':' . str_pad($contentSeconds, 2, 0, STR_PAD_LEFT);
}

?>

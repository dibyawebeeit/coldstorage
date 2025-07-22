<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_setting')) {
    function get_setting()
    {
        $CI =& get_instance(); // Get the CodeIgniter instance
        $CI->load->database(); // Load database

        $setting = $CI->db->get('settings')->row_array();
      
        return $setting;
    }
}

if (!function_exists('format_float')) {
    function format_float($number, $decimals = 2)
    {
        return number_format((float)$number, $decimals, '.', '');
    }
}

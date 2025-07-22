<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_user_details')) {
    function get_user_details($user_id)
    {
        $CI =& get_instance(); // Get the CodeIgniter instance
        $CI->load->database(); // Load database
        $CI->load->model('Model_users'); // Load User model
      
        return $CI->Model_users->getUserById($user_id);
    }
}

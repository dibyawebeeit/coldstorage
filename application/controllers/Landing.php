<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Landing extends Admin_Controller 
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_auth');
	}

	/* 
		Check if the login form is submitted, and validates the user credential
		If not submitted it redirects to the login page
	*/
	public function index()
	{

		$this->load->view('landing/index');

	}


}

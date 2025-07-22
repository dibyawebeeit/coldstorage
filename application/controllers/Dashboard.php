<?php 

class Dashboard extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Dashboard';
		
	
		$this->load->model('model_users');
		$this->load->model('model_dashboard');
		
	}

	/* 
	* It only redirects to the manage category page
	* It passes the total product, total paid orders, total users, and total stores information
	into the frontend.
	*/
	public function index()
	{
		
		$this->data['total_users'] =  $this->model_dashboard->countTotalUsers();
		/**contacts */
		$this->data['total_contacts'] =  $this->model_dashboard->get_total_contacts();
		$this->data['today_total_contacts'] =  $this->model_dashboard->get_total_contacts('today');
		$this->data['current_month_contacts'] = $this->model_dashboard->get_total_contacts('current_month');
		$this->data['last_month_contacts'] =  $this->model_dashboard->get_total_contacts('last_month');
		$this->data['this_year_contacts'] =  $this->model_dashboard->get_total_contacts('this_year');
		/**contacts */

		



		$this->data['current_month_total_users'] =  $this->no_format($this->model_dashboard->countTotalUsers());
		$this->data['last_month_total_users'] =  $this->no_format($this->model_dashboard->countTotalUsers());
		$this->data['this_year_total_users'] =  $this->no_format($this->model_dashboard->countTotalUsers());

		$this->data['total_categories'] =  $this->model_dashboard->countTotalCategories();
		$this->data['current_month_total_paid_income'] =  $this->no_format($this->model_dashboard->get_paid_total_income('current_month'));
		$this->data['current_month_total_income'] =  $this->no_format($this->model_dashboard->get_total_income('current_month'));
		$this->data['current_month_unpaid_income'] =  $this->no_format($this->model_dashboard->get_total_income('current_month')-$this->model_dashboard->get_paid_total_income('current_month'));


		$this->data['current_month_total_stock_stored'] =  $this->no_format($this->model_dashboard->get_total_stock_stored('current_month'));
		$this->data['current_month_total_stock_out'] =  $this->no_format($this->model_dashboard->get_total_stock_out('current_month'));
		$this->data['current_month_stock_in'] =  $this->no_format($this->data['current_month_total_stock_stored']-$this->data['current_month_total_stock_out']);


	
		$this->data['current_month_expenses'] =  $this->no_format($this->model_dashboard->get_total_expense('current_month'));
		$this->data['last_month_expenses'] =  $this->no_format($this->model_dashboard->get_total_expense('last_month'));
		$this->data['this_year_expenses'] =  $this->no_format($this->model_dashboard->get_total_expense('this_year'));


		$this->data['current_month_service'] =  $this->model_dashboard->get_upcomming_service('current_month');
        $this->data['last_month_service'] =  $this->model_dashboard->get_upcomming_service('last_month');
        $this->data['this_year_service'] =  $this->model_dashboard->get_upcomming_service('this_year');
      
      
		$this->data['current_month_renewal'] =  $this->model_dashboard->get_upcomming_renewal('current_month');
        $this->data['last_month_renewal'] =  $this->model_dashboard->get_upcomming_renewal('last_month');
        $this->data['this_year_renewal'] =  $this->model_dashboard->get_upcomming_renewal('this_year');


		$this->data['total_stock_in_till_date'] =  $this->no_format($this->model_dashboard->get_total_stock_stored());
		$this->data['total_stock_out_till_date'] =  $this->no_format($this->model_dashboard->get_total_stock_out());
		$this->data['total_storage_till_date'] =  $this->no_format($this->data['total_stock_in_till_date']-$this->data['total_stock_out_till_date']);

		$config_info = $this->model_dashboard->getSettings();
      
        $storage_capacity = $this->model_dashboard->get_storage_capacity();
		$this->data['storage_capacity'] =   $storage_capacity;
        //$this->data['storage_capacity'] = $this->no_format($config_info['total_capacity']);

		$this->data['available_storage_capacity'] =  $this->no_format($storage_capacity -$this->data['total_storage_till_date']);
		


		

	
	

		$user_id = $this->session->userdata('id');
		$is_admin = ($user_id == 1) ? true :false;

		$this->data['is_admin'] = $is_admin;
		$this->render_template('dashboard', $this->data);
	}

	public function no_format($number)
	{
		$formattedNumber = number_format($number, 2, '.', '');
		return $formattedNumber;
	}
}
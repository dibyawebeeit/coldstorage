<?php 
class Model_dashboard extends CI_Model
{
	protected $table="units";

	public $active_user_id;

	public function __construct()
	{
		parent::__construct();

		if ($this->session->userdata('logged_in')) {
			$this->active_user_id = $this->session->userdata('id');
		} else {
			$this->active_user_id = null;
		}
	}

	public function getSettings()
	{
		$sql = "SELECT * FROM settings";
		$query = $this->db->query($sql);
		return $query->row_array();
	}

	public function countTotalUsers()
	{
		$sql = "SELECT * FROM users";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	public function countTotalCategories()
	{
		$sql = "SELECT * FROM categories WHERE data_status = 'A'";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}


	public function get_total_contacts($filter=null) {

			$this->db->select('id');
			$this->db->where('data_status','A');
			$this->db->from('contacts');
			 switch($filter) {
				case 'today':
					$this->db->where('MONTH(created_at)', date('m'));
					$this->db->where('DAY(created_at)', date('d'));
					break;
				case 'current_month':
					$this->db->where('MONTH(created_at)', date('m'));
					$this->db->where('YEAR(created_at)', date('Y'));
					break;
				case 'last_month':
					$this->db->where('MONTH(created_at)', 'MONTH(CURRENT_DATE - INTERVAL 1 MONTH)', false);
					$this->db->where('YEAR(created_at)', 'YEAR(CURRENT_DATE - INTERVAL 1 MONTH)', false);
					break;
				case 'this_year':
					$this->db->where('YEAR(created_at)', date('Y'));
					break;
        	}
			
			$result = $this->db->get()->num_rows();
			return $result ? $result : 0;
    }

	public function get_total_stock_stored($filter=null) {

			 $this->db->select('SUM(stock) AS total_value');
			 $this->db->where('data_status','A');
			 switch($filter) {
				case 'today':
					$this->db->where('MONTH(created_at)', date('m'));
					$this->db->where('DAY(created_at)', date('d'));
					break;
				case 'current_month':
					$this->db->where('MONTH(created_at)', date('m'));
					$this->db->where('YEAR(created_at)', date('Y'));
					break;
				case 'last_month':
					$this->db->where('MONTH(created_at)', 'MONTH(CURRENT_DATE - INTERVAL 1 MONTH)', false);
					$this->db->where('YEAR(created_at)', 'YEAR(CURRENT_DATE - INTERVAL 1 MONTH)', false);
					break;
				case 'this_year':
					$this->db->where('YEAR(created_at)', date('Y'));
					break;
        	}
			$this->db->from('checkin_items');
			$query = $this->db->get();
			$result = $query->row();
			return $result->total_value ? $result->total_value : 0;
    }


	public function get_total_stock_out($filter = null) {

			 $this->db->select('SUM(stock) AS total_value');
			 $this->db->where('data_status','A');
			 switch($filter) {
				case 'today':
					$this->db->where('MONTH(created_at)', date('m'));
					$this->db->where('DAY(created_at)', date('d'));
					break;
				case 'current_month':
					$this->db->where('MONTH(created_at)', date('m'));
					$this->db->where('YEAR(created_at)', date('Y'));
					break;
				case 'last_month':
					$this->db->where('MONTH(created_at)', 'MONTH(CURRENT_DATE - INTERVAL 1 MONTH)', false);
					$this->db->where('YEAR(created_at)', 'YEAR(CURRENT_DATE - INTERVAL 1 MONTH)', false);
					break;
				case 'this_year':
					$this->db->where('YEAR(created_at)', date('Y'));
					break;
        	}
			$this->db->from('adjust_items');
			$query = $this->db->get();
			$result = $query->row();
			return $result->total_value ? $result->total_value : 0;
    }


	public function get_total_income($filter) {

			 $this->db->select('SUM(price * stock) AS total_value');
			 $this->db->where('data_status','A');
			 switch($filter) {
				case 'today':
					$this->db->where('MONTH(created_at)', date('m'));
					$this->db->where('DAY(created_at)', date('d'));
					break;
				case 'current_month':
					$this->db->where('MONTH(created_at)', date('m'));
					$this->db->where('YEAR(created_at)', date('Y'));
					break;
				case 'last_month':
					$this->db->where('MONTH(created_at)', 'MONTH(CURRENT_DATE - INTERVAL 1 MONTH)', false);
					$this->db->where('YEAR(created_at)', 'YEAR(CURRENT_DATE - INTERVAL 1 MONTH)', false);
					break;
				case 'this_year':
					$this->db->where('YEAR(created_at)', date('Y'));
					break;
        	}
			$this->db->from('checkin_items');
			$query = $this->db->get();
			$result = $query->row();
			return $result->total_value ? $result->total_value : 0;
    }



	public function get_paid_total_income($filter) {
        $this->db->select_sum('amount');
		//$this->db->where('data_status','A');
        $this->db->from('payments');
        
        switch($filter) {
			case 'today':
				$this->db->where('MONTH(created_at)', date('m'));
				$this->db->where('DAY(created_at)', date('d'));
				break;
            case 'current_month':
                $this->db->where('MONTH(created_at)', date('m'));
                $this->db->where('YEAR(created_at)', date('Y'));
                break;
            case 'last_month':
                $this->db->where('MONTH(created_at)', 'MONTH(CURRENT_DATE - INTERVAL 1 MONTH)', false);
                $this->db->where('YEAR(created_at)', 'YEAR(CURRENT_DATE - INTERVAL 1 MONTH)', false);
                break;
            case 'this_year':
                $this->db->where('YEAR(created_at)', date('Y'));
                break;
        }
        $result = $this->db->get()->row();
        return $result->amount ? $result->amount : 0;
    }


	public function get_paid_total_expense($filter) {

        $this->db->select_sum('amount');
		//$this->db->where('data_status','A');
        $this->db->from('expense');
        
        switch($filter) {
			case 'today':
				$this->db->where('MONTH(created_at)', date('m'));
				$this->db->where('DAY(created_at)', date('d'));
				break;
            case 'current_month':
                $this->db->where('MONTH(created_at)', date('m'));
                $this->db->where('YEAR(created_at)', date('Y'));
                break;
            case 'last_month':
                $this->db->where('MONTH(created_at)', 'MONTH(CURRENT_DATE - INTERVAL 1 MONTH)', false);
                $this->db->where('YEAR(created_at)', 'YEAR(CURRENT_DATE - INTERVAL 1 MONTH)', false);
                break;
            case 'this_year':
                $this->db->where('YEAR(created_at)', date('Y'));
                break;
        }
        $result = $this->db->get()->row();
        return $result->amount ? $result->amount : 0;

    }




	public function get_expense_reports() {
        $reports = array();
        
		  switch($filter) {
				case 'current_month':
					// This month
					$first_day = date('Y-m-01');
					$last_day = date('Y-m-t');
					$this->db->where('from_date >=', $first_day);
					$this->db->where('to_date <=', $last_day);
					$reports['this_month'] = $this->db->get('expense')->result();
					break;
				case 'last_month':
				
					// Last month
					$first_day = date('Y-m-01', strtotime('first day of last month'));
					$last_day = date('Y-m-t', strtotime('last day of last month'));
					$this->db->where('from_date >=', $first_day);
					$this->db->where('to_date <=', $last_day);
					$reports['last_month'] = $this->db->get('expense')->result();
					break;
			
				case 'this_year':
					// This year
					$first_day = date('Y-01-01');
					$last_day = date('Y-12-31');
					$this->db->where('from_date >=', $first_day);
					$this->db->where('to_date <=', $last_day);
					$reports['this_year'] = $this->db->get('expense')->result();
					break;
		 }
       
        
        return $reports;
    }


	 public function get_total_expense($filter) {
        $this->db->select_sum('amount');
        $this->db->where('data_status','A');
        switch($filter) {
            case 'current_month':
                $this->db->where('from_date >=', date('Y-m-01'));
                $this->db->where('to_date <=', date('Y-m-t'));
                break;
                
            case 'last_month':
                $this->db->where('from_date >=', date('Y-m-01', strtotime('first day of last month')));
                $this->db->where('to_date <=', date('Y-m-t', strtotime('last day of last month')));
                break;
                
            case 'this_year':
                $this->db->where('from_date >=', date('Y-01-01'));
                $this->db->where('to_date <=', date('Y-12-31'));
                break;
                
            default:
                // Optionally handle invalid period
                return 0;
        }
        
        $query = $this->db->get('expense');
        $result = $query->row();
		//echo $str = $this->db->last_query();
        return $result->amount ? $result->amount : 0;
    }


	public function get_upcomming_service($filter) {
		
		$machine_count = $this->get_service_count_by_table('machines','service_type_id', $filter);
		$compliance_count = $this->get_service_count_by_table('compliences','compliences_heads_id', $filter);

		return $machine_count + $compliance_count;
	}

	public function get_service_count_by_table($table ,$field , $filter) {
        $this->db->select('id');
		$this->db->where('data_status','A');
		$this->db->where($field,1);
        $this->db->from($table);
        
        switch($filter) {
			case 'today':
				$this->db->where('MONTH(service_date)', date('m'));
				$this->db->where('DAY(service_date)', date('d'));
				break;
            case 'current_month':
                $this->db->where('MONTH(service_date)', date('m'));
                $this->db->where('YEAR(service_date)', date('Y'));
                break;
            case 'last_month':
                $this->db->where('MONTH(service_date)', 'MONTH(CURRENT_DATE - INTERVAL 1 MONTH)', false);
                $this->db->where('YEAR(service_date)', 'YEAR(CURRENT_DATE - INTERVAL 1 MONTH)', false);
                break;
            case 'this_year':
                $this->db->where('YEAR(service_date)', date('Y'));
                break;
        }

        $result = $this->db->get()->num_rows();
        return $result ? $result : 0;
    }

	public function get_upcomming_renewal($filter) {
		
		$machine_count = $this->get_renewal_count_by_table('machines','service_type_id', $filter);
		$compliance_count = $this->get_renewal_count_by_table('compliences','compliences_heads_id', $filter);

		return $machine_count + $compliance_count;
	}

	public function get_renewal_count_by_table($table ,$field, $filter) {
        $this->db->select('id');
		$this->db->where('data_status','A');
		$this->db->where($field,2);
        $this->db->from($table);
        
        switch($filter) {
			case 'today':
				$this->db->where('MONTH(service_date)', date('m'));
				$this->db->where('DAY(service_date)', date('d'));
				break;
            case 'current_month':
                $this->db->where('MONTH(service_date)', date('m'));
                $this->db->where('YEAR(service_date)', date('Y'));
                break;
            case 'last_month':
                $this->db->where('MONTH(service_date)', 'MONTH(CURRENT_DATE - INTERVAL 1 MONTH)', false);
                $this->db->where('YEAR(service_date)', 'YEAR(CURRENT_DATE - INTERVAL 1 MONTH)', false);
                break;
            case 'this_year':
                $this->db->where('YEAR(service_date)', date('Y'));
                break;
        }
        $result = $this->db->get()->num_rows();
        return $result ? $result : 0;
    }
  
   public function get_storage_capacity()
   {
   		$storage_capacity_value = $this->db->select_sum('capacity')->where('data_status','A')->get('chambers')->row_array();
        $storage_capacity = (float) ($storage_capacity_value['capacity'] ?? 0);
        $storage_capacity = number_format($storage_capacity, 2, '.', '');
        return $storage_capacity;
   }
}
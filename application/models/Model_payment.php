<?php
class Model_payment extends CI_Model
{
	protected $table="contacts";
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
	/* get active brand infromation */

	public function get_by_id($id)
	{
		$this->db->select('*');
		$this->db->where('id', $id);
		$query = $this->db->get('payments');
		return $query->row_array();
	}
	
	/* get the brand data */
	public function getData($id = null)
	{
		if ($id) {
       		return $this->db->where('id', $id)->get($this->table)->row_array();
    	}
    	return $this->db->where('data_status','A')->get($this->table)->result_array();
	}


	public function getDataList($data=[])
	{
		$sql = "SELECT p.*,cnt.name as customer_name,cnt.email as customer_email,cnt.phone as customer_phone
		,cnt.phone as customer_phone FROM `payments` p
        
        LEFT JOIN  `checkouts` chout ON chout.unique_id = p.checkout_id 
		LEFT JOIN  `checkins` cin ON cin.unique_id = chout.checkin_id 
		LEFT JOIN  `contacts` cnt ON cnt.id = cin.contact_id 
		WHERE 1 ";


		if(!empty($data['filter_q'])){
			$sql.=' AND (
				p.checkout_id LIKE "%'.$data['filter_q'].'%" 
				OR cnt.email LIKE "%'.$data['filter_q'].'%" 
				OR cnt.phone LIKE "%'.$data['filter_q'].'%" 
				OR cnt.name LIKE "%'.$data['filter_q'].'%" 
			) ';
		}

		

		if(!empty($data['filter_contact_id'])){
			$sql.=' AND cin.contact_id = "'.$data['filter_contact_id'].'"  ';
		}

	
		if($data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (p.payment_date  BETWEEN '".$data['filter_from_date']."' AND '".$data['filter_to_date']."')";
		}else if($data['filter_from_date'] && !$data['filter_to_date']){
			$sql.= " AND (p.payment_date  BETWEEN '".$data['filter_from_date']."' AND '".$data['filter_from_date']."')";
		}else if(!$data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (p.payment_date  BETWEEN '".$data['filter_to_date']."' AND '".$data['filter_to_date']."')";
		}


		$sql.=' ORDER BY p.id DESC ';

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getDataListTotal($data=[])
	{
		$sql = "SELECT p.id FROM `payments` p
		LEFT JOIN  `checkouts` chout ON chout.unique_id = p.checkout_id 
		LEFT JOIN  `checkins` cin ON cin.unique_id = chout.checkin_id 
		LEFT JOIN  `contacts` cnt ON cnt.id = cin.contact_id 
		WHERE 1 ";


		if(!empty($data['filter_q'])){
			$sql.=' AND (
				p.checkout_id LIKE "%'.$data['filter_q'].'%" 
				OR cnt.email LIKE "%'.$data['filter_q'].'%" 
				OR cnt.phone LIKE "%'.$data['filter_q'].'%" 
				OR cnt.name LIKE "%'.$data['filter_q'].'%" 
			) ';
		}

		

		if(!empty($data['filter_contact_id'])){
			$sql.=' AND cin.contact_id = "'.$data['filter_contact_id'].'"  ';
		}

	
		if($data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (p.payment_date  BETWEEN '".$data['filter_from_date']."' AND '".$data['filter_to_date']."')";
		}else if($data['filter_from_date'] && !$data['filter_to_date']){
			$sql.= " AND (p.payment_date  BETWEEN '".$data['filter_from_date']."' AND '".$data['filter_from_date']."')";
		}else if(!$data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (p.payment_date  BETWEEN '".$data['filter_to_date']."' AND '".$data['filter_to_date']."')";
		}


		$query = $this->db->query($sql);
		return $num = $query->num_rows();
		
	}



	public function getExportDataList($data=[])
	{
		$sql = "SELECT p.*,cnt.name as customer_name,cnt.email as customer_email,cnt.phone as customer_phone
		,cnt.phone as customer_phone FROM `payments` p
		LEFT JOIN  `checkouts` chout ON chout.unique_id = p.checkout_id 
		LEFT JOIN  `checkins` cin ON cin.unique_id = chout.checkin_id 
		LEFT JOIN  `contacts` cnt ON cnt.id = cin.contact_id 
		WHERE 1 ";


		if(!empty($data['filter_q'])){
			$sql.=' AND (
				p.checkout_id LIKE "%'.$data['filter_q'].'%" 
				OR cnt.email LIKE "%'.$data['filter_q'].'%" 
				OR cnt.phone LIKE "%'.$data['filter_q'].'%" 
				OR cnt.name LIKE "%'.$data['filter_q'].'%" 
			) ';
		}

		

		if(!empty($data['filter_contact_id'])){
			$sql.=' AND cin.contact_id = "'.$data['filter_contact_id'].'"  ';
		}

	
		if($data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (p.payment_date  BETWEEN '".$data['filter_from_date']."' AND '".$data['filter_to_date']."')";
		}else if($data['filter_from_date'] && !$data['filter_to_date']){
			$sql.= " AND (p.payment_date  BETWEEN '".$data['filter_from_date']."' AND '".$data['filter_from_date']."')";
		}else if(!$data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (p.payment_date  BETWEEN '".$data['filter_to_date']."' AND '".$data['filter_to_date']."')";
		}


		$sql.=' ORDER BY p.id DESC ';
		$query = $this->db->query($sql);
		return $query->result_array();
	}
}
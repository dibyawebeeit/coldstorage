<?php 

class Model_report extends CI_Model
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
		$sql = "SELECT ai.*,cnt.name as customer_name,cnt.email as customer_email,cnt.phone as customer_phone
		,cnt.phone as customer_phone ,itm.name as item_name,un.code as unit_code ,un.name as unit_name,chm.name as chamber_name,rk.name as rack_name
		
		FROM `adjust_items` ai
		LEFT JOIN  `checkin_items` ci ON ci.id = ai.checkin_item_id 
        LEFT JOIN  `checkins` cin ON cin.unique_id = ci.checkin_id  
		LEFT JOIN  `racks` rk ON rk.id = ai.rack_id
		LEFT JOIN  `chambers` chm ON chm.id = ai.chamber_id
		
		LEFT JOIN  `items` itm ON itm.id = ci.item_id 
		LEFT JOIN  `contacts` cnt ON cnt.id = cin.contact_id 
		LEFT JOIN  `units` un ON un.id = itm.unit_id 
		
		WHERE ai.data_status = 'A'";



		if(!empty($data['filter_q'])){
			$sql.=' AND (
				ai.checkin_id LIKE "%'.$data['filter_q'].'%" 
				OR cnt.email LIKE "%'.$data['filter_q'].'%" 
				OR cnt.phone LIKE "%'.$data['filter_q'].'%" 
				OR cnt.name LIKE "%'.$data['name'].'%" 
			) ';
		}

		if(!empty($data['filter_checkout_type'])){
			$sql.=' AND ai.checkout_type = "'.$data['filter_checkout_type'].'"  ';
		}

		
		if(!empty($data['filter_contact_id'])){
			$sql.=' AND cin.contact_id = "'.$data['filter_contact_id'].'"  ';
		}

		

		if($data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (ai.date  BETWEEN '".$data['filter_from_date']."' AND '".$data['filter_to_date']."') ";
		}else if($data['filter_from_date'] && !$data['filter_to_date']){
			$sql.= " AND (ai.date  BETWEEN '".$data['filter_from_date']."' AND '".$data['filter_from_date']."')";
		}else if(!$data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (ai.date  BETWEEN '".$data['filter_to_date']."' AND '".$data['filter_to_date']."')";
		}


		$sql.=' ORDER BY ai.id DESC ';

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
		$sql = "SELECT ai.id FROM `adjust_items` ai
		LEFT JOIN  `checkin_items` ci ON ci.id = ai.checkin_item_id 
        LEFT JOIN  `checkins` cin ON cin.unique_id = ci.checkin_id  
		LEFT JOIN  `racks` rk ON rk.id = ai.rack_id
		LEFT JOIN  `chambers` chm ON chm.id = ai.chamber_id
		
		LEFT JOIN  `items` itm ON itm.id = ci.item_id 
		LEFT JOIN  `contacts` cnt ON cnt.id = cin.contact_id 
		LEFT JOIN  `units` un ON un.id = itm.unit_id 
		
		WHERE ai.data_status = 'A'";


		if(!empty($data['filter_q'])){
			$sql.=' AND (
				ai.checkin_id LIKE "%'.$data['filter_q'].'%" 
				OR cnt.email LIKE "%'.$data['filter_q'].'%" 
				OR cnt.phone LIKE "%'.$data['filter_q'].'%" 
				OR cnt.name LIKE "%'.$data['name'].'%" 
			) ';
		}

		if(!empty($data['filter_checkout_type'])){
			$sql.=' AND ai.checkout_type = "'.$data['filter_checkout_type'].'"  ';
		}

		if(!empty($data['filter_contact_id'])){
			$sql.=' AND cin.contact_id = "'.$data['filter_contact_id'].'"  ';
		}



		
		if($data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (ai.date  BETWEEN '".$data['filter_from_date']."' AND '".$data['filter_to_date']."') ";
		}else if($data['filter_from_date'] && !$data['filter_to_date']){
			$sql.= " AND (ai.date  BETWEEN '".$data['filter_from_date']."' AND '".$data['filter_from_date']."')";
		}else if(!$data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (ai.date  BETWEEN '".$data['filter_to_date']."' AND '".$data['filter_to_date']."')";
		}



		$query = $this->db->query($sql);
		return $num = $query->num_rows();
		
	}



	public function getExportDataList($data=[])
	{
		$sql = "SELECT ai.*,cnt.name as customer_name,cnt.email as customer_email,cnt.phone as customer_phone
		,cnt.phone as customer_phone ,itm.name as item_name ,un.name as unit_name,chm.name as chamber_name,rk.name as rack_name
		
		FROM `adjust_items` ai
		LEFT JOIN  `checkin_items` ci ON ci.id = ai.checkin_item_id 
        LEFT JOIN  `checkins` cin ON cin.unique_id = ci.checkin_id  
		LEFT JOIN  `racks` rk ON rk.id = ai.rack_id
		LEFT JOIN  `chambers` chm ON chm.id = ai.chamber_id
		
		LEFT JOIN  `items` itm ON itm.id = ci.item_id 
		LEFT JOIN  `contacts` cnt ON cnt.id = cin.contact_id 
		LEFT JOIN  `units` un ON un.id = itm.unit_id 
		
		WHERE ai.data_status = 'A'";



		if(!empty($data['filter_q'])){
			$sql.=' AND (
				ai.checkin_id LIKE "%'.$data['filter_q'].'%" 
				OR cnt.email LIKE "%'.$data['filter_q'].'%" 
				OR cnt.phone LIKE "%'.$data['filter_q'].'%" 
				OR cnt.name LIKE "%'.$data['name'].'%" 
			) ';
		}

		if(!empty($data['filter_checkout_type'])){
			$sql.=' AND ai.checkout_type = "'.$data['filter_checkout_type'].'"  ';
		}

		
		if(!empty($data['filter_contact_id'])){
			$sql.=' AND cin.contact_id = "'.$data['filter_contact_id'].'"  ';
		}

		

		if($data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (ai.date  BETWEEN '".$data['filter_from_date']."' AND '".$data['filter_to_date']."') ";
		}else if($data['filter_from_date'] && !$data['filter_to_date']){
			$sql.= " AND (ai.date  BETWEEN '".$data['filter_from_date']."' AND '".$data['filter_from_date']."')";
		}else if(!$data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (ai.date  BETWEEN '".$data['filter_to_date']."' AND '".$data['filter_to_date']."')";
		}



		$sql.=' ORDER BY ai.id DESC ';

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




	
}
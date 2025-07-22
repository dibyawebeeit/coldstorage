<?php 

class Model_expense extends CI_Model
{
	protected $table="expense";
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


	

		if($id) {
			$this->db->select('expense.*,expense_heads.name as expense_heads_name');
			$this->db->from('expense');
			$this->db->join('expense_heads', 'expense_heads.id = expense.expense_heads_id', 'left');
			$this->db->where('expense.id',$id);
			$this->db->where('expense.data_status','A');
			return $this->db->get()->row_array();
       		
    	}

		$this->db->select('expense.*,expense_heads.name as expense_heads_name');
		$this->db->from('expense');
		$this->db->join('expense_heads', 'expense_heads.id = expense.expense_heads_id', 'left');
		$this->db->where('expense.data_status','A');
		$this->db->order_by('expense.id', 'DESC');
		return $this->db->get()->result_array();
		
	}

	public function getDataList($data=[])
	{
		$sql = "SELECT expn.*,expnh.name as expense_heads_name FROM `expense` expn
		LEFT JOIN  `expense_heads` expnh ON expnh.id = expn.expense_heads_id 
		WHERE expn.data_status = 'A' ";

		if(!empty($data['filter_expense_heads_id'])){
			$sql.=' AND expn.expense_heads_id = "'.$data['filter_expense_heads_id'].'"  ';
		}

		if($data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (expn.from_date >= '".$data['filter_from_date']."' AND expn.to_date <= '".$data['filter_to_date']."') ";
		}else if($data['filter_from_date'] && !$data['filter_to_date']){
			$sql.= " AND (expn.from_date  BETWEEN '".$data['filter_from_date']."' AND '".$data['filter_from_date']."')";
		}else if(!$data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (expn.to_date  BETWEEN '".$data['filter_to_date']."' AND '".$data['filter_to_date']."')";
		}


		$sql.=' ORDER BY expn.id DESC ';

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
		$sql = "SELECT expn.id FROM `expense` expn
		LEFT JOIN  `expense_heads` expnh ON expnh.id = expn.expense_heads_id 
		WHERE expn.data_status = 'A' ";

		if(!empty($data['filter_expense_heads_id'])){
			$sql.=' AND expn.expense_heads_id = "'.$data['filter_expense_heads_id'].'"  ';
		}


		if($data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (expn.from_date >= '".$data['filter_from_date']."' AND expn.to_date <= '".$data['filter_to_date']."') ";
		}else if($data['filter_from_date'] && !$data['filter_to_date']){
			$sql.= " AND (expn.from_date  BETWEEN '".$data['filter_from_date']."' AND '".$data['filter_from_date']."')";
		}else if(!$data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (expn.to_date  BETWEEN '".$data['filter_to_date']."' AND '".$data['filter_to_date']."')";
		}

		$query = $this->db->query($sql);
		return $num = $query->num_rows();
	}


	public function getExportDataList($data=[])
	{
      
     
		$sql = "SELECT expn.*,expnh.name as expense_heads_name FROM `expense` expn
		LEFT JOIN  `expense_heads` expnh ON expnh.id = expn.expense_heads_id 
		WHERE expn.data_status = 'A' ";


		if(!empty($data['filter_expense_heads_id'])){
			$sql.=' AND expn.expense_heads_id = "'.$data['filter_expense_heads_id'].'"  ';
		}


		if($data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (expn.from_date >= '".$data['filter_from_date']."' AND expn.to_date <= '".$data['filter_to_date']."') ";
		}else if($data['filter_from_date'] && !$data['filter_to_date']){
			$sql.= " AND (expn.from_date  BETWEEN '".$data['filter_from_date']."' AND '".$data['filter_from_date']."')";
		}else if(!$data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (expn.to_date  BETWEEN '".$data['filter_to_date']."' AND '".$data['filter_to_date']."')";
		}

		$sql.=' ORDER BY expn.id DESC ';
      
    
		$query = $this->db->query($sql);
		return $query->result_array();
	}


	

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert($this->table, $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update($this->table, $data);
			return ($update == true) ? true : false;
		}
	}

	

	public function remove($id)
	{
		if($id) {
			$data =array(
				'deleted_by' => $this->active_user_id,
				'data_status' => 'D'
			);
			$this->db->where('id', $id);
			$delete = $this->db->update($this->table, $data);
			return ($delete == true) ? true : false;
		}
	}

}
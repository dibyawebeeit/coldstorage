<?php 

class Model_chamber extends CI_Model
{
	protected $table="chambers";

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
  
    public function getTotalUsedStock($id = null)
	{
		$used_capacity_value = $this->db->select_sum('avl_stock')->where('chamber_id',$id)->where('data_status','A')->get('checkin_items')->row_array();
        $used_capacity = (float) ($used_capacity_value['avl_stock'] ?? 0);
        return $used_capacity;
	}

}
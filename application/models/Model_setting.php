<?php 

class Model_setting extends CI_Model
{
    protected $table="settings";

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

	public function getData() 
	{
		$query = $this->db->get($this->table);
		return $query->row_array();
	}

	

	public function update($data = array())
	{
		$this->db->where('id', 1);
		$update = $this->db->update($this->table, $data);
			
		return ($update == true) ? true : false;	
	}

	
	
}
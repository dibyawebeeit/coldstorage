<?php 
class Model_item extends CI_Model
{
	protected $table="items";

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

	public function getCheckinItemList($id = null)
	{
		$this->db->select('checkin_items.stock, checkin_items.price ,racks.name as rack,chambers.name as chamber, items.name as item_name, units.code as unit_code, contacts.name as contact_name, checkins.date as checkin_date');
		$this->db->from('checkin_items');
		$this->db->join('racks', 'racks.id = checkin_items.rack_id', 'left');
		$this->db->join('chambers', 'chambers.id = checkin_items.chamber_id', 'left');
		$this->db->join('items', 'items.id = checkin_items.item_id', 'left');
		$this->db->join('units', 'units.id = items.unit_id', 'left');
		$this->db->join('checkins', 'checkins.unique_id = checkin_items.checkin_id', 'left');
		$this->db->join('contacts', 'contacts.id = checkins.contact_id', 'left');
		$this->db->where('checkin_items.item_id',$id);
		$this->db->where('checkin_items.data_status','A');
		$this->db->order_by('checkin_items.id', 'DESC');
		$results = $this->db->get()->result_array();
      
        return $results;
	}
	

	/* get the brand data */
	public function getData($id = null)
	{
		if ($id) {
       		return $this->db->where('id', $id)->get($this->table)->row_array();
    	}

		$this->db->select('items.*,categories.name as category_name,units.name as unit_name');
		$this->db->from($this->table);
		$this->db->join('units', 'units.id = items.unit_id', 'left');
		$this->db->join('categories', 'categories.id = items.category_id', 'left');
		$this->db->where('items.data_status','A');
		$this->db->order_by('items.name', 'DESC');
		$results = $this->db->get()->result_array();
      
        return $results;
		
    	//return $this->db->where('data_status','A')->get($this->table)->result_array();
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
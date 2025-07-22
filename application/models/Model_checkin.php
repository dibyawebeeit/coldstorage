<?php 

class Model_checkin extends CI_Model
{
	protected $table="checkins";

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

	public function getContactList()
    {
        return $this->db->where('data_status','A')->order_by('id', 'DESC')->get('contacts')->result_array();
    }

    public function getChamberList()
    {
        return $this->db->where('data_status','A')->get('chambers')->result_array();
    }

    public function getRackList($chamberId)
    {
        return $this->db->where('data_status','A')->where('chamber_id',$chamberId)->get('racks')->result_array();
    }

    public function getContactById($id)
    {
        $count = $this->db->where('id',$id)->get('contacts')->num_rows();
        if($count>0)
        {
            return $this->db->where('id',$id)->get('contacts')->row_array();
        }
        return null;
    }

    public function getChamberById($id)
    {
        $count = $this->db->where('id',$id)->get('chambers')->num_rows();
        if($count>0)
        {
            return $this->db->where('id',$id)->get('chambers')->row_array();
        }
        return null;
    }

    public function getRackById($id)
    {
        $count = $this->db->where('id',$id)->get('racks')->num_rows();
        if($count>0)
        {
            return $this->db->where('id',$id)->get('racks')->row_array();
        }
        return null;
    }

    

    public function insert_checkin_item($data) {
         $this->db->insert('checkin_items', $data);
         return $this->db->insert_id(); 
    }

    public function insert_adjust_item($data) {
        return $this->db->insert('adjust_items', $data);
    }
    

    public function search_items($query) {
        $this->db->select('id, name');
        $this->db->where('data_status', 'A');
        $this->db->like('name', $query);
        $this->db->limit(10);
        $query = $this->db->get('items');
        return $query->result_array();
    }

    public function get_item($id) {
        $this->db->select('items.id, items.name, units.name as unit, items.price');
        $this->db->from('items');
        $this->db->join('units', 'units.id = items.unit_id', 'left');
        $this->db->where('items.id', $id);
        return $this->db->get()->row_array();
    }

    public function getCheckinItemListById($id)
    {
        $this->db->select('checkin_items.*, items.name as item_name, units.name as unit_name, units.code as unit_code, chambers.name as chamber, racks.name as rack');
        $this->db->from('checkin_items');
        $this->db->join('items', 'items.id = checkin_items.item_id', 'left');
        $this->db->join('units', 'units.id = items.unit_id', 'left');
        $this->db->join('chambers', 'chambers.id = checkin_items.chamber_id', 'left');
        $this->db->join('racks', 'racks.id = checkin_items.rack_id', 'left');
        $this->db->where('checkin_items.checkin_id', $id);
        $this->db->where('checkin_items.data_status', 'A');
        return $this->db->get()->result_array();
    }

    public function getCheckinDetails($id = null)
	{
		if ($id) {
            $this->db->select('checkins.id, checkins.unique_id, checkins.date, contacts.name as contact');
            $this->db->from('checkins');
            $this->db->join('contacts', 'contacts.id = checkins.contact_id', 'left');
            $this->db->where('checkins.unique_id', $id);
            return $this->db->get()->row_array();
    	}
    	return null;
	}

    public function removeCheckinItem($id)
	{
		if($id) {
			$data =array(
				'deleted_by' => $this->active_user_id,
				'data_status' => 'D'
			);
			$this->db->where('id', $id);
			$delete = $this->db->update('checkin_items', $data);
			return ($delete == true) ? true : false;
		}
	}

    
    public function fetchCheckinItemById($id = null)
	{
		if ($id) {
            $value = $this->db->where('id', $id)->get('checkin_items')->row_array();

            $this->db->select('items.name as name,items.id as item_id,  units.name as unit_name, units.code as unit_code');
            $this->db->from('items');
            $this->db->join('units', 'units.id = items.unit_id', 'left');
            $this->db->where('items.id', $value['item_id']);
            return $this->db->get()->row_array();

       		// return $this->db->where('id', $value['item_id'])->get('items')->row_array();
    	}
    	return null;
	}
    
    public function adjustItem($data,$id)
	{
		if($data) {
            $newRow = $this->db->select('price,chamber_id,rack_id')->where('id',$id)->get('checkin_items')->row_array();
            $data['price'] = $newRow['price'];
            $data['chamber_id'] = $newRow['chamber_id'];
            $data['rack_id'] = $newRow['rack_id'];
			$insert = $this->db->insert('adjust_items', $data);

            // $result = $this->db->where('id',$id)->get('checkin_items')->row_array();
            // if($data['checkout_type'] == 'checkin')
            // {
            //     //add stock
            //     $stock = $result['stock'];
            //     $adjust_stock = $stock + $data['stock'];
            // }
            // if($data['checkout_type'] == 'checkout' || $data['checkout_type'] == 'damage')
            // {
            //     //substract stock
            //     $stock = $result['stock'];
            //     $adjust_stock = $stock - $data['stock'];
            // }
            // $newArr = array(
            //     'stock' => $adjust_stock
            // );
            // $this->db->where('id', $id);
            // $this->db->update('checkin_items',$newArr);


			return ($insert == true) ? true : false;
		}
	}
    

	public function getData($id = null)
	{
		if ($id) {
       		return $this->db->where('id', $id)->get($this->table)->row_array();
    	}
    	return $this->db->where('data_status','A')->order_by('id','desc')->get($this->table)->result_array();
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert($this->table, $data);
			return ($insert == true) ? true : false;
		}
	}
    
    public function paymentCreate($data)
	{
		if($data) {
			$insert = $this->db->insert('payments', $data);

            $newArr=array(
                'payment_status' => 'paid'
            );
            $this->db->where('unique_id',$data['checkin_id']);
            $this->db->update($this->table, $newArr);
            
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
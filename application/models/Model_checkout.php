<?php 

class Model_checkout extends CI_Model
{
	protected $table="checkouts";

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

    public function insert_checkout($data) {
        $this->db->insert($this->table, $data); 
        return true;
    }

    public function insert_adjust_item($data) {
        return $this->db->insert('adjust_items', $data);
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

    public function getAmount($unique_id, $duration_in_days)
    {
        $amount = 0;
        $result = $this->db->select('stock,price')->where('checkout_type','checkout')->where('checkout_id',$unique_id)->get('adjust_items')->result_array();
        foreach ($result as $value) {
            $amount += (float)($value['stock']* $value['price']*$duration_in_days);
        }
        return format_float($amount);
    }

    public function getPaidAmount($unique_id)
    {
        $amount = (float)(0);
        $result = $this->db->select_sum('store_cost')->where('checkout_id',$unique_id)->get('payments')->row_array();
        if($result)
        {
            $amount = (float)($result['store_cost']);
        }
        return format_float($amount);
    }

    public function modifyCheckoutAmount($unique_id,$data)
    {
        $this->db->where('unique_id',$unique_id)->update($this->table,$data);
        return true;
    }


    public function getData($id = null)
	{
		if ($id) {
       		return $this->db->where('id', $id)->get($this->table)->row_array();
    	}
    	return $this->db->where('data_status','A')->order_by('id','desc')->get($this->table)->result_array();
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
            $this->db->select('checkins.id, checkins.unique_id, checkins.date, checkins.contact_id, contacts.name as contact');
            $this->db->from('checkins');
            $this->db->join('contacts', 'contacts.id = checkins.contact_id', 'left');
            $this->db->where('checkins.unique_id', $id);
            return $this->db->get()->row_array();
    	}
    	return null;
	}

    public function getCheckoutDetails($id = null)
	{
		if ($id) {
            $this->db->select('checkouts.*, contacts.name as name, contacts.email as email, contacts.phone as phone');
            $this->db->from('checkouts');
            $this->db->join('contacts', 'contacts.id = checkouts.contact_id', 'left');
            $this->db->where('checkouts.unique_id', $id);
            return $this->db->get()->row_array();
    	}
    	return null;
	}

    public function getCheckoutItemListById($id)
    {
        $this->db->select('adjust_items.*, items.name as item_name, units.name as unit_name, units.code as unit_code, chambers.name as chamber, racks.name as rack');
        $this->db->from('adjust_items');
        $this->db->join('items', 'items.id = adjust_items.item_id', 'left');
        $this->db->join('units', 'units.id = items.unit_id', 'left');
        $this->db->join('chambers', 'chambers.id = adjust_items.chamber_id', 'left');
        $this->db->join('racks', 'racks.id = adjust_items.rack_id', 'left');
        $this->db->where('adjust_items.checkout_id', $id);
        $this->db->where('adjust_items.data_status', 'A');
        return $this->db->get()->result_array();
    }

    public function getCheckinItemDetailsById($id = null)
	{
		if ($id) {
            $this->db->select('checkin_items.*');
            $this->db->from('checkin_items');
            $this->db->where('checkin_items.id', $id);
            return $this->db->get()->row_array();
    	}
    	return null;
	}

    public function update_stock($data, $id)
    {
        $this->db->where('id',$id);
        $this->db->update('checkin_items',$data);
        return true;
    }

    
    public function paymentCreate($data,$deductionData)
	{
		if($data) {
			$insert = $this->db->insert('payments', $data);

            if($insert) {
                $payment_id = $this->db->insert_id();
                // Add common metadata to each deduction row
                foreach ($deductionData as &$deduction) {
                    $deduction['payment_id'] = $payment_id;
                    $deduction['checkout_id'] = $data['checkout_id'];
                    $deduction['payment_date'] = $data['payment_date'];
                    $deduction['added_by'] = $data['added_by'];
                }

                // Now insert all deductions in batch (if any)
                if (!empty($deductionData)) {
                    $this->db->insert_batch('deductions', $deductionData);
                }
                
            }

            $checkoutQuery = $this->db->where('unique_id',$data['checkout_id'])->get($this->table)->row_array();
            $bill_amount = $checkoutQuery['bill_amount'];
            $paid_amount = format_float($checkoutQuery['paid_amount'] + $data['store_cost']);
            $deduction_amount = format_float($checkoutQuery['deduction_amount'] + $data['deduction_cost']);
            if($bill_amount == $paid_amount)
            {
                $payment_status = 'paid';
            }
            else
            {
                $payment_status = 'pending';
            }

            $newArr=array(
                'paid_amount' => $paid_amount,
                'deduction_amount' => $deduction_amount,
                'payment_status' => $payment_status
            );
            $this->db->where('unique_id',$data['checkout_id']);
            $this->db->update($this->table, $newArr);
            
			return ($insert == true) ? true : false;
		}
	}

	

}
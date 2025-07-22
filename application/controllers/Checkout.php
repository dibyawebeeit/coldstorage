<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends Admin_Controller 
{
	public $active_user_id;
	
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->data['page_title'] = 'Stock Out';
		$this->load->model('model_checkout');
		if ($this->session->userdata('logged_in')) {
			$this->active_user_id = $this->session->userdata('id');
		} else {
			$this->active_user_id = null;
		}

	}

	public function index()
	{
		if(!in_array('viewCheckout', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->render_template('checkout/index', $this->data);	
	}	

    public function create()
	{
		if(!in_array('createCheckout', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
        if($this->session->userdata('checkout_contact_id'))
        {
			$searchData = $this->session->userdata('searchData');
			if(strlen($searchData)!=10)
			{
				//direct checkin id
				$this->data['checkinList'] = $this->db->where('unique_id',$searchData)->where('data_status','A')->get('checkins')->result_array();
			}
			else
			{
				//mobile
				$checkout_contact_id = $this->session->userdata('checkout_contact_id');
				$this->data['checkinList'] = $this->db->where('contact_id',$checkout_contact_id)->where('data_status','A')->get('checkins')->result_array();
			}
			
			
        }

		if($this->session->userdata('selected_checkin_id'))
        {
            $checkin_id = $this->session->userdata('selected_checkin_id');
            $this->data['checkin_item_list'] = $this->model_checkout->getCheckinItemListById($checkin_id);
			
        }

		

		$this->render_template('checkout/create', $this->data);	
	}

    public function validate_checkin_id()
    {
        // Get raw input and decode JSON
        $input = json_decode(trim(file_get_contents("php://input")), true);

        if (!isset($input['checkin_id']) || empty($input['checkin_id'])) {
            echo json_encode(['status' => 'error', 'message' => 'Missing Checkin ID / Mobile No']);
            return;
        }

		$checkin_id = $input['checkin_id'];

		$length = strlen($input['checkin_id']);
		if($length == 10)
		{
			//mobile no
			$customerCount = $this->db->where('phone', $checkin_id)->get('contacts')->num_rows();
			if($customerCount > 0)
			{
				$customerDetails = $this->db->where('phone', $checkin_id)->get('contacts')->row_array();
				$contact_id = $customerDetails['id'];

				$this->session->set_userdata('checkout_contact_id',$contact_id);
				$this->session->set_userdata('searchData',$checkin_id);
				echo json_encode(['status' => 'success', 'message' => 'Validated ID']);
				return;
			}
			else
			{
				echo json_encode(['status' => 'error', 'message' => 'Invalid Mobile No']);
            	return;
			}
		}
		else
		{
			//checkin id
			$exists = $this->db->where('unique_id', $checkin_id)->get('checkins')->num_rows();
			if ($exists > 0) {
				$checkinDetails = $this->db->where('unique_id', $checkin_id)->get('checkins')->row_array();
			    $contact_id = $checkinDetails['contact_id'];

				$this->session->set_userdata('checkout_contact_id',$contact_id);
				$this->session->set_userdata('searchData',$checkin_id);
				echo json_encode(['status' => 'success', 'message' => 'Validated ID']);
				return;
			} else {
				echo json_encode(['status' => 'error', 'message' => 'Invalid Checkin ID']);
				return;
			}

			
		}

       
        
    }

	public function proceedCheckinId()
	{
		// Get raw input and decode JSON
        $input = json_decode(trim(file_get_contents("php://input")), true);
		$checkin_id = $input['checkin_id'];
		$this->session->set_userdata('selected_checkin_id',$checkin_id);

		echo json_encode(['status' => 'success', 'message' => 'Validated ID']);
		return;
	}

    public function reset_checkin_id()
    {
        $this->session->unset_userdata('checkout_contact_id');
        $this->session->unset_userdata('searchData');
        $this->session->unset_userdata('selected_checkin_id');
        redirect('checkout/create');
    }


    public function checkout_submit()
	{
		if(!in_array('createCheckout', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$response = array();

		$selected_items = $this->input->post('selected_items'); // array of checked IDs
    	$checkout_qty   = $this->input->post('checkout_qty');   // array keyed by item_id

		$checkin_id = $this->session->userdata('selected_checkin_id');
		$unique_id = date('ymdhis');
		$checkinDetails = $this->model_checkout->getCheckinDetails($checkin_id);

		$date1 = new DateTime($checkinDetails['date']);
		$date2 = new DateTime(date('Y-m-d'));
		$interval = $date1->diff($date2);
		$duration_in_days = 1 + $interval->days;

		// if($duration_in_days == 0)
		// {
		// 	$duration_in_days = 1;
		// }


		if (!empty($selected_items)) {

			
			$checkout_data = [
				'unique_id' => $unique_id,
				'checkin_id' => $checkin_id,
				'checkin_date' => $checkinDetails['date'],
				'checkout_date' => date('Y-m-d'),
				'contact_id' => $checkinDetails['contact_id'],
				'duration_in_days' => $duration_in_days,
				'added_by' => $this->active_user_id,
			];
			$create = $this->model_checkout->insert_checkout($checkout_data);
			if($create == true) {

				foreach ($selected_items as $item_id) {
					$qty = isset($checkout_qty[$item_id]) ? $checkout_qty[$item_id] : 0;

					$getCheckinItemDetailsById = $this->model_checkout->getCheckinItemDetailsById($item_id);
					$avl_stock = $getCheckinItemDetailsById['avl_stock'];

					if ($qty > 0) {
						$adjustItemData=array(
							'checkin_id' => $checkin_id,
							'checkout_id' => $unique_id,
							'date' => date('Y-m-d'),
							'item_id' => $getCheckinItemDetailsById['item_id'],
							'checkin_item_id' => $item_id,
							'chamber_id' => $getCheckinItemDetailsById['chamber_id'],
							'rack_id' => $getCheckinItemDetailsById['rack_id'],
							'stock' => $qty,
							'price' => $getCheckinItemDetailsById['price'],
							'checkout_type' => 'checkout',
							'added_by' => $this->active_user_id,	
						);
						$this->model_checkout->insert_adjust_item($adjustItemData);

						$current_avl_stock = (float)($avl_stock-$qty);
						$newDataArr=array(
							'avl_stock' => $current_avl_stock
						);
						$this->model_checkout->update_stock($newDataArr,$item_id);

					}
				}

				//update section
				$total_bill_amount = $this->model_checkout->getAmount($unique_id,$duration_in_days);
				$updateData=array(
					'bill_amount' => $total_bill_amount,
					'paid_amount' => 0.00
				);
				$this->model_checkout->modifyCheckoutAmount($unique_id,$updateData);


				//unset all data
				$this->session->unset_userdata('checkout_contact_id');
				$this->session->unset_userdata('searchData');
				$this->session->unset_userdata('selected_checkin_id');

				$response['success'] = true;
				$response['messages'] = 'You have checkout succesfully';
			}
			else {
				$response['success'] = false;
				$response['messages'] = 'Error in the database while creating checkout';			
			}

		} else {
			$response['success'] = false;
        	$response['messages'] = 'No items selected.';	
		}
		echo json_encode($response);
	}
	
	public function fetchData()
	{
		$result = array('data' => array());

		$setting = get_setting();
		$gst_percent = $setting['gst_percent'];

		$data = $this->model_checkout->getData();

		foreach ($data as $key => $value) {

			$total_amount = $this->model_checkout->getAmount($value['unique_id'],$value['duration_in_days']);

			$deduction_amount = format_float($value['deduction_amount']);
			$paid_amount = format_float($value['paid_amount']);
			$pending_amount = format_float($total_amount - $paid_amount);


			// button
			$buttons = '';

			if(in_array('updateCheckout', $this->permission)) {
				// $buttons .= '<button type="button" class="btn btn-default" onclick="editFunc('.$value['id'].')" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></button>';


				if($value['payment_status']=='pending')
				{
					$payment = ' <button type="button" class="btn btn-primary" onclick="paymentFunc(this)" data-toggle="modal" data-target="#paymentModal" data-id="'.$value['id'].'" data-checkoutid="'.$value['unique_id'].'" data-amount="'.$pending_amount.'" data-gst="'.$gst_percent.'">Pay Now</button>';
				}
				else
				{
					$payment = '<span class="label label-success">Paid</span>';
				}
			}

			// if(in_array('deleteCheckout', $this->permission)) {
			// 	$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			// }

			
				
			// $status = ($value['status'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

            $contact ='';
           
            $contactValue = $this->model_checkout->getContactById($value['contact_id']);
            if($contactValue != null)
            {
                $contact = $contactValue['name'];
            }

            $unique_id = "<a href='invoice/".$value['unique_id']."'>".$value['unique_id']."</a>";

			$result['data'][$key] = array(
				$key+1,
				$unique_id,
				$value['checkin_date'],
				$value['checkout_date'],
                $contact,
				'₹ '.$total_amount,
				'₹ '.$paid_amount,
				'₹ '.$deduction_amount,
				$payment,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	public function payment()
	{
		if(!in_array('updateCheckout', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

			$this->form_validation->set_rules('payment_date', 'Payment Date', 'trim|required');
            $this->form_validation->set_rules('store_cost', 'Store Cost', 'trim|required');
            $this->form_validation->set_rules('transportation_cost', 'Transportation Cost', 'trim|required');
            $this->form_validation->set_rules('payment_type', 'Payment Type', 'trim');

			$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

	        if ($this->form_validation->run() == TRUE) {

				//Deduction Section ##############################################3
				$reasons = $this->input->post('deduct_reson');   // array of reasons
				$amounts = $this->input->post('deduct_amount');  // array of amounts

				$deductionData = [];
				$total_deduction = 0;

				if (!empty($reasons) && !empty($amounts) && is_array($reasons) && is_array($amounts)) {
					$count = count($reasons);
					for ($i = 0; $i < $count; $i++) {
						$reason = trim($reasons[$i]);
						$amount = floatval($amounts[$i]);

						// Optional: Skip empty inputs
						if ($reason !== '' && $amount > 0) {
							$deductionData[] = [
								'reason' => $reason,
								'amount' => $amount
							];
							$total_deduction += $amount;
						}
					}
				}

	        	$data = array(
	        		'checkout_id' => $this->input->post('checkoutId'),
	        		'payment_date' => $this->input->post('payment_date'),
	        		'store_cost' => $this->input->post('store_cost'),
	        		'transportation_cost' => $this->input->post('transportation_cost'),
					'deduction_cost' => $total_deduction,
	        		'gst_amount' => $this->input->post('gst_amount'),
                    'amount' => $this->input->post('total_amount'),
                    'payment_type' => $this->input->post('payment_type'),
					'added_by' => $this->active_user_id,
	        	);

	        	$update = $this->model_checkout->paymentCreate($data,$deductionData);
	        	if($update == true) {
	        		$response['success'] = true;
	        		$response['messages'] = 'Payment created succesfully';
	        	}
	        	else {
	        		$response['success'] = false;
	        		$response['messages'] = 'Error in the database while adding information';			
	        	}
	        }
	        else {
	        	$response['success'] = false;
	        	foreach ($_POST as $key => $value) {
	        		$response['messages'][$key] = form_error($key);
	        	}
	        }
		

		echo json_encode($response);
	}



}
<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Checkin extends Admin_Controller 
{
	public $active_user_id;
	
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Stock In';

		$this->load->model('model_checkin');
		$this->load->model('model_contact');

		if ($this->session->userdata('logged_in')) {
			$this->active_user_id = $this->session->userdata('id');
		} else {
			$this->active_user_id = null;
		}

	}

	/* 
	* It only redirects to the manage category page
	*/
	public function index()
	{
		if(!in_array('viewCheckin', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
        $this->data['contactList'] = $this->model_checkin->getContactList();
        $this->data['chamberList'] = $this->model_checkin->getChamberList();

		$this->render_template('checkin/index', $this->data);	
	}	

	public function create()
	{
		if(!in_array('createCheckin', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
        $this->data['contactList'] = $this->model_checkin->getContactList();
        $this->data['chamberList'] = $this->model_checkin->getChamberList();

		$this->render_template('checkin/create', $this->data);	
	}

	public function store()
	{
		if(!in_array('createCheckin', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

            $unique_id = date('ymdhis');
        	$data = array(
        		'unique_id' => $unique_id,
        		'date' => date('Y-m-d'),
        		'contact_id' => $this->input->post('customer_id'),
        		'added_by' => $this->active_user_id,	
        	);

            $item_ids = $this->input->post('item_id');
            $stocks = $this->input->post('stock');
			$chambers = $this->input->post('chamber_id');
        	$racks = $this->input->post('rack_id');

        	$create = $this->model_checkin->create($data);
        	if($create == true) {

                // insert item 
                if (is_array($item_ids) && !empty($item_ids)) {
                    foreach ($item_ids as $index => $item_id) {

                        $stock = isset($stocks[$index]) ? $stocks[$index] : 0;
                        $chamber = isset($chambers[$index]) ? $chambers[$index] : 0;
                        $rack = isset($racks[$index]) ? $racks[$index] : 0;

                        // Optional: skip empty or zero values
                        if ((float)$stock > 0 && (float)$chamber > 0 && (float)$rack > 0) {

                            $itemDetails = $this->model_checkin->get_item($item_id);
                            $price = $itemDetails['price'];

                            $data = [
                                'checkin_id' => $unique_id,
                                'item_id' => $item_id,
                                'chamber_id' => $chamber,
                                'rack_id' => $rack,
                                'stock' => $stock,
                                'avl_stock' => $stock,
                                'price' => $price,
                                'added_by' => $this->active_user_id,	
                            ];

                            $checkinItem = $this->model_checkin->insert_checkin_item($data);

							// $adjustItemData=array(
							// 	'checkin_id' => $unique_id,
							// 	'date' => date('Y-m-d'),
							// 	'item_id' => $item_id,
							// 	'checkin_item_id' => $checkinItem,
							// 	'store_id' => $this->input->post('store_id'),
							// 	'stock' => $stock,
							// 	'price' => $price,
							// 	'checkout_type' => 'checkin',
							// 	'added_by' => $this->active_user_id,	
							// );

							// $this->model_checkin->insert_adjust_item($adjustItemData);


                        }
                    }
                }


        		$response['success'] = true;
        		$response['messages'] = 'Succesfully created';
        	}
        	else {
        		$response['success'] = false;
        		$response['messages'] = 'Error in the database while creating the brand information';			
        	}
        

        echo json_encode($response);
	}

	public function details($id)
    {
        if(!in_array('updateCheckin', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

        $this->data['checkinDetails'] = $this->model_checkin->getCheckinDetails($id);
        $this->data['itemList'] = $this->model_checkin->getCheckinItemListById($id);

		$this->render_template('checkin/details', $this->data);
    }

	public function fetchDataById($id) 
	{
		if($id) {
			$data = $this->model_checkin->getData($id);
			echo json_encode($data);
		}

		return false;
	}

	public function fetchCheckinItemById($id) 
	{
		if($id) {
			$data = $this->model_checkin->fetchCheckinItemById($id);
			echo json_encode($data);
		}

		return false;
	}

	public function fetchItem() {
        $query = $this->input->post('query');
        $results = $this->model_checkin->search_items($query);
        echo json_encode($results);
    }

    public function get_item_by_id() {
        $id = $this->input->post('id');
        $item = $this->model_checkin->get_item($id);
        echo json_encode($item);
    }

	public function getRackList()
	{
		$response = array();
		$chamberId = $this->input->post('chamberId');
        $data = $this->model_checkin->getRackList($chamberId);
		if($data)
		{
			$response['success'] = true;
			$response['data'] = $data;
		}
		else
		{
			$response['success'] = false;
		}

        echo json_encode($response);
	}

	public function removeCheckinItem()
	{
		if(!in_array('deleteCheckin', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		
		$id = $this->input->post('id');

		$response = array();
		if($id) {
			$delete = $this->model_checkin->removeCheckinItem($id);
			if($delete == true) {
				$response['success'] = true;
				$response['messages'] = "Successfully removed";	
			}
			else {
				$response['success'] = false;
				$response['messages'] = "Error in the database while removing the brand information";
			}
		}
		else {
			$response['success'] = false;
			$response['messages'] = "Refersh the page again!!";
		}

		echo json_encode($response);
	}

	public function adjustItem($id)
	{
		if(!in_array('createCheckin', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		$this->form_validation->set_rules('stock', 'Stock', 'trim|required');
		$this->form_validation->set_rules('checkout_type', 'Adjust', 'trim|required');

		$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

        if ($this->form_validation->run() == TRUE) {

        	$data = array(
        		'checkin_id' => $this->input->post('checkin_id'),
        		'item_id' => $this->input->post('item_id'),
        		'checkin_item_id' => $id,
        		'date' => date('Y-m-d'),
        		'stock' => $this->input->post('stock'),
        		'checkout_type' => $this->input->post('checkout_type'),
        		'added_by' => $this->active_user_id,	
        	);
	
        	$create = $this->model_checkin->adjustItem($data,$id);
        	if($create == true) {

        		$response['success'] = true;
        		$response['messages'] = 'Succesfully created';
        	}
        	else {
        		$response['success'] = false;
        		$response['messages'] = 'Error in the database while creating the brand information';			
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


	public function fetchData()
	{
		$result = array('data' => array());

		$data = $this->model_checkin->getData();

		foreach ($data as $key => $value) {

			// button
			$buttons = '';

			if(in_array('viewCheckin', $this->permission)) {
				$buttons .= '<a href="details/'.$value['unique_id'].'"><button type="button" class="btn btn-default"><i class="fa fa-eye"></i></button></a>';
			}

			if(in_array('updateCheckin', $this->permission)) {
				$buttons .= '<button type="button" class="btn btn-default" onclick="editFunc('.$value['id'].')" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></button>';
			}

			if(in_array('deleteCheckin', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			}

			
				
			// $status = ($value['status'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

            $contact ='';
           
            $contactValue = $this->model_checkin->getContactById($value['contact_id']);
            if($contactValue != null)
            {
                $contact = $contactValue['name'];
            }

            $unique_id = "<a href='details/".$value['unique_id']."'>".$value['unique_id']."</a>";

			$result['data'][$key] = array(
                $key+1,
				$unique_id,
				$value['date'],
                $contact,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	public function payment()
	{
		if(!in_array('updateCheckin', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

			$this->form_validation->set_rules('payment_date', 'Payment Date', 'trim|required');
            $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
            $this->form_validation->set_rules('payment_type', 'Payment Type', 'trim');

			$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

	        if ($this->form_validation->run() == TRUE) {
	        	$data = array(
	        		'checkin_id' => $this->input->post('checkinId'),
	        		'payment_date' => $this->input->post('payment_date'),
                    'amount' => $this->input->post('amount'),
                    'payment_type' => $this->input->post('payment_type'),
					'added_by' => $this->active_user_id,
	        	);

	        	$update = $this->model_checkin->paymentCreate($data);
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

	
	

	
	public function update($id)
	{

		if(!in_array('updateCheckin', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		if($id) {
            $this->form_validation->set_rules('edit_contact_id', 'Contact', 'trim|required');

			$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

	        if ($this->form_validation->run() == TRUE) {
	        	$data = array(
                    'contact_id' => $this->input->post('edit_contact_id'),
					'updated_by' => $this->active_user_id,
					'updated_at' => date('Y-m-d H:i:s'), // current datetime
	        	);

	        	$update = $this->model_checkin->update($data, $id);
	        	if($update == true) {
	        		$response['success'] = true;
	        		$response['messages'] = 'Succesfully updated';
	        	}
	        	else {
	        		$response['success'] = false;
	        		$response['messages'] = 'Error in the database while updated the brand information';			
	        	}
	        }
	        else {
	        	$response['success'] = false;
	        	foreach ($_POST as $key => $value) {
	        		$response['messages'][$key] = form_error($key);
	        	}
	        }
		}
		else {
			$response['success'] = false;
    		$response['messages'] = 'Error please refresh the page again!!';
		}

		echo json_encode($response);
	}

	/*
	* It removes the category information from the database 
	* and returns the json format operation messages
	*/
	public function remove()
	{
		if(!in_array('deleteCheckin', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		
		$id = $this->input->post('id');

		$response = array();
		if($id) {
			$delete = $this->model_checkin->remove($id);
			if($delete == true) {
				$response['success'] = true;
				$response['messages'] = "Successfully removed";	
			}
			else {
				$response['success'] = false;
				$response['messages'] = "Error in the database while removing the brand information";
			}
		}
		else {
			$response['success'] = false;
			$response['messages'] = "Refersh the page again!!";
		}

		echo json_encode($response);
	}


	//Customer Section

	public function checkCustomer() {
		$this->load->helper('security');

		$name = $this->input->post('name', true);
		$email = $this->input->post('email', true);
		$phone = $this->input->post('phone', true);
		$details = $this->input->post('details', true);

		// Basic validation
		if (empty($name) || empty($email) || empty($phone)) {
			echo json_encode(['success' => false, 'message' => 'Required fields are missing.']);
			return;
		}

		// Check for unique email or phone
		if (!$this->model_contact->is_unique_email_or_phone($email, $phone)) {
			echo json_encode(['success' => false, 'message' => 'Email or Phone already exists.']);
			return;
		}

		echo json_encode(['success' => true]);
	}
	public function addCustomer()
	{
		$this->load->helper('security');

		$name = $this->input->post('name', true);
		$email = $this->input->post('email', true);
		$phone = $this->input->post('phone', true);
		$details = $this->input->post('details', true);

		// Basic validation
		if (empty($name) || empty($email) || empty($phone)) {
			echo json_encode(['success' => false, 'message' => 'Required fields are missing.']);
			return;
		}

		// Check for unique email or phone
		if (!$this->model_contact->is_unique_email_or_phone($email, $phone)) {
			echo json_encode(['success' => false, 'message' => 'Email or Phone already exists.']);
			return;
		}

		$data = [
			'name'    => $name,
			'email'   => $email,
			'phone'   => $phone,
			'details' => $details,
			'added_by' => $this->active_user_id,
		];

		$inserted = $this->model_contact->create($data);

		if ($inserted) {
			$new_id = $this->db->insert_id(); // Get newly inserted ID
			$new_customer = $this->model_contact->get_by_id($new_id);

			echo json_encode([
				'success' => true,
				'customer' => $new_customer,
			]);
			// echo json_encode(['success' => true]);
		} else {
			echo json_encode(['success' => false, 'message' => 'Database error.']);
		}
	}

}
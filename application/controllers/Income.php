<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Income extends Admin_Controller 
{
	private $active_user_id;
    private $current_data_id;
	
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Income';

		$this->load->model('model_income');

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
		if(!in_array('viewIncome', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$this->render_template('income/index', $this->data);	
	}	

	/*
	* It checks if it gets the category id and retreives
	* the category information from the category model and 
	* returns the data into json format. 
	* This function is invoked from the view page.
	*/
	public function fetchDataById($id) 
	{
		if($id) {
			$data = $this->model_income->getData($id);
			echo json_encode($data);
		}

		return false;
	}

	/*
	* Fetches the category value from the category table 
	* this function is called from the datatable ajax function
	*/
	public function fetchData()
	{
		$result = array('data' => array());

		$data = $this->model_income->getData();

		foreach ($data as $key => $value) {

			// button
			$buttons = '';

			if(in_array('updateIncome', $this->permission)) {
				$buttons .= '<button type="button" class="btn btn-default" onclick="editFunc('.$value['id'].')" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></button>';
			}

			if(in_array('deleteIncome', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			}

			$file_link = "--";
			if(trim($value['receipt_file'])){
				$file_link = '<a href="'.base_url($value['receipt_file']).'" target="_blank">View / Download File</a>';
			}
				

			// $status = ($value['status'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

			$result['data'][$key] = array(
				$value['name'],
				$value['amount'],
				$value['from_date'],
				$value['to_date'],
				$file_link ,
				$value['details'],
				// $status,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}

	/*
	* Its checks the store form validation 
	* and if the validation is successfully then it inserts the data into the database 
	* and returns the json format operation messages
	*/
	public function create()
	{
		if(!in_array('createIncome', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		$this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('amount', 'Income Amount', 'trim|required');
		$this->form_validation->set_rules('from_date', 'From Date', 'trim|required');
		$this->form_validation->set_rules('to_date', 'To Date', 'trim|required');
		$this->form_validation->set_rules('details', 'Details', 'trim');

		$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');
		$receipt_file = $this->upload_file('reciept_file');

        if ($this->form_validation->run() == TRUE) {
        	$data = array(
        		'name' 			=> $this->input->post('name'),
        		'amount' 		=> $this->input->post('amount'),
				'receipt_file' 	=> $this->input->post('receipt_file'),
        		'from_date' 	=> $this->input->post('from_date'),
				'to_date' 		=> $this->input->post('to_date'),
        		'details' 		=> $this->input->post('details'),
        		'added_by' 		=> $this->active_user_id,	
        	);

			

        	$create = $this->model_income->create($data);
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

	/*
	* Its checks the store form validation 
	* and if the validation is successfully then it updates the data into the database 
	* and returns the json format operation messages
	*/
	public function update($id)
	{

		if(!in_array('updateIncome', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		if($id) {
            $this->current_data_id = $id;

			$this->form_validation->set_rules('edit_name', 'Name', 'trim|required');
            $this->form_validation->set_rules('edit_amount', 'Income Amount', 'trim|required');
			$this->form_validation->set_rules('edit_from_date', 'From Date ', 'trim|required');
			$this->form_validation->set_rules('edit_to_date', 'To Date ', 'trim|required');
			$this->form_validation->set_rules('edit_details', 'Details', 'trim');

			$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

	        if ($this->form_validation->run() == TRUE) {
	        	$data = array(
	        		'name' => $this->input->post('edit_name'),
	        		'amount' => $this->input->post('edit_amount'),
	        		'from_date' => $this->input->post('edit_from_date'),
					'to_date' => $this->input->post('edit_to_date'),
	        		'details' => $this->input->post('edit_details'),
					'updated_by' => $this->active_user_id,
					'updated_at' => date('Y-m-d H:i:s'), // current datetime
	        	);

	        	$update = $this->model_income->update($data, $id);

				if($_FILES['edit_reciept_file']['size'] > 0) {

					$upload_file = $this->upload_file('edit_reciept_file');
					$upload_file = array('receipt_file' => $upload_file);
					$this->model_income->update($upload_file,$id);
            	}

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

    public function amount_check($amount) {
        $this->db->where('amount', $amount);
        $this->db->where('id !=', $this->current_data_id); // Exclude current user
        $query = $this->db->get('incomes');

        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('amount_check', 'The {field} is already in use.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

	/*
	* It removes the category information from the database 
	* and returns the json format operation messages
	*/
	public function remove()
	{
		if(!in_array('deleteIncome', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		
		$id = $this->input->post('id');

		$response = array();
		if($id) {
			$delete = $this->model_income->remove($id);
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

	public function upload_file($field_name)
    {
    	// assets/images/logo
        $config['upload_path'] = 'uploads/reciept';
        $config['file_name'] =  uniqid();
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '1000';

        // $config['max_width']  = '1024';s
        // $config['max_height']  = '768';

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload($field_name))
        {
            $error = $this->upload->display_errors();
            return false;
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            $type = explode('.', $_FILES[$field_name]['name']);
            $type = $type[count($type) - 1];
            
            $path = $config['upload_path'].'/'.$config['file_name'].'.'.$type;
            return ($data == true) ? $path : false;            
        }
    }


}
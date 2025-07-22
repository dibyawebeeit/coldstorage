<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Unit extends Admin_Controller 
{
	private $active_user_id;
    private $current_data_id;
	
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Units';

		$this->load->model('model_unit');

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
		if(!in_array('viewUnit', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
        $this->data['baseUnit'] = $this->model_unit->getBaseUnit();

		$this->render_template('unit/index', $this->data);	
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
			$data = $this->model_unit->getData($id);
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

		$data = $this->model_unit->getData();

		foreach ($data as $key => $value) {

			// button
			$buttons = '';

			if(in_array('updateUnit', $this->permission)) {
				$buttons .= '<button type="button" class="btn btn-default" onclick="editFunc('.$value['id'].')" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></button>';
			}

			if(in_array('deleteUnit', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			}
				

			// $status = ($value['status'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

            $baseUnit ='';
            $formula = '';

            if($value['base_unit_id']!=0)
            {
                $baseUnitValue = $this->model_unit->getBaseUnit($value['base_unit_id']); 
                $baseUnit = $baseUnitValue['name'].'('.$baseUnitValue['code'].')';

                $formula = $baseUnitValue['name'].' ( '.$baseUnitValue['code'].' ) * '.$value['operation_value'] .' = '.$value['name'].' ( '.$value['code'].' )';
            }
            

            
            

			$result['data'][$key] = array(
                $key+1,
				$value['name'],
				$value['code'],
				$baseUnit,
				$formula,
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
		if(!in_array('createUnit', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		$this->form_validation->set_rules('name', 'Name', 'trim|required');
		$this->form_validation->set_rules('code', 'Code', 'trim|required');
		$this->form_validation->set_rules('operator', 'Operator', 'trim');
		$this->form_validation->set_rules('operation_value', 'Operation Value', 'trim');
		$this->form_validation->set_rules('base_unit_id', 'Base Unit', 'trim');

		$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

        if ($this->form_validation->run() == TRUE) {
        	$data = array(
        		'name' => $this->input->post('name'),
        		'code' => $this->input->post('code'),
        		'operator' => $this->input->post('operator'),
        		'operation_value' => $this->input->post('operation_value'),
        		'base_unit_id' => $this->input->post('base_unit_id'),
        		'added_by' => $this->active_user_id,	
        	);

        	$create = $this->model_unit->create($data);
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

		if(!in_array('updateUnit', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		if($id) {
            $this->current_data_id = $id;

			$this->form_validation->set_rules('edit_name', 'Name', 'trim|required');
            $this->form_validation->set_rules('edit_code', 'Code', 'trim|required');
            $this->form_validation->set_rules('edit_operator', 'Operator', 'trim');
            $this->form_validation->set_rules('edit_operation_value', 'Operation Value', 'trim');
            $this->form_validation->set_rules('edit_base_unit_id', 'Base Unit', 'trim');

			$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

	        if ($this->form_validation->run() == TRUE) {
	        	$data = array(
	        		'name' => $this->input->post('edit_name'),
                    'code' => $this->input->post('edit_code'),
                    'operator' => $this->input->post('edit_operator'),
                    'operation_value' => $this->input->post('edit_operation_value'),
                    'base_unit_id' => $this->input->post('edit_base_unit_id'),
					'updated_by' => $this->active_user_id,
					'updated_at' => date('Y-m-d H:i:s'), // current datetime
	        	);

	        	$update = $this->model_unit->update($data, $id);
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
		if(!in_array('deleteUnit', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		
		$id = $this->input->post('id');

		$response = array();
		if($id) {
			$delete = $this->model_unit->remove($id);
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

}
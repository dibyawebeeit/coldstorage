<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends Admin_Controller 
{
	private $active_user_id;
    private $current_data_id;
	
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Items';

		$this->load->model('model_item');
        $this->load->model('model_unit');
		$this->load->model('model_category');

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
		if(!in_array('viewItem', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

        $this->data['categories'] = $this->model_category->getCategoryData();
		$this->data['units'] = $this->model_unit->getData();
		$this->render_template('item/index', $this->data);	
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
			$data = $this->model_item->getData($id);
			echo json_encode($data);
		}

		return false;
	}

	public function details($id)
    {
        if(!in_array('updateItem', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

        $this->data['itemList'] = $this->model_item->getCheckinItemList($id);

		// print_r($this->data['itemList']);die;

		$this->render_template('item/details', $this->data);
    }

	public function fetchData()
	{
		$result = array('data' => array());

		$data = $this->model_item->getData();

		foreach ($data as $key => $value) {

			// button
			$buttons = '';

			if(in_array('updateItem', $this->permission)) {

				$buttons .= "<a href='details/".$value['id']."'><button type='button' 
				class='btn btn-default'><i class='fa fa-eye'></i></button></a>";

				$buttons .= '<button type="button" class="btn btn-default" onclick="editFunc('.$value['id'].')" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></button>';
			}

			// if(in_array('deleteItem', $this->permission)) {
			// 	$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			// }

			// $status = ($value['status'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

			$stockResult = $this->db->select_sum('stock')->where('item_id',$value['id'])->get('checkin_items')->row_array();
			$totalStock = $stockResult['stock'];


			$result['data'][$key] = array(
                $key+1,
				$value['name'],
				$value['category_name'],
				$value['unit_name'],
				'₹ '.$value['price'],
				$totalStock,
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
		if(!in_array('createItem', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		$this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('category_id', 'Category', 'trim|required');
		$this->form_validation->set_rules('unit_id', 'Phone', 'trim|required');
		$this->form_validation->set_rules('price', 'Details', 'trim');

		$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

        if ($this->form_validation->run() == TRUE) {
        	$data = array(
        		'name' => $this->input->post('name'),
        		'category_id' => $this->input->post('category_id'),
        		'unit_id' => $this->input->post('unit_id'),
        		'price' => $this->input->post('price'),
        		'added_by' => $this->active_user_id,	
        	);

        	$create = $this->model_item->create($data);
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
        		$response['messages']["create_".$key] = form_error($key);
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

		if(!in_array('updateItem', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		if($id) {
            $this->current_data_id = $id;

			$this->form_validation->set_rules('edit_name', 'Name', 'trim|required');
            $this->form_validation->set_rules('category_id', 'Category', 'trim|required');
			$this->form_validation->set_rules('unit_id', 'Phone', 'trim|required');
			$this->form_validation->set_rules('price', 'Details', 'trim');

			$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

	        if ($this->form_validation->run() == TRUE) {
	        	$data = array(
	        		'name' => $this->input->post('edit_name'),
	        		'category_id' => $this->input->post('category_id'),
	        		'unit_id' => $this->input->post('unit_id'),
	        		'price' => $this->input->post('price'),
					'updated_by' => $this->active_user_id,
					'updated_at' => date('Y-m-d H:i:s'), // current datetime
	        	);

	        	$update = $this->model_item->update($data, $id);
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

    public function category_id_check($category_id) {
        $this->db->where('category_id', $category_id);
        $this->db->where('id !=', $this->current_data_id); // Exclude current user
        $query = $this->db->get('items');

        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('category_id_check', 'The {field} is already in use.');
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
		if(!in_array('deleteItem', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		
		$id = $this->input->post('id');

		$response = array();
		if($id) {
			$delete = $this->model_item->remove($id);
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
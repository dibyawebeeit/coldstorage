<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Fixed_asset extends Admin_Controller 
{
	private $active_user_id;
    private $current_data_id;
	
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Machine';

		$this->load->model('model_fixed_asset');
		$this->load->model('model_fixed_asset_heads');
		$this->load->model('model_fixed_asset_service_type');

		if ($this->session->userdata('logged_in')) {
			$this->active_user_id = $this->session->userdata('id');
		} else {
			$this->active_user_id = null;
		}
		$this->load->library('pagination');

	}

	/* 
	* It only redirects to the manage category page
	*/
	public function index()
	{
		if(!in_array('viewFixedAsset', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$data = $this->model_fixed_asset->getData();
		$this->data['fixed_assest_heads'] = $this->model_fixed_asset_heads->getData();
		$this->data['fixed_assest_service_types'] = $this->model_fixed_asset_service_type->getData();
		$this->getList();
	}	



	protected function getList()
	{
		$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		$config["per_page"] = 20;

		$is_report_download = false;
		if( $this->input->get('filter_from_date') || $this->input->get('filter_to_date') || $this->input->get('filter_fixed_asset_heads_id') ){
			$is_report_download = true;
		}


		$filter=array(
			"filter_fixed_asset_heads_id"	  		  =>$this->input->get('filter_fixed_asset_heads_id'),
			"filter_fixed_asset_service_type_id"	  =>$this->input->get('filter_fixed_asset_service_type_id'),
			"filter_service_status"	  		  		  =>$this->input->get('filter_service_status'),
			"filter_from_date"		  	  	          =>$this->input->get('filter_from_date'),
			"filter_to_date"		      	          =>$this->input->get('filter_to_date'),
			'start'			                          =>$page,
			'limit'			                          =>$config["per_page"]
		);

		$this->data['fixed_assests']=$this->model_fixed_asset->getDataList($filter);
		$total_item=$this->model_fixed_asset->getDataListTotal($filter);
		$this->data['total_item']= $total_item;
		

		//START PAGINATION
		$config["total_rows"] =$total_item;
		$config["base_url"] = base_url('payment');
		$config['reuse_query_string'] = TRUE;
        $config['full_tag_open'] = '<div ><ul class="pagination justify-content-end pagination-info pagin-border-info">';
        $config['full_tag_close'] = '</ul></div><!--pagination-->';
        $config['first_link'] = '&laquo; First';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last &raquo;';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = 'Next &rarr;';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&larr; Previous';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';
 		$this->pagination->initialize($config);
		
        $this->data['pagination'] = $this->pagination->create_links();
		//END PAGINATION


	
       
	    $this->data['filter_fixed_asset_heads_id'] = $this->input->get('filter_fixed_asset_heads_id');
		$this->data['filter_fixed_asset_service_type_id'] = $this->input->get('filter_fixed_asset_service_type_id');
		$this->data['filter_service_status'] = $this->input->get('filter_service_status');

		$this->data['filter_submit'] = $this->input->get('filter_submit');
		$this->data['filter_from_date'] = $this->input->get('filter_from_date');
		$this->data['filter_to_date'] = $this->input->get('filter_to_date');
		$this->data['is_report_download'] =  $is_report_download;
		$this->render_template('fixed_asset/index', $this->data);
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
			$data = $this->model_fixed_asset->getData($id);
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

		$data = $this->model_fixed_asset->getData();

		foreach ($data as $key => $value) {

			// button
			$buttons = '';
			if(in_array('updateFixedAsset', $this->permission)) {
				$buttons .= '<button type="button" class="btn btn-default" onclick="editFunc('.$value['id'].')" data-toggle="modal" data-target="#editModal"><i class="fa fa-pencil"></i></button>';
			}

			if(in_array('deleteFixedAsset', $this->permission)) {
				$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
			}
				

			$file_link = "--";
			if(trim($value['receipt_file'])){
				$file_link = '<a href="'.base_url($value['receipt_file']).'" target="_blank">View / Download File</a>';
			}
			
			// $status = ($value['status'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

			$result['data'][$key] = array(
				$value['fixed_asset_heads_name'],
				$value['fixed_asset_service_type_name'],
				$value['start_date'],
				$value['details'],
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
		if(!in_array('createFixedAsset', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		$this->form_validation->set_rules('fixed_asset_heads_id', 'Fixed Asset Heads', 'trim|required');
		$this->form_validation->set_rules('fixed_asset_service_type_id', 'Fixed Asset Heads', 'trim|required');
		$this->form_validation->set_rules('start_date', 'Date', 'trim|required');
		$this->form_validation->set_rules('details', 'Details', 'trim');
		$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

        if ($this->form_validation->run() == TRUE) {

			//$receipt_file = $this->upload_file('reciept_file');

			$date = new DateTime($this->input->post('start_date'));
			$date->modify('+'.$this->input->post('service_duration').' months');
			$service_date = $date->format('Y-m-d');

        	$data = array(
				'fixed_asset_heads_id' 					=> $this->input->post('fixed_asset_heads_id'),
				'fixed_asset_service_type_id' 			=> $this->input->post('fixed_asset_service_type_id'),
				'start_date' 							=> $this->input->post('start_date'),
				'service_date' 							=> $service_date,
				'service_duration' 						=> $this->input->post('service_duration'),
        		'details' 								=> $this->input->post('details'),
        		'added_by' 								=> $this->active_user_id,	
        	);

        	$create = $this->model_fixed_asset->create($data);
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

		
		if(!in_array('updateFixedAsset', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$response = array();

		if($id) {

			

            $this->current_data_id = $id;
			$this->form_validation->set_rules('edit_fixed_asset_heads_id', 'Fixed Asset Heads', 'trim|required');
			$this->form_validation->set_rules('edit_fixed_asset_service_type_id', 'Fixed Asset Service Type', 'trim|required');
			$this->form_validation->set_rules('edit_start_date', 'Date', 'trim|required');
			$this->form_validation->set_rules('edit_details', 'Details', 'trim');
		

			$this->form_validation->set_error_delimiters('<p class="text-danger">','</p>');

	        if ($this->form_validation->run() == TRUE) {

				$date = new DateTime($this->input->post('start_date'));
				$date->modify('+'.$this->input->post('edit_service_duration').' months');
				$service_date = $date->format('Y-m-d');

	        	$data = array(
	        		'fixed_asset_heads_id' 					=> $this->input->post('edit_fixed_asset_heads_id'),
					'fixed_asset_service_type_id' 			=> $this->input->post('edit_fixed_asset_service_type_id'),
	        		'start_date' 							=> $this->input->post('edit_start_date'),
					'service_date' 							=> $service_date,
					'service_duration' 						=> $this->input->post('edit_service_duration'),
	        		'details' 								=> $this->input->post('edit_details'),
					'updated_by' 							=> $this->active_user_id,
					'updated_at' 							=> date('Y-m-d H:i:s'), // current datetime
	        	);

	        	$update = $this->model_fixed_asset->update($data, $id);

				/*if($_FILES['edit_reciept_file']['size'] > 0) {

					$upload_file = $this->upload_file('edit_reciept_file');
					$upload_file = array('receipt_file' => $upload_file);
					$this->model_fixed_asset->update($upload_file,$id);
            	}*/

	        	if($update == true) {

	        		$response['success'] = true;
					$this->session->set_flashdata('messages', 'Succesfully updated');
	        		$response['messages'] = 'Succesfully updated';

	        	}else {
	        		$response['success'] = false;
					$this->session->set_flashdata('err_messages', 'Error in the database while updated the brand information');
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
			$this->session->set_flashdata('err_messages', 'Error please refresh the page again!!');
    		$response['messages'] = 'Error please refresh the page again!!';
		}

		echo json_encode($response);
	}

    public function amount_check($amount) {
        $this->db->where('amount', $amount);
        $this->db->where('id !=', $this->current_data_id); // Exclude current user
        $query = $this->db->get('fixed_assests');

        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('amount_check', 'The {field} is already in use.');
            return FALSE;
        } else {
            return TRUE;
        }
    }


	public function upload_file($field_fixed_asset_heads_id)
    {
    	// assets/images/logo
        $config['upload_path'] = 'uploads/reciept';
        $config['file_fixed_asset_heads_id'] =  uniqid();
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '1000';

        // $config['max_width']  = '1024';s
        // $config['max_height']  = '768';

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload($field_fixed_asset_heads_id))
        {
            $error = $this->upload->display_errors();
            return false;
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            $type = explode('.', $_FILES[$field_fixed_asset_heads_id]['fixed_asset_heads_id']);
            $type = $type[count($type) - 1];
            
            $path = $config['upload_path'].'/'.$config['file_fixed_asset_heads_id'].'.'.$type;
            return ($data == true) ? $path : false;            
        }
    }

	public function export_report()
	{
		
		$filter=array(
			"filter_fixed_asset_heads_id"	  		  =>$this->input->get('filter_fixed_asset_heads_id'),
			"filter_fixed_asset_service_type_id"	  =>$this->input->get('filter_fixed_asset_service_type_id'),
			"filter_service_status"	  		  		  =>$this->input->get('filter_service_status'),
			"filter_from_date"		  	  	          =>$this->input->get('filter_from_date'),
			"filter_to_date"		      	          =>$this->input->get('filter_to_date')
		
		);
      
    
		$results = $this->model_fixed_asset->getExportDataList($filter);
		$spreadsheet = new Spreadsheet(); // instantiate Spreadsheet
        $sheet = $spreadsheet->getActiveSheet();

        // manually set table data value
        $sheet->setCellValue('A1', 'Fixed Asset Heads'); 
        $sheet->setCellValue('B1', 'Type');
        $sheet->setCellValue('C1', 'Start Date');
		$sheet->setCellValue('D1', 'Service Date');
		$sheet->setCellValue('E1', 'Status');
		$sheet->setCellValue('F1', 'Description');
		
	
        $writer = new Xlsx($spreadsheet); // instantiate Xlsx
        $total = 0;
		if($results){

			$inc=2;
			foreach($results as $row){
				
			
				$service_status_text= "";
				 if($row['service_status']==1){ $service_status_text = "Completed";}
			
			  	$total = $total + $row['amount'];
				$sheet->setCellValue('A'.$inc, $row['fixed_asset_heads_name']); 
				$sheet->setCellValue('B'.$inc, $row['fixed_asset_service_type_name']);
				$sheet->setCellValue('C'.$inc, date("d/m/Y",strtotime($row['start_date'])));
				$sheet->setCellValue('D'.$inc, date("d/m/Y",strtotime($row['service_date'])));
				$sheet->setCellValue('E'.$inc, $service_status_text );
				$sheet->setCellValue('F'.$inc, $row['details']);
			
				
				$inc++;
			}

		}
      
      	$sheet->setCellValue('F'.($inc+2), 'Total');
		$sheet->setCellValue('G'.($inc+2),  $total);

        $filename = 'Fixed Assets - '.date("d-m-Y"); // set filename for excel file to be exported
        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        $writer->save('php://output');	// download file 
	}

	public function service_status_update(){

			$id = $this->input->post('id');
			$service_status = $this->input->post('service_status');
			
			$response = array();
			if($id) {
				$data = array(
	        		'service_status' 					=> $service_status,
	        	);

	        	$update = $this->model_fixed_asset->update($data, $id);

				if($update == true) {
					$response['success'] = true;
					$response['messages'] = "Successfully updated";	
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



	/*
	* It removes the category information from the database 
	* and returns the json format operation messages
	*/
	public function remove()
	{
		if(!in_array('deleteFixedAsset', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		
		$id = $this->input->post('id');

		$response = array();
		if($id) {
			$delete = $this->model_fixed_asset->remove($id);
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
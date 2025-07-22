<?php

defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Report extends Admin_Controller 
{
	private $active_user_id;
    private $current_data_id;
	
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Report';

		$this->load->model('model_checkin');
		$this->load->model('model_report');
		$this->load->library('pagination');
		$this->load->helper('url');

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
		if(!in_array('viewReport', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->getList();

	}

	protected function getList()
	{
		$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		$config["per_page"] = 20;

		$is_report_download = false;
		if(trim($this->input->get('filter_q')) || $this->input->get('filter_contact_id') || $this->input->get('filter_checkout_type') 
		|| $this->input->get('filter_from_date') || $this->input->get('filter_to_date') ){
			$is_report_download = true;
		}

		$filter=array(
			"filter_q"		  		      =>trim($this->input->get('filter_q')),
			"filter_contact_id"		      =>$this->input->get('filter_contact_id'),
			"filter_checkout_type"		  =>$this->input->get('filter_checkout_type'),
			"filter_from_date"		  	  =>$this->input->get('filter_from_date'),
			"filter_to_date"		      =>$this->input->get('filter_to_date'),
			'start'			              =>$page,
			'limit'			              =>$config["per_page"]
		);

		$this->data['reports']=$this->model_report->getDataList($filter);
      
  
		$total_item=$this->model_report->getDataListTotal($filter);
		$this->data['total_item']= $total_item;

	

		//START PAGINATION
		$config["total_rows"] =$total_item;
		$config["base_url"] = base_url('report');
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


	    $this->data['contactList'] = $this->model_checkin->getContactList();
        $this->data['storeList'] = [];


		$this->data['filter_submit'] = $this->input->get('filter_submit');
		$this->data['filter_q'] = $this->input->get('filter_q');
		$this->data['filter_contact_id'] = $this->input->get('filter_contact_id');
		$this->data['filter_store_id'] = $this->input->get('filter_store_id');
		$this->data['filter_checkout_type'] = $this->input->get('filter_checkout_type');
		$this->data['filter_from_date'] = $this->input->get('filter_from_date');
		$this->data['filter_to_date'] = $this->input->get('filter_to_date');
		$this->data['is_report_download'] =  $is_report_download;
        
		$this->render_template('report/index', $this->data);
	}

	public function exportReport()
	{

		

		$filter=array(
			"filter_q"		  		      =>$this->input->get('filter_q'),
			"filter_contact_id"		      =>$this->input->get('filter_contact_id'),
			"filter_store_id"		  	  =>$this->input->get('filter_store_id'),
			"filter_checkout_type"		  =>$this->input->get('filter_checkout_type'),
			"filter_from_date"		  	  =>$this->input->get('filter_from_date'),
			"filter_to_date"		      =>$this->input->get('filter_to_date'),
			'start'			              =>$page,
			'limit'			              =>$config["per_page"]
		);


		$results = $this->model_report->getExportDataList($filter);

		//echo "<pre>".print_r($results,1)."</pre>";
		//exit;
		$spreadsheet = new Spreadsheet(); // instantiate Spreadsheet
        $sheet = $spreadsheet->getActiveSheet();

        // manually set table data value
        $sheet->setCellValue('A1', 'ID'); 
        $sheet->setCellValue('B1', 'Date');
        $sheet->setCellValue('C1', 'Contact');
		$sheet->setCellValue('D1', 'Item');
		$sheet->setCellValue('E1', 'Stock');
		$sheet->setCellValue('F1', 'Chamber');
		$sheet->setCellValue('G1', 'Rack');
		$sheet->setCellValue('H1', 'Type');
		$sheet->setCellValue('I1', 'Unit');
        $writer = new Xlsx($spreadsheet); // instantiate Xlsx

		if($results){

			$inc=2;
			foreach($results as $row){
				
			
				$status ="";
				


				$checkout_type = "";
				if($row['checkout_type']=='checkin'){
					$checkout_type = "Checkin";
				}else if($row['checkout_type']=='checkout'){
					$checkout_type = "Checkout";
				}else if($row['checkout_type']=='damage'){
					$checkout_type = "Damage";
				}

				$sheet->setCellValue('A'.$inc, $row['checkin_id']); 
				$sheet->setCellValue('B'.$inc, $row['date']);
				$sheet->setCellValue('C'.$inc, $row['customer_name']);
				$sheet->setCellValue('D'.$inc, $row['item_name']);
				$sheet->setCellValue('E'.$inc, $row['stock']);
				$sheet->setCellValue('F'.$inc, $row['chamber_name']);
				$sheet->setCellValue('G'.$inc, $row['rack_name']);
				$sheet->setCellValue('H'.$inc, $checkout_type);
				$sheet->setCellValue('I'.$inc, $row['unit_name']);
				$inc++;
			}

		}

        $filename = 'report-'.$row['checkin_id'].'-'.$row['customer_name'].'-'.date("d-m-Y"); // set filename for excel file to be exported
        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        $writer->save('php://output');	// download file 

	}

}
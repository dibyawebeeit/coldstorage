<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Payment extends Admin_Controller 
{
	private $active_user_id;
    private $current_data_id;
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->data['page_title'] = 'Payment';
		$this->load->model('model_checkin');
		$this->load->model('model_payment');
		$this->load->model('model_checkout');
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
		if(!in_array('viewPayment', $this->permission)) {
			redirect('dashboard', 'refresh');
		}
		$this->getList();

	}

	protected function getList()
	{
		$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
		$config["per_page"] = 20;

		$is_report_download = false;
		if(trim($this->input->get('filter_q')) || $this->input->get('filter_contact_id')
		|| $this->input->get('filter_from_date') || $this->input->get('filter_to_date') ){
			$is_report_download = true;
		}


		$filter=array(
			"filter_q"		  		      =>trim($this->input->get('filter_q')),
			"filter_contact_id"		      =>$this->input->get('filter_contact_id'),
			"filter_from_date"		  	  =>$this->input->get('filter_from_date'),
			"filter_to_date"		      =>$this->input->get('filter_to_date'),
			'start'			              =>$page,
			'limit'			              =>$config["per_page"]
		);

		$this->data['reports']=$this->model_payment->getDataList($filter);
	
		$total_item=$this->model_payment->getDataListTotal($filter);
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


	    $this->data['contactList'] = $this->model_checkin->getContactList();
       


		$this->data['filter_submit'] = $this->input->get('filter_submit');
		$this->data['filter_q'] = $this->input->get('filter_q');
		$this->data['filter_contact_id'] = $this->input->get('filter_contact_id');
		
		$this->data['filter_checkout_type'] = $this->input->get('filter_checkout_type');
		$this->data['filter_from_date'] = $this->input->get('filter_from_date');
		$this->data['filter_to_date'] = $this->input->get('filter_to_date');
		$this->data['is_report_download'] =  $is_report_download;
		$this->render_template('payment/index', $this->data);
	}

	public function exportPayment()
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

		$results = $this->model_payment->getExportDataList($filter);

		//echo "<pre>".print_r($results,1)."</pre>";
		//exit;
		$spreadsheet = new Spreadsheet(); // instantiate Spreadsheet
        $sheet = $spreadsheet->getActiveSheet();

        // manually set table data value
        $sheet->setCellValue('A1', 'ID'); 
        $sheet->setCellValue('B1', 'Date');
        $sheet->setCellValue('C1', 'Contact');
		$sheet->setCellValue('D1', 'Phone');
		$sheet->setCellValue('E1', 'Email');
		$sheet->setCellValue('F1', 'Pay. Mode');
		$sheet->setCellValue('G1', 'Amount');
	
        $writer = new Xlsx($spreadsheet); // instantiate Xlsx

		$total = 0;
		if($results){

			$inc=2;
			foreach($results as $row){
				
			
			
			    $total = $total + $row['amount'];
				$sheet->setCellValue('A'.$inc, $row['checkout_id']); 
				$sheet->setCellValue('B'.$inc, date("d/m/Y",strtotime($row['created_at'])));
				$sheet->setCellValue('C'.$inc, $row['customer_name']);
				$sheet->setCellValue('D'.$inc, $row['customer_phone']);
				$sheet->setCellValue('E'.$inc, $row['customer_email']);
				$sheet->setCellValue('F'.$inc, $row['payment_type']);
				$sheet->setCellValue('G'.$inc, $row['amount']);
				
				$inc++;
			}
		}


		$sheet->setCellValue('F'.($inc+2), 'Total');
		$sheet->setCellValue('G'.($inc+2),  $total);

        $filename = 'payment-'.$row['checkout_id'].'-'.$row['customer_name'].'-'.date("d-m-Y"); // set filename for excel file to be exported
        header('Content-Type: application/vnd.ms-excel'); // generate excel file
        header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        header('Cache-Control: max-age=0');
        $writer->save('php://output');	// download file 

	}


	public function invoice($id)
    {
        if(!in_array('viewPayment', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$this->data['page_title'] = 'Payment Invoice';

		$paymentDetails = $this->model_payment->get_by_id($id);
		$this->data['paymentDetails'] = $paymentDetails;

        $this->data['checkoutDetails'] = $this->model_checkout->getCheckoutDetails($paymentDetails['checkout_id']);
        $this->data['itemList'] = $this->model_checkout->getCheckoutItemListById($paymentDetails['checkout_id']);

		$this->render_template('payment/invoice', $this->data);
    }

}
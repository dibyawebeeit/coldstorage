<?php 

class Setting extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->not_logged_in();
		$this->data['page_title'] = 'Setting';
		$this->load->model('model_setting');
	}

	
	public function index()
	{
		if(!in_array('updateTotalcapacity', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$this->data['setting'] = $this->model_setting->getData();

		$this->render_template('setting/index', $this->data);
	}


	public function update()
	{
		if(!in_array('updateTotalcapacity', $this->permission)) {
			redirect('dashboard', 'refresh');
		}

		$this->form_validation->set_rules('total_capacity', 'Total Capacity', 'required');

		if ($this->form_validation->run() == TRUE) {
	            // true case
		    $data = array(
                'total_capacity' => $this->input->post('total_capacity'),
            );

            $update = $this->model_setting->update($data);
            if($update == true) {
                $this->session->set_flashdata('success', 'Successfully updated');
                redirect('setting/', 'refresh');
            }
            else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('setting/', 'refresh');
            }
	    }
        else {
            redirect('setting/', 'refresh');
        }	
		
	}

	


}
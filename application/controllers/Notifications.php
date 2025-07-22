<?php
class Notifications extends CI_Controller {

    public function __construct() {
        parent::__construct();

		$this->data['page_title'] = 'Notification';

		$this->load->model('model_notification');

    }

    public function fetch() {
       
        $data['notifications'] = $this->model_notification->getUnreadNotifications();
        $data['unread_count'] = $this->model_notification->countUnread();
        echo json_encode($data);
    }

    public function mark_read() {
        $this->model_notification->markAllAsRead();
        echo json_encode(['status' => 'success']);
    }

    public function create()
    {
        // // for machine section
        // $machineQuery = $this->db->select('id,heads_id,service_type_id,service_date')
        // ->where('DATE(service_date) <=', date('Y-m-d', strtotime('+7 days')))
        // ->where('data_status','A')->get('machines')->result_array();
        // if ($machineQuery) {
        //     foreach ($machineQuery as $value) {
                
        //         $message ='';
        //         $headesQuery = $this->model_notification->get_heades($value['heads_id'],'machine_heads');
        //         $servicetypeQuery = $this->model_notification->get_service_type($value['service_type_id'],'machine_service_type');
        //         $days_left = $this->calculate_days_left($value['service_date']);

        //         if( isset($headesQuery) && isset($servicetypeQuery) ) {
        //             $message = "ALERT: {$headesQuery['name']} {$servicetypeQuery['name']} due in {$days_left} days! ";
        //             $message .= "Date: {$value['service_date']} ";
        //         }

        //         $checkMachine = $this->db
        //         ->where('notification_type', 'machine')
        //         ->where('property_id', $value['id'])
        //         ->where('created_at >=', date('Y-m-d', strtotime($value['service_date'] . ' -7 days')))
        //         ->get('notifications')
        //         ->num_rows();


        //         if($checkMachine == 0)
        //         {
        //             $data=array(
        //                 'notification_type' => 'machine', //complience or machine
        //                 'property_id' => $value['id'],
        //                 'message' => $message
        //             );
        //             $this->model_notification->create($data);

        //             //start alert to mail
        //                 // $this->send_mail($message, $servicetypeQuery['name']);
        //             //end alert to mail
        //         }
        //     }
        // }


        // // for compliences section
        // $complienceQuery = $this->db->select('id,compliences_heads_id,compliences_service_type_id,service_date')
        // ->where('DATE(service_date) <=', date('Y-m-d', strtotime('+7 days')))
        // ->where('data_status','A')->get('compliences')->result_array();
        // if ($complienceQuery) {
        //     foreach ($complienceQuery as $value) {
        //         $message ='';
        //         $headesQuery = $this->model_notification->get_heades($value['compliences_heads_id'],'compliences_heads');
        //         $servicetypeQuery = $this->model_notification->get_service_type($value['compliences_service_type_id'],'compliences_service_type');

        //         $days_left = $this->calculate_days_left($value['service_date']);

        //         if( isset($headesQuery) && isset($servicetypeQuery) ) {
        //             $message = "ALERT: {$headesQuery['name']} {$servicetypeQuery['name']} due in {$days_left} days! ";
        //             $message .= "Date: {$value['service_date']} ";
        //         }

        //         $checkComplience = $this->db
        //         ->where('notification_type', 'complience')
        //         ->where('property_id', $value['id'])
        //         ->where('created_at >=', date('Y-m-d', strtotime($value['service_date'] . ' -7 days')))
        //         ->get('notifications')
        //         ->num_rows();


        //         if($checkComplience == 0)
        //         {
        //             $data=array(
        //                 'notification_type' => 'complience', //complience or machine
        //                 'property_id' => $value['id'],
        //                 'message' => $message
        //             );
        //             $this->model_notification->create($data);

        //             //start alert to mail
        //                 // $this->send_mail($message, $servicetypeQuery['name']);
        //             //end alert to mail
        //         }
        //     }
        // }

        $days = 7;

        //  1. For Machine Section
        $sevenDaysLater = date('Y-m-d', strtotime("+{$days} days"));

        $machineQuery = $this->db->select('
            m.id as machine_id,
            m.service_date,
            mh.name as head_name,
            st.name as service_type_name
        ')
        ->from('machines m')
        ->join('machine_heads mh', 'mh.id = m.heads_id', 'left')
        ->join('machine_service_type st', 'st.id = m.service_type_id', 'left')
        ->where('m.data_status', 'A')
        ->where('DATE(m.service_date) <=', $sevenDaysLater)
        ->get()
        ->result_array();

        if ($machineQuery) {
            foreach ($machineQuery as $row) {
                $days_left = $this->calculate_days_left($row['service_date']);

                $message = "ALERT: {$row['head_name']} {$row['service_type_name']} due in {$days_left} days! ";
                $message .= "Date: {$row['service_date']}";

                $startDate = date('Y-m-d', strtotime($row['service_date'] . " -{$days} days"));

                $checkMachine = $this->db
                    ->where('notification_type', 'machine')
                    ->where('property_id', $row['machine_id'])
                    ->where('created_at >=', $startDate)
                    ->get('notifications')
                    ->num_rows();

                if ($checkMachine == 0) {
                    $data = [
                        'notification_type' => 'machine',
                        'property_id' => $row['machine_id'],
                        'message' => $message
                    ];
                    $this->model_notification->create($data);

                    // Optional: send email
                    // $this->send_mail($message, $row['service_type_name']);
                }
            }
        }

        //  2. For Complience Section
        $complienceQuery = $this->db->select('
            c.id as complience_id,
            c.service_date,
            ch.name as head_name,
            st.name as service_type_name
        ')
        ->from('compliences c')
        ->join('compliences_heads ch', 'ch.id = c.compliences_heads_id', 'left')
        ->join('compliences_service_type st', 'st.id = c.compliences_service_type_id', 'left')
        ->where('c.data_status', 'A')
        ->where('DATE(c.service_date) <=', $sevenDaysLater)
        ->get()
        ->result_array();

        if ($complienceQuery) {
            foreach ($complienceQuery as $row) {
                $days_left = $this->calculate_days_left($row['service_date']);

                $message = "ALERT: {$row['head_name']} {$row['service_type_name']} due in {$days_left} days! ";
                $message .= "Date: {$row['service_date']}";

                $startDate = date('Y-m-d', strtotime($row['service_date'] . " -{$days} days"));

                $checkComplience = $this->db
                    ->where('notification_type', 'complience')
                    ->where('property_id', $row['complience_id'])
                    ->where('created_at >=', $startDate)
                    ->get('notifications')
                    ->num_rows();

                if ($checkComplience == 0) {
                    $data = [
                        'notification_type' => 'complience',
                        'property_id' => $row['complience_id'],
                        'message' => $message
                    ];
                    $this->model_notification->create($data);

                    // Optional: send email
                    // $this->send_mail($message, $row['service_type_name']);
                }
            }
        }

        echo "Notifications sent: ";
        
    }

    private function calculate_days_left($service_date) {
        $today = new DateTime();
        $due_date = new DateTime($service_date);
        return $today->diff($due_date)->days;
    }


    private function send_mail($message, $type) {


        // Example: Send email (configure email settings first)
        $this->load->library('email');
        $this->email->from('alerts@example.com', 'Machine & Compliance System');
        $this->email->to('admin@example.com');
        $this->email->subject("Upcoming {$type}");
        $this->email->message($message);
        $this->email->send();
       
        // For testing: Log the alert
        log_message('error', $message);
    }
}

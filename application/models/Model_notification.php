<?php
class Model_notification extends CI_Model {

    protected $table="notifications";
	public $active_user_id;
	public function __construct()
	{
		parent::__construct();

		if ($this->session->userdata('logged_in')) {
			$this->active_user_id = $this->session->userdata('id');
		} else {
			$this->active_user_id = null;
		}
	}

    public function getUnreadNotifications() {
        return $this->db
            ->order_by('created_at', 'DESC')
            ->get($this->table)
            ->result();
    }

    public function markAllAsRead() {
        return $this->db
            ->where('is_read', 0)
            ->update($this->table, ['is_read' => 1]);
    }

    public function countUnread() {
        return $this->db
            ->where('is_read', 0)
            ->count_all_results($this->table);
    }

    public function create($data) {
        return $this->db->insert($this->table, $data);
    }

    public function get_heades($id, $table)
    {
        $result = $this->db->select('name')->where('id',$id)->get($table)->row_array();
        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }
    }

    public function get_service_type($id, $table)
    {
        $result = $this->db->select('name')->where('id',$id)->get($table)->row_array();
        if($result)
        {
            return $result;
        }
        else
        {
            return null;
        }
    }
}

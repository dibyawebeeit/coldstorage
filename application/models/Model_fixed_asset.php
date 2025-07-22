<?php 

class Model_fixed_asset extends CI_Model
{
	protected $table="fixed_asset";
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

	/* get active brand infromation */
	

	/* get the brand data */
	public function getData($id = null)
	{
		if($id) {
			$this->db->select('fixed_asset.*,fixed_asset_heads.name as fixed_asset_heads_name');
			$this->db->from('fixed_asset');
			$this->db->join('fixed_asset_heads', 'fixed_asset_heads.id = fixed_asset.fixed_asset_heads_id', 'left');
			$this->db->join('fixed_asset_service_type', 'fixed_asset_service_type.id = fixed_asset.fixed_asset_service_type_id', 'left');
			$this->db->where('fixed_asset.id',$id);
			$this->db->where('fixed_asset.data_status','A');
			return $this->db->get()->row_array();
       		
    	}

		$this->db->select('fixed_asset.*,fixed_asset_heads.name as fixed_asset_heads_name');
		$this->db->from('fixed_asset');
		$this->db->join('fixed_asset_heads', 'fixed_asset_heads.id = fixed_asset.fixed_asset_heads_id', 'left');
		$this->db->join('fixed_asset_service_type', 'fixed_asset_service_type.id = fixed_asset.fixed_asset_service_type_id', 'left');
		$this->db->where('fixed_asset.data_status','A');
		$this->db->order_by('fixed_asset.id', 'DESC');
		return $this->db->get()->result_array();
		
	}

	
	public function getDataList($data=[])
	{
		$sql = "SELECT fa.*,fah.name as fixed_asset_heads_name,fst.name as fixed_asset_service_type_name FROM `fixed_asset` fa
		LEFT JOIN  `fixed_asset_heads` fah ON fah.id = fa.fixed_asset_heads_id 
		LEFT JOIN  `fixed_asset_service_type` fst ON fst.id = fa.fixed_asset_service_type_id 
		WHERE fa.data_status = 'A' ";

		if(!empty($data['filter_fixed_asset_heads_id'])){
			$sql.=' AND fa.fixed_asset_heads_id = "'.$data['filter_fixed_asset_heads_id'].'"  ';
		}

		if(!empty($data['filter_fixed_asset_heads_id'])){
			$sql.=' AND fa.fixed_asset_heads_id = "'.$data['filter_fixed_asset_heads_id'].'"  ';
		}

		if(!empty($data['filter_service_status'])){
			$sql.=' AND fa.service_status = "'.$data['filter_service_status'].'"  ';
		}

		if($data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (fa.service_date  BETWEEN '".$data['filter_from_date']."' AND '".$data['filter_to_date']."')";
		}else if($data['filter_from_date'] && !$data['filter_to_date']){
			$sql.= " AND (fa.service_date  BETWEEN '".$data['filter_from_date']."' AND '".$data['filter_from_date']."')";
		}else if(!$data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (fa.service_date  BETWEEN '".$data['filter_to_date']."' AND '".$data['filter_to_date']."')";
		}


		$sql.=' ORDER BY fa.id DESC ';

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);
		
		return $query->result_array();
	}

	public function getDataListTotal($data=[])
	{
		$sql = "SELECT fa.id FROM `fixed_asset` fa
		LEFT JOIN  `fixed_asset_heads` fah ON fah.id = fa.fixed_asset_heads_id 
		LEFT JOIN  `fixed_asset_service_type` fst ON fst.id = fa.fixed_asset_service_type_id 
		WHERE fa.data_status = 'A' ";

		if(!empty($data['filter_fixed_asset_heads_id'])){
			$sql.=' AND fa.fixed_asset_heads_id = "'.$data['filter_fixed_asset_heads_id'].'"  ';
		}

		if(!empty($data['filter_fixed_asset_heads_id'])){
			$sql.=' AND fa.fixed_asset_heads_id = "'.$data['filter_fixed_asset_heads_id'].'"  ';
		}

		if(!empty($data['filter_service_status'])){
			$sql.=' AND fa.service_status = "'.$data['filter_service_status'].'"  ';
		}



		if($data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (fa.service_date  BETWEEN '".$data['filter_from_date']."' AND '".$data['filter_to_date']."')";
		}else if($data['filter_from_date'] && !$data['filter_to_date']){
			$sql.= " AND (fa.service_date  BETWEEN '".$data['filter_from_date']."' AND '".$data['filter_from_date']."')";
		}else if(!$data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (fa.service_date  BETWEEN '".$data['filter_to_date']."' AND '".$data['filter_to_date']."')";
		}

		$query = $this->db->query($sql);
		return $num = $query->num_rows();
	}


	public function getExportDataList($data=[])
	{
      
     
		$sql = "SELECT fa.*,fah.name as fixed_asset_heads_name,fst.name as fixed_asset_service_type_name FROM `fixed_asset` fa
		LEFT JOIN  `fixed_asset_heads` fah ON fah.id = fa.fixed_asset_heads_id 
		LEFT JOIN  `fixed_asset_service_type` fst ON fst.id = fa.fixed_asset_service_type_id 
		WHERE fa.data_status = 'A' ";


		if(!empty($data['filter_fixed_asset_heads_id'])){
			$sql.=' AND fa.fixed_asset_heads_id = "'.$data['filter_fixed_asset_heads_id'].'"  ';
		}

		if(!empty($data['filter_fixed_asset_heads_id'])){
			$sql.=' AND fa.fixed_asset_heads_id = "'.$data['filter_fixed_asset_heads_id'].'"  ';
		}

		if(!empty($data['filter_service_status'])){
			$sql.=' AND fa.service_status = "'.$data['filter_service_status'].'"  ';
		}


	    if($data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (fa.service_date  BETWEEN '".$data['filter_from_date']."' AND '".$data['filter_to_date']."')";
		}else if($data['filter_from_date'] && !$data['filter_to_date']){
			$sql.= " AND (fa.service_date  BETWEEN '".$data['filter_from_date']."' AND '".$data['filter_from_date']."')";
		}else if(!$data['filter_from_date'] && $data['filter_to_date']){
			$sql.= " AND (fa.service_date  BETWEEN '".$data['filter_to_date']."' AND '".$data['filter_to_date']."')";
		}

		$sql.=' ORDER BY fa.id DESC ';
      
    
		$query = $this->db->query($sql);
		return $query->result_array();
	}


	

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert($this->table, $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update($this->table, $data);
			return ($update == true) ? true : false;
		}
	}

	

	public function remove($id)
	{
		if($id) {
			$data =array(
				'deleted_by' => $this->active_user_id,
				'data_status' => 'D'
			);
			$this->db->where('id', $id);
			$delete = $this->db->update($this->table, $data);
			return ($delete == true) ? true : false;
		}
	}

}
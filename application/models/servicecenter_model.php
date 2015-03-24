<?php
class Servicecenter_model extends CI_Model {
    protected $table_name = "servicecenter";
    /**
    * Responsable for auto load the database
    * @return void
    */
    public function __construct()
    {
        $this->load->database();
    }

    /**
    * Get product by his is
    * @param int $product_id 
    * @return array
    */
    public function get_servicecenter_by_id($id)
    {
		$this->db->select('servicecenter.*');
        $this->db->select('city.name as city_name,city.id as city_id,state.name as state_name,state.id as state_id,country.country_name as country_name,country.id as country_id,area.area_name as area_name,area.id as area_id');
		$this->db->from($this->table_name);
        $this->db->join('area', $this->table_name.'.area = area.id', 'left');
        $this->db->join('city', 'area.city_id = city.id', 'inner');
        $this->db->join('state', 'city.stateId = state.id', 'inner');
        $this->db->join('country', 'state.country_id = country.id', 'inner');
		$this->db->where('servicecenter.id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }
    
    public function is_servicecenter_exists($servicecenter,$area_id,$servicecenterid)
    {
        $this->db->select('id');
        $this->db->from($this->table_name);
        $this->db->where('name', $servicecenter);
        $this->db->where('area', $area_id);
        if($servicecenterid !=0)
            $this->db->where('id !='. $servicecenterid);
        $query = $this->db->get();
        return count($query->result_array());
    }

    /**
    * Fetch servicecenter data from the database
    * possibility to mix search, filter and order
    * @param string $area_id
	* @param string $search_string 
    * @param strong $order
    * @param string $order_type 
    * @param int $limit_start
    * @param int $limit_end
    * @return array
    */
    public function get_servicecenter($area_id=null,$search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select($this->table_name.'.*');
		$this->db->select('area.area_name');
        //$this->db->select('city.name as city_name,city.id as city_id,state.name as state_name,state.id as state_id,country.country_name as country_name,country.id as country_id,area.are_name as area_name,area.id as area_id');
		$this->db->from($this->table_name);
		if($area_id != null && $area_id != 0){
			$this->db->where('area', $area_id);
		}
		
		$this->db->join('area', $this->table_name.'.area = area.id', 'left');
        //$this->db->join('city', 'area.city_id = city.id', 'inner');
        //$this->db->join('state', 'city.stateId = state.id', 'inner');
        //$this->db->join('country', 'state.country_id = country.id', 'inner');

		$this->db->group_by($this->table_name.'.id');
		
		if($search_string){
			$this->db->where($this->table_name.".`name` like '".$search_string."'");
		}
		$this->db->group_by('id');

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('id', $order_type);
		}

        if($limit_start && $limit_end){
          $this->db->limit($limit_start, $limit_end);	
        }

        if($limit_start != null){
          $this->db->limit($limit_start, $limit_end);    
        }
        
		$query = $this->db->get();
//		echo "<pre>";
//		print_r($this->db->last_query());
//		echo "</pre>";
		return $query->result_array(); 	
    }

    /**
    * Count the number of rows
    * @param int $search_string
    * @param int $order
    * @return int
    */
    function count_servicecenter($area_id=null,$search_string=null, $order=null)
    {
		$this->db->select('*');
		$this->db->from($this->table_name);
		if($search_string){
			$this->db->where($this->table_name.".`name` like '".$search_string."'");
		}
		if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('id', 'Asc');
		}
		$query = $this->db->get();
//		echo "<pre>";
//		print_r($this->db->last_query());
//		echo "</pre>";
		return $query->num_rows();        
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function store_servicecenter($data)
    {
		$insert = $this->db->insert($this->table_name, $data);
	    return $this->db->insert_id();
	}

    /**
    * Update servicecenter
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_servicecenter($id, $data)
    {
		$this->db->where('id', $id);
		$this->db->update($this->table_name, $data);
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return true;
		}else{
			return false;
		}
	}

    /**
    * Delete servicecenter
    * @param int $id - servicecenter id
    * @return boolean
    */
	function delete_servicecenter($id){
		$this->db->where('id', $id);
		$this->db->delete($this->table_name); 
	}
 
}
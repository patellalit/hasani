<?php
class Area_model extends CI_Model {
    protected $table_name = "area";
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
    public function get_area_by_id($id)
    {
		$this->db->select('*');
		//$this->db->select('(select stateId from city where city.id='.$this->table_name.'.city_id) as stateId');
        $this->db->select('city.name as city_name,city.id as city_id,state.name as state_name,state.id as state_id,country.country_name as country_name,country.id as country_id');
		$this->db->from($this->table_name);
		$this->db->where($this->table_name.'.id', $id);
        $this->db->join('city', $this->table_name.'.city_id = city.id', 'inner');
        $this->db->join('state', 'city.stateId = state.id', 'inner');
        $this->db->join('country', 'state.country_id = country.id', 'inner');
		$query = $this->db->get();
		return $query->result_array(); 
    }    
    
    public function is_area_exists($area,$city_id,$areaid)
    {
        $this->db->select('id');
        $this->db->from($this->table_name);
        $this->db->where('name', $area);
        $this->db->where('city_id', $city_id);
        if($areaid !=0)
            $this->db->where('id !='. $areaid);
        $query = $this->db->get();
        return count($query->result_array());
    }
    /**
    * Fetch area data from the database
    * possibility to mix search, filter and order
	* @param string $city_id
    * @param string $search_string 
    * @param strong $order
    * @param string $order_type 
    * @param int $limit_start
    * @param int $limit_end
    * @return array
    */
    public function get_area($city_id=null,$search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select($this->table_name.'.*');
		//$this->db->select('city.name as city_name,(select name from state where state.id=city.stateId) as state_name');
        $this->db->select('city.name as city_name,city.id as city_id,state.name as state_name,state.id as state_id,country.country_name as country_name,country.id as country_id');
		
		$this->db->from($this->table_name);
		
		if($city_id != null && $city_id != 0){
			$this->db->where('city_id', $city_id);
		}

		$this->db->join('city', $this->table_name.'.city_id = city.id', 'inner');
        $this->db->join('state', 'city.stateId = state.id', 'inner');
        $this->db->join('country', 'state.country_id = country.id', 'inner');

		$this->db->group_by($this->table_name.'.id');

		if($search_string){
			$this->db->where($this->table_name.".`area_name` like '".$search_string."'");
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
        //print_r($this->db->last_query());
		return $query->result_array(); 	
    }

    /**
    * Count the number of rows
    * @param int $search_string
    * @param int $order
    * @return int
    */
    function count_area($city_id,$search_string=null, $order=null)
    {
		$this->db->select('*');
		$this->db->from($this->table_name);
		if($search_string){
			$this->db->where($this->table_name.".`area_name` like '".$search_string."'");
		}
		if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('id', 'Asc');
		}
		$query = $this->db->get();
		return $query->num_rows();        
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function store_area($data)
    {
		$insert = $this->db->insert($this->table_name, $data);
	    return $this->db->insert_id();
	}

    /**
    * Update area
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_area($id, $data)
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
    * Delete area
    * @param int $id - area id
    * @return boolean
    */
	function delete_area($id){
		$this->db->where('id', $id);
		$this->db->delete($this->table_name); 
	}
 
}
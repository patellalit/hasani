<?php
class City_model extends CI_Model {
    protected $table_name = "city";
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
    public function get_city_by_id($id)
    {
		$this->db->select('*');
		$this->db->select('(select country_id from state where state.id='.$this->table_name.'.stateId) as country_id');
		$this->db->from($this->table_name);
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }    
    
    public function is_city_exists($city,$stateId,$cityid)
    {
        $this->db->select('id');
        $this->db->from($this->table_name);
        $this->db->where('name', $city);
        $this->db->where('stateId', $stateId);
        if($cityid !=0)
            $this->db->where('id !='. $cityid);
        $query = $this->db->get();
        return count($query->result_array());
    }
    /**
    * Fetch city data from the database
    * possibility to mix search, filter and order
	* @param string $stateId
    * @param string $search_string 
    * @param strong $order
    * @param string $order_type 
    * @param int $limit_start
    * @param int $limit_end
    * @return array
    */
    public function get_city($stateId=null,$search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select($this->table_name.'.*');
		$this->db->select('state.name as state_name,(select country_name from country where country.id=state.country_id) as country_name');
		
		$this->db->from($this->table_name);
		
		if($stateId != null && $stateId != 0){
			$this->db->where('stateId', $stateId);
		}

		$this->db->join('state', $this->table_name.'.stateId = state.id', 'left');

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

		return $query->result_array(); 	
    }

    /**
    * Count the number of rows
    * @param int $search_string
    * @param int $order
    * @return int
    */
    function count_city($stateId,$search_string=null, $order=null)
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
		return $query->num_rows();        
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function store_city($data)
    {
		$insert = $this->db->insert($this->table_name, $data);
	    return $this->db->insert_id();
	}

    /**
    * Update city
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_city($id, $data)
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
    * Delete city
    * @param int $id - city id
    * @return boolean
    */
	function delete_city($id){
		$this->db->where('id', $id);
		$this->db->delete($this->table_name); 
	}
 
}
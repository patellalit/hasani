<?php
class Country_model extends CI_Model {
    protected $table_name = "country";
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
    public function get_country_by_id($id)
    {
		$this->db->select('*');
		$this->db->from($this->table_name);
		$this->db->where('id', $id);
		$query = $this->db->get();
        print_r($this->db->last_query());
		return $query->result_array(); 
    }    
    public function is_country_exists($country,$id=0)
    {
        $this->db->select('id');
        $this->db->from($this->table_name);
        $this->db->where('country_name', $country);
        if($id!=0)
            $this->db->where('id !='.$id);
        $query = $this->db->get();
        $rs = $query->result_array();
        return count($rs);
    
    }
    /**
    * Fetch country data from the database
    * possibility to mix search, filter and order
    * @param string $search_string 
    * @param strong $order
    * @param string $order_type 
    * @param int $limit_start
    * @param int $limit_end
    * @return array
    */
    public function get_country($search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {	    
		$this->db->select('*');
		$this->db->from($this->table_name);

		if($search_string){
			$this->db->where("`country_name` like '".$search_string."'");
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
    function count_country($search_string=null, $order=null)
    {
		$this->db->select('*');
		$this->db->from($this->table_name);
		if($search_string){
			$this->db->like('country_name', $search_string);
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
    function store_country($data)
    {
		$insert = $this->db->insert($this->table_name, $data);
	    return $this->db->insert_id();
	}

    /**
    * Update country
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_country($id, $data)
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
    * Delete country
    * @param int $id - country id
    * @return boolean
    */
	function delete_country($id){
		$this->db->where('id', $id);
		$this->db->delete($this->table_name); 
	}
 
}
?>	

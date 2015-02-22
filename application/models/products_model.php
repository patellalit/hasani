<?php
class Products_model extends CI_Model {
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
    public function get_product_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('plans');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    /**
    * Fetch products data from the database
    * possibility to mix search, filter and order
    * @param string $search_string 
    * @param strong $order
    * @param string $order_type 
    * @param int $limit_start
    * @param int $limit_end
    * @return array
    */
    public function get_products_api($search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select('id');
		$this->db->select('price');
		$this->db->select('plan_full_name as item');
		$this->db->from('plans');
		if($search_string){
			$this->db->like('plan_full_name', $search_string);
		}

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('id', $order_type);
		}
		if($limit_start && $limit_end)
			$this->db->limit($limit_start, $limit_end);
		//$this->db->limit('4', '4');

		$query = $this->db->get();
		
		return $query->result_array(); 	
    }

    /**
    * Count the number of rows
    * @param int $manufacture_id
    * @param int $search_string
    * @param int $order
    * @return int
    */
    function count_products_api($search_string=null, $order=null)
    {
		$this->db->select('*');
		$this->db->from('plans');
		if($search_string){
			$this->db->like('plan_full_name', $search_string);
		}
		$query = $this->db->get();
		return $query->num_rows();        
    }

    public function get_planss_with_package_api()
    {
	    
		$this->db->select('p.id as plan_id');
		$this->db->select('p.price');
		$this->db->select('p.plan_full_name as plan_name');
		$this->db->select('pk.id as package_id');
		$this->db->select('pk.package_name');
		$this->db->from('plans p')
				->join('packages pk', 'pk.id = p.package AND pk.status=1', 'inner');
		
		$this->db->order_by('pk.id', "ASC");
		$this->db->order_by('p.price', "ASC");
		$query = $this->db->get();

		return $query->result_array();
		
    }
}

                            
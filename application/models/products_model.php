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

    public function get_all_plans()
    {
        $this->db->select('id');
        $this->db->select('(select count(*) from productregistration where plan_id = plans.id) as count');
        $this->db->from('plans');
        
        $query = $this->db->get();
        return $query->result_array();
    }
    
    public function get_all_state()
    {
        $this->db->select('DISTINCT(state)');
        $this->db->from('productregistration');
        $query = $this->db->get();
        $rs = $query->result_array();
        //print_r($this->db->last_query());
        return $rs;
    }
    public function get_all_cities($state)
    {
        $this->db->select('DISTINCT(city)');
        $this->db->from('productregistration');
        $this->db->where('state',$state);
        $this->db->where('plan_id != 0');
        $query = $this->db->get();
        $rs = $query->result_array();
        //print_r($this->db->last_query());
        return $rs;
        
    }
    public function get_all_area($state,$city)
    {
        $this->db->select('DISTINCT(dealerName)');
        $this->db->from('productregistration');
        $this->db->where('state',$state);
        $this->db->where('city',$city);
        $this->db->where('plan_id != 0');
        $query = $this->db->get();
        $rs = $query->result_array();
        //print_r($this->db->last_query());
        return $rs;
        
    }
    public function getplan_count($value,$planid,$field)
    {
        $this->db->select('count(*) as count');
        $this->db->from('productregistration');
        $this->db->where($field,$value);
        $this->db->where('plan_id',$planid);
        $query = $this->db->get();
        $rs = $query->result_array();
        //print_r($this->db->last_query());exit;
        return $rs[0];
    }
    public function getplan_city_count($value,$state,$planid,$field)
    {
        $this->db->select('count(*) as count');
        $this->db->from('productregistration');
        $this->db->where('state',$state);
        $this->db->where($field,$value);
         $this->db->where('plan_id',$planid);
        $query = $this->db->get();
        $rs = $query->result_array();
        //print_r($this->db->last_query());exit;
        return $rs[0];
    }
    
    public function getplan_area_count($value,$city,$state,$planid,$field)
    {
        $this->db->select('count(*) as count');
        $this->db->from('productregistration');
        $this->db->where('state',$state);
        $this->db->where('city',$city);
        $this->db->where($field,$value);
        $this->db->where('plan_id',$planid);
        $query = $this->db->get();
        $rs = $query->result_array();
        //print_r($this->db->last_query());exit;
        return $rs[0];
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

                            
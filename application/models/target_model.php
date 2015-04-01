<?php

class Target_model extends CI_Model {
	/**
    * Responsable for auto load the database
    * @return void
    */
    public function __construct()
    {
        $this->load->database();
    }

    /**
    * Get user by his is
    * @param int $target_id 
    * @return array
    */
    public function get_target_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('target');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }
    
    public function check_duplicate($customer_id,$user_id)
    {
    	$this->db->select('*');
    	$this->db->from('target');
    	$this->db->where('customer_id', $customer_id);
    	$this->db->where('user_id', $user_id);
    	$query = $this->db->get();
    	if($query->num_rows() > 0)
    		return true;
    	else
    		return false;
    }

	/**
    * Fetch target data from the database
    * possibility to mix search, filter and order
    * @param string $search_string 
    * @param strong $order
    * @param string $order_type 
    * @param int $limit_start
    * @param int $limit_end
    * @return array
    */
    public function get_product_registration_by_cdkey_api($cdkey)
    {
	    
		$this->db->select('p.id as customer_id');
		$this->db->select('p.customerName as customer_name');
		$this->db->select('p.customerAddress as customer_address');
		$this->db->select('p.phoneNo as customer_phone');

		$this->db->select('ps.id as product_id');
		$this->db->select('ps.plan_full_name as item');
		
		$this->db->select('pk.package_name');

		$this->db->from('productregistration p')
			->join('login l', 'l.id = p.loginId', 'inner')
			->join('plans ps', 'ps.id = p.plan_id', 'inner')
			->join('packages pk', 'pk.id = ps.package', 'inner');

		$this->db->where("l.cdkey",$cdkey);
		$this->db->limit(1);

		$query = $this->db->get();
		
		return $query->result_array(); 
    }
    
	/**
    * Fetch target data from the database
    * possibility to mix search, filter and order
    * @param string $search_string 
    * @param strong $order
    * @param string $order_type 
    * @param int $limit_start
    * @param int $limit_end
    * @return array
    */
    public function get_target($date,$date_end,$search_string=null,$search_in, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
        
        $this->db->select('t.*');
        
        $this->db->select('p.id as customer_id');
        $this->db->select('p.customerName as customer_name');
        $this->db->select('p.customerAddress as customer_address');
        $this->db->select('CONCAT(first_name," ",last_name) as user_name',false);
        
        
        $this->db->from('target t')
        ->join('productregistration p', 'p.id = t.customer_id', 'inner')
        ->join('membership m', 'm.id = t.user_id', 'inner');
        
        if($date_end)
            $this->db->where('STR_TO_DATE(DATE_FORMAT(t.modified_at,"%Y-%m-%d"),"%Y-%m-%d") between "'.$date.'" and "'.$date_end.'"');
        else
            $this->db->where('DATE_FORMAT(t.modified_at,"%Y-%m-%d")',$date);
        
        if($search_string){
            if($search_in=='user_name')
            {
                
                $this->db->where('m.first_name like "'.$search_string.'" or last_name like "'.$search_string.'"');
            }
            else
                $this->db->like('p.customerName', $search_string);
        }
        
        if($order){
            $this->db->order_by($order, $order_type);
        }else{
            $this->db->order_by('t.id', $order_type);
        }
        if($limit_start)
            $this->db->limit($limit_start, $limit_end);
        //$this->db->limit('4', '4');
        
        $query = $this->db->get();
        
        $res_array = array();
        foreach ($query->result_array() as $row)
        {
            $res_array[] = $row;
        }
        //print_r($this->db->last_query());exit;
        return $res_array;
    }
    
    /**
     * Count the number of rows
     * @param int $manufacture_id
     * @param int $search_string
     * @param int $order
     * @return int
     */
    function count_target($date,$date_end,$search_string=null,$search_in)
    {
        $this->db->select('*');
        $this->db->from('target t')
        ->join('productregistration p', 'p.id = t.customer_id', 'inner')
         ->join('membership m', 'm.id = t.user_id', 'inner');
        if($date_end)
            $this->db->where('STR_TO_DATE(DATE_FORMAT(t.modified_at,"%Y-%m-%d"),"%Y-%m-%d") between "'.$date.'" and "'.$date_end.'"');
        else
            $this->db->where('DATE_FORMAT(t.modified_at,"%Y-%m-%d")',$date);
        
        if($search_string){
            if($search_in=='user_name')
            $this->db->where('m.first_name like "'.$search_string.'" or last_name like "'.$search_string.'"');
            else
            $this->db->like('p.customerName', $search_string);
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }
    public function get_target_api($search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select('t.id');

		$this->db->select('p.id as customer_id');
		$this->db->select('p.customerName as customer_name');
		$this->db->select('p.customerAddress as customer_address');
		$this->db->select('p.phoneNo as customer_phone');

		$this->db->select('ps.id as product_id');
		$this->db->select('ps.plan_full_name as item');
		
		$this->db->select('pk.package_name');

		$this->db->from('target t')
			->join('productregistration p', 'p.id = t.customer_id', 'inner')
			->join('plans ps', 'ps.id = p.plan_id', 'inner')
			->join('packages pk', 'pk.id = ps.package', 'inner');
        
        
        
		if($search_string){
			$this->db->like('p.customerName', $search_string);
		}
        
		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('t.id', $order_type);
		}
		if($limit_start)
		$this->db->limit($limit_start, $limit_end);
		//$this->db->limit('4', '4');

		$query = $this->db->get();
		
		$res_array = array();
		foreach ($query->result_array() as $row)
		{
		   $res_array[] = $row;
		}

		return $res_array; 	
    }

    /**
    * Count the number of rows
    * @param int $manufacture_id
    * @param int $search_string
    * @param int $order
    * @return int
    */
    function count_target_api($search_string=null, $order=null)
    {
		$this->db->select('*');
		$this->db->from('target t')
			->join('productregistration p', 'p.id = t.customer_id', 'inner')
			->join('plans ps', 'ps.id = p.plan_id', 'inner')
			->join('packages pk', 'pk.id = ps.package', 'inner');
		if($search_string){
			$this->db->like('p.customerName', $search_string);
		}
		if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('t.id', 'Asc');
		}
		$query = $this->db->get();
		return $query->num_rows();        
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function add_target_api($new_member_insert_data)
    {
		$insert = $this->db->insert('target', $new_member_insert_data);
	    return  $this->db->insert_id();
	}

    /**
    * Update target
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_target_api($id, $data)
    {
		$this->db->where('id', $id);
		$this->db->update('target', $data);
		$report = array();
		$report['error'] = $this->db->_error_number();
		$report['message'] = $this->db->_error_message();
		if($report !== 0){
			return $id;
		}else{
			return false;
		}
	}

    /**
    * Delete target
    * @param int $id - target id
    * @return boolean
    */
	function delete_target_api($id){
		$this->db->where('id', $id);
		$this->db->delete('target'); 
	}
}

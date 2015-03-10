<?php

class Dsr_model extends CI_Model {
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
    * @param int $dsr_id 
    * @return array
    */
    public function get_dsr_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('dsr');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }
    
    public function check_duplicate($customer_id,$user_id)
    {
    	$this->db->select('*');
    	$this->db->from('dsr');
    	$this->db->where('customer_id', $customer_id);
    	$this->db->where('user_id', $user_id);
    	$query = $this->db->get();
    	if($query->num_rows() > 0)
    		return true;
    	else
    		return false;
    }

    /**
    * Fetch dsr data from the database
    * possibility to mix search, filter and order
    * @param string $search_string 
    * @param strong $order
    * @param string $order_type 
    * @param int $limit_start
    * @param int $limit_end
    * @return array
    */
    public function get_dsr_api($search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select('dsr.id');
		$this->db->select('dsr.customer_id');
		$this->db->select('c.customer_name');

		$this->db->select('city.id as city_id');
		$this->db->select('city.name as city_name');
		
		$this->db->select('s.id as state_id');
		$this->db->select('s.name as state_name');

		$this->db->select('dsr.cheque_number');
		$this->db->select('dsr.bank_name');
		$this->db->select('dsr.cheque_date');

		$this->db->select('dsr.remarks');
		
		$this->db->from('dsr')
			->join('customers c', 'c.id = dsr.customer_id', 'inner')
			->join('city', 'city.id = c.city_id', 'inner')
			->join('state s', 's.id = city.stateId', 'inner');
		if($search_string){
			$this->db->like('c.customer_name', $search_string);
		}

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('dsr.id', $order_type);
		}
		if($limit_start)
		$this->db->limit($limit_start, $limit_end);
		//$this->db->limit('4', '4');

		$query = $this->db->get();
		
		$res_array = array();
		foreach ($query->result_array() as $row)
		{
			if($row["cheque_date"] && $row["cheque_date"] != "" && $row["cheque_date"] != null && $row["cheque_date"] != "0000-00-00")
		   		$row["cheque_date"] = date("d-m-Y",strtotime($row["cheque_date"]));
			else if($row["cheque_date"] == "0000-00-00")
				$row["cheque_date"] = null;

			$this->db->select('p.id as product_id');
			$this->db->select('p.plan_full_name as item');
			$this->db->select('d.qty');
			$this->db->select('d.price');
			
			$this->db->from('dsr_products d')
				->join('plans p', 'p.id = d.product_id', 'inner');
			$this->db->where('d.dsr_id',$row["id"]);

			$query = $this->db->get();
			$row["products"]=$query->result_array();
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
    function count_dsr_api($search_string=null, $order=null)
    {
		$this->db->select('*');
		$this->db->from('dsr')
			->join('customers c', 'c.id = dsr.customer_id', 'inner');
		if($search_string){
			$this->db->like('c.customer_name', $search_string);
		}
		if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('dsr.id', 'Asc');
		}
		$query = $this->db->get();
		return $query->num_rows();        
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function add_dsr_api($new_member_insert_data)
    {
		$insert = $this->db->insert('dsr', $new_member_insert_data);
	    return  $this->db->insert_id();
	}

	/**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function add_dsr_product_api($new_member_insert_data,$id=0)
    {
		if($id > 0){
			$this->db->select('*')
				 ->from('dsr_products')
				 ->where("id=".$id);

			$query = $this->db->get();
			if($query->num_rows() > 0){
				$this->update_dsr_product_api($id,$new_member_insert_data);
				return;	
			}
		}
		$insert = $this->db->insert('dsr_products', $new_member_insert_data);
	    return  $this->db->insert_id();
	}

	/**
    * Update dsr
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_dsr_product_api($id, $data)
    {
		$this->db->where('id', $id);
		$this->db->update('dsr_products', $data);
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
    * Update dsr
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_dsr_api($id, $data)
    {
		$this->db->where('id', $id);
		$this->db->update('dsr', $data);
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
    * Delete dsr
    * @param int $id - dsr id
    * @return boolean
    */
	function delete_dsr_api($id){
		$this->delete_dsr_product_api(0,$id);

		$this->db->where('id', $id);
		$this->db->delete('dsr');
		
	}

	/**
    * Delete dsr
    * @param int $id - dsr id
    * @return boolean
    */
	function delete_dsr_product_api($id=0,$dsr_id=0){
		if($dsr_id > 0)
			$this->db->where('dsr_id', $dsr_id);
		else if($id > 0)
			$this->db->where('id', $id);
		else 
			return false;
		$this->db->delete('dsr_products');
	}
}

                            
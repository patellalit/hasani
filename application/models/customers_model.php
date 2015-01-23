<?php

class Customers_model extends CI_Model {
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
    * @param int $customer_id 
    * @return array
    */
    public function get_customer_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('customers');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }

	public function get_area_api()
    {
	    
		$this->db->select('customers.id');
		$this->db->select('customers.ol_area');
		
		$this->db->from('customers')->group_by('customers.ol_area');
		$this->db->order_by("customers.ol_area","asc");

		$query = $this->db->get();
		
		$res_array = array();
		foreach ($query->result_array() as $row)
		{
		   $res_array[] = $row;
		}

		return $res_array; 	
    }

	public function get_customer_by_area_api($area_name)
    {
	    
		$this->db->select('customers.id');
		$this->db->select('customers.ol_area');
		$this->db->where('customers.ol_area',$area_name);
		
		$this->db->from('customers');
		$this->db->order_by("customers.ol_area","asc");

		$query = $this->db->get();
		
		$res_array = array();
		foreach ($query->result_array() as $row)
		{
		   $res_array[] = $row;
		}

		return $res_array; 	
    }

    /**
    * Fetch customers data from the database
    * possibility to mix search, filter and order
    * @param string $search_string 
    * @param strong $order
    * @param string $order_type 
    * @param int $limit_start
    * @param int $limit_end
    * @return array
    */
    public function get_customers_api($search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select('customers.id');
		$this->db->select('customers.customer_name');
		$this->db->select('customers.ol_name');
		$this->db->select('customers.ol_address');
		$this->db->select('customers.ol_area');
		$this->db->select('customers.mobile');
		
		$this->db->select('customers.email');
		$this->db->select('customers.cst_number');
		$this->db->select('customers.cst_date');
		$this->db->select('customers.gst_number');
		$this->db->select('customers.gst_date');
		$this->db->select('customers.photo');
		
		$this->db->from('customers');
		if($search_string){
			$this->db->like('customer_name', $search_string);
		}

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('id', $order_type);
		}
		if($limit_start)
		$this->db->limit($limit_start, $limit_end);
		//$this->db->limit('4', '4');

		$query = $this->db->get();
		
		$res_array = array();
		foreach ($query->result_array() as $row)
		{
			if($row["cst_date"] && $row["cst_date"] != "" && $row["cst_date"] != null && $row["cst_date"] != "0000-00-00")
		   		$row["cst_date"] = date("d-m-Y",strtotime($row["cst_date"]));
			else if($row["cst_date"] == "0000-00-00")
				$row["cst_date"] = null;

			if($row["gst_date"] && $row["gst_date"] != "" && $row["gst_date"] != null && $row["gst_date"] != "0000-00-00")
		   		$row["gst_date"] = date("d-m-Y",strtotime($row["gst_date"]));
			else if($row["gst_date"] == "0000-00-00")
				$row["gst_date"] = null;
			if($row["photo"] && $row["photo"] != "")
				$row["photo"] = base_url()."assets/uploads/".$row["photo"];

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
    function count_customers_api($search_string=null, $order=null)
    {
		$this->db->select('*');
		$this->db->from('customers');
		if($search_string){
			$this->db->like('customer_name', $search_string);
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
    function add_customer_api($new_member_insert_data)
    {
		$this->db->where('email', $new_member_insert_data['email']);
		$query = $this->db->get('customers');

        if($query->num_rows > 0){
        	return false;
		}else{

			$insert = $this->db->insert('customers', $new_member_insert_data);
		    return  $this->db->insert_id();
		}		
	}

    /**
    * Update customer
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_customer_api($id, $data)
    {
		$this->db->where('id', $id);
		$this->db->update('customers', $data);
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
    * Delete customer
    * @param int $id - customer id
    * @return boolean
    */
	function delete_customer_api($id){
		$this->db->where('id', $id);
		$this->db->delete('customers'); 
	}
}

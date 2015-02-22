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

	public function get_customer_by_city_api($city_id)
    {
	    
		$this->db->select('customers.id');
		$this->db->select('customers.customer_name');
$this->db->select('customers.ol_name');
		$this->db->where('customers.city_id',$city_id);
		
		$this->db->from('customers');
		$this->db->order_by("customers.customer_name","asc");

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
		$this->db->select('customers.mobile');
		$this->db->select('customers.email');
		$this->db->select('customers.cst_number');
		$this->db->select('customers.cst_date');
		$this->db->select('customers.gst_number');
		$this->db->select('customers.gst_date');
		$this->db->select('customers.photo');
		
		$this->db->select('customers.promoter');
		
		$this->db->select('customers.pan_number');
		$this->db->select('customers.pan_date');
		$this->db->select('customers.cin_number');
		$this->db->select('customers.cin_date');
		
		$this->db->select('customers.tl_id');
		$this->db->select('tlm.first_name as tl_first_name');
		$this->db->select('tlm.last_name as tl_last_name');
		
		$this->db->select('customers.isd_user_id');
		$this->db->select('isdm.first_name as isd_first_name');
		$this->db->select('isdm.last_name as isd_last_name');

		$this->db->select('city.id as city_id');
		$this->db->select('city.name as city_name');
		
		$this->db->select('f.id as firm_id');
		$this->db->select('f.name as firm_name');
	
		$this->db->select('state.id as state_id');
		$this->db->select('state.name as state_name');
		
		$this->db->from('customers')
			->join('city', 'city.id = customers.city_id', 'left')
			->join('state', 'state.id = city.stateId', 'left')
			->join('membership tlm', 'tlm.id = customers.tl_id', 'left')
			->join('membership isdm', 'isdm.id = customers.isd_user_id', 'left')
			->join('firms f', 'f.id = customers.firm_id', 'left');
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

			if($row["pan_date"] && $row["pan_date"] != "" && $row["pan_date"] != null && $row["pan_date"] != "0000-00-00")
		   		$row["pan_date"] = date("d-m-Y",strtotime($row["pan_date"]));
			else if($row["pan_date"] == "0000-00-00")
				$row["pan_date"] = null;

			if($row["cin_date"] && $row["cin_date"] != "" && $row["cin_date"] != null && $row["cin_date"] != "0000-00-00")
		   		$row["cin_date"] = date("d-m-Y",strtotime($row["cin_date"]));
			else if($row["cin_date"] == "0000-00-00")
				$row["cin_date"] = null;

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
		$this->db->from('customers')
			->join('city', 'city.id = customers.city_id', 'left')
			->join('state', 'state.id = city.stateId', 'left')
			->join('membership tlm', 'tlm.id = customers.tl_id', 'left')
			->join('membership isdm', 'isdm.id = customers.isd_user_id', 'left')
			->join('firms f', 'f.id = customers.firm_id', 'left');
		if($search_string){
			$this->db->like('customer_name', $search_string);
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

                            
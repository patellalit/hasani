<?php

class Trainee_model extends CI_Model {
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
    * @param int $trainee_id 
    * @return array
    */
    public function get_trainee_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('trainee');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    /**
    * Fetch trainee data from the database
    * possibility to mix search, filter and order
    * @param string $search_string 
    * @param strong $order
    * @param string $order_type 
    * @param int $limit_start
    * @param int $limit_end
    * @return array
    */
    public function get_trainee($search_string=null,$search_in=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
        
        $this->db->select('trainee.id');
        
        $this->db->select('c.id as customer_id');
        $this->db->select('c.customer_name');
        //$this->db->select('m.customer_name');
        $this->db->select('CONCAT(first_name," ",last_name) as user_name',false);
        //$this->db->select('c.ol_address as customer_address');
        //$this->db->select('c.mobile as customer_phone');
        
        /*$this->db->select('p.id as product_id');
         $this->db->select('p.plan_full_name as item');
         $this->db->select('p.price');*/
        
        $this->db->select('trainee.trainee_name');
        $this->db->select('trainee.trainee_mobile');
        $this->db->select('trainee.remarks');
        
        $this->db->from('trainee')
        ->join('customers c', 'c.id = trainee.customer_id', 'inner')
        ->join('membership m', 'm.id = trainee.user_id', 'inner');
        //->join('plans p', 'p.id = pr.plan_id', 'inner');
        
        if($search_string){
            if($search_in=='trainee_name')
                $this->db->like('trainee.trainee_name', $search_string);
            if($search_in=='trainee_mobile')
                $this->db->like('trainee.trainee_mobile', $search_string);
            if($search_in=='customer_name')
                $this->db->like('c.customer_name', $search_string);
            if($search_in=='user_name')
                $this->db->where('m.first_name like "'.$search_string.'" or last_name like "'.$search_string.'"');
        }
        
        if($order){
            $this->db->order_by($order, $order_type);
        }else{
            $this->db->order_by('trainee.id', $order_type);
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
    function count_trainee($search_string=null,$search_in=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('trainee')
        ->join('customers c', 'c.id = trainee.customer_id', 'inner')
        ->join('membership m', 'm.id = trainee.user_id', 'inner');//->join('plans p', 'p.id = pr.plan_id', 'inner');
        
        if($search_string){
            if($search_in=='trainee_name')
                $this->db->like('trainee.trainee_name', $search_string);
            if($search_in=='trainee_mobile')
                $this->db->like('trainee.trainee_mobile', $search_string);
            if($search_in=='customer_name')
                $this->db->like('c.customer_name', $search_string);
            if($search_in=='user_name')
                $this->db->where('m.first_name like "'.$search_string.'" or last_name like "'.$search_string.'"');
        }
        if($order){
            $this->db->order_by($order, 'Asc');
        }else{
            $this->db->order_by('trainee.id', 'Asc');
        }
        $query = $this->db->get();
        return $query->num_rows();
    }
    public function get_trainee_api($search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select('trainee.id');
		
		$this->db->select('c.id as customer_id');
		$this->db->select('c.customer_name');
		$this->db->select('c.ol_address as customer_address');
		$this->db->select('c.mobile as customer_phone');
		$this->db->select('c.ol_name');

		/*$this->db->select('p.id as product_id');
		$this->db->select('p.plan_full_name as item');
		$this->db->select('p.price');*/

		$this->db->select('trainee.trainee_name');
		$this->db->select('trainee.trainee_mobile');
		$this->db->select('trainee.remarks');

		$this->db->from('trainee')
			->join('customers c', 'c.id = trainee.customer_id', 'inner');
			//->join('plans p', 'p.id = pr.plan_id', 'inner');

		if($search_string){
			$this->db->like('trainee.trainee_name', $search_string);
		}

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('trainee.id', $order_type);
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
    function count_trainee_api($search_string=null, $order=null)
    {
		$this->db->select('*');
		$this->db->from('trainee')
			->join('customers c', 'c.id = trainee.customer_id', 'inner');
			//->join('plans p', 'p.id = pr.plan_id', 'inner');

		if($search_string){
			$this->db->like('trainee.trainee_name', $search_string);
		}
		if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('trainee.id', 'Asc');
		}
		$query = $this->db->get();
		return $query->num_rows();        
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function add_trainee_api($new_member_insert_data)
    {
		$insert = $this->db->insert('trainee', $new_member_insert_data);
	    return  $this->db->insert_id();
	}

    /**
    * Update trainee
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_trainee_api($id, $data)
    {
		$this->db->where('id', $id);
		$this->db->update('trainee', $data);
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
    * Delete trainee
    * @param int $id - trainee id
    * @return boolean
    */
	function delete_trainee_api($id){
		$this->db->where('id', $id);
		$this->db->delete('trainee'); 
	}
}

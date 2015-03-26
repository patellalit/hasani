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
    
    public function get_dsr_products($id)
    {
        $this->db->select('*');
        $this->db->from('dsr');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $rs = $query->result_array();
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
            $this->db->select('pk.package_name');
            
            $this->db->from('dsr_products d')
            ->join('plans p', 'p.id = d.product_id', 'inner')
            ->join('packages pk', 'pk.id = p.package', 'inner');
            
            
            
            $this->db->where('d.dsr_id',$row["id"]);
            
            $query = $this->db->get();
            //print_r($this->db->last_query());
            $row["products"]=$query->result_array();
            $res_array[] = $row;
        }
        
        return $res_array;
        
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
    public function get_dsr($date,$date_end,$search_string=null,$searchin, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
        
        $this->db->select('dsr.id');
        $this->db->select('dsr.customer_id');
        $this->db->select('c.customer_name');
        $this->db->select('c.ol_name');
        
        $this->db->select('city.id as city_id');
        $this->db->select('city.name as city_name');
        
        $this->db->select('s.id as state_id');
        $this->db->select('s.name as state_name');
        
        $this->db->select('dsr.cheque_number');
        $this->db->select('dsr.bank_name');
        $this->db->select('dsr.cheque_date');
        $this->db->select('dsr.payment_by');
        $this->db->select('dsr.modified_at');
        
        $this->db->select('dsr.remarks');
        
        $this->db->from('dsr')
        ->join('customers c', 'c.id = dsr.customer_id', 'inner')
        ->join('city', 'city.id = c.city_id', 'inner')
        ->join('state s', 's.id = city.stateId', 'inner');
        
        if($date_end)
            $this->db->where('STR_TO_DATE(DATE_FORMAT(dsr.modified_at,"%Y-%m-%d"),"%Y-%m-%d") between "'.$date.'" and "'.$date_end.'"');
        else
            //$this->db->where('DATE_FORMAT(dsr.modified_at,"%Y-%m-%d")',$date);
        
        if($search_string){
            if($searchin=='dsr.id')
                $this->db->where('dsr.id',$search_string);
            elseif($searchin=='c.customer_name')
                $this->db->where('c.customer_name',$search_string);
            elseif($searchin=='user_name')
                $this->db->where('m.first_name like "'.$search_string.'" or last_name like "'.$search_string.'"');
            elseif($searchin=='dsr.payment_by')
                $this->db->where('dsr.payment_by',$search_string);
            elseif($searchin=='dsr.cheque_number')
                $this->db->where('dsr.cheque_number',$search_string);
            elseif($searchin=='dsr.bank_name')
                $this->db->where('dsr.bank_name',$search_string);
            elseif($searchin=='dsr.cheque_date')
                $this->db->where('dsr.cheque_date',$search_string);
            elseif($searchin=='dsr.remarks')
                $this->db->where('dsr.remarks',$search_string);
            else
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
        //print_r($this->db->last_query());
        //echo $limit_end;
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
            $this->db->select('pk.package_name');
            
            $this->db->from('dsr_products d')
            ->join('plans p', 'p.id = d.product_id', 'inner')
            ->join('packages pk', 'pk.id = p.package', 'inner');
            
            
            
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
    function count_dsr($date,$date_end,$search_string=null,$searchin, $order=null)
    {
        $this->db->select('*');
        $this->db->from('dsr')
        ->join('customers c', 'c.id = dsr.customer_id', 'inner');
        if($date_end)
            $this->db->where('STR_TO_DATE(DATE_FORMAT(dsr.modified_at,"%Y-%m-%d"),"%Y-%m-%d") between "'.$date.'" and "'.$date_end.'"');
        else
            //$this->db->where('DATE_FORMAT(dsr.modified_at,"%Y-%m-%d")',$date);
        if($search_string){
            if($searchin=='dsr.id')
                $this->db->where('dsr.id',$search_string);
            elseif($searchin=='c.customer_name')
            $this->db->where('c.customer_name',$search_string);
            elseif($searchin=='user_name')
            $this->db->where('m.first_name like "'.$search_string.'" or last_name like "'.$search_string.'"');
            elseif($searchin=='dsr.payment_by')
            $this->db->where('dsr.payment_by',$search_string);
            elseif($searchin=='dsr.cheque_number')
            $this->db->where('dsr.cheque_number',$search_string);
            elseif($searchin=='dsr.bank_name')
            $this->db->where('dsr.bank_name',$search_string);
            elseif($searchin=='dsr.cheque_date')
            $this->db->where('dsr.cheque_date',$search_string);
            elseif($searchin=='dsr.remarks')
            $this->db->where('dsr.remarks',$search_string);
            else
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
    public function get_dsr_api($search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select('dsr.id');
		$this->db->select('dsr.customer_id');
		$this->db->select('c.customer_name');
		$this->db->select('c.ol_name');

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
			$this->db->select('pk.package_name');
			
			$this->db->from('dsr_products d')
				->join('plans p', 'p.id = d.product_id', 'inner')
				->join('packages pk', 'pk.id = p.package', 'inner');
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

                            
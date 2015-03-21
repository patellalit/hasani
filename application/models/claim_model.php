<?php

class Claim_model extends CI_Model {
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
    * @param int $claim_id 
    * @return array
    */
    public function get_claim_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('claim');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }
    
    /**
     * Get user by his is
     * @param int $claim_id
     * @return array
     */
    public function check_duplicate_claim($customer_id,$user_id)
    {
    	$this->db->select('*');
    	$this->db->from('claim');
    	$this->db->where('target_customer_id', $customer_id);
    	$this->db->where('user_id', $user_id);
    	$this->db->where('status != ', 5);
    	$query = $this->db->get();
    	if( $query->num_rows() > 0)
    		return true;
    	else 
    		return false;
    }
    
    /**
     * Fetch claim data from the database
     * possibility to mix search, filter and order
     * @param string $search_string
     * @param strong $order
     * @param string $order_type
     * @param int $limit_start
     * @param int $limit_end
     * @return array
     */
    public function get_claim($date,$date_end=null,$search_string=null,$searchin=null,$searchstatus=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null,$user_id=null)
    {
        //echo $order;exit;
        $this->db->select('ct.id as id');
        
        $this->db->select('pr.id as customer_id');
        $this->db->select('pr.customerName as customer_name');
        $this->db->select('pr.customerAddress as customer_address');
        $this->db->select('pr.phoneNo as customer_phone');
        
        $this->db->select('p.id as product_id');
        $this->db->select('p.plan_full_name as item');
        $this->db->select('p.price');
        $this->db->select('(select `name` from servicecenter where servicecenter.id=ct.service_center_id) as service_center');
        
        $this->db->select('CONCAT(m.first_name," ",m.last_name) as user_name',false);
        
        $this->db->select('ct.*');
        
        $this->db->from('claim_track ct')
        ->join('claim', 'ct.claim_id = claim.id', 'inner')
        ->join('productregistration pr', 'pr.id = claim.target_customer_id', 'inner')
        ->join('plans p', 'p.id = pr.plan_id', 'inner')
        ->join('membership m', 'm.id = ct.user_id', 'inner');
        
        //echo $searchstatus;exit;
        if($searchstatus!=0)
        {
            $this->db->where('ct.status',$searchstatus);
        }
        if($date_end)
            $this->db->where('STR_TO_DATE(DATE_FORMAT(ct.modified_at,"%Y-%m-%d"),"%Y-%m-%d") between "'.$date.'" and "'.$date_end.'"');
        else
            $this->db->where('DATE_FORMAT(ct.modified_at,"%Y-%m-%d")',$date);
        
        if($search_string){
            if($searchin=='claim_id')
                $this->db->where('ct.claim_id',$search_string);
            elseif($searchin=='customer_name')
                $this->db->where('pr.customerName',$search_string);
            elseif($searchin=='user_name')
                $this->db->where('m.first_name like "'.$search_string.'" or last_name like "'.$search_string.'"');
            elseif($searchin=='recieve_person_name')
                $this->db->where('submit_to_person_name',$search_string);
            elseif($searchin=='recieve_person_phone')
                $this->db->where('submit_to_person_phone',$search_string);
            elseif($searchin=='state')
                $this->db->where('pr.state',$search_string);
            elseif($searchin=='city')
                $this->db->where('pr.city',$search_string);
            elseif($searchin=='area')
                $this->db->where('pr.area',$search_string);
            elseif($searchin=='jobsheet_no')
                $this->db->where('jobsheet_no',$search_string);
            else
                $this->db->where('pr.customerName like "'.$search_string.'" or m.first_name like "'.$search_string.'" or last_name like "'.$search_string.'"');
        }
        //echo $order;exit;
        if($order){
            $this->db->order_by($order, $order_type);
        }else{
            $this->db->order_by('ct.id', 'DESC');
        }
        $this->db->order_by('ct.claim_id', 'DESC');
        //$this->db->group_by('ct.claim_id', $order_type);
        if($limit_start)
            $this->db->limit($limit_start, $limit_end);
        //$this->db->limit('4', '4');
        
        $query = $this->db->get();
        //print_r($this->db->last_query());//exit;
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
    function count_claim($date,$date_end=null,$search_string=null,$searchin=null,$searchstatus=null, $order=null,$user_id=null)
    {
        $this->db->select('*');
        $this->db->from('claim_track ct')
        ->join('claim', 'ct.claim_id = claim.id', 'inner')
        ->join('productregistration pr', 'pr.id = claim.target_customer_id', 'inner')
        ->join('plans p', 'p.id = pr.plan_id', 'inner')
        ->join('membership m', 'm.id = ct.user_id', 'inner');
        
        if($date_end)
            $this->db->where('STR_TO_DATE(DATE_FORMAT(ct.modified_at,"%Y-%m-%d"),"%Y-%m-%d") between "'.$date.'" and "'.$date_end.'"');
        else
            $this->db->where('DATE_FORMAT(ct.modified_at,"%Y-%m-%d")',$date);
        
        
        if($searchstatus!=0)
        {
            $this->db->where('ct.status',$searchstatus);
        }
        
        if($search_string){
            if($searchin=='claim_id')
                $this->db->where('ct.claim_id',$search_string);
            elseif($searchin=='customer_name')
                $this->db->where('pr.customerName',$search_string);
            elseif($searchin=='user_name')
                $this->db->where('m.first_name like "'.$search_string.'" or last_name like "'.$search_string.'"');
            elseif($searchin=='recieve_person_name')
                $this->db->where('submit_to_person_name',$search_string);
            elseif($searchin=='recieve_person_phone')
                $this->db->where('submit_to_person_phone',$search_string);
            elseif($searchin=='state')
                $this->db->where('pr.state',$search_string);
            elseif($searchin=='city')
                $this->db->where('pr.city',$search_string);
            elseif($searchin=='area')
                $this->db->where('pr.area',$search_string);
            elseif($searchin=='jobsheet_no')
                $this->db->where('jobsheet_no',$search_string);
            else
                $this->db->where('pr.customerName like "'.$search_string.'" or m.first_name like "'.$search_string.'" or last_name like "'.$search_string.'"');
        }
        if($order){
            $this->db->order_by($order, 'Asc');
        }else{
            $this->db->order_by('ct.id', 'Asc');
        }
        $query = $this->db->get();
        return $query->num_rows();        
    }
    
    /**
    * Fetch claim data from the database
    * possibility to mix search, filter and order
    * @param string $search_string 
    * @param strong $order
    * @param string $order_type 
    * @param int $limit_start
    * @param int $limit_end
    * @return array
    */
    public function get_claim_api($search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null,$user_id=null)
    {
	    
		$this->db->select('claim.id');

		$this->db->select('pr.id as customer_id');
		$this->db->select('pr.customerName as customer_name');
		$this->db->select('pr.customerAddress as customer_address');
		$this->db->select('pr.phoneNo as customer_phone');

		$this->db->select('p.id as product_id');
		$this->db->select('p.plan_full_name as item');
		$this->db->select('p.price');

		$this->db->select('claim.remarks');
		$this->db->select('claim.status');

		$this->db->from('claim')
			->join('claim_track ct', 'ct.claim_id = claim.id AND ct.status=1', 'inner')
			->join('productregistration pr', 'pr.id = claim.target_customer_id', 'inner')
			->join('plans p', 'p.id = pr.plan_id', 'inner');
		
		$this->db->where('claim.status',1);
		if($user_id)
			$this->db->where('claim.user_id',$user_id);

		if($search_string){
			$this->db->like('pr.customerName', $search_string);
		}

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('claim.id', $order_type);
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
    function count_claim_api($search_string=null, $order=null,$user_id=null)
    {
		$this->db->select('*');
		$this->db->from('claim')
			->join('claim_track ct', 'ct.claim_id = claim.id AND ct.status=1', 'inner')
			->join('productregistration pr', 'pr.id = claim.target_customer_id', 'inner')
			->join('plans p', 'p.id = pr.plan_id', 'inner');
		
		$this->db->where('claim.status',1);
		if($user_id)
			$this->db->where('claim.user_id',$user_id);

		if($search_string){
			$this->db->like('pr.customerName', $search_string);
		}
		if($order){
			$this->db->order_by($order, 'Asc');
		}else{
		    $this->db->order_by('claim.id', 'Asc');
		}
		$query = $this->db->get();
		return $query->num_rows();        
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function add_claim_api($new_member_insert_data)
    {
    	if($this->check_duplicate_claim($this->input->post('target_customer_id'),$new_member_insert_data['user_id']))
    		return false;
		$insert = $this->db->insert('claim', $new_member_insert_data);
		$claim_id = $this->db->insert_id();
		$new_member_insert_data = array(
					'claim_id' => $claim_id,
					'remarks' => $new_member_insert_data['remarks'],
					'status' => 1,
					'user_id' => $new_member_insert_data['user_id'],
					'created_at' => date("Y-m-d H:i:s"),
				);

		$this->add_claim_track_api($new_member_insert_data);
	    return  $claim_id;
	}

    /**
    * Update claim
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_claim_api($id, $data)
    {
		$this->db->where('id', $id);
		$this->db->update('claim', $data);
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
    * Delete claim
    * @param int $id - claim id
    * @return boolean
    */
	function delete_claim_api($id){
		$this->db->where('id', $id);
		$this->db->delete('claim'); 
	}
	
	
    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function add_claim_track_api($new_member_insert_data)
    {
		$this->db->select('*');
		$this->db->from('claim_track');
		$this->db->where('claim_id',$new_member_insert_data["claim_id"]);
		$this->db->where('user_id',$new_member_insert_data["user_id"]);
		$this->db->where('status',$new_member_insert_data["status"]);
		$query = $this->db->get();
		if($query->num_rows() > 0)
			return false;

		$insert = $this->db->insert('claim_track', $new_member_insert_data);
		$inser_id = $this->db->insert_id();
		
		$this->db->where('id', $new_member_insert_data["claim_id"]);
		$data = array("status"=>$new_member_insert_data["status"]);
		$this->db->update('claim', $data);
		
	    return  $inser_id;
	}

	function get_claim_track_api($status){
	     $this->db->select('claim.id');
	
			$this->db->select('pr.id as customer_id');
			$this->db->select('pr.customerName as customer_name');
			$this->db->select('pr.customerAddress as customer_address');
			$this->db->select('pr.phoneNo as customer_phone');
	
			$this->db->select('p.id as product_id');
			$this->db->select('p.plan_full_name as item');
			$this->db->select('p.price');
	
			$this->db->select('claim.remarks');
			$this->db->select('claim.status');
			
			$this->db->select('l.cdkey');
			$this->db->select('pk.package_name');
	
			$this->db->from('claim')
				->join('claim_track ct', 'ct.claim_id = claim.id AND ct.status=1', 'inner')
				->join('productregistration pr', 'pr.id = claim.target_customer_id', 'inner')
				->join('login l', 'l.id = pr.loginId', 'inner')
				->join('plans p', 'p.id = pr.plan_id', 'inner')
				->join('packages pk', 'pk.id = p.package', 'inner');
			
			$this->db->where('claim.status',$status);
			
			$this->db->order_by('claim.id', "DESC");
			
			$query = $this->db->get();
			
			return $query->result_array(); 
	}
	
	function count_claim_track_api($status){
		$this->db->select('*');
	
		$this->db->from('claim')
		->join('claim_track ct', 'ct.claim_id = claim.id AND ct.status=1', 'inner')
		->join('productregistration pr', 'pr.id = claim.target_customer_id', 'inner')
		->join('plans p', 'p.id = pr.plan_id', 'inner');
			
		$this->db->where('claim.status',$status);
			
		$query = $this->db->get();
			
		return $query->num_rows();
	}
	
	function check_valid_status_claim_track($claim_id,$status){
		$this->db->select('*');
	
		$this->db->from('claim');
			
		$this->db->where('claim.status',$status);
		$this->db->where('claim.id',$claim_id);
			
		$query = $this->db->get();
			
		return $query->num_rows();
	}
}
                            
                            
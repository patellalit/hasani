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
		$insert = $this->db->insert('claim', $new_member_insert_data);
		$claim_id = $this->db->insert_id();
		$new_member_insert_data = array(
					'claim_id' => $claim_id,
					'remarks' => $new_member_insert_data['remarks'],
					'status' => $new_member_insert_data['status'],
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
	
			$this->db->from('claim')
				->join('claim_track ct', 'ct.claim_id = claim.id AND ct.status=1', 'inner')
				->join('productregistration pr', 'pr.id = claim.target_customer_id', 'inner')
				->join('plans p', 'p.id = pr.plan_id', 'inner');
			
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
}
                            
                            
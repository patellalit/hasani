<?php

class Users_model extends CI_Model {
	/**
    * Responsable for auto load the database
    * @return void
    */
    public function __construct()
    {
        $this->load->database();
    }

    /**
    * Validate the login's data with the database
    * @param string $user_name
    * @param string $password
    * @return void
    */
	function validate($user_name, $password)
	{
		$this->db->select('id');
		$this->db->select('first_name');
		$this->db->select('last_name');
		$this->db->select('email_address');
		$this->db->select('user_name');
		$this->db->select('mobile');
		$this->db->where('role', 1);
		$this->db->where('user_name', $user_name);
		$this->db->where('pass_word', $password);
		$query = $this->db->get('membership');
		
		if($query->num_rows == 1)
		{
			return $query->result_array();
		}
		return false;
	}
	
    /**
    * Serialize the session data stored in the database, 
    * store it in a new array and return it to the controller 
    * @return array
    */
	function get_db_session_data()
	{
		$query = $this->db->select('user_data')->get('ci_sessions');
		$user = array(); /* array to store the user data we fetch */
		foreach ($query->result() as $row)
		{
		    $udata = unserialize($row->user_data);
		    /* put data in array using username as key */
		    $user['user_name'] = $udata['user_name']; 
		    $user['is_logged_in'] = $udata['is_logged_in']; 
		}
		return $user;
	}
	
    /**
    * Get user by his is
    * @param int $user_id 
    * @return array
    */
    public function get_user_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('membership');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }

    /**
    * Fetch users data from the database
    * possibility to mix search, filter and order
    * @param string $search_string 
    * @param strong $order
    * @param string $order_type 
    * @param int $limit_start
    * @param int $limit_end
    * @return array
    */
    public function get_users($search_string=null, $order=null, $order_type='Asc', $limit_start, $limit_end)
    {
	    
		$this->db->select('membership.id');
		$this->db->select('membership.first_name');
		$this->db->select('membership.last_name');
		$this->db->select('membership.email_address');
		$this->db->select('membership.user_name');
		$this->db->select('membership.mobile');
		$this->db->from('membership');
		if($search_string){
			$this->db->like('first_name', $search_string);
		}

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('id', $order_type);
		}

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
    function count_users($search_string=null, $order=null)
    {
		$this->db->select('*');
		$this->db->from('membership');
		if($search_string){
			$this->db->like('first_name', $search_string);
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
    * Validate the login's data with the database
    * @param string $user_name
    * @param string $password
    * @return void
    */
	function validate_api($email, $password)
	{
		$this->db->select('id');
		$this->db->select('first_name');
		$this->db->select('last_name');
		$this->db->select('email_address');
		$this->db->select('user_name');
		$this->db->select('mobile');
		$this->db->select('role');
		$this->db->where('email_address', $email);
		$this->db->where('pass_word', $password);
		$query = $this->db->get('membership');
		
		if($query->num_rows == 1)
		{
			return $query->result_array();
		}
		return false;
	}

	public function get_users_api($search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select('membership.id');
		$this->db->select('membership.first_name');
		$this->db->select('membership.last_name');
		$this->db->select('membership.email_address');
		$this->db->select('membership.user_name');
		$this->db->select('membership.mobile');
		$this->db->select('membership.role');
		$this->db->select('membership.ol_name');
		$this->db->select('membership.ol_area');
		$this->db->select('membership.address');
		$this->db->select('membership.personal_email');
		$this->db->select('membership.personal_phone');
		
		$this->db->from('membership');
		
		$this->db->where('membership.role > 1');
		
		if($search_string){
			$this->db->like('first_name', $search_string);
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
		
		return $query->result_array(); 	
    }

	function count_users_api($search_string=null, $order=null)
    {
		$this->db->select('*');
		$this->db->from('membership');
		$this->db->where('role > 1');
		
		if($search_string){
			$this->db->like('first_name', $search_string);
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
    function add_user_api($new_member_insert_data)
    {
		$this->db->where('email_address', $new_member_insert_data["email_address"]);
		$query = $this->db->get('membership');

        if($query->num_rows > 0){
        	return false;
		}else{
			$insert = $this->db->insert('membership', $new_member_insert_data);
		    return  $this->db->insert_id();
		}		
	}

    /**
    * Update user
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_user_api($id, $data)
    {
		$this->db->where('id', $id);
		$this->db->update('membership', $data);
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
    * Delete user
    * @param int $id - user id
    * @return boolean
    */
	function delete_user_api($id){
		$this->db->where('id', $id);
		$this->db->delete('membership'); 
	}
}

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
    * Validate the login's data with the database
    * @param string $user_name
    * @param string $password
    * @return void
    */
	function validateApi($email, $password)
	{
		$this->db->select('id');
		$this->db->select('first_name');
		$this->db->select('last_name');
		$this->db->select('email_address');
		$this->db->select('user_name');
		$this->db->select('mobile');
		$this->db->where('email_address', $email);
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
    * Store the new user's data into the database
    * @return boolean - check the insert
    */	
	function create_member()
	{

		$this->db->where('user_name', $this->input->post('username'));
		$query = $this->db->get('membership');

        if($query->num_rows > 0){
        	echo '<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>';
			  echo "Username already taken";	
			echo '</strong></div>';
		}else{

			$new_member_insert_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'email_addres' => $this->input->post('email_address'),			
				'user_name' => $this->input->post('username'),
				'pass_word' => md5($this->input->post('password')),
				'mobile' => $this->input->post('mobile')
			);
			$insert = $this->db->insert('membership', $new_member_insert_data);
		    return $insert;
		}
	      
	}//create_member

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
	
	public function get_api_users($search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
	    
		$this->db->select('membership.id');
		$this->db->select('membership.first_name');
		$this->db->select('membership.last_name');
		$this->db->select('membership.email_address');
		$this->db->select('membership.user_name');
		$this->db->select('membership.mobile');
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
	
	function count_api_users($search_string=null, $order=null)
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
    function store_user()
    {
		$this->db->where('email_address', $this->input->post('email'));
		$query = $this->db->get('membership');

        if($query->num_rows > 0){
        	return false;
		}else{

			$new_member_insert_data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'email_address' => $this->input->post('email'),			
				//'user_name' => $this->input->post('username'),
				'pass_word' => md5($this->input->post('password')),
				'mobile' => $this->input->post('mobile'),
				'role' => (int)$this->input->post('role'),
				'ol_name' => $this->input->post('ol_name'),
				'ol_area' => $this->input->post('ol_area'),
				'address' => $this->input->post('address'),
				'personal_email' => $this->input->post('personal_email'),
				'personal_phone' => $this->input->post('personal_phone'),
			);
			$insert = $this->db->insert('membership', $new_member_insert_data);
		    return  $this->db->insert_id();
		}		
	}

    /**
    * Update user
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_user($id, $data)
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
	function delete_user($id){
		$this->db->where('id', $id);
		$this->db->delete('membership'); 
	}
}


                            
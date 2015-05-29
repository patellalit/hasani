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
	function is_phone_email_exists($phone,$personal_phone,$email,$personal_email,$id=0)
    {
        $this->db->select('id',false);
        $where='';
        if($id!=0)
        {
            //$this->db->where("id!=",(int)$id);
            $where = " and id != ".(int)$id;
        }
        $this->db->where("(email_address = '".$email."' or personal_email = '".$personal_email."' or mobile = '".$phone."' or personal_phone = '".$personal_phone."') ".$where);
        
        
        $query = $this->db->get('membership');
       // print_r($this->db->last_query());exit;
        if($query->num_rows >= 1)
        {
            return $query->result_array();
            
        }
        
        return array();
    }
	function is_login($user_id){
		$this->db->select('id');
		$this->db->where('id', $user_id);
		$this->db->where('is_logged_in', 1);
		$query = $this->db->get('membership');
		
		if($query->num_rows == 1)
		{
			return true;
		}
		return false;
	}
    
    function get_roles()
    {
        $query = $this->db->select('*')->get('roles');
        $user = $query->result_array(); /* array to store the user data we fetch */
        
        return $user;
    }
    function get_user_by_role_id($role_id)
    {
        $query = $this->db->select('parent_role_id')->where('id',$role_id)->get('roles');
        $role = $query->result_array(); /* array to store the user data we fetch */
        $user=array();
        if(!empty($role))
        {
            $query = $this->db->select('*')->where('role',$role[0]['parent_role_id'])->get('membership');
            $user = $query->result_array(); /* array to store the user data we fetch */
        }
        return $user;
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
        $this->db->select('city.name as city_name,city.id as city_id,state.name as state_name,state.id as state_id,country.country_name as country_name,country.id as country_id,area.area_name as area_name,area.id as area_id');
		$this->db->from('membership');
        $this->db->join('area', 'membership.area_id = area.id', 'left');
        $this->db->join('city', 'area.city_id = city.id', 'left');
        $this->db->join('state', 'city.stateId = state.id', 'left');
        $this->db->join('country', 'state.country_id = country.id', 'left');
		$this->db->where('membership.id', $id);
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
	    
		$this->db->select('*');
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
                $this->db->select('is_logged_in');
		$this->db->where('pass_word', $password);
		
		$where = "(mobile='".$email."' or email_address='".$email."') ";
		$this->db->where($where);

		$query = $this->db->get('membership');
		
		if($query->num_rows == 1)
		{
			return $query->result_array();
		}
		return false;
	}
	
	public function get_users_by_role_api($role)
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
		
		$this->db->where('membership.role',$role);
		
		$this->db->order_by('membership.first_name', "ASC");
		$this->db->order_by('membership.last_name', "ASC");
		
		$query = $this->db->get();
		
		return $query->result_array(); 	
    }

public function get_users_api($params,$is_admin=false)
    {
    	$search_string=$params["search_string"];
    	$search_in=$params["search_in"];
    	$order=$params["sort"];
    	$order_type=$params["sort_dir"];
    	$offset=$params["offset"];
    	$limit=$params["limit"];
    	
		$this->db->select('m.id');
		$this->db->select('m.first_name');
		$this->db->select('m.last_name');
		$this->db->select('m.email_address');
		$this->db->select('m.user_name');
		$this->db->select('m.mobile');
		$this->db->select('m.ol_name');
		$this->db->select('m.ol_area');
		$this->db->select('m.address');
		$this->db->select('m.personal_email');
		$this->db->select('m.personal_phone');
		$this->db->select('r.role_name');
		$this->db->select('r.id as role_id');
		$this->db->select('m.is_logged_in');
		
		$this->db->from('membership m')
				->join('roles r', 'r.id = m.role', 'inner');

		//if(!$is_admin)
			//$this->db->where('m.role > 1');
		
		if($search_string && $search_in){
			$this->db->like($search_in, $search_string);
		}

		if($order && $order_type){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('m.id', $order_type);
		}

		if($limit !== null && $limit > 0){
			$this->db->limit($limit,$offset);
		}

		$query = $this->db->get();

		return $query->result_array(); 	
    }

	function count_users_api($params, $is_admin=false)
    {
    	$search_string=$params["search_string"];
    	$search_in=$params["search_in"];
    	
		$this->db->select('*');
		$this->db->from('membership m')
				->join('roles r', 'r.id = m.role', 'inner');
		//if(!$is_admin)
			//$this->db->where('m.role > 1');
		
		if($search_string){
			$this->db->like($search_in, $search_string);
		}
		
		return $this->db->count_all_results();
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
    function update_productregistration($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('productregistration', $data);
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
    function get_registered_user($id){
        
        $this->db->select('*,p.id as prid');
        $this->db->select('p.id as registraion_id');
        $this->db->select('pp.id as planid');
        
        $this->db->from('productregistration p')
        ->join('login l', 'l.id = p.loginId', 'inner')
        ->join('plans pp', 'pp.id = p.plan_id', 'inner')
        ->join('packages pk', 'pk.id = pp.package', 'inner');
        
        $this->db->where("p.id",$id);
        
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result_array();
    }
	function get_registered_users($params,$id=null){
		
		$search_string=$params["search_string"];
        $search_in=$params["search_in"];
		$search_from_date=$params["search_from_date"];
		$search_to_date=$params["search_to_date"];
		$order=$params["sort"];
		$order_type=$params["sort_dir"];
		$offset=$params["offset"];
		$limit=$params["limit"];

		$this->db->select('*,p.id as prid');
		$this->db->select('p.id as registraion_id');
		
		$this->db->from('productregistration p')
			->join('login l', 'l.id = p.loginId', 'inner')
			->join('plans pp', 'pp.id = p.plan_id', 'inner')
			->join('packages pk', 'pk.id = pp.package', 'inner');
        $this->db->where('pp.status','1');
        $this->db->where('pk.status','1');
        if(isset($params["selected_plan"]) && $params["selected_plan"] != '')
        {
            $this->db->where('p.plan_id',$params["selected_plan"]);
        }
		if($id != null){
			$this->db->where("p.id",$id);
		}else if($search_string && $search_in){
			$this->db->like($search_in, $search_string);
			/*$this->db->or_like('p.customerName', $search_string);
			$this->db->or_like('p.phoneNo', $search_string);
			$this->db->or_like('p.imeiNo', $search_string);
			$this->db->or_like('p.imeiNo2', $search_string);
			$this->db->or_like('p.billNo', $search_string);
			$this->db->or_like('pk.package_name', $search_string);
			$this->db->or_like('pp.plan_name', $search_string);*/
		}
		if($search_from_date){
			$this->db->where('p.planDate >=', $search_from_date);
		}
                if($search_to_date){
			$this->db->where('p.planDate <=', $search_to_date);
		}

		if($order){
			$this->db->order_by($order, $order_type);
		}else{
		    $this->db->order_by('p.id', "DESC");
		}

		if($limit !== null){
			$this->db->limit($limit,$offset);
		}

		$query = $this->db->get();
		if($id != null){
			//echo $this->db->last_query();
		}
        //echo $this->db->last_query();
		return $query->result_array(); 	
	}

	function get_registered_users_count($params,$is_search=false){
		$search_string=$params["search_string"];
		$search_in=$params["search_in"];
		$search_from_date=$params["search_from_date"];
		$search_to_date=$params["search_to_date"];
        $search_date=(isset($params["search_date"]))?$params["search_date"]:false;

		

		$this->db->select('*');
		$this->db->from('productregistration p')
			->join('login l', 'l.id = p.loginId', 'inner')
			->join('plans pp', 'pp.id = p.plan_id', 'inner')
			->join('packages pk', 'pk.id = pp.package', 'inner');
        $this->db->where('pp.status','1');
        $this->db->where('pk.status','1');
        if(isset($params["selected_plan"]) && $params["selected_plan"] != '')
        {
            $this->db->where('p.plan_id',$params["selected_plan"]);
        }
		if($search_string && $search_in && $is_search){
			$this->db->like($search_in, $search_string);
			/*$this->db->or_like('p.customerName', $search_string);
			$this->db->or_like('p.phoneNo', $search_string);
			$this->db->or_like('p.imeiNo', $search_string);
			$this->db->or_like('p.imeiNo2', $search_string);
			$this->db->or_like('p.billNo', $search_string);
			$this->db->or_like('pk.package_name', $search_string);
			$this->db->or_like('pp.plan_name', $search_string);*/
		}
		if($search_from_date && !$search_date){
			$this->db->where('p.planDate >=', $search_from_date);
		}
                if($search_to_date && !$search_date){
			$this->db->where('p.planDate <=', $search_to_date);
		}
		if($search_date){
			$this->db->where('p.planDate', $search_date);
		}
		return $this->db->count_all_results();
	}

	function get_registered_users_total_bill($params,$type,$is_search=false){
		$search_string=$params["search_string"];
		$search_in=$params["search_in"];
		$search_from_date=$params["search_from_date"];
		$search_to_date=$params["search_to_date"];
        $search_date=(isset($params["search_date"]))?$params["search_date"]:false;
		

		if($type == "bill")
			$this->db->select_sum('billAmount');
		else
			$this->db->select_sum('pp.price');

		$this->db->from('productregistration p')
			->join('login l', 'l.id = p.loginId', 'inner')
			->join('plans pp', 'pp.id = p.plan_id', 'inner')
			->join('packages pk', 'pk.id = pp.package', 'inner');
		$this->db->where('pp.status','1');
        $this->db->where('pk.status','1');
        if(isset($params["selected_plan"]) && $params["selected_plan"] != '')
        {
            $this->db->where('p.plan_id',$params["selected_plan"]);
        }
		if($search_string && $search_in && $is_search){
			$this->db->like($search_in, $search_string);
			/*$this->db->or_like('p.customerName', $search_string);
			$this->db->or_like('p.phoneNo', $search_string);
			$this->db->or_like('p.imeiNo', $search_string);
			$this->db->or_like('p.imeiNo2', $search_string);
			$this->db->or_like('p.billNo', $search_string);
			$this->db->or_like('pk.package_name', $search_string);
			$this->db->or_like('pp.plan_name', $search_string);*/
		}
		if($search_from_date && !$search_date){
			$this->db->where('p.planDate >=', $search_from_date);
		}
                if($search_to_date && !$search_date){
			$this->db->where('p.planDate <=', $search_to_date);
		}
		if($search_date){
			$this->db->where('p.planDate', $search_date);
		}
        $query = $this->db->get();
        $result = $query->result_array();
		
		if($type == "bill")
			return $result[0]['billAmount'];
		else
			return $result[0]['price'];
	}
	
	function old_password_check($user_id,$old_password){
		
		$this->db->from('membership');
		$this->db->where('id',$user_id);
		$this->db->where('pass_word',md5($old_password));
		
		//$query = $this->db->get();
		
		if($this->db->count_all_results())
			return true;
		else
			return false;
	}
	function get_child_users($user_id){
		$user = $this->get_user_by_id($user_id);
		if(empty($user))
			return array();
	
		$user = $user[0];
	
		$this->db->from('membership m')
		->where('m.role IN (select r.id from roles r where r.parent_role_id='.$user["role"].')');
	
		$this->db->order_by("m.first_name","ASC");
		$this->db->order_by("m.last_name","ASC");
	
		$query = $this->db->get();
	
		return $query->result_array();
	}
    function get_plan(){
        $this->db->select('plans.*');
        $this->db->from('plans')->join('packages pk','pk.id=plans.package','inner');
        
        
        $this->db->order_by("plan_name","ASC");
        
        
        $query = $this->db->get();
        
        return $query->result_array();
    }
}
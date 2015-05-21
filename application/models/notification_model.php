<?php

class Notification_model extends CI_Model {
	/**
    * Responsable for auto load the database
    * @return void
    */
    public function __construct()
    {
        $this->load->database();
    }

    public function get_notifications($date,$date_end,$search_string,$searchin, $order, $order_type,$offset,$limit)
    {
        $this->db->select('nm.notification_id');
        $this->db->select('nm.message');
        $this->db->select('nm.created_at as message_datetime');
        $this->db->select('nu.is_read');
        $this->db->select('nu.read_datetime as message_read_datetime');
        
        $this->db->select('m1.id as from_user_id');
        $this->db->select('m1.first_name as from_user_first_name');
        $this->db->select('m1.last_name as from_user_last_name');
        
        $this->db->select('m2.id as to_user_id');
        $this->db->select('m2.first_name as to_user_first_name');
        $this->db->select('m2.last_name as to_user_last_name');
        
        $this->db->from('notification_master nm')
        ->join('notification_users nu', 'nu.notification_id = nm.notification_id', 'inner')
        ->join('membership m1', 'm1.id = nm.user_id', 'inner')
        ->join('membership m2', 'm2.id = nu.user_id', 'inner');
        
        //$this->db->where("nu.user_id",$user_id);
        
        if($date_end)
            $this->db->where('STR_TO_DATE(DATE_FORMAT(nm.created_at,"%Y-%m-%d"),"%Y-%m-%d") between "'.$date.'" and "'.$date_end.'"');
        
        if($search_string){
            if($searchin=='nm.notification_id')
                $this->db->where('nu.notification_id',$search_string);
            elseif($searchin=='m2.first_name')
            $this->db->where('m2.first_name like \'%'.$search_string.'%\' or m2.last_name like \'%'.$search_string.'%\' or CONCAT(m2.first_name," ",m2.last_name) like \'%'.$search_string.'%\'');
            elseif($searchin=='m1.first_name')
            $this->db->where('m1.first_name like \'%'.$search_string.'%\' or m1.last_name like \'%'.$search_string.'%\' or CONCAT(m1.first_name," ",m1.last_name) like \'%'.$search_string.'%\'');
            elseif($searchin=='nm.message')
            $this->db->where('nm.message like \'%'.$search_string.'%\'');
            //test
        }
        if($order){
            $this->db->order_by($order, $order_type);
        }else{
            $this->db->order_by("nu.notification_id",$order_type);
        }//echo $limit;
        if($limit > 0){
            if($offset == "")
                $offset = 0;
            $this->db->limit($limit,$offset);
        }
        
        $query = $this->db->get();
        
        $results = $query->result_array();
        //echo $this->db->last_query();exit;
        //mark as read
        /*$data = array("is_read"=>1,"read_datetime"=>date("Y-m-d H:i:s"));
         $this->db->where('user_id', $user_id);
         $this->db->update('notification_users', $data);*/
        
        return $results;
    }
    
    public function count_notifications($date,$date_end,$search_string,$searchin)
    {
        $this->db->select('nm.notification_id');
        $this->db->select('nm.message');
        $this->db->select('nm.created_at as message_datetime');
        $this->db->select('nu.is_read');
        $this->db->select('nu.read_datetime as message_read_datetime');
        
        $this->db->select('m1.id as from_user_id');
        $this->db->select('m1.first_name as from_user_first_name');
        $this->db->select('m1.first_name as from_user_last_name');
        
        $this->db->select('m2.id as to_user_id');
        $this->db->select('m2.first_name as to_user_first_name');
        $this->db->select('m2.first_name as to_user_last_name');
        
        $this->db->from('notification_master nm')
        ->join('notification_users nu', 'nu.notification_id = nm.notification_id', 'inner')
        ->join('membership m1', 'm1.id = nm.user_id', 'inner')
        ->join('membership m2', 'm2.id = nu.user_id', 'inner');
        
        //$this->db->where("nu.user_id",$user_id);
        
        if($date_end)
            $this->db->where('STR_TO_DATE(DATE_FORMAT(nm.created_at,"%Y-%m-%d"),"%Y-%m-%d") between "'.$date.'" and "'.$date_end.'"');
        
        if($search_string){
            if($searchin=='nm.notification_id')
                $this->db->where('nm.notification_id',$search_string);
            elseif($searchin=='m2.first_name')
            $this->db->where('m2.first_name like \'%'.$search_string.'%\' or m2.last_name like \'%'.$search_string.'%\'');
            elseif($searchin=='nm.message')
            $this->db->where('nm.message',$search_string);
            //test
        }
        
        $this->db->order_by("nu.notification_id","DESC");
        $query = $this->db->get();
        
        $results = $query->num_rows();
        
        return $results;
    }
    
    
    public function get_notification_list($user_id,$offset,$limit)
    {
		$this->db->select('nm.notification_id');
		$this->db->select('nm.message');
		$this->db->select('nm.created_at as message_datetime');
		$this->db->select('nu.is_read');
		$this->db->select('nu.read_datetime as message_read_datetime');

		$this->db->select('m1.id as from_user_id');
		$this->db->select('m1.first_name as from_user_first_name');
		$this->db->select('m1.first_name as from_user_last_name');

		$this->db->select('m2.id as to_user_id');
		$this->db->select('m2.first_name as from_user_first_name');
		$this->db->select('m2.first_name as from_user_last_name');

		$this->db->from('notification_master nm')
				->join('notification_users nu', 'nu.notification_id = nm.notification_id', 'inner')
				->join('membership m1', 'm1.id = nm.user_id', 'inner')
				->join('membership m2', 'm2.id = nu.user_id', 'inner');

		$this->db->where("nu.user_id",$user_id);

		$this->db->order_by("nu.notification_id","DESC");
		
		if($limit > 0){
			if($offset == "")
				$offset = 0;
			$this->db->limit($limit,$offset);
		}

		$query = $this->db->get();
		
		$results = $query->result_array(); 	

		//mark as read
		/*$data = array("is_read"=>1,"read_datetime"=>date("Y-m-d H:i:s"));
		$this->db->where('user_id', $user_id);
		$this->db->update('notification_users', $data);*/

		return $results;
    }
    public function get_all_member($roles,$states)
    {
        $this->db->select('membership.id');
        if($states!=0)
        {
            $this->db->select('stateId');
            $this->db->select('area_id');
            $this->db->select('city_id');
        }
        $this->db->from('membership');
        if($roles!=0)
            $this->db->where('role',$roles);
        if($states!=0)
        {
            $this->db->join('area a', 'a.id = membership.area_id', 'inner');
            $this->db->join('city c', 'c.id = a.city_id', 'inner');
            //$this->db->join('state s', 's.id = c.stateId', 'inner');
            $this->db->where('c.stateId',$states);
        }
        $query = $this->db->get();
        $results = $query->result_array();
        //echo $this->db->last_query();exit;
        return $results;

    }
    public function get_all_role()
    {
        $this->db->select('*');
        $this->db->from('roles');
        $query = $this->db->get();
        $results = $query->result_array();
        return $results;
        
    }
    public function get_all_state()
    {
        $this->db->select('*');
        $this->db->from('state');
        $query = $this->db->get();
        $results = $query->result_array();
        return $results;
        
    }
	/**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function add_notification_api($new_member_insert_data,$user_ids)
    {
		$insert = $this->db->insert('notification_master', $new_member_insert_data);
	    $notification_id = $this->db->insert_id();

		for($i=0;$i<count($user_ids);$i++){
			$new_member_insert_data = array(
				'notification_id' => $notification_id,
				'user_id' => $user_ids[$i]
			);
			$this->db->insert('notification_users', $new_member_insert_data);
		}
		return $notification_id;
	}
	
}
                            
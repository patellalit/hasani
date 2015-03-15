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
                            
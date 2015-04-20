<?php

class Location_model extends CI_Model {
	/**
    * Responsable for auto load the database
    * @return void
    */
    public function __construct()
    {
        $this->load->database();
    }

    public function get_location($date,$date_end,$search_string,$searchin, $order, $order_type,$offset,$limit)
    {
        $this->db->select('lt.id');
        $this->db->select('lt.lat');
        $this->db->select('lt.long');
        $this->db->select('lt.address');
        $this->db->select('lt.created_at as location_datetime');
        
        $this->db->select('m.id as from_user_id');
        $this->db->select('m.first_name as from_user_first_name');
        $this->db->select('m.last_name as from_user_last_name');
        
        $this->db->from('location_tracker lt')
        ->join('membership m', 'm.id = lt.user_id', 'inner');
        
        //$this->db->where("lt.user_id",$user_id);
        if($date_end)
            $this->db->where('STR_TO_DATE(DATE_FORMAT(lt.created_at,"%Y-%m-%d"),"%Y-%m-%d") between "'.$date.'" and "'.$date_end.'"');
        else
            //$this->db->where('DATE_FORMAT(dsr.modified_at,"%Y-%m-%d")',$date);
            
        if($search_string){
            if($searchin=='lt.id')
                $this->db->where('.id',$search_string);
            elseif($searchin=='m.first_name')
                $this->db->where('m.first_name like \'%'.$search_string.'%\' or m.last_name like \'%'.$search_string.'%\' or CONCAT(first_name," ",last_name) like \'%'.$search_string.'%\'');
            elseif($searchin=='lt.address')
                $this->db->where('lt.address',$search_string);
                //test
        }
        
        if($order){
            $this->db->order_by($order, $order_type);
        }else{
            $this->db->order_by('lt.id', $order_type);
        }
        
        //$this->db->order_by("lt.id","DESC");
        //echo $limit;
        if($limit > 0){
            if($offset == "")
                $offset = 0;
            $this->db->limit($limit,$offset);
        }
        
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        $results = $query->result_array(); 	
        
        return $results;
    }
    
    public function count_location($date,$date_end,$search_string,$searchin)
    {
        $this->db->select('lt.id');
        $this->db->select('lt.lat');
        $this->db->select('lt.long');
        $this->db->select('lt.created_at as location_datetime');
        
        $this->db->select('m.id as from_user_id');
        $this->db->select('m.first_name as from_user_first_name');
        $this->db->select('m.first_name as from_user_last_name');
        
        $this->db->from('location_tracker lt')
        ->join('membership m', 'm.id = lt.user_id', 'inner');
        
        //$this->db->where("lt.user_id",$user_id);
        
        $this->db->order_by("lt.id","DESC");
        
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    
    public function get_location_list($user_id,$offset,$limit)
    {
		$this->db->select('lt.id');
		$this->db->select('lt.lat');
		$this->db->select('lt.long');
		$this->db->select('lt.created_at as location_datetime');

		$this->db->select('m.id as from_user_id');
		$this->db->select('m.first_name as from_user_first_name');
		$this->db->select('m.first_name as from_user_last_name');

		$this->db->from('location_tracker lt')
				->join('membership m', 'm.id = lt.user_id', 'inner');

		$this->db->where("lt.user_id",$user_id);

		$this->db->order_by("lt.id","DESC");
		
		if($limit > 0){
			if($offset == "")
				$offset = 0;
			$this->db->limit($limit,$offset);
		}

		$query = $this->db->get();
		
		$results = $query->result_array(); 	

		return $results;
    }

	/**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function add_location_api($new_member_insert_data)
    {
		$insert = $this->db->insert('location_tracker', $new_member_insert_data);
	    $location_id = $this->db->insert_id();

		return $location_id;
	}
	
}
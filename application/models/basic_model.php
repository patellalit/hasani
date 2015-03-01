<?php

class Basic_model extends CI_Model {
	/**
    * Responsable for auto load the database
    * @return void
    */
    public function __construct()
    {
        $this->load->database();
    }

    public function get_role_list()
    {
		$this->db->select('id');
		$this->db->select('role_name');
		$this->db->select('parent_role_id');
		$this->db->from('roles');

		$query = $this->db->get();
		
		return $query->result_array(); 	
    }
	public function get_role_api()
    {
		$this->db->select('id');
		$this->db->select('role_name');
		$this->db->select('parent_role_id');
		$this->db->from('roles');
		$this->db->where('id >',5);

		$query = $this->db->get();
		
		return $query->result_array(); 	
    }
    
    public function get_service_center_api(){
    	$this->db->select('sc.id');
    	$this->db->select('sc.name');
    	$this->db->select('sc.address');
    	$this->db->select('sc.zipCode');
    	$this->db->select('sc.contactNo');
    	$this->db->select('sc.emailId');
    	
    	$this->db->select('a.id as area_id');
    	$this->db->select('a.area_name');
    	
    	$this->db->select('c.id as city_id');
    	$this->db->select('c.name as city_name');
    	
    	$this->db->select('s.id as state_id');
    	$this->db->select('s.name as state_name');
    	
    	$this->db->from('servicecenter sc')
	    	->join('area a', 'a.id = sc.area', 'left')
	    	->join('city c', 'c.id = a.city_id', 'left')
	    	->join('state s', 's.id = c.stateId', 'left');
    	
    	$this->db->order_by("s.name","ASC");
    	$this->db->order_by("c.name","ASC");
    	$this->db->order_by("a.area_name","ASC");
    	$this->db->order_by("sc.name","ASC");
    	$query = $this->db->get();
    	
    	return $query->result_array();
    }
}
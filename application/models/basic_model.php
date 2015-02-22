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
}
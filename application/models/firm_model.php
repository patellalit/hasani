<?php

class Firm_model extends CI_Model {
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
    * @param int $customer_id 
    * @return array
    */
    public function get_firm_by_id($id)
    {
		$this->db->select('*');
		$this->db->from('firms');
		$this->db->where('id', $id);
		$query = $this->db->get();
		return $query->result_array(); 
    }

	public function get_firm_api()
    {
		$this->db->select('firms.id');
		$this->db->select('firms.name');
				$this->db->from('firms');
		$this->db->order_by("firms.name","asc");

		$query = $this->db->get();
		
		return $query->result_array(); 	
    }
}
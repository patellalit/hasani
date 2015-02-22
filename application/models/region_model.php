<?php

class Region_model extends CI_Model {
	protected $db2;
	/**
    * Responsable for auto load the database
    * @return void
    */
    public function __construct()
    {
        $this->db2 = $this->load->database("default",true);
    }

	/**
    * Get user by his is
    * @param int $dsr_id 
    * @return array
    */
    public function get_city_state()
    {
		$this->db2->select('s.id as state_id,s.name as state_name,c.id as city_id,c.name as city_name');
		$this->db2->from('city c')
			->join('state s', 's.id = c.stateId', 'left');
		$this->db2->order_by('state_id', 'Asc');
		$this->db2->order_by('city_name', 'Asc');
		$query = $this->db2->get();
		$results = $query->result_array(); 
		return $results;
    }
}

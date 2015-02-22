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
    public function get_states_by_country($country_id)
    {
		$this->db2->select('s.id as state_id,s.name as state_name');
		$this->db2->from('state s');
		$this->db2->where('country_id',$country_id);
		$this->db2->order_by('state_name', 'Asc');
		$query = $this->db2->get();
		$results = $query->result_array(); 
		return $results;
    }
	
	public function get_city_by_state($state_id)
    {
		$this->db2->select('c.id as city_id,c.name as city_name');
		$this->db2->from('city c');
		$this->db2->where('c.stateId',$state_id);
		$this->db2->order_by('c.name', 'Asc');
		$query = $this->db2->get();
		$results = $query->result_array(); 
		return $results;
    }
	
	public function get_area_by_city($city_id)
    {
		$this->db2->select('a.id as area_id,a.area_name');
		$this->db2->from('area a');
		$this->db2->where('a.city_id',$city_id);
		$this->db2->order_by('a.area_name', 'Asc');
		$query = $this->db2->get();
		$results = $query->result_array(); 
		return $results;
    }
}

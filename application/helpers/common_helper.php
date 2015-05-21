<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('role_array'))
{
    function role_array($role_id=null)
    {
		$roles = array(
					1=>"Admin",
					2=>"ISD",
					3=>"Non-ISD",
					4=>"TL",
					5=>"Pickup",
					6=>"Root Trainer",
					7=>"Technical Runner",
				);
		if($role_id)
			return $roles[$role_id];
        return $roles;
    }
    
}
    function get_paging_options(){
        return array(10, 20, 30, 50, 100, 200, 500);
    }
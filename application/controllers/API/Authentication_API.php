<?php
class Authentication_API extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
    }

	/**
    * encript the password 
    * @return mixed
    */	
    function __encrip_password($password) {
        return md5($password);
    }	

	function json_response($data){
		header('Content-Type: application/json; charset=UTF8');
		echo trim(json_encode($data));exit;
	}

    /**
    * check the username and the password with the database
    * @return void
    */
	public function validate_credentials(){	
		$email = $this->input->post('email');
		$password = $this->__encrip_password($this->input->post('password'));

		$user = $this->users_model->validate_api($email, $password);
		
		if($user)
		{
			$new_member_insert_data = array(
					'is_logged_in' => 1,
			);
			
			$this->users_model->update_user_api($user[0]["id"],$new_member_insert_data);
			
			$data = array();
			$data['status'] = 1;
			$user[0]['role'] = (int)$user[0]['role'];
			$data['data'] = $user[0];
			$this->json_response($data);
		}
		else // incorrect username or password
		{
			$data = array();
			$data['status'] = 0;
			$data['message'] = "Email or Password is not valid";
			$this->json_response($data);
		}
	}
	
	/**
	 * check the username and the password with the database
	 * @return void
	 */
	public function logout(){
		$user_id = $this->input->get('user_id');
	
		if($this->users_model->is_login($user_id))
		{
			$new_member_insert_data = array(
					'is_logged_in' => 0,
			);
				
			$this->users_model->update_user_api($user_id,$new_member_insert_data);
				
			$data = array();
			$data['status'] = 1;
			$data['message'] = "User has been logged out";
			$this->json_response($data);
		}
		else // incorrect username or password
		{
			$data = array();
			$data['status'] = 0;
			$data['message'] = "User is not logged in.";
			$this->json_response($data);
		}
	}
}

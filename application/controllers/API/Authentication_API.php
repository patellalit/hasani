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
}

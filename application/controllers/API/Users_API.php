<?php
class Users_API extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
		
		$user_id = (int)$this->input->get('user_id');
		if($user_id <= 0){
			$data = array();
			$data['status'] = 1;
			$data['message'] = "You are not authorised user.";
			$this->json_response($data);
		}
    }

	function json_response($data){
		header('Content-Type: application/json; charset=UTF8');
		echo trim(json_encode($data));exit;
	}

	public function user_add(){
		//if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
			$this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required|trim');                       
			$this->form_validation->set_rules('email', 'Email', 'required|trim');
            $this->form_validation->set_rules('password', 'Password', 'required|trim');
            $this->form_validation->set_rules('mobile', 'Mobile', 'required|trim');

            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                //if the insert has returned true then we show the flash message
            	$new_member_insert_data = array(
            			'first_name' => $this->input->post('first_name'),
            			'last_name' => $this->input->post('last_name'),
            			'email_address' => $this->input->post('email'),
            			//'user_name' => $this->input->post('username'),
            			'pass_word' => md5($this->input->post('password')),
            			'mobile' => $this->input->post('mobile'),
            			'role' => (int)$this->input->post('role'),
            			'ol_name' => $this->input->post('ol_name'),
            			'ol_area' => $this->input->post('ol_area'),
            			'address' => $this->input->post('address'),
            			'personal_email' => $this->input->post('personal_email'),
            			'personal_phone' => $this->input->post('personal_phone'),
            	);
            	
                if($user_id = $this->users_model->add_user_api($new_member_insert_data)){
                    $data = array();
					$data['status'] = 1;
					$data['message'] = "User registered successfully";
					$data['data'] = array("user_id"=>$user_id);
					$this->json_response($data); 
                }else{
                    $data = array();
					$data['status'] = 0;
					$data['message'] = "Email address already exists";
					$this->json_response($data);
                }

            }else{
				$data = array();
				$data['status'] = 0;
				$data['message'] = "Enter required fields";
				$this->json_response($data);
			}
			
        }
		
		$data = array();
		$data['status'] = 0;
		$data['message'] = "Method is not valid";
		$this->json_response($data);
	}
	
	public function user_update(){
		//if save button was clicked, get the data sent via post
		$user_id = $this->uri->segment(5);
        if ($this->input->server('REQUEST_METHOD') === 'POST' && (int)$user_id > 0)
        {
            //form validation
			$this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required|trim');                       
			//$this->form_validation->set_rules('email', 'Email', 'required|trim');
            //$this->form_validation->set_rules('password', 'Password', 'required|trim');
            $this->form_validation->set_rules('mobile', 'Mobile', 'required|trim');

            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                //if the insert has returned true then we show the flash message
				$new_member_insert_data = array(
					'first_name' => $this->input->post('first_name'),
					'last_name' => $this->input->post('last_name'),
					//'email_address' => $this->input->post('email'),			
					//'user_name' => $this->input->post('username'),
					//'pass_word' => md5($this->input->post('password')),
					'mobile' => $this->input->post('mobile'),
					'role' => (int)$this->input->post('role'),
					'ol_name' => $this->input->post('ol_name'),
					'ol_area' => $this->input->post('ol_area'),
					'address' => $this->input->post('address'),
					'personal_email' => $this->input->post('personal_email'),
					'personal_phone' => $this->input->post('personal_phone'),
				);
                if($user_id = $this->users_model->update_user_api($user_id,$new_member_insert_data)){
                    $data = array();
					$data['status'] = 1;
					$data['message'] = "User updated successfully";
					$data['data'] = array("user_id"=>$user_id);
					$this->json_response($data); 
                }else{
                    $data = array();
					$data['status'] = 0;
					$data['message'] = "User not updated. Try again.";
					$this->json_response($data);
                }

            }else{
				$data = array();
				$data['status'] = 0;
				$data['message'] = "Enter required fields";
				$this->json_response($data);
			}
			
        }
		
		$data = array();
		$data['status'] = 0;
		$data['message'] = "Method/User Id is not valid";
		$this->json_response($data);
	}
	
	 /**
    * Delete customer by his id
    * @return void
    */
    public function user_delete()
    {
        //customerw id 
        $id = $this->uri->segment(5);
		if((int) $id > 0){
			$this->users_model->delete_user_api($id);
			
			$data = array();
			$data['status'] = 1;
			$data['message'] = "User deleted successfully";
			$data['data'] = array("user_id"=>$id);
			$this->json_response($data); 
		}else{
			$data = array();
			$data['status'] = 0;
			$data['message'] = "User not deleted. Try again.";
			$this->json_response($data);
		}
    }//delete
	
	/**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function user_list(){
		$user_id = (int)$this->input->get('user_id');
		$s = $this->input->get('search_string');
		$offset = (int)$this->input->get('offset');
		$limit = (int)$this->input->get('limit');
		$sort = $this->input->get('sort');
		$sort_dir = $this->input->get('sort_dir');
		
		$data = array();
		$data['status'] = 1;
		$data['data']['count_users']= $this->users_model->count_users_api($s);
	    $data['data']['users'] = $this->users_model->get_users_api($s, $sort, $sort_dir,$offset,$limit);
		$this->json_response($data);
	}
	
	/**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function user_list_by_role(){
		//customerw id 
        $role_id = $this->uri->segment(5);
		if((int) $role_id > 0){
			$data = array();
			$data['status'] = 1;
			$data['data']['users'] = $this->users_model->get_users_by_role_api($role_id);
			$data['data']['count_users']= count($data['data']['users']);
			$this->json_response($data);
		}else{
			$data = array();
			$data['status'] = 0;
			$data['message'] = "Enter role. Try again.";
			$this->json_response($data);
		}
	}
}

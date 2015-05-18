<?php
class Mobile_API extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
		$this->load->model('customers_model');
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

		$user = $this->users_model->validateApi($email, $password);
		
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
	
	public function register(){
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
            	
                if($user_id = $this->users_model->store_user($new_member_insert_data)){
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
                if($user_id = $this->users_model->store_user($user_id,$new_member_insert_data)){
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
			$this->users_model->delete_user($id);
			
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
    public function users(){
		
		/*$user_id = (int)$this->input->get('user_id');
		if($user_id > 0){*/
			$data = array();
			$data['status'] = 1;
			$data['data']['count_users']= $this->users_model->count_api_users();
		    $data['data']['users'] = $this->users_model->get_api_users('', '', 'DESC');
			$this->json_response($data);
		/*}
		else
		{
			$data = array();
			$data['status'] = 1;
			$data['message'] = "You are not authorised user.";
			$this->json_response($data);
		}*/
	}
	
	
	
	public function customers_add(){
		//if save button was clicked, get the data sent via post
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
			$config['upload_path'] = 'assets/uploads/';
			$config['allowed_types'] = 'jpg|png|gif|bmp';
			$this->load->library('upload', $config);

            //form validation
			$this->form_validation->set_rules('customer_name', 'Customer Name', 'required|trim');
			$this->form_validation->set_rules('ol_name', 'O/L Name', 'required|trim');
			$this->form_validation->set_rules('ol_address', 'O/L Address', 'required|trim');                        
			$this->form_validation->set_rules('ol_area', 'O/L Area', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|email');
            $this->form_validation->set_rules('mobile', 'Mobile', 'required|trim');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
				if ( isset($_FILES['photo']) && !$this->upload->do_upload('photo')) {
					$error = array('error' => $this->upload->display_errors());
					$data = array();
					$data['status'] = 0;
					$data['message'] = $this->upload->display_errors();
					$this->json_response($data);
				}

				if(isset($_FILES['photo']))
					$file_data = $this->upload->data(); 

				$new_member_insert_data = array(
					'customer_name' => $this->input->post('customer_name'),
					'ol_name' => $this->input->post('ol_name'),
					'ol_address' => $this->input->post('ol_address'),
					'ol_area' => $this->input->post('ol_area'),
					'email' => $this->input->post('email'),
					'mobile' => $this->input->post('mobile'),
				
					'cst_number' => $this->input->post('cst_number',null),
					'gst_number' => $this->input->post('gst_number',null),
					'created_at' => date("Y-m-d H:i:s")
				);

				if($this->input->post('cst_date',"") != ""){
					$new_member_insert_data['cst_date'] = date("Y-m-d",strtotime($this->input->post('cst_date')));
				}
				if($this->input->post('gst_date',"") != ""){
					$new_member_insert_data['gst_date'] = date("Y-m-d",strtotime($this->input->post('gst_date')));
				}
				if(isset($_FILES['photo']) && $file_data["full_path"] != ""){
					$new_member_insert_data["photo"]= $file_data["file_name"];
				}
                //if the insert has returned true then we show the flash message
                if($customer_id = $this->customers_model->store_customer($new_member_insert_data)){
                    $data = array();
					$data['status'] = 1;
					$data['message'] = "Customer registered successfully";
					$data['data'] = array("customer_id"=>$customer_id);
					$this->json_response($data); 
                }else{
                    $data = array();
					$data['status'] = 0;
					$data['message'] = "Email address already exists";
					$this->json_response($data);
                }

            }else{
				print_r($_POST);
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
	
	public function customer_update(){
		//if save button was clicked, get the data sent via post
        //if save button was clicked, get the data sent via post
		$customer_id = $this->uri->segment(5);
        if ($this->input->server('REQUEST_METHOD') === 'POST' && (int)$customer_id > 0)
        {
			$config['upload_path'] = 'assets/uploads/';
			$config['allowed_types'] = 'jpg|png|gif|bmp';
			$this->load->library('upload', $config);
			
            //form validation
			$this->form_validation->set_rules('customer_name', 'Customer Name', 'required|trim');
			$this->form_validation->set_rules('ol_name', 'O/L Name', 'required|trim');
			$this->form_validation->set_rules('ol_address', 'O/L Address', 'required|trim');                        
			$this->form_validation->set_rules('ol_area', 'O/L Area', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|email');
            $this->form_validation->set_rules('mobile', 'Mobile', 'required|trim');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
				
				if ( isset($_FILES['photo']) && !$this->upload->do_upload('photo')) {
					$error = array('error' => $this->upload->display_errors());
					$data = array();
					$data['status'] = 0;
					$data['message'] = $this->upload->display_errors();
					$this->json_response($data);
				}
				if(isset($_FILES['photo']))
					$file_data = $this->upload->data(); 
				
                //if the insert has returned true then we show the flash message
				$new_member_insert_data = array(
					'customer_name' => $this->input->post('customer_name'),
					'ol_name' => $this->input->post('ol_name'),
					'ol_address' => $this->input->post('ol_address'),
					'ol_area' => $this->input->post('ol_area'),
					'email' => $this->input->post('email'),
					'mobile' => $this->input->post('mobile'),
					
					'cst_number' => $this->input->post('cst_number'),
					'gst_number' => $this->input->post('gst_number'),
					'created_at' => date("Y-m-d H:i:s")
				);

				if($this->input->post('cst_date',"") != ""){
					$new_member_insert_data['cst_date'] = date("Y-m-d",strtotime($this->input->post('cst_date')));
				}
				if($this->input->post('gst_date',"") != ""){
					$new_member_insert_data['gst_date'] = date("Y-m-d",strtotime($this->input->post('gst_date')));
				}
				
				if(isset($_FILES['photo']) && $file_data["full_path"] != ""){
					$new_member_insert_data["photo"]= $file_data["file_name"];
				}
				
                if($customer_id = $this->customers_model->update_customer($customer_id,$new_member_insert_data)){
                    $data = array();
					$data['status'] = 1;
					$data['message'] = "Customer updated successfully";
					$data['data'] = array("customer_id"=>$customer_id);
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

	/**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function customers(){
		
		/*$user_id = (int)$this->input->get('user_id');
		if($user_id > 0){*/
			$data = array();
			$data['status'] = 1;
			$data['data']['count_customers']= $this->customers_model->count_customers();
		    $data['data']['customers'] = $this->customers_model->get_customers('', '', 'DESC');
			$this->json_response($data);
		/*}
		else
		{
			$data = array();
			$data['status'] = 1;
			$data['message'] = "You are not authorised user.";
			$this->json_response($data);
		}*/
	}
	
	 /**
    * Delete customer by his id
    * @return void
    */
    public function customer_delete()
    {
        //customerw id 
        $id = $this->uri->segment(5);
		if((int) $id > 0){
			$this->customers_model->delete_customer($id);
			
			$data = array();
			$data['status'] = 1;
			$data['message'] = "O/L deleted successfully";
			$data['data'] = array("user_id"=>$id);
			$this->json_response($data); 
		}else{
			$data = array();
			$data['status'] = 0;
			$data['message'] = "O/L not deleted. Try again.";
			$this->json_response($data);
		}
    }//delete
}

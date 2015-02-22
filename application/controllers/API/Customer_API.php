<?php
class Customer_API extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
		$this->load->model('customers_model');

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

	public function customer_add(){
		
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
			$config['upload_path'] = 'assets/uploads/';
			$config['allowed_types'] = 'jpg|png|gif|bmp';
			$this->load->library('upload', $config);

            //form validation
			$this->form_validation->set_rules('customer_name', 'Customer Name', 'required|trim');
			$this->form_validation->set_rules('ol_name', 'O/L Name', 'required|trim');
			$this->form_validation->set_rules('ol_address', 'O/L Address', 'required|trim');                        
			$this->form_validation->set_rules('city_id', 'O/L City', 'required|trim');
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
					'city_id' => $this->input->post('city_id'),
					'email' => $this->input->post('email'),
					'mobile' => $this->input->post('mobile'),
					
					'promoter' => $this->input->post('promoter'),
					'isd_user_id' => (int)$this->input->post('isd_user_id'),
					'pan_number' => $this->input->post('pan_number'),
					'pan_date' => $this->input->post('pan_date'),
					'cin_number' => $this->input->post('cin_number'),
					'cin_date' => $this->input->post('cin_date'),
					'tl_id' => $this->input->post('tl_id'),
					'firm_id' => $this->input->post('firm_id'),
				
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
				
				if($this->input->post('pan_date',"") != ""){
					$new_member_insert_data['pan_date'] = date("Y-m-d",strtotime($this->input->post('pan_date')));
				}
				if($this->input->post('cin_date',"") != ""){
					$new_member_insert_data['cin_date'] = date("Y-m-d",strtotime($this->input->post('cin_date')));
				}

                if($customer_id = $this->customers_model->add_customer_api($new_member_insert_data)){
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
			$this->form_validation->set_rules('city_id', 'O/L City', 'required|trim');
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
					'city_id' => $this->input->post('city_id'),
					'ol_area' => $this->input->post('ol_area'),
					'email' => $this->input->post('email'),
					'mobile' => $this->input->post('mobile'),
					
					'promoter' => $this->input->post('promoter'),
					'isd_user_id' => (int)$this->input->post('isd_user_id'),
					'pan_number' => $this->input->post('pan_number'),
					'pan_date' => $this->input->post('pan_date'),
					'cin_number' => $this->input->post('cin_number'),
					'cin_date' => $this->input->post('cin_date'),
					'tl_id' => $this->input->post('tl_id'),
					'firm_id' => $this->input->post('firm_id'),
					
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
				
				if($this->input->post('pan_date',"") != ""){
					$new_member_insert_data['pan_date'] = date("Y-m-d",strtotime($this->input->post('pan_date')));
				}
				if($this->input->post('cin_date',"") != ""){
					$new_member_insert_data['cin_date'] = date("Y-m-d",strtotime($this->input->post('cin_date')));
				}
				
                if($customer_id = $this->customers_model->update_customer_api($customer_id,$new_member_insert_data)){
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
    * Delete customer by his id
    * @return void
    */
    public function customer_delete()
    {
        //customerw id 
        $customer_id = $this->uri->segment(5);
		if((int) $customer_id > 0){
			$this->customers_model->delete_customer_api($customer_id);
			
			$data = array();
			$data['status'] = 1;
			$data['message'] = "O/L deleted successfully";
			$data['data'] = array("customer_id"=>$customer_id);
			$this->json_response($data); 
		}else{
			$data = array();
			$data['status'] = 0;
			$data['message'] = "O/L not deleted. Try again.";
			$this->json_response($data);
		}
    }//delete

	/**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function customer_list(){
                $user_id = (int)$this->input->get('user_id');
		$s = $this->input->get('search_string');
		$offset = (int)$this->input->get('offset');
		$limit = (int)$this->input->get('limit');
		$sort = $this->input->get('sort');
		$sort_dir = $this->input->get('sort_dir');
		
		$data = array();
		$data['status'] = 1;
		$data['data']['count_customers']= $this->customers_model->count_customers_api($s);
	        $data['data']['customers'] = $this->customers_model->get_customers_api($s, $sort, $sort_dir,$offset,$limit);
		$this->json_response($data);

	}
}

                            
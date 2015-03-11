<?php
class Claim_API extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
		$this->load->model('trainee_model');
		
		$this->load->model('claim_model');

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

	public function claim_add(){
		//if save button was clicked, get the data sent via post
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
			$this->form_validation->set_rules('target_customer_id', 'Customer Id', 'required|trim');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
				$new_member_insert_data = array(
					'target_customer_id' => $this->input->post('target_customer_id'),
					'remarks' => $this->input->post('remarks'),
					'user_id' => $this->input->get('user_id'),
					'created_at' => date("Y-m-d"),
				);

                //if the insert has returned true then we show the flash message
                if($claim_id = $this->claim_model->add_claim_api($new_member_insert_data)){
                    $data = array();
					$data['status'] = 1;
					$data['message'] = "Claim added successfully";
					$data['data'] = array("claim_id"=>$claim_id);
					$this->json_response($data); 
                }else{
                    $data = array();
					$data['status'] = 0;
					$data['message'] = "Claim already created with this customer.";
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
	
	public function claim_update(){
		//if save button was clicked, get the data sent via post
        //if save button was clicked, get the data sent via post
		$claim_id = $this->uri->segment(5);
        if ($this->input->server('REQUEST_METHOD') === 'POST' && (int)$claim_id > 0)
        {
			//form validation
			$this->form_validation->set_rules('target_customer_id', 'Customer Id', 'required|trim');                       

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
				
				$new_member_insert_data = array(
					'target_customer_id' => $this->input->post('target_customer_id'),
					'remarks' => $this->input->post('remarks'),
					'user_id' => $this->input->get('user_id'),
				);

                if($claim_id = $this->claim_model->update_claim_api($claim_id,$new_member_insert_data)){
                    $data = array();
					$data['status'] = 1;
					$data['message'] = "Claim updated successfully";
					$data['data'] = array("claim_id"=>$claim_id);
					$this->json_response($data); 
                }else{
                    $data = array();
					$data['status'] = 0;
					$data['message'] = "Claim not updated. Try again.";
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
    * Delete claim by his id
    * @return void
    */
    public function claim_delete()
    {
        //claim id 
        $id = $this->uri->segment(5);
		if((int) $id > 0){
			$this->claim_model->delete_claim_api($id);
			
			$data = array();
			$data['status'] = 1;
			$data['message'] = "Claim deleted successfully";
			$data['data'] = array("claim_id"=>$id);
			$this->json_response($data); 
		}else{
			$data = array();
			$data['status'] = 0;
			$data['message'] = "Claim not deleted. Try again.";
			$this->json_response($data);
		}
    }//delete

	/**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function claim_list(){
		$user_id = (int)$this->input->get('user_id');
		$data = array();
		$data['status'] = 1;
		$data['data']['count_claim']= $this->claim_model->count_claim_api(null,null,$user_id);
	    $data['data']['claim'] = $this->claim_model->get_claim_api('', '', 'ASC',null,null,$user_id);
		$this->json_response($data);
	}

	public function claim_list_all(){
		$data = array();
		$data['status'] = 1;
		$data['data']['count_claim']= $this->claim_model->count_claim_api();
	    $data['data']['claim'] = $this->claim_model->get_claim_api('', '', 'ASC');
		$this->json_response($data);
	}
	
	public function pickup_add(){
	$claim_id =$this->input->post('claim_id');
		if($this->claim_model->check_valid_status_claim_track($claim_id,1))
			$this->claim_track_add(2,"Pickup");
		else{
			$data = array();
			$data['status'] = 0;
			$data['message'] = "Claim not found";
			$this->json_response($data);
		}
	}
	public function pickup_list(){
		$this->claim_track_list(2);
	}
	
	public function drop_to_service_center_add(){
		$claim_id =$this->input->post('claim_id');
		$service_center_id =$this->input->post('service_center_id');
                $jobsheet_no =$this->input->post('jobsheet_no');
		
		if($this->claim_model->check_valid_status_claim_track($claim_id,2))
			$this->claim_track_add(3,"Deliver to Service Center",array("service_center_id"=>$service_center_id,"jobsheet_no"=>$jobsheet_no));
		else{
			$data = array();
			$data['status'] = 0;
			$data['message'] = "Claim is not picked up";
			$this->json_response($data);
		}
	}
	public function drop_to_service_center_list(){
		$this->claim_track_list(3);
	}
	
	public function pickup_from_service_center_add(){
		$claim_id =$this->input->post('claim_id');
		$service_center_id =$this->input->post('service_center_id');
	
		if($this->claim_model->check_valid_status_claim_track($claim_id,3))
			$this->claim_track_add(4,"Pickup from Service Center",array("service_center_id"=>$service_center_id));
		else{
			$data = array();
			$data['status'] = 0;
			$data['message'] = "Claim is not in service center";
			$this->json_response($data);
		}
	}
	public function pickup_from_service_center_list(){
		$this->claim_track_list(4);
	}
	
	public function drop_to_customer_add(){
		$claim_id =$this->input->post('claim_id');
		$service_center_id =$this->input->post('service_center_id');
		
		$submit_to_person_name =$this->input->post('submit_to_person_name');
		$submit_to_person_phone =$this->input->post('submit_to_person_phone');
		if(!$submit_to_person_name || $submit_to_person_name == ""){
			$data = array();
			$data['status'] = 0;
			$data['message'] = "Required Submit to person name";
			$this->json_response($data);
		}
		if(!$submit_to_person_phone || $submit_to_person_phone == ""){
			$data = array();
			$data['status'] = 0;
			$data['message'] = "Required Submit to person phone number";
			$this->json_response($data);
		}
		if($this->claim_model->check_valid_status_claim_track($claim_id,4))
			$this->claim_track_add(5,"Drop to Customer",array("submit_to_person_name"=>$submit_to_person_name,"submit_to_person_phone"=>$submit_to_person_phone));
		else{
			$data = array();
			$data['status'] = 0;
			$data['message'] = "Claim is not pickup from service center";
			$this->json_response($data);
		}
	}
	public function drop_to_customer_list(){
		$this->claim_track_list(5);
	}
	
	public function claim_track_add($status,$label,$params = array()){
		
        $claim_id =$this->input->post('claim_id');
		//if save button was clicked, get the data sent via post
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            if ($claim_id != "")
            {
            	$created_at = $this->input->post('date');
            	if(!$created_at || $created_at == ""){
            		$created_at = date("Y-m-d H:i:s");
            	}else{
            		$created_at = date("Y-m-d H:i:s",strtotime($created_at));
            	}
            	
				$new_member_insert_data = array(
					'claim_id' => $claim_id,
					'remarks' => $this->input->post('remarks'),
					'status' => $status,
					'user_id' => $this->input->get('user_id'),
					'created_at' => $created_at,
				);
				
				if(!empty($params) && isset($params["service_center_id"]) && (int)$params["service_center_id"] > 0){
					$new_member_insert_data["service_center_id"] = $params["service_center_id"]; 
				}
				
				if(!empty($params) && isset($params["submit_to_person_name"]) && $params["submit_to_person_name"] != ""){
					$new_member_insert_data["submit_to_person_name"] = $params["submit_to_person_name"];
				}
				
				if(!empty($params) && isset($params["submit_to_person_phone"]) && (int)$params["submit_to_person_phone"] > 0){
					$new_member_insert_data["submit_to_person_phone"] = $params["submit_to_person_phone"];
				}

if(!empty($params) && isset($params["jobsheet_no"]) && (int)$params["jobsheet_no"] > 0){
					$new_member_insert_data["jobsheet_no"] = $params["jobsheet_no"];
				}

                //if the insert has returned true then we show the flash message
                if($claim_id = $this->claim_model->add_claim_track_api($new_member_insert_data)){
                    $data = array();
					$data['status'] = 1;
					$data['message'] = $label." added successfully";
					$data['data'] = array("claim_id"=>$claim_id);
					$this->json_response($data); 
                }else{
                    $data = array();
					$data['status'] = 0;
					$data['message'] = "Claim already $label.";
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
    public function claim_track_list($status){
		
		$user_id = (int)$this->input->get('user_id');
		$data = array();
		$data['status'] = 1;
		$data['data']['count_claim']= $this->claim_model->count_claim_track_api($status);
	    $data['data']['claim'] = $this->claim_model->get_claim_track_api($status);
		$this->json_response($data);
	}
}
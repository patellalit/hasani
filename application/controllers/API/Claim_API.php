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
	
	public function pickup_add(){
		$this->claim_track_add(2,"Pickup");
	}
	public function pickup_list(){
		$this->claim_track_list(2);
	}
	
	public function claim_track_add($status,$label){
		
        $claim_id =$this->input->post('claim_id');
		//if save button was clicked, get the data sent via post
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            if ($claim_id != "")
            {
				$new_member_insert_data = array(
					'claim_id' => $claim_id,
					'remarks' => $this->input->post('remarks'),
					'status' => $status,
					'user_id' => $this->input->get('user_id'),
					'created_at' => date("Y-m-d H:i:s"),
				);

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

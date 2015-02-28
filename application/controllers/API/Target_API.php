<?php
class Target_API extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('target_model');
		$this->load->model('products_model');

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

	public function customer_details(){
		$cdkey = $this->input->post('customer_id');
		if($cdkey != ""){
			$data = array();
			$data['status'] = 1;
			$data['data']['customers'] = $this->target_model->get_product_registration_by_cdkey_api($cdkey);
			$this->json_response($data);
		}else{
			$data = array();
			$data['status'] = 0;
			$data['message'] = "Customer not found.";
			$this->json_response($data);
		}
	}

	public function target_add(){
		//if save button was clicked, get the data sent via post
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {

            //form validation
            $this->form_validation->set_rules('customer_id', 'Customer ID', 'required|trim');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
            	if($this->target_model->check_duplicate($this->input->post('customer_id'),$this->input->get('user_id'))){
            		$data = array();
            		$data['status'] = 0;
            		$data['message'] = "Customer is duplicate.";
            		$this->json_response($data);
            	}
            	
				$new_member_insert_data = array(
					'customer_id' => $this->input->post('customer_id'),
					'user_id' => $this->input->get('user_id'),
					'created_at' => date("Y-m-d"),
				);

                //if the insert has returned true then we show the flash message
                if($target_id = $this->target_model->add_target_api($new_member_insert_data)){
                    $data = array();
					$data['status'] = 1;
					$data['message'] = "Target added successfully";
					$data['data'] = array("target_id"=>$target_id);
					$this->json_response($data); 
                }else{
                    $data = array();
					$data['status'] = 0;
					$data['message'] = "Target not updated. Try again.";
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
	
	public function target_update(){
		//if save button was clicked, get the data sent via post
        //if save button was clicked, get the data sent via post
		$target_id = $this->uri->segment(5);
        if ($this->input->server('REQUEST_METHOD') === 'POST' && (int)$target_id > 0)
        {
			//form validation
            $this->form_validation->set_rules('customer_id', 'Customer ID', 'required|trim');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
				
				$new_member_insert_data = array(
					'customer_id' => $this->input->post('customer_id'),
					'user_id' => $this->input->get('user_id'),
				);

                if($target_id = $this->target_model->update_target_api($target_id,$new_member_insert_data)){
                    $data = array();
					$data['status'] = 1;
					$data['message'] = "Target updated successfully";
					$data['data'] = array("target_id"=>$target_id);
					$this->json_response($data); 
                }else{
                    $data = array();
					$data['status'] = 0;
					$data['message'] = "Target not updated. Try again.";
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
    * Delete target by his id
    * @return void
    */
    public function target_delete()
    {
        //target id 
        $target_id = $this->uri->segment(5);
		if((int) $id > 0){
			$this->target_model->delete_target_api($target_id);
			
			$data = array();
			$data['status'] = 1;
			$data['message'] = "Target deleted successfully";
			$data['data'] = array("target_id"=>$target_id);
			$this->json_response($data); 
		}else{
			$data = array();
			$data['status'] = 0;
			$data['message'] = "Target not deleted. Try again.";
			$this->json_response($data);
		}
    }//delete

	/**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function target_list(){
		
		$data = array();
		$data['status'] = 1;
		$data['data']['count_target']= $this->target_model->count_target_api();
	    $data['data']['target'] = $this->target_model->get_target_api('', '', 'ASC');
		$this->json_response($data);
	}
}

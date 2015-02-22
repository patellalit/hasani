<?php
class Trainee_DSR_API extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
		$this->load->model('customers_model');
		
		$this->load->model('trainee_model');

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

	public function trainee_add(){
		//if save button was clicked, get the data sent via post
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
			$this->form_validation->set_rules('customer_id', 'Customer Id', 'required|trim');
			$this->form_validation->set_rules('trainee_name', 'Trainee Name', 'required|trim');
			$this->form_validation->set_rules('trainee_mobile', 'Trainee Mobile', 'required|trim');                        

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
				$new_member_insert_data = array(
					'customer_id' => $this->input->post('customer_id'),
					'trainee_name' => $this->input->post('trainee_name'),
					'trainee_mobile' => $this->input->post('trainee_mobile'),
					'remarks' => $this->input->post('remarks'),
					'user_id' => $this->input->get('user_id'),
					'created_at' => date("Y-m-d"),
				);

                //if the insert has returned true then we show the flash message
                if($trainee_id = $this->trainee_model->add_trainee_api($new_member_insert_data)){
                    $data = array();
					$data['status'] = 1;
					$data['message'] = "Trainee added successfully";
					$data['data'] = array("trainee_id"=>$trainee_id);
					$this->json_response($data); 
                }else{
                    $data = array();
					$data['status'] = 0;
					$data['message'] = "Trainee not updated. Try again.";
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
	
	public function trainee_update(){
		//if save button was clicked, get the data sent via post
        //if save button was clicked, get the data sent via post
		$trainee_id = $this->uri->segment(5);
        if ($this->input->server('REQUEST_METHOD') === 'POST' && (int)$trainee_id > 0)
        {
			//form validation
			$this->form_validation->set_rules('customer_id', 'Customer Id', 'required|trim');
			$this->form_validation->set_rules('trainee_name', 'Trainee Name', 'required|trim');
			$this->form_validation->set_rules('trainee_mobile', 'Trainee Mobile', 'required|trim');                        

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
				
				$new_member_insert_data = array(
					'customer_id' => $this->input->post('customer_id'),
					'trainee_name' => $this->input->post('trainee_name'),
					'trainee_mobile' => $this->input->post('trainee_mobile'),
					'remarks' => $this->input->post('remarks'),
					'user_id' => $this->input->get('user_id'),
				);

                if($trainee_id = $this->trainee_model->update_trainee_api($trainee_id,$new_member_insert_data)){
                    $data = array();
					$data['status'] = 1;
					$data['message'] = "Trainee updated successfully";
					$data['data'] = array("trainee_id"=>$trainee_id);
					$this->json_response($data); 
                }else{
                    $data = array();
					$data['status'] = 0;
					$data['message'] = "Trainee not updated. Try again.";
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
    * Delete trainee by his id
    * @return void
    */
    public function trainee_delete()
    {
        //trainee id 
        $id = $this->uri->segment(5);
		if((int) $id > 0){
			$this->trainee_model->delete_trainee_api($id);
			
			$data = array();
			$data['status'] = 1;
			$data['message'] = "Trainee deleted successfully";
			$data['data'] = array("trainee_id"=>$id);
			$this->json_response($data); 
		}else{
			$data = array();
			$data['status'] = 0;
			$data['message'] = "Trainee not deleted. Try again.";
			$this->json_response($data);
		}
    }//delete

	/**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function trainee_list(){
		
		$data = array();
		$data['status'] = 1;
		$data['data']['count_trainee']= $this->trainee_model->count_trainee_api();
	    $data['data']['trainee'] = $this->trainee_model->get_trainee_api('', '', 'ASC');
		$this->json_response($data);
	}
}

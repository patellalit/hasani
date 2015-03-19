<?php
class Location_API extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('location_model');
		$this->load->model('users_model');

		$user_id = (int)$this->input->get('user_id');
		if($user_id <= 0){
			$data = array();
			$data['status'] = 0;
			$data['message'] = "You are not authorised user.";
			$this->json_response($data);
		}
    }

	function json_response($data){
		header('Content-Type: application/json; charset=UTF8');
		echo trim(json_encode($data));exit;
	}

	/**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function location_list(){
		$data = array();
		$data['status'] = 1;
		
		$offset = (int)$this->input->get('offset',0);
		$limit = (int)$this->input->get('limit',0);
		$user_id = $this->input->get('user_id');
		
		$notifications = $this->location_model->get_location_list($user_id,$offset,$limit);
		
		$data['data']['locations'] = $notifications;
		$this->json_response($data);
	}

	/**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function location_add(){
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {

            //form validation
            $this->form_validation->set_rules('lat', 'Lat', 'required|trim');
            $this->form_validation->set_rules('long', 'Long', 'required|trim');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
				$new_member_insert_data = array(
					'lat' => $this->input->post('lat'),
					'long' => $this->input->post('long'),
					'user_id' => $this->input->get('user_id'),
					'created_at' => date("Y-m-d h:i:s"),
				);

                //if the insert has returned true then we show the flash message
                if($notification_id = $this->location_model->add_location_api($new_member_insert_data)){
                    $data = array();
					$data['status'] = 1;
					$data['message'] = "Location added successfully";
					$data['data'] = array("notification_id"=>$notification_id);
					$this->json_response($data); 
                }else{
                    $data = array();
					$data['status'] = 0;
					$data['message'] = "Location not updated. Try again.";
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

}
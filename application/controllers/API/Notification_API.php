<?php
class Notification_API extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('notification_model');
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
    public function notification_list(){
		$data = array();
		$data['status'] = 1;
		
		$firms = $this->notification_model->get_notification_list($this->input->get('user_id'));
		
		$data['data']['notifications'] = $firms;
		$this->json_response($data);
	}

	/**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function notification_add(){
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {

            //form validation
            $this->form_validation->set_rules('message', 'Message', 'required|trim');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
				$new_member_insert_data = array(
					'message' => $this->input->post('message'),
					'user_id' => $this->input->get('user_id'),
					'created_at' => date("Y-m-d"),
				);
				$to_users = $this->users_model->get_child_users($new_member_insert_data["user_id"]);
				$to_user_ids = array();
				foreach($to_users as $to_user){
					$to_user_ids[] = $to_user["id"];
				}

                //if the insert has returned true then we show the flash message
                if($notification_id = $this->notification_model->add_notification_api($new_member_insert_data,$to_user_ids)){
                    $data = array();
					$data['status'] = 1;
					$data['message'] = "Message added successfully";
					$data['data'] = array("notification_id"=>$notification_id);
					$this->json_response($data); 
                }else{
                    $data = array();
					$data['status'] = 0;
					$data['message'] = "Message not updated. Try again.";
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
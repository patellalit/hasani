<?php
class DSR_API extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
		$this->load->model('customers_model');
		$this->load->model('products_model');
		
		$this->load->model('dsr_model');

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

	public function customer_area_list(){
		$data = array();
		$data['status'] = 1;
	    $data['data']['area'] = $this->customers_model->get_area_api();
		$this->json_response($data);
	}
	public function customer_list_by_area(){
		$area = $this->input->post('ol_area');
		if($area != ""){
			$data = array();
			$data['status'] = 1;
			$data['data']['customers'] = $this->customers_model->get_customer_by_area_api($area);
			$this->json_response($data);
		}else{
			$data = array();
			$data['status'] = 0;
			$data['message'] = "Area not found.";
			$this->json_response($data);
		}
	}

	public function dsr_add(){
		//if save button was clicked, get the data sent via post
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
			$this->form_validation->set_rules('customer_id', 'Customer Id', 'required|trim');
			$this->form_validation->set_rules('product_id', 'Product Id', 'required|trim');
			$this->form_validation->set_rules('qty', 'QTY', 'required|trim');                        
			$this->form_validation->set_rules('payment_by', 'Payment By', 'required|trim');
            $this->form_validation->set_rules('amount', 'Amount', 'required|trim');
			if($this->input->post('payment_by') == "cheque"){
				$this->form_validation->set_rules('cheque_number', 'Cheque Number', 'required|trim');
		        $this->form_validation->set_rules('bank_name', 'Bank Name', 'required|trim');
		        $this->form_validation->set_rules('cheque_date', 'Cheque Date', 'required|trim');
			}

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
				$new_member_insert_data = array(
					'customer_id' => $this->input->post('customer_name'),
					'product_id' => $this->input->post('ol_name'),
					'qty' => $this->input->post('ol_address'),
					'payment_by' => $this->input->post('ol_area'),
					'amount' => $this->input->post('email'),
					'remarks' => $this->input->post('mobile'),
					'user_id' => $this->input->get('user_id'),
					'created_at' => date("Y-m-d"),
				);

				if($this->input->post('payment_by') == "cheque"){
					$new_member_insert_data['cheque_number'] = $this->input->post('cheque_date');
					$new_member_insert_data['bank_name'] = $this->input->post('bank_name');
					if($this->input->post('cheque_date',"") != ""){
						$new_member_insert_data['cheque_date'] = date("Y-m-d",strtotime($this->input->post('cheque_date')));
					}
				}

				
                //if the insert has returned true then we show the flash message
                if($dsr_id = $this->dsr_model->add_dsr_api($new_member_insert_data)){
                    $data = array();
					$data['status'] = 1;
					$data['message'] = "DSR added successfully";
					$data['data'] = array("dsr_id"=>$dsr_id);
					$this->json_response($data); 
                }else{
                    $data = array();
					$data['status'] = 0;
					$data['message'] = "DSR not updated. Try again.";
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
	
	public function dsr_update(){
		//if save button was clicked, get the data sent via post
        //if save button was clicked, get the data sent via post
		$dsr_id = $this->uri->segment(5);
        if ($this->input->server('REQUEST_METHOD') === 'POST' && (int)$dsr_id > 0)
        {
			//form validation
			$this->form_validation->set_rules('customer_id', 'Customer Id', 'required|trim');
			$this->form_validation->set_rules('product_id', 'Product Id', 'required|trim');
			$this->form_validation->set_rules('qty', 'QTY', 'required|trim');                        
			$this->form_validation->set_rules('payment_by', 'Payment By', 'required|trim');
            $this->form_validation->set_rules('amount', 'Amount', 'required|trim');
			if($this->input->post('payment_by') == "cheque"){
				$this->form_validation->set_rules('cheque_number', 'Cheque Number', 'required|trim');
		        $this->form_validation->set_rules('bank_name', 'Bank Name', 'required|trim');
		        $this->form_validation->set_rules('cheque_date', 'Cheque Date', 'required|trim');
			}

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
				
				$new_member_insert_data = array(
					'customer_id' => $this->input->post('customer_name'),
					'product_id' => $this->input->post('ol_name'),
					'qty' => $this->input->post('ol_address'),
					'payment_by' => $this->input->post('ol_area'),
					'amount' => $this->input->post('email'),
					'remarks' => $this->input->post('mobile'),
					'user_id' => $this->input->get('user_id'),
				);

				if($this->input->post('payment_by') == "cheque"){
					$new_member_insert_data['cheque_number'] = $this->input->post('cheque_date');
					$new_member_insert_data['bank_name'] = $this->input->post('bank_name');
					if($this->input->post('cheque_date',"") != ""){
						$new_member_insert_data['cheque_date'] = date("Y-m-d",strtotime($this->input->post('cheque_date')));
					}
				}
				
                if($dsr_id = $this->dsr_model->update_dsr_api($dsr_id,$new_member_insert_data)){
                    $data = array();
					$data['status'] = 1;
					$data['message'] = "DSR updated successfully";
					$data['data'] = array("dsr_id"=>$dsr_id);
					$this->json_response($data); 
                }else{
                    $data = array();
					$data['status'] = 0;
					$data['message'] = "DSR not updated. Try again.";
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
    * Delete dsr by his id
    * @return void
    */
    public function dsr_delete()
    {
        //dsr id 
        $id = $this->uri->segment(5);
		if((int) $id > 0){
			$this->dsr_model->delete_dsr_api($id);
			
			$data = array();
			$data['status'] = 1;
			$data['message'] = "DSR deleted successfully";
			$data['data'] = array("dsr_id"=>$id);
			$this->json_response($data); 
		}else{
			$data = array();
			$data['status'] = 0;
			$data['message'] = "DSR not deleted. Try again.";
			$this->json_response($data);
		}
    }//delete

	/**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function dsr_list(){
		
		$data = array();
		$data['status'] = 1;
		$data['data']['count_dsr']= $this->dsr_model->count_dsr_api();
	    $data['data']['dsr'] = $this->dsr_model->get_dsr_api('', '', 'ASC');
		$this->json_response($data);
	}
}

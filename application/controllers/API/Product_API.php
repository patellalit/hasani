<?php
class Product_API extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
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

	public function product_add(){
		//if save button was clicked, get the data sent via post
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {

            //form validation
			$this->form_validation->set_rules('product_id', 'Product Id', 'required|trim');
			$this->form_validation->set_rules('customer_name', 'Customer name', 'required|trim');                        
			$this->form_validation->set_rules('customer_address', 'Customer address', 'required|trim');
            $this->form_validation->set_rules('customer_id', 'Customer ID', 'required|trim');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
				$new_member_insert_data = array(
					'product_id' => $this->input->post('product_id'),
					'customer_name' => $this->input->post('customer_name'),
					'customer_address' => $this->input->post('customer_address'),
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
	
	public function product_update(){
		//if save button was clicked, get the data sent via post
        //if save button was clicked, get the data sent via post
		$target_id = $this->uri->segment(5);
        if ($this->input->server('REQUEST_METHOD') === 'POST' && (int)$target_id > 0)
        {
			//form validation
			$this->form_validation->set_rules('product_id', 'Product Id', 'required|trim');
			$this->form_validation->set_rules('customer_name', 'Customer name', 'required|trim');                        
			$this->form_validation->set_rules('customer_address', 'Customer address', 'required|trim');
            $this->form_validation->set_rules('customer_id', 'Customer ID', 'required|trim');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
				
				$new_member_insert_data = array(
					'product_id' => $this->input->post('product_id'),
					'customer_name' => $this->input->post('customer_name'),
					'customer_address' => $this->input->post('customer_address'),
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
    public function product_delete()
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
    public function product_list(){
		
		$data = array();
		$data['status'] = 1;
		$data['data']['count_products']= $this->products_model->count_products_api();
	    $data['data']['products'] = $this->products_model->get_products_api('', '', 'DESC');
		$this->json_response($data);
	}
}

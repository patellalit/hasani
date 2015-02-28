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

	public function customer_list_by_city(){
		$city_id = $this->input->post('city_id');
		if($city_id != ""){
			$data = array();
			$data['status'] = 1;
			$data['data']['customers'] = $this->customers_model->get_customer_by_city_api($city_id);
			$this->json_response($data);
		}else{
			$data = array();
			$data['status'] = 0;
			$data['message'] = "O/L not found.";
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
			$this->form_validation->set_rules('products', 'Products', 'required|trim');
			//$this->form_validation->set_rules('product_id', 'Product Id', 'required|trim');
			//$this->form_validation->set_rules('qty', 'QTY', 'required|trim');
			//$this->form_validation->set_rules('amount', 'Amount', 'required|trim');

			$this->form_validation->set_rules('payment_by', 'Payment By', 'required|trim');
            
			if($this->input->post('payment_by') == "cheque"){
				$this->form_validation->set_rules('cheque_number', 'Cheque Number', 'required|trim');
		        $this->form_validation->set_rules('bank_name', 'Bank Name', 'required|trim');
		        $this->form_validation->set_rules('cheque_date', 'Cheque Date', 'required|trim');
			}

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
            	if($this->dsr_model->check_duplicate($this->input->post('customer_id'),$this->input->get('user_id'))){
            		$data = array();
            		$data['status'] = 0;
            		$data['message'] = "Customer is duplicate.";
            		$this->json_response($data);
            	}
            	
				$new_member_insert_data = array(
					'customer_id' => $this->input->post('customer_id'),
					//'product_id' => $this->input->post('product_id'),
					//'qty' => $this->input->post('qty'),
					'payment_by' => $this->input->post('payment_by'),
					//'amount' => $this->input->post('amount'),
					'remarks' => $this->input->post('remarks'),
					'user_id' => $this->input->get('user_id'),
					'created_at' => date("Y-m-d"),
				);

				if($this->input->post('payment_by') == "cheque"){
					$new_member_insert_data['cheque_number'] = $this->input->post('cheque_number');
					$new_member_insert_data['bank_name'] = $this->input->post('bank_name');
					if($this->input->post('cheque_date',"") != ""){
						$new_member_insert_data['cheque_date'] = date("Y-m-d",strtotime($this->input->post('cheque_date')));
					}
				}

				
                //if the insert has returned true then we show the flash message
                if($dsr_id = $this->dsr_model->add_dsr_api($new_member_insert_data)){
					$products = json_decode($this->input->post('products'),true);
					foreach($products as $product){
						$new_member_insert_data = array(
							"dsr_id"=>$dsr_id,
							"product_id"=>$product["product_id"],
							"qty"=>$product["qty"],
							"price"=>"(select price from plans where id=".$product["product_id"].")",
						);
						$this->dsr_model->add_dsr_product_api($new_member_insert_data);
					}

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
			$this->form_validation->set_rules('products', 'Products', 'required|trim');
			//$this->form_validation->set_rules('product_id', 'Product Id', 'required|trim');
			//$this->form_validation->set_rules('qty', 'QTY', 'required|trim');                        
			$this->form_validation->set_rules('payment_by', 'Payment By', 'required|trim');
            //$this->form_validation->set_rules('amount', 'Amount', 'required|trim');
			if($this->input->post('payment_by') == "cheque"){
				$this->form_validation->set_rules('cheque_number', 'Cheque Number', 'required|trim');
		        $this->form_validation->set_rules('bank_name', 'Bank Name', 'required|trim');
		        $this->form_validation->set_rules('cheque_date', 'Cheque Date', 'required|trim');
			}

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
				
				$new_member_insert_data = array(
					'customer_id' => $this->input->post('customer_id'),
					'product_id' => $this->input->post('product_id'),
					'qty' => $this->input->post('qty'),
					'payment_by' => $this->input->post('payment_by'),
					'amount' => $this->input->post('amount'),
					'remarks' => $this->input->post('remarks'),
					'user_id' => $this->input->get('user_id'),
					'created_at' => date("Y-m-d"),
				);

				if($this->input->post('payment_by') == "cheque"){
					$new_member_insert_data['cheque_number'] = $this->input->post('cheque_number');
					$new_member_insert_data['bank_name'] = $this->input->post('bank_name');
					if($this->input->post('cheque_date',"") != ""){
						$new_member_insert_data['cheque_date'] = date("Y-m-d",strtotime($this->input->post('cheque_date')));
					}
				}
				
                if($dsr_id = $this->dsr_model->update_dsr_api($dsr_id,$new_member_insert_data)){
					$products = json_decode($this->input->post('products'),true);
					foreach($products as $product){
						$new_member_insert_data = array(
							"dsr_id"=>$dsr_id,
							"product_id"=>$product["product_id"],
							"qty"=>$product["qty"],
							"price"=>"(select price from plans where id=".$product["product_id"].")",
						);
						$this->dsr_model->add_dsr_product_api($new_member_insert_data,$product["item_id"]);
					}

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

                            
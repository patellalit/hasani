<?php
class Admin_products extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('products_model');

        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }
    }
 
    /**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function index()
    {

        //all the posts sent by the view
        $search_string = $this->input->post('search_string');        
        $order = $this->input->post('order'); 
        $order_type = $this->input->post('order_type'); 

        //pagination settings
        $config['per_page'] = 25;
        $config['base_url'] = base_url().'admin/products';
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 20;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        //limit end
        $page = $this->uri->segment(3);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 

        //if order type was changed
        if($order_type){
            $filter_session_data['order_type'] = $order_type;
        }
        else{
            //we have something stored in the session? 
            if($this->session->userdata('order_type')){
                $order_type = $this->session->userdata('order_type');    
            }else{
                //if we have nothing inside session, so it's the default "Asc"
                $order_type = 'Asc';    
            }
        }
        //make the data type var avaible to our view
        $data['order_type_selected'] = $order_type;        


        //we must avoid a page reload with the previous session data
        //if any filter post was sent, then it's the first time we load the content
        //in this case we clean the session filter data
        //if any filter post was sent but we are in some page, we must load the session data

        //filtered && || paginated
        if($search_string !== false && $order !== false || $this->uri->segment(3) == true){ 
           
            /*
            The comments here are the same for line 79 until 99

            if post is not null, we store it in session data array
            if is null, we use the session data already stored
            we save order into the the var to load the view with the param already selected       
            */

            if($search_string){
                $filter_session_data['search_string_selected'] = $search_string;
            }else{
                $search_string = $this->session->userdata('search_string_selected');
            }
            $data['search_string_selected'] = $search_string;

            if($order){
                $filter_session_data['order'] = $order;
            }
            else{
                $order = $this->session->userdata('order');
            }
            $data['order'] = $order;

            //save session data into the session
            $this->session->set_userdata($filter_session_data);

            $data['count_products']= $this->products_model->count_products($search_string, $order);
            $config['total_rows'] = $data['count_products'];

            //fetch sql data into arrays
            if($search_string){
                if($order){
                    $data['products'] = $this->products_model->get_products($search_string, $order, $order_type, $config['per_page'],$limit_end);        
                }else{
                    $data['products'] = $this->products_model->get_products($search_string, '', $order_type, $config['per_page'],$limit_end);           
                }
            }else{
                if($order){
                    $data['products'] = $this->products_model->get_products('', $order, $order_type, $config['per_page'],$limit_end);        
                }else{
					
                    $data['products'] = $this->products_model->get_products('', '', $order_type, $config['per_page'],$limit_end);        
                }
            }

        }else{

            //clean filter data inside section
            $filter_session_data['search_string_selected'] = null;
            $filter_session_data['order'] = null;
            $filter_session_data['order_type'] = null;
            $this->session->set_userdata($filter_session_data);

            //pre selected options
            $data['search_string_selected'] = '';
            $data['order'] = 'id';

            //fetch sql data into arrays
            $data['count_products']= $this->products_model->count_products();
            $data['products'] = $this->products_model->get_products('', '', $order_type, $config['per_page'],$limit_end);        
            $config['total_rows'] = $data['count_products'];

        }//!isset($manufacture_id) && !isset($search_string) && !isset($order)

        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'admin/products/list';
        $this->load->view('includes/template', $data);  

    }//index

    public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {

            //form validation
			$this->form_validation->set_rules('item', 'Item', 'required');            
			$this->form_validation->set_rules('description', 'Description', 'required');
            $this->form_validation->set_rules('um', 'U/M', 'required');
            $this->form_validation->set_rules('cost_price', 'Cost', 'required|numeric');

            $this->form_validation->set_rules('sell_price_retail', 'Retail Price', 'required|numeric');
			$this->form_validation->set_rules('sell_price_wholesale', 'Wholesale Price', 'required|numeric');
			$this->form_validation->set_rules('sell_price_retail_max', 'Retail Max Parameters', 'required|numeric');
			$this->form_validation->set_rules('sell_price_wholesale_max', 'Wholesale Max Parameters Price', 'required|numeric');

            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $data_to_store = array(
                    'item' => $this->input->post('item'),
                    'description' => $this->input->post('description'),
					'um' => $this->input->post('um'),
                    'cost_price' => $this->input->post('cost_price'),
                    'sell_price_retail' => $this->input->post('sell_price_retail'),          
                    'sell_price_wholesale' => $this->input->post('sell_price_wholesale'),
					'sell_price_retail_max' => $this->input->post('sell_price_retail_max'),
					'sell_price_wholesale_max' => $this->input->post('sell_price_wholesale_max'),
					'created_at' => date("Y-m-d H:i:s")
                );
                //if the insert has returned true then we show the flash message
                if($this->products_model->store_product($data_to_store)){
                    $data['flash_message'] = TRUE; 
                }else{
                    $data['flash_message'] = FALSE; 
                }

            }

        }
        //load the view
        $data['main_content'] = 'admin/products/add';
        $this->load->view('includes/template', $data);  
    }       

    /**
    * Update item by his id
    * @return void
    */
    public function update()
    {
        //product id 
        $id = $this->uri->segment(4);
  
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
           $this->form_validation->set_rules('item', 'Item', 'required');            
			$this->form_validation->set_rules('description', 'Description', 'required');
            $this->form_validation->set_rules('um', 'U/M', 'required');
            $this->form_validation->set_rules('cost_price', 'Cost', 'required|numeric');

            $this->form_validation->set_rules('sell_price_retail', 'Retail Price', 'required|numeric');
			$this->form_validation->set_rules('sell_price_wholesale', 'Wholesale Price', 'required|numeric');
			$this->form_validation->set_rules('sell_price_retail_max', 'Retail Max Parameters', 'required|numeric');
			$this->form_validation->set_rules('sell_price_wholesale_max', 'Wholesale Max Parameters Price', 'required|numeric');
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
    
                $data_to_store = array(
                    'item' => $this->input->post('item'),
                    'description' => $this->input->post('description'),
					'um' => $this->input->post('um'),
                    'cost_price' => $this->input->post('cost_price'),
                    'sell_price_retail' => $this->input->post('sell_price_retail'),          
                    'sell_price_wholesale' => $this->input->post('sell_price_wholesale'),
					'sell_price_retail_max' => $this->input->post('sell_price_retail_max'),
					'sell_price_wholesale_max' => $this->input->post('sell_price_wholesale_max'),
					'created_at' => date("Y-m-d H:i:s")
                );
                //if the insert has returned true then we show the flash message
                if($this->products_model->update_product($id, $data_to_store) == TRUE){
                    $this->session->set_flashdata('flash_message', 'updated');
                }else{
                    $this->session->set_flashdata('flash_message', 'not_updated');
                }
                redirect('admin/products/update/'.$id.'');

            }//validation run

        }

        //if we are updating, and the data did not pass trough the validation
        //the code below wel reload the current data

        //product data 
        $data['product'] = $this->products_model->get_product_by_id($id);
        //load the view
        $data['main_content'] = 'admin/products/edit';
        $this->load->view('includes/template', $data);            

    }//update

    /**
    * Delete product by his id
    * @return void
    */
    public function delete()
    {
        //product id 
        $id = $this->uri->segment(4);
        $this->products_model->delete_product($id);
        redirect('admin/products');
    }//edit

	public function csv(){
		if ($this->input->server('REQUEST_METHOD') === 'POST'){
//			echo "f=".mime_content_type($_FILES['csv_file']['tmp_name']);
			$this->load->helper(array('form', 'url'));
			//$this->load->library('csvimport');
			$config['upload_path'] = 'assets/uploads/';
			$config['allowed_types'] = 'csv';
			$this->load->library('upload', $config);

			if ( !$this->upload->do_upload('csv_file')) {
				$error = array('error' => $this->upload->display_errors());
				$this->session->set_flashdata('flash_message', 'csv_error');
				$data["csv_error"] = $error;
			}else{
				$file_data = $this->upload->data(); 

				$file = fopen($file_data["full_path"],"r");
				$index=1;
				while(! feof($file))
				{
					$cols = fgetcsv($file);
					if($index == 1){
					$index++;continue;
					}
					

					$item = trim($cols[0]);
					$description = trim($cols[1]);
					$um = trim($cols[2]);
					$cost = str_replace("$","",trim($cols[3]));
					$wp = str_replace("$","",trim($cols[4]));
					$wmp = str_replace("$","",trim($cols[5]));
					$rp = str_replace("$","",trim($cols[6]));
					$rmp = str_replace("$","",trim($cols[7]));
					if($item != "" && $um != "" && $cost != "" && $wp != "" && $wmp != "" && $rp != "" && $rmp != ""){

						$data_to_store = array(
				            'item' => $item,
				            'description' => $description,
							'um' => $um,
				            'cost_price' => (float)$cost,
				            'sell_price_retail' => (float)$rp,          
				            'sell_price_wholesale' => (float)$wp,
							'sell_price_retail_max' => (float)$rmp,
							'sell_price_wholesale_max' => (float)$wmp,
							'created_at' => date("Y-m-d H:i:s")
				        );
				        //if the insert has returned true then we show the flash message
				        $this->products_model->store_product($data_to_store);
					}
					$index++;
				}

				fclose($file);
				@unlink($file_data["full_path"]);

				$this->session->set_flashdata('flash_message', 'csv_success');
				
			}
			
			redirect('admin/products');
		}else{
			redirect('admin/products');
		}
	}

}

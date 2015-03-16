<?php
class Admin_dealers extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('customers_model');
		$this->load->helper('common_helper');
		$this->load->library('apicall');

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

        //pagination settings
        $config['per_page'] = 25;
        $config['base_url'] = base_url().'admin/dealers';
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 20;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['uri_segment'] = 3;
        $config['num_links'] = 4;

		//limit end
        $page = $this->uri->segment(3);
		//math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 

		//all the posts sent by the view
        $search_string = $this->input->get('search_string');
        $search_in = $this->input->get('search_in');
        $order = $this->input->get('order');
        $order_type = $this->input->get('order_type');
		
		//filtered && || paginated
        if($search_string !== false && $search_string != "" && $order !== false || $this->uri->segment(3) == true){ 
			$filter_session_data = array();
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
		            $order_type = 'DESC';    
		        }
		    }
		    //make the data type var avaible to our view
		    $data['order_type_selected'] = $order_type;
		
			if($search_string){
		        $filter_session_data['search_string_selected'] = $search_string;
		    }else{
		        $search_string = $this->session->userdata('search_string_selected');
		    }
		    $data['search_string_selected'] = $search_string;
            
            if($search_in){
                $filter_session_data['search_in'] = $search_in;
            }else{
                $search_in = $this->session->userdata('search_in');
            }
            $data['search_in'] = $search_in;

			if($order){
		        $filter_session_data['order'] = $order;
		    }
		    else{
		        $order = $this->session->userdata('order');
		    }
		    $data['order'] = $order;

			//save session data into the session
		    $this->session->set_userdata($filter_session_data);
		}else{
			//clean filter data inside section
            $filter_session_data['search_string_selected'] = null;
            $filter_session_data['search_in'] = null;
            $filter_session_data['order'] = null;
            $filter_session_data['order_type'] = null;
            $this->session->set_userdata($filter_session_data);

            //pre selected options
            $data['search_string_selected'] = '';
            $data['search_in'] = '';
            $data['order'] = 'id';
			$data['order_type_selected'] = 'desc';
		}

		$request_params = array("search_string"=>$data['search_string_selected'],"search_in"=>$data['search_in'],"offset"=>$limit_end,"limit"=>$config['per_page'],"sort"=>$data['order'],"sort_dir"=>$data['order_type_selected']);
		$users = $this->apicall->call("GET","dealers",$request_params);

		$data['count_dealers'] = $users["data"]["count_customers"];
		$data['dealers'] = $users["data"]["customers"];
		$config['total_rows'] = $data['count_dealers'];

        //initializate the panination helper 
        $this->pagination->initialize($config);  

        //load the view
        $data['main_content'] = 'admin/dealers/list';
        $this->load->view('includes/template', $data);  

    }//index

    public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {

            //form validation
			$this->form_validation->set_rules('customer_name', 'Customer Name', 'required|trim');
			$this->form_validation->set_rules('ol_name', 'O/L Name', 'required|trim');
			$this->form_validation->set_rules('ol_address', 'O/L Address', 'required|trim');                        
			$this->form_validation->set_rules('ol_area', 'O/L Area', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|email');
            $this->form_validation->set_rules('mobile', 'Mobile', 'required|trim');

            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $request_params = $_POST;
				$users = $this->apicall->call("POST","dealer/add",$request_params);

                //if the insert has returned true then we show the flash message
                if($users["status"] == 1){
                    $data['flash_message'] = TRUE; 
                }else{
                    $data['flash_message'] = FALSE;
                }

            }

        }
        //load the view
        $data['main_content'] = 'admin/dealers/add';
        $this->load->view('includes/template', $data);  
    }       

    /**
    * Update item by his id
    * @return void
    */
    public function update()
    {
        //customer id 
        $id = $this->uri->segment(4);
  
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
            $this->form_validation->set_rules('customer_name', 'Customer Name', 'required|trim');
			$this->form_validation->set_rules('ol_name', 'O/L Name', 'required|trim');
			$this->form_validation->set_rules('ol_address', 'O/L Address', 'required|trim');                        
			$this->form_validation->set_rules('ol_area', 'O/L Area', 'required|trim');
            $this->form_validation->set_rules('email', 'Email', 'required|trim|email');
            $this->form_validation->set_rules('mobile', 'Mobile', 'required|trim');

            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
    
                $request_params = $_POST;

				$users = $this->apicall->call("POST","dealer/update/".$id,$request_params);
                //if the insert has returned true then we show the flash message
                if($users["status"] == 1){
                    $this->session->set_flashdata('flash_message', 'updated');
                }else{
                    $this->session->set_flashdata('flash_message', 'not_updated');
                }
                redirect('admin/dealers/update/'.$id.'');

            }//validation run

        }

        //if we are updating, and the data did not pass trough the validation
        //the code below wel reload the current data

        //customer data 
        $data['customers'] = $this->customers_model->get_customer_by_id($id);
        //load the view
        $data['main_content'] = 'admin/dealers/edit';
        $this->load->view('includes/template', $data);            

    }//update

    /**
    * Delete customer by his id
    * @return void
    */
    public function delete()
    {
        //customerw id 
        $id = $this->uri->segment(4);
        $users = $this->apicall->call("GET","dealer/delete/".$id);
        redirect('admin/dealers');
    }//edit

}

                            
                            
<?php
class Admin_users extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('users_model');
        $this->load->helper('common_helper');
		$this->load->library('apicall');
        
        $this->load->model('country_model');
        $this->load->model('city_model');
        $this->load->model('state_model');
        $this->load->model('products_model');
        $this->load->model('area_model');

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
        //$config['per_page'] = 25;
        $data['pagingoption'] = get_paging_options();
        if($this->input->get('pagingval') != "")
            $config['per_page'] = $this->input->get('pagingval');
        else
            $config['per_page'] = $data['pagingoption'][0];
        
        $gets = $_GET;
        unset($gets['per_page']);
        $config['base_url'] = base_url().'admin/users/page?'.http_build_query($gets);
        $config['use_page_numbers'] = TRUE;
        $config['page_query_string'] = TRUE;
        
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
        $page = $this->input->get('per_page');//$this->uri->segment(3);
        
        $data['pagingval'] = $config['per_page'];
		//math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 
        //echo $this->input->get('search_string');exit;
		//all the posts sent by the view
        $search_string = $this->input->get('search_string');
        $order = $this->input->get('order');
        $order_type = $this->input->get('order_type');
		$search_in = $this->input->get('search_in');
		
		//filtered && || paginated
		if(($search_string != "" && $search_in ) || $order !== false || $this->uri->segment(3) == true){ 
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
		
		    if($search_in == "all"){
		        $search_string = null;
		    }else if($search_string){
		        $filter_session_data['search_string_selected'] = $search_string;
		    }else{
				$search_string = $this->session->userdata('search_string_selected');
			}
		    $data['search_string_selected'] = $search_string;

			if($search_in == "all"){
		        $search_in = null;
		    }else if($search_in){
		        $filter_session_data['search_in_selected'] = $search_in;
		    }else{
		        $search_in = $this->session->userdata('search_in_selected');
		    }
		    $data['search_in_selected'] = $search_in;
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
			$filter_session_data["search_in_selected"]=null;
            $filter_session_data['order'] = null;
            $filter_session_data['order_type'] = null;

            $this->session->set_userdata($filter_session_data);

            //pre selected options
            $data['search_string_selected'] = '';
            $data['order'] = 'm.id';
			$data['order_type_selected'] = 'desc';
			$data["search_in_selected"]="";
		}

		$request_params = array("search_in"=>$search_in,"search_string"=>$data['search_string_selected'],"offset"=>$limit_end,"limit"=>$config['per_page'],"sort"=>$data['order'],"sort_dir"=>$data['order_type_selected']);
		$users = $this->apicall->call("GET","users",$request_params);

		$data['count_users'] = $users["data"]["count_users"];
		$data['users'] = $users["data"]["users"];
		$config['total_rows'] = $data['count_users'];

        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'admin/users/list';
        $this->load->view('includes/template', $data);  

    }//index
    
    public function users_csv()
    {
        
        //pagination settings
        //$config['per_page'] = 25;
        $data['pagingoption'] = get_paging_options();
        if($this->input->get('pagingval') != "")
            $config['per_page'] = $this->input->get('pagingval');
        else
            $config['per_page'] = $data['pagingoption'][0];
        
        $gets = $_GET;
        unset($gets['per_page']);
        $config['base_url'] = base_url().'admin/users/page?'.http_build_query($gets);
        $config['use_page_numbers'] = TRUE;
        $config['page_query_string'] = TRUE;
        
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
        $page = $this->input->get('per_page');//$this->uri->segment(3);
        
        $data['pagingval'] = $config['per_page'];
        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        }
        //echo $this->input->get('search_string');exit;
        //all the posts sent by the view
        $search_string = $this->input->get('search_string');
        $order = $this->input->get('order');
        $order_type = $this->input->get('order_type');
        $search_in = $this->input->get('search_in');
        
        //filtered && || paginated
        if(($search_string != "" && $search_in ) || $order !== false || $this->uri->segment(3) == true){
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
            
            if($search_in == "all"){
                $search_string = null;
            }else if($search_string){
                $filter_session_data['search_string_selected'] = $search_string;
            }else{
                $search_string = $this->session->userdata('search_string_selected');
            }
            $data['search_string_selected'] = $search_string;
            
            if($search_in == "all"){
                $search_in = null;
            }else if($search_in){
                $filter_session_data['search_in_selected'] = $search_in;
            }else{
                $search_in = $this->session->userdata('search_in_selected');
            }
            $data['search_in_selected'] = $search_in;
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
            $filter_session_data["search_in_selected"]=null;
            $filter_session_data['order'] = null;
            $filter_session_data['order_type'] = null;
            
            $this->session->set_userdata($filter_session_data);
            
            //pre selected options
            $data['search_string_selected'] = '';
            $data['order'] = 'm.id';
            $data['order_type_selected'] = 'desc';
            $data["search_in_selected"]="";
        }
        
        $request_params = array("search_in"=>$search_in,"search_string"=>$data['search_string_selected'],"offset"=>$limit_end,"limit"=>$config['per_page'],"sort"=>$data['order'],"sort_dir"=>$data['order_type_selected']);
        $users = $this->apicall->call("GET","users",$request_params);
        
        $data['count_users'] = $users["data"]["count_users"];
        $data['users'] = $users["data"]["users"];
        $config['total_rows'] = $data['count_users'];
        
        $file = fopen("users.csv","w");
        fputcsv($file,array('','First Name','Last Name','Company Phone','Company Email','Personal Phone','Personal Email','Address','Role'));
        
        foreach ($data['users'] as $line)
        {
            $arr = array($line['id'],$line['first_name'],$line['last_name'],$line['mobile'],$line['email_address'],$line['personal_phone'],$line['personal_email'],$line['address'],$line['role_name']);
            fputcsv($file,$arr);
           
        }
        
        fclose($file);
        echo $filename = "users.csv";exit;
        
        //initializate the panination helper 
        //$this->pagination->initialize($config);
        
        //load the view
        //$data['main_content'] = 'admin/users/list';
        //$this->load->view('includes/template', $data);
        
    }//index

    public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {

            //form validation
			$this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required|trim');
			$this->form_validation->set_rules('mobile', 'Company Mobile', 'required|trim');
			$this->form_validation->set_rules('personal_phone', 'Personal Mobile', 'required|trim');
			$this->form_validation->set_rules('address', 'Address', 'required|trim');
			$this->form_validation->set_rules('role', 'Role', 'required|trim');
			if($this->input->post('role') == 7){ //If ISD user selected
				$this->form_validation->set_rules('ol_name', 'Dealer Name', 'required|trim');
				$this->form_validation->set_rules('ol_area', 'Dealer Area', 'required|trim');
			}
            $this->form_validation->set_rules('mobile', 'Company Mobile', 'required|trim');
			$this->form_validation->set_rules('password', 'Password', 'required|trim');
            
            $this->form_validation->set_rules('parent', 'parent', 'required|trim');
            $this->form_validation->set_rules('area_id', 'Area', 'required|trim');

            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
				$request_params = $_POST;
                //check if email or phone exists start
                $isexists = $this->users_model->is_phone_email_exists($request_params['mobile'],$request_params['personal_phone'],$request_params['email'],$request_params['personal_email'],0);
                if(empty($isexists))
                {
                    $users = $this->apicall->call("POST","user/add",$request_params);

                    //if the insert has returned true then we show the flash message
                    if($users["status"] == 1){
                        $data['flash_message'] = TRUE; 
                    }else{
                        $data['flash_message'] = FALSE;
                    }
                }
                else
                {
                    $data['flash_message'] = FALSE;
                }
            }
        }
        $data['country'] = $this->country_model->get_country();
        $data['state'] = array();
        $data['city'] = array();
        $data['area'] = array();
        $data['roles'] = $this->users_model->get_roles();
        //load the view
        $data['main_content'] = 'admin/users/add';
        $this->load->view('includes/template', $data);  
    }       
    /**
     * Update item by his id
     * @return void
     */
    public function registered_user_edit()
    {
        //user id
        $id = $this->uri->segment(5);
        
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
            $this->form_validation->set_rules('cdkey', 'Cdkey', 'required|trim');
            $this->form_validation->set_rules('customer_name', 'Customer name', 'required|trim');
            //$this->form_validation->set_rules('username', 'Username', 'required|trim');
            $this->form_validation->set_rules('modelNo', 'Model No', 'required|trim');
            $this->form_validation->set_rules('imeiNo', 'ImeiNo', 'required');
            $this->form_validation->set_rules('billNo', 'BillNo', 'required|trim');
            
            $this->form_validation->set_rules('dealerName', 'Dealer Name', 'required|trim');
            $this->form_validation->set_rules('customerAddress', 'Customer Address', 'trim');
            //$this->form_validation->set_rules('package_name', 'Package name', 'required|trim');
            
            $this->form_validation->set_rules('imeiNo2', 'ImeiNo2', 'trim');
            $this->form_validation->set_rules('planDate', 'Plan Date', 'required|trim');
            
            $this->form_validation->set_rules('state', 'State', 'required|trim');
            $this->form_validation->set_rules('city', 'City', 'required|trim');
            $this->form_validation->set_rules('area', 'Area', 'required|trim');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $request_params = $_POST;
                //$isexists = $this->users_model->is_phone_email_exists($request_params['mobile'],$request_params['personal_phone'],$request_params['email'],$request_params['personal_email'],$id);
                //if(empty($isexists))
                {
                    //$users = $this->apicall->call("POST","user/update/".$id,$request_params);
                    $array = array('customerName'=>$request_params['customer_name'],
                                   'phoneNo'=>$request_params['phoneNo'],
                                   'modelNo'=>$request_params['modelNo'],
                                   'modelName'=>$request_params['modelName'],
                                   'imeiNo'=>$request_params['imeiNo'],
                                   'billNo'=>$request_params['billNo'],
                                   'purchaseDate'=>$request_params['purchaseDate'],
                                   'billAmount'=>$request_params['billAmount'],
                                   'dealerName'=>$request_params['dealerName'],
                                   'plan_id'=>$request_params['plan'],
                                   'customerAddress'=>$request_params['customerAddress'],
                                   'imeiNo2'=>$request_params['imeiNo2'],
                                   'state'=>$request_params['state'],
                                   'city'=>$request_params['city'],
                                   'area'=>$request_params['area'],
                                   //'package'=>$request_params['package'],
                                   'planDate'=>$request_params['planDate']);
                    $this->users_model->update_productregistration($id,$array);
                    //if the insert has returned true then we show the flash message
                    //if($users["status"] == 1){
                        $this->session->set_flashdata('flash_message', 'updated');
                    //}else{
                    //    $this->session->set_flashdata('flash_message', 'not_updated');
                    //}
                }
                //else
                {
                //    $this->session->set_flashdata('flash_message', 'not_updated');
                }
                redirect('admin/registered/users/edit/'.$id.'');
                
            }//validation run
            
        }
        /*
        //if we are updating, and the data did not pass trough the validation
        //the code below wel reload the current data
        $data['roles'] = $this->users_model->get_roles();
        
        //fetch country data to populate the select field
        
        
        //user data
        $data['user'] = $this->users_model->get_user_by_id($id);
        
        $data['country'] = $this->country_model->get_country();
        
        
        $data['area'] =$this->area_model->get_area($data['user'][0]['city_id']);
        //print_r($data['servicecenter']);
        $data['city'] =$this->city_model->get_city($data['user'][0]['state_id']);
        $data['state'] =$this->state_model->get_state($data['user'][0]['country_id']);
        
        $data['parents'] = $this->users_model->get_user_by_role_id($data['user'][0]['role']);
        */
        $data['plan'] = $this->users_model->get_plan();
        $data['user'] = $this->users_model->get_registered_user($id);
        //print_r($data['user']);exit;
        //load the view
        $data['main_content'] = 'admin/users/registerededit';
        $this->load->view('includes/template', $data);            
        
    }//update
    /**
    * Update item by his id
    * @return void
    */
    public function update()
    {
        //user id 
        $id = $this->uri->segment(4);
  
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
            $this->form_validation->set_rules('first_name', 'First Name', 'required|trim');
			$this->form_validation->set_rules('last_name', 'Last Name', 'required|trim');
			//$this->form_validation->set_rules('username', 'Username', 'required|trim');                        
			$this->form_validation->set_rules('email', 'Email', 'required|trim');
            $this->form_validation->set_rules('password', 'Password', 'trim');
            $this->form_validation->set_rules('mobile', 'Mobile', 'required|trim');

            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $request_params = $_POST;
                $isexists = $this->users_model->is_phone_email_exists($request_params['mobile'],$request_params['personal_phone'],$request_params['email'],$request_params['personal_email'],$id);
                if(empty($isexists))
                {//echo "12";exit;
                    $users = $this->apicall->call("POST","user/update/".$id,$request_params);
                    //print_r($users);exit;
                    //if the insert has returned true then we show the flash message
                    if($users["status"] == 1){
                        $this->session->set_flashdata('flash_message', 'updated');
                    }else{
                        $this->session->set_flashdata('flash_message', 'not_updated');
                    }
                }
                else
                {//echo "dfa";exit;
                    $this->session->set_flashdata('flash_message', 'not_updated');
                }
                redirect('admin/users/update/'.$id.'');

            }//validation run

        }

        //if we are updating, and the data did not pass trough the validation
        //the code below wel reload the current data
$data['roles'] = $this->users_model->get_roles();
        
        //fetch country data to populate the select field
        
        
        //user data 
        $data['user'] = $this->users_model->get_user_by_id($id);
        
        $data['country'] = $this->country_model->get_country();
        
        
        $data['area'] =$this->area_model->get_area($data['user'][0]['city_id']);
        //print_r($data['servicecenter']);
        $data['city'] =$this->city_model->get_city($data['user'][0]['state_id']);
        $data['state'] =$this->state_model->get_state($data['user'][0]['country_id']);
        
        $data['parents'] = $this->users_model->get_user_by_role_id($data['user'][0]['role']);
        
        //load the view
        $data['main_content'] = 'admin/users/edit';
        $this->load->view('includes/template', $data);            

    }//update
    public function getparent()
    {
        $roleid = $this->input->get('roleid', TRUE);
        $user = $this->users_model->get_user_by_role_id($roleid);
        
        $html='<option selected="selected" value="">Select</option>';
        for($i=0;$i<count($user);$i++)
        {
            $html .= '<option value="'.$user[$i]['id'].'">'.$user[$i]['first_name'].' '.$user[$i]['last_name'].'</option>';
        }
        echo $html;exit;
    }
    /**
    * Delete user by his id
    * @return void
    */
    public function delete()
    {
        //userw id 
        $id = $this->uri->segment(4);
		$users = $this->apicall->call("GET","user/delete/".$id);
        redirect('admin/users');
    }//edit


	/**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function registered_user_list()
    {
		//pagination settings
        $data['pagingoption'] = get_paging_options();
        if($this->input->get('pagingval') != "")
            $config['per_page'] = $this->input->get('pagingval');
        else
            $config['per_page'] = $data['pagingoption'][0];
        
        
        $gets = $_GET;
        unset($gets['per_page']);
        $config['base_url'] = base_url().'admin/registered/users/page?'.http_build_query($gets);
        $config['use_page_numbers'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['num_links'] = 20;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['uri_segment'] = 4;
        $config['num_links'] = 4;

        $data['pagingval'] = $config['per_page'];
		//limit end
        //$data['page_selected'] = $page = $this->uri->segment(4);
        $data['page_selected'] = $page = $this->input->get('per_page');
		//math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 

		//all the posts sent by the view
        $search_string = $this->input->get('search_string');
		$search_from_date = $this->input->get('search_from_date');
		$search_to_date = $this->input->get('search_to_date');
        $order = $this->input->get('order');
        $order_type = $this->input->get('order_type');
		$search_in = $this->input->get('search_in');
        $selected_plan = $this->input->get('selected_plan');
        $showdate = date("Y-m-d");
        if($this->input->get('search_string')!='')
        {
            $search_string = $this->input->get('search_string');
            $search_in = $this->input->get('search_in');
            $showdate='';
        }
		//filtered && || paginated
        if(($search_string != "" && $search_in )|| $search_from_date != "" || $selected_plan != "" || $search_to_date != "" && $order !== false || $this->uri->segment(4) == true){
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
		
            if($search_from_date){
		        $filter_session_data['search_from_date_selected'] = $search_from_date;
                $data['search_from_date_selected'] = date("Y-m-d",strtotime($search_from_date));
            }else{//echo $this->session->userdata('search_from_date_selected');exit;
		        $search_from_date = $showdate;//$this->session->userdata('search_from_date_selected');
                $data['search_from_date_selected'] =$showdate;
		    }
		    
			
		    if($search_to_date){
		        $filter_session_data['search_to_date_selected'] = $search_to_date;
                $data['search_to_date_selected'] = date("Y-m-d",strtotime($search_to_date));
		    }else{
		        $search_to_date = $showdate;//$this->session->userdata('search_to_date_selected');
                $data['search_to_date_selected'] = $showdate;
		    }
            
            if($selected_plan){
                $filter_session_data['selected_plan'] = $selected_plan;
                $data['selected_plan'] = $selected_plan;
            }else{
                $filter_session_data['selected_plan'] = '';
                $data['selected_plan'] = '';
            }
		    

		    if($search_in == "all"){
		        $search_string = null;
		    }else if($search_string){
		        $filter_session_data['search_string_selected'] = $search_string;
		    }else{
				$search_string = $this->session->userdata('search_string_selected');
			}
		    $data['search_string_selected'] = $search_string;

			if($search_in == "all"){
		        $search_in = null;
		    }else if($search_in){
		        $filter_session_data['search_in_selected'] = $search_in;
		    }else{
		        $search_in = $this->session->userdata('search_in_selected');
		    }
		    $data['search_in_selected'] = $search_in;
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
			$filter_session_data["search_in_selected"]=null;
	    	$filter_session_data['search_from_date_selected'] = date("Y-m-d");
            $filter_session_data['search_to_date_selected'] = date("Y-m-d");
            $filter_session_data['order'] = null;
            $filter_session_data['order_type'] = null;

            $this->session->set_userdata($filter_session_data);

            //pre selected options
            $data['search_string_selected'] = '';
		    $data['search_from_date_selected'] = $showdate;//date("Y-m-d");
            $data['search_to_date_selected'] = $showdate;//date("Y-m-d");
            $data['order'] = 'p.id';
			$data['order_type_selected'] = 'desc';
			$data["search_in_selected"]="";
            $data['selected_plan'] = "";
		}
        $plans = $this->products_model->get_plans();
        $data['plans'] = $plans;
        
		$request_params = array("search_in"=>$search_in,"search_from_date"=>$data['search_from_date_selected'],"search_to_date"=>$data['search_to_date_selected'],"search_string"=>$data['search_string_selected'],"offset"=>$limit_end,"limit"=>$config['per_page'],"sort"=>$data['order'],"sort_dir"=>$data['order_type_selected'],"selected_plan"=>$data['selected_plan']);
		//$users = $this->apicall->call("GET","users",$request_params);
		$users = $this->users_model->get_registered_users($request_params);

		$data['count_users'] = $this->users_model->get_registered_users_count($request_params,true);
		$data['total_bill'] = $this->users_model->get_registered_users_total_bill($request_params,"bill",true);
$data['total_price'] = $this->users_model->get_registered_users_total_bill($request_params,"price",true);

		$date = new DateTime();
        $data['search_date_0'] = $request_params["search_date"] = date_format($date, 'Y-m-d');
        $data['count_users_0'] = $this->users_model->get_registered_users_count($request_params);
		$data['total_bill_0'] = $this->users_model->get_registered_users_total_bill($request_params,"bill");
$data['total_price_0'] = $this->users_model->get_registered_users_total_bill($request_params,"price");

		$date = new DateTime($request_params["search_date"]);
		$date->sub(new DateInterval('P1D'));
        $data['search_date_1'] = $request_params["search_date"] = date_format($date, 'Y-m-d');
        $data['count_users_1'] = $this->users_model->get_registered_users_count($request_params);
		$data['total_bill_1'] = $this->users_model->get_registered_users_total_bill($request_params,"bill");
$data['total_price_1'] = $this->users_model->get_registered_users_total_bill($request_params,"price");

		$date = new DateTime($request_params["search_date"]);
		$date->sub(new DateInterval('P1D'));
        $data['search_date_2'] = $request_params["search_date"] = date_format($date, 'Y-m-d');
        $data['count_users_2'] = $this->users_model->get_registered_users_count($request_params);
		$data['total_bill_2'] = $this->users_model->get_registered_users_total_bill($request_params,"bill");
$data['total_price_2'] = $this->users_model->get_registered_users_total_bill($request_params,"price");

		$date = new DateTime($request_params["search_date"]);
		$date->sub(new DateInterval('P1D'));
        $data['search_date_3'] = $request_params["search_date"] = date_format($date, 'Y-m-d');
        $data['count_users_3'] = $this->users_model->get_registered_users_count($request_params);
		$data['total_bill_3'] = $this->users_model->get_registered_users_total_bill($request_params,"bill");
$data['total_price_3'] = $this->users_model->get_registered_users_total_bill($request_params,"price");
		
        if($data['search_from_date_selected']!='')
            $data['search_from_date_selected'] = date("d-m-Y",strtotime($data['search_from_date_selected']));
        
            
        
        if($data['search_to_date_selected']!='')
            $data['search_to_date_selected'] = date("d-m-Y",strtotime($data['search_to_date_selected']));
		$data['users'] = $users;
		$config['total_rows'] = $data['count_users'];
        

        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'admin/users/registered_list';
        $this->load->view('includes/template', $data);  

    }//index
    public function registered_user_list_csv()
    {
        //pagination settings
        $data['pagingoption'] = get_paging_options();
        if($this->input->get('pagingval') != "")
            $config['per_page'] = $this->input->get('pagingval');
        else
            $config['per_page'] = $data['pagingoption'][0];
        
        
        $gets = $_GET;
        unset($gets['per_page']);
        $config['base_url'] = base_url().'admin/registered/users/page?'.http_build_query($gets);
        $config['use_page_numbers'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['num_links'] = 20;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['uri_segment'] = 4;
        $config['num_links'] = 4;
        
        $data['pagingval'] = $config['per_page'];
        //limit end
        //$data['page_selected'] = $page = $this->uri->segment(4);
        $data['page_selected'] = $page = $this->input->get('per_page');
        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        }
        
        //all the posts sent by the view
        $search_string = $this->input->get('search_string');
        $search_from_date = $this->input->get('search_from_date');
        $search_to_date = $this->input->get('search_to_date');
        $order = $this->input->get('order');
        $order_type = $this->input->get('order_type');
        $search_in = $this->input->get('search_in');
        $selected_plan = $this->input->get('selected_plan');
        $showdate = date("Y-m-d");
        if($this->input->get('search_string')!='')
        {
            $search_string = $this->input->get('search_string');
            $search_in = $this->input->get('search_in');
            $showdate='';
        }
        //filtered && || paginated
        if(($search_string != "" && $search_in )|| $search_from_date != "" || $selected_plan != "" || $search_to_date != "" && $order !== false || $this->uri->segment(4) == true){
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
            
            if($search_from_date){
                $filter_session_data['search_from_date_selected'] = $search_from_date;
                $data['search_from_date_selected'] = date("Y-m-d",strtotime($search_from_date));
            }else{//echo $this->session->userdata('search_from_date_selected');exit;
                $search_from_date = $showdate;//$this->session->userdata('search_from_date_selected');
                $data['search_from_date_selected'] =$showdate;
            }
            
            
            if($search_to_date){
                $filter_session_data['search_to_date_selected'] = $search_to_date;
                $data['search_to_date_selected'] = date("Y-m-d",strtotime($search_to_date));
            }else{
                $search_to_date = $showdate;//$this->session->userdata('search_to_date_selected');
                $data['search_to_date_selected'] = $showdate;
            }
            
            if($selected_plan){
                $filter_session_data['selected_plan'] = $selected_plan;
                $data['selected_plan'] = $selected_plan;
            }else{
                $filter_session_data['selected_plan'] = '';
                $data['selected_plan'] = '';
            }
            
            
            if($search_in == "all"){
                $search_string = null;
            }else if($search_string){
                $filter_session_data['search_string_selected'] = $search_string;
            }else{
                $search_string = $this->session->userdata('search_string_selected');
            }
            $data['search_string_selected'] = $search_string;
            
            if($search_in == "all"){
                $search_in = null;
            }else if($search_in){
                $filter_session_data['search_in_selected'] = $search_in;
            }else{
                $search_in = $this->session->userdata('search_in_selected');
            }
            $data['search_in_selected'] = $search_in;
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
            $filter_session_data["search_in_selected"]=null;
            $filter_session_data['search_from_date_selected'] = date("Y-m-d");
            $filter_session_data['search_to_date_selected'] = date("Y-m-d");
            $filter_session_data['order'] = null;
            $filter_session_data['order_type'] = null;
            
            $this->session->set_userdata($filter_session_data);
            
            //pre selected options
            $data['search_string_selected'] = '';
            $data['search_from_date_selected'] = $showdate;//date("Y-m-d");
            $data['search_to_date_selected'] = $showdate;//date("Y-m-d");
            $data['order'] = 'p.id';
            $data['order_type_selected'] = 'desc';
            $data["search_in_selected"]="";
            $data['selected_plan'] = "";
        }
        $plans = $this->products_model->get_plans();
        $data['plans'] = $plans;
        
        $request_params = array("search_in"=>$search_in,"search_from_date"=>$data['search_from_date_selected'],"search_to_date"=>$data['search_to_date_selected'],"search_string"=>$data['search_string_selected'],"offset"=>$limit_end,"limit"=>$config['per_page'],"sort"=>$data['order'],"sort_dir"=>$data['order_type_selected'],"selected_plan"=>$data['selected_plan']);
        //$users = $this->apicall->call("GET","users",$request_params);
        $users = $this->users_model->get_registered_users($request_params);
        
        $data['count_users'] = $this->users_model->get_registered_users_count($request_params,true);
        $data['total_bill'] = $this->users_model->get_registered_users_total_bill($request_params,"bill",true);
        $data['total_price'] = $this->users_model->get_registered_users_total_bill($request_params,"price",true);
        
        $date = new DateTime();
        $data['search_date_0'] = $request_params["search_date"] = date_format($date, 'Y-m-d');
        $data['count_users_0'] = $this->users_model->get_registered_users_count($request_params);
        $data['total_bill_0'] = $this->users_model->get_registered_users_total_bill($request_params,"bill");
        $data['total_price_0'] = $this->users_model->get_registered_users_total_bill($request_params,"price");
        
        $date = new DateTime($request_params["search_date"]);
        $date->sub(new DateInterval('P1D'));
        $data['search_date_1'] = $request_params["search_date"] = date_format($date, 'Y-m-d');
        $data['count_users_1'] = $this->users_model->get_registered_users_count($request_params);
        $data['total_bill_1'] = $this->users_model->get_registered_users_total_bill($request_params,"bill");
        $data['total_price_1'] = $this->users_model->get_registered_users_total_bill($request_params,"price");
        
        $date = new DateTime($request_params["search_date"]);
        $date->sub(new DateInterval('P1D'));
        $data['search_date_2'] = $request_params["search_date"] = date_format($date, 'Y-m-d');
        $data['count_users_2'] = $this->users_model->get_registered_users_count($request_params);
        $data['total_bill_2'] = $this->users_model->get_registered_users_total_bill($request_params,"bill");
        $data['total_price_2'] = $this->users_model->get_registered_users_total_bill($request_params,"price");
        
        $date = new DateTime($request_params["search_date"]);
        $date->sub(new DateInterval('P1D'));
        $data['search_date_3'] = $request_params["search_date"] = date_format($date, 'Y-m-d');
        $data['count_users_3'] = $this->users_model->get_registered_users_count($request_params);
        $data['total_bill_3'] = $this->users_model->get_registered_users_total_bill($request_params,"bill");
        $data['total_price_3'] = $this->users_model->get_registered_users_total_bill($request_params,"price");
        
        if($data['search_from_date_selected']!='')
            $data['search_from_date_selected'] = date("d-m-Y",strtotime($data['search_from_date_selected']));
        
        
        
        if($data['search_to_date_selected']!='')
            $data['search_to_date_selected'] = date("d-m-Y",strtotime($data['search_to_date_selected']));
        $data['users'] = $users;
        $config['total_rows'] = $data['count_users'];
        
        /*header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Content-Type: text/x-csv");
        header("Content-Disposition: attachment;filename=\"search_results.csv\"");
        
        
        print_r($data['users']);exit;
        */
        
        $file = fopen("Activation-CDKEY.csv","w");
        fputcsv($file,array('','CDKEY','Customer Name','Phone Number','Model No','Model Name','IMEI No','Bill No','Purchase Date','Bill Amount','Dealer Name','Customer Address','State','City','Area','Plan','IMEI No2','Plan Date'));
        $i=1;
        foreach ($data['users'] as $line)
        {
            $arr = array($i,$line['cdkey'],$line['customerName'],$line['phoneNo'],$line['modelNo'],$line['modelName'],$line['imeiNo'],$line['billNo'],$line['purchaseDate'],$line['billAmount'],$line['dealerName'],$line['customerAddress'],$line['state'],$line['city'],$line['area'],$line['package_name'],$line['imeiNo2'],$line['planDate']);
            fputcsv($file,$arr);
            $i++;
        }
        
        fclose($file);
        echo $filename = "Activation-CDKEY.csv";exit;
        
        
    }//index
    
	public function generate_pdf($id){
		if(!$id || $id == "" || $id <= 0){
			echo "Record not found";exit;
		}
		$request_params = array("search_in"=>"","search_from_date"=>"","search_to_date"=>"","search_string"=>"","offset"=>"","limit"=>null,"sort"=>"","sort_dir"=>"");
		$users = $this->users_model->get_registered_users($request_params,$id);
		if($users)
			$user = $users[0];
		else{
			echo "Record not found";exit;
		}
		//print_r($user);
		$loginId=$user['loginId'];
		$customerName=$user['customerName'];
		
		$phoneNo=$user['phoneNo'];
		$modelNo=$user['modelNo'];
		$modelName=$user['modelName'];
		$imeiNo=$user['imeiNo'];
		
		$billNo=$user['billNo'];
		$purchaseDate=$user['purchaseDate'];
		if($purchaseDate != "")
			$purchaseDate = date("Y-m-d",strtotime($purchaseDate));
		$billAmount=$user['billAmount'];
		$dealerName=$user['dealerName'];
		$state=$user['state'];
		$city=$user['city'];
		$area=$user['area'];
		
		$plan=$user['plan_name'];
		
		$customerAddress=$user['customerAddress'];
		$imeiNo2=$user['imeiNo2'];
		$package=$user['package_name'];
		
		$planDate=$user['planDate'];
		if($planDate != "")
			$planDate = date("Y-m-d",strtotime($planDate));
		
		$deviceaddress = trim($user['deviceaddress']);
		
		$package_id = $user["package"];
		$login_info["cdkey"] = $user["cdkey"];
		
		//generate PDF
		$customInfo = true;
		$planDetails = $package." - ".$plan . " Insurance at ".$user['price'];
		
		$base_dir = '/home/hasanooh/public_html/novasecurity/v2/';
		
		//echo $loginId."/".$login_info["cdkey"]."-PRODUCT-REGISTRATION-CERTIFICATE.pdf";
		///home/hasanooh/public_html/novasecurity/v2/data/187263
		require $base_dir.'pdf.php';
		
		echo "PDF Generated successfully.";exit;
	}
}
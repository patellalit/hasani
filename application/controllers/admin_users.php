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
        $config['per_page'] = 25;
        $config['base_url'] = base_url().'admin/users';
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
        $search_string = $this->input->post('search_string');  
        $order = $this->input->post('order'); 
        $order_type = $this->input->post('order_type'); 
		$search_in = $this->input->post('search_in'); 
		
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
        $config['per_page'] = 25;
        $config['base_url'] = base_url().'admin/registered/users';
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 20;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        $config['uri_segment'] = 4;
        $config['num_links'] = 4;

		//limit end
        $data['page_selected'] = $page = $this->uri->segment(4);
		//math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 

		//all the posts sent by the view
        $search_string = $this->input->post('search_string');  
		$search_from_date = $this->input->post('search_from_date');
		$search_to_date = $this->input->post('search_to_date');
        $order = $this->input->post('order'); 
        $order_type = $this->input->post('order_type'); 
		$search_in = $this->input->post('search_in');
		
		//filtered && || paginated
        if(($search_string != "" && $search_in )|| $search_from_date != "" || $search_to_date != "" && $order !== false || $this->uri->segment(4) == true){ 
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
		    }else{
		        $search_from_date = $this->session->userdata('search_from_date_selected');
		    }
		    $data['search_from_date_selected'] = date("Y-m-d",strtotime($search_from_date));
			
		    if($search_to_date){
		        $filter_session_data['search_to_date_selected'] = $search_to_date;
		    }else{
		        $search_to_date = $this->session->userdata('search_to_date_selected');
		    }
		    $data['search_to_date_selected'] = date("Y-m-d",strtotime($search_to_date));

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
		    $data['search_from_date_selected'] = date("Y-m-d");
            $data['search_to_date_selected'] = date("Y-m-d");
            $data['order'] = 'p.id';
			$data['order_type_selected'] = 'desc';
			$data["search_in_selected"]="";
		}

		$request_params = array("search_in"=>$search_in,"search_from_date"=>$data['search_from_date_selected'],"search_to_date"=>$data['search_to_date_selected'],"search_string"=>$data['search_string_selected'],"offset"=>$limit_end,"limit"=>$config['per_page'],"sort"=>$data['order'],"sort_dir"=>$data['order_type_selected']);
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
		
		$data['search_from_date_selected'] = date("d-m-Y",strtotime($data['search_from_date_selected']));
		$data['search_to_date_selected'] = date("d-m-Y",strtotime($data['search_to_date_selected']));
		$data['users'] = $users;
		$config['total_rows'] = $data['count_users'];


        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'admin/users/registered_list';
        $this->load->view('includes/template', $data);  

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
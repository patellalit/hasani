<?php
class Admin_servicecenter extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('servicecenter_model');
        $this->load->model('country_model');
        $this->load->model('city_model');
        $this->load->model('state_model');
        
		$this->load->model('area_model');
        $this->load->helper('common_helper');
		if(!$this->session->userdata('is_logged_in')){
            redirect(site_url());exit;
        }
    }
 
    /**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function index()
    {
        $data['pagingoption'] = get_paging_options();
        if($this->input->get('pagingval') != "")
            $perPage  = $this->input->get('pagingval');
        else
            $perPage = $data['pagingoption'][0];
        
        $data['perpage'] = $perPage;
        $data['pagingval'] = $perPage;
        //all the posts sent by the view
		$country_id = $this->input->get('country_id');
        $perpagePost = $this->input->get('perpage');
		if($perpagePost != '')
		{
			$perPage = $perpagePost;
		}
		$data['perpage'] = $perPage;
		$currentpagePost = $this->input->get('currentpage');
		
        $search = $this->input->get('search_string');
        if($search != '')
        {
            $data['search_string_selected']=$search;
        }
        else
        {
            $data['search_string_selected']='';
        }
		
		       
        $order = $this->input->get('order'); 
		if($order == '')
			$order="id";
        $order_type = $this->input->get('order_type'); 

        //pagination settings
        $config['per_page'] = $perPage;
        $gets = $_GET;
        unset($gets['per_page']);
        $config['base_url'] = base_url().'admin/servicecenter/page?'.http_build_query($gets);
        $config['use_page_numbers'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['num_links'] = 20;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        //limit end
        $page = $this->input->get('per_page');
		if($currentpagePost != '')
		{
			$page = $currentpagePost;
		}
		
        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
			$page=1;
        } 
		
		$data['currentpage'] = $page;
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
		$order='id';
        
		$filter_session_data['order'] = null;
		$filter_session_data['order_type'] = null;
		$this->session->set_userdata($filter_session_data);
		
		if($country_id !== false){ 
            if($country_id !== 0){
                $filter_session_data['country_selected'] = $country_id;
            }else{
                $country_id = $this->session->userdata('country_selected');
            }
            $data['country_selected'] = $country_id;
			
			if($search){
                $filter_session_data['search_string_selected'] = $search;
            }else{
                $search_string = $this->session->userdata('search_string_selected');
            }
            $data['search_string_selected'] = $search;

            if($order){
                $filter_session_data['order'] = $order;
            }
            else{
                $order = $this->session->userdata('order');
            }
            $data['order'] = $order;
			
			$data['count_servicecenter']= $this->servicecenter_model->count_servicecenter($country_id,$search);
			$data['servicecenter'] = $this->servicecenter_model->get_servicecenter($country_id,$search, '', $order_type, $config['per_page'],$limit_end);
		}
		else
		{
			$data['country_selected'] = $country_id;
			//pre selected options
			$data['search_string_selected'] = '';
			$data['order'] = 'id';
			
			$data['count_servicecenter']= $this->servicecenter_model->count_servicecenter('',$search);
			$data['servicecenter'] = $this->servicecenter_model->get_servicecenter('',$search, '', $order_type, $config['per_page'],$limit_end);
		}
		      
		$config['total_rows'] = $data['count_servicecenter'];
		//fetch manufacturers data into arrays
        $data['area'] = $this->area_model->get_area();
        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'admin/servicecenter/list';
        $this->load->view('includes/template', $data);  

    }//index
    public function center_csv()
    {
        $data['pagingoption'] = get_paging_options();
        if($this->input->get('pagingval') != "")
            $perPage  = $this->input->get('pagingval');
        else
            $perPage = $data['pagingoption'][0];
        
        $data['perpage'] = $perPage;
        $data['pagingval'] = $perPage;
        //all the posts sent by the view
        $country_id = $this->input->get('country_id');
        $perpagePost = $this->input->get('perpage');
        if($perpagePost != '')
        {
            $perPage = $perpagePost;
        }
        $data['perpage'] = $perPage;
        $currentpagePost = $this->input->get('currentpage');
        
        $search = $this->input->get('search_string');
        if($search != '')
        {
            $data['search_string_selected']=$search;
        }
        else
        {
            $data['search_string_selected']='';
        }
        
        
        $order = $this->input->get('order');
        if($order == '')
            $order="id";
        $order_type = $this->input->get('order_type');
        
        //pagination settings
        $config['per_page'] = $perPage;
        $gets = $_GET;
        unset($gets['per_page']);
        $config['base_url'] = base_url().'admin/servicecenter/page?'.http_build_query($gets);
        $config['use_page_numbers'] = TRUE;
        $config['page_query_string'] = TRUE;
        $config['num_links'] = 20;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';
        
        //limit end
        $page = $this->input->get('per_page');
        if($currentpagePost != '')
        {
            $page = $currentpagePost;
        }
        
        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
            $page=1;
        }
        
        $data['currentpage'] = $page;
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
        $order='id';
        
        $filter_session_data['order'] = null;
        $filter_session_data['order_type'] = null;
        $this->session->set_userdata($filter_session_data);
        
        if($country_id !== false){
            if($country_id !== 0){
                $filter_session_data['country_selected'] = $country_id;
            }else{
                $country_id = $this->session->userdata('country_selected');
            }
            $data['country_selected'] = $country_id;
            
            if($search){
                $filter_session_data['search_string_selected'] = $search;
            }else{
                $search_string = $this->session->userdata('search_string_selected');
            }
            $data['search_string_selected'] = $search;
            
            if($order){
                $filter_session_data['order'] = $order;
            }
            else{
                $order = $this->session->userdata('order');
            }
            $data['order'] = $order;
            
            $data['count_servicecenter']= $this->servicecenter_model->count_servicecenter($country_id,$search);
            $data['servicecenter'] = $this->servicecenter_model->get_servicecenter($country_id,$search, '', $order_type, $config['per_page'],$limit_end);
        }
        else
        {
            $data['country_selected'] = $country_id;
            //pre selected options
            $data['search_string_selected'] = '';
            $data['order'] = 'id';
            
            $data['count_servicecenter']= $this->servicecenter_model->count_servicecenter('',$search);
            $data['servicecenter'] = $this->servicecenter_model->get_servicecenter('',$search, '', $order_type, $config['per_page'],$limit_end);
        }
		      
        $config['total_rows'] = $data['count_servicecenter'];
        //fetch manufacturers data into arrays
        $data['area'] = $this->area_model->get_area();
        
        $file = fopen("servicecenter.csv","w");
        fputcsv($file,array('','Area','Service center','Service center address','Zipcode','Contact','Email'));
        
        foreach ($data['servicecenter'] as $line)
        {
            $arr = array($line['id'],$line['area_name'],$line['name'],$line['address'],$line['zipCode'],$line['contactNo'],$line['emailId']);
            fputcsv($file,$arr);
            
        }
        
        fclose($file);
        echo $filename = "servicecenter.csv";exit;
        //initializate the panination helper 
        //$this->pagination->initialize($config);
        
        //load the view
        //$data['main_content'] = 'admin/servicecenter/list';
        //$this->load->view('includes/template', $data);
        
    }//index
    public function fetchState()
    {
        $countryid = $this->input->get('countryid', TRUE);
        //print_r($_REQUEST);
        //echo $_REQUEST['jilloid'];exit;
        if($countryid != "")
            $data = $this->state_model->get_state($countryid);
        else
            $data = array();
        
        $html='<option selected="selected" value="">Select</option>';
        for($i=0;$i<count($data);$i++)
        {
            $html .= '<option value="'.$data[$i]['id'].'">'.$data[$i]['name'].'</option>';
        }
        echo $html;exit;
        //print_r($data);exit;
    }
    public function fetchCity()
    {
        $stateid = $this->input->get('stateid', TRUE);
        //print_r($_REQUEST);
        //echo $_REQUEST['jilloid'];exit;
        if($stateid != "")
            $data = $this->city_model->get_city($stateid);
        else
            $data = array();
        
        $html='<option selected="selected" value="">Select</option>';
        for($i=0;$i<count($data);$i++)
        {
            $html .= '<option value="'.$data[$i]['id'].'">'.$data[$i]['name'].'</option>';
        }
        echo $html;exit;
        //print_r($data);exit;
    }
    
	public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
		    //form validation
			$this->form_validation->set_rules('area_id', 'area_id', 'required');
            $this->form_validation->set_rules('servicecenter_name', 'servicecenter_name', 'required');
            $this->form_validation->set_rules('servicecenter_address', 'servicecenter_address', 'required');
            $this->form_validation->set_rules('zipcode', 'zipcode', 'required');
            $this->form_validation->set_rules('email', 'email', 'required');
            $this->form_validation->set_rules('contactNo', 'contactNo', 'required');
			
			
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                if(!$this->servicecenter_model->is_servicecenter_exists($this->input->post('servicecenter_name'),$this->input->post('area_id'),0))
                {
                    $data_to_store = array(
                        'area' => $this->input->post('area_id'),
                        'name' => $this->input->post('servicecenter_name'),
                        'address' => $this->input->post('servicecenter_address'),
                        'zipCode' => $this->input->post('zipcode'),
                        'emailId' => $this->input->post('email'),
                        'contactNo' => $this->input->post('contactNo'),
                    );
                    $servicecenterid=$this->servicecenter_model->store_servicecenter($data_to_store);
                }
                else
                    $servicecenterid=0;
                    
                //if the insert has returned true then we show the flash message
                if($servicecenterid){
					$data['flash_message'] = TRUE;
                }else{
                    $data['flash_message'] = FALSE; 
                }

            }

        }
		//fetch country data to populate the select field
        $data['country'] = $this->country_model->get_country();
        $data['state'] = array();
        $data['city'] = array();
        $data['area'] = array();
		//load the view
        $data['main_content'] = 'admin/servicecenter/add';
        $this->load->view('includes/template', $data);  //echo "lsdfhads";exit;
    }       

    /**
    * Update item by his id
    * @return void
    */
    public function update()
    {
        //aanganvadi id 
        $id = $this->uri->segment(4);
  
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
            $this->form_validation->set_rules('area_id', 'area_id', 'required');
            $this->form_validation->set_rules('servicecenter_name', 'servicecenter_name', 'required');
            $this->form_validation->set_rules('servicecenter_address', 'servicecenter_address', 'required');
            $this->form_validation->set_rules('zipcode', 'zipcode', 'required');
            $this->form_validation->set_rules('email', 'email', 'required');
            $this->form_validation->set_rules('contactNo', 'contactNo', 'required');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
    
                $data_to_store = array(
                                       'area' => $this->input->post('area_id'),
                                       'name' => $this->input->post('servicecenter_name'),
                                       'address' => $this->input->post('servicecenter_address'),
                                       'zipCode' => $this->input->post('zipcode'),
                                       'emailId' => $this->input->post('email'),
                                       'contactNo' => $this->input->post('contactNo'),
                );
                //if the insert has returned true then we show the flash message
                if($this->servicecenter_model->update_servicecenter($id, $data_to_store) == TRUE){
					$this->session->set_flashdata('flash_message', 'updated');
                }else{
                    $this->session->set_flashdata('flash_message', 'not_updated');
                }
                redirect('admin/servicecenter/update/'.$id.'');

            }//validation run

        }
		//fetch country data to populate the select field
        $data['country'] = $this->country_model->get_country();
        //aanganvadi data 
        $data['servicecenter'] = $this->servicecenter_model->get_servicecenter_by_id($id);
        $data['area'] =$this->area_model->get_area($data['servicecenter'][0]['city_id']);
        //print_r($data['servicecenter']);
        $data['city'] =$this->city_model->get_city($data['servicecenter'][0]['state_id']);
        $data['state'] =$this->state_model->get_state($data['servicecenter'][0]['country_id']);
        //load the view
        $data['main_content'] = 'admin/servicecenter/edit';
        $this->load->view('includes/template', $data);            

    }//update

    /**
    * Delete aanganvadi by his id
    * @return void
    */
    public function delete()
    {
        //aanganvadi id 
        $id = $this->uri->segment(4);
        $this->servicecenter_model->delete_servicecenter($id);
		$data_to_send = "id=".$id;
		redirect('admin/servicecenter');
    }//edit

}
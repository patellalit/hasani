<?php
class Admin_city extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('city_model');        
		$this->load->model('country_model');        
		$this->load->model('state_model');
        $this->load->helper('common_helper');//
		if(!$this->session->userdata('is_logged_in')){
            redirect(site_url());exit;
        }
    }
	public function remainingcitym()
    {
		$ourvillages = $this->city_model->get_all_city_id();
		$str='';
		for($i=0;$i<count($ourvillages);$i++)
		{
			if($str!='')
				$str.=',';
				
			$str.=$ourvillages[$i]['id'];
		}
		echo $str;
		//print_r($ourvillages);exit;
	}
    /**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function index()
    {
		//$perPage = 20;
        $data['pagingoption'] = get_paging_options();
        if($this->input->get('pagingval') != "")
            $perPage  = $this->input->get('pagingval');
        else
            $perPage = $data['pagingoption'][0];
		$data['perpage'] = $perPage;
        $data['pagingval'] = $perPage;
        //all the posts sent by the view
		$country_id = $this->input->get('country_id');
		$stateId = $this->input->get('stateId');
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
			$data['search']=$search;
		}
		else
		{
			$data['search']='';
		}
		
		       
        $order = $this->input->get('order'); 
		if($order == '')
			$order="id";
        $order_type = $this->input->get('order_type'); 

        //pagination settings
        $config['per_page'] = $perPage;
        $gets = $_GET;
        unset($gets['per_page']);
        $config['base_url'] = base_url().'admin/city/page?'.http_build_query($gets);
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
		
		if($country_id !== false && $stateId !== false){ 
            if($country_id !== 0){
                $filter_session_data['country_selected'] = $country_id;
            }else{
                $country_id = $this->session->userdata('country_selected');
            }
            $data['country_selected'] = $country_id;
			
			if($stateId !== 0){
                $filter_session_data['state_selected'] = $stateId;
            }else{
                $stateId = $this->session->userdata('state_selected');
            }
            $data['state_selected'] = $stateId;
			
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
			
			$data['count_city']= $this->city_model->count_city($stateId,$search);
			$data['city'] = $this->city_model->get_city($stateId,$search, '', $order_type, $config['per_page'],$limit_end);  
			
			//fetch manufacturers data into arrays
			$data['country'] = $this->country_model->get_country();
			
			//fetch manufacturers data into arrays
			$data['state'] = $this->state_model->get_state($country_id);
		}
		else
		{
			$data['country_selected'] = $country_id;
			$data['state_selected'] = $country_id;
			//pre selected options
			$data['search_string_selected'] = '';
			$data['order'] = 'id';
			
			$data['count_city']= $this->city_model->count_city('',$search);
			$data['city'] = $this->city_model->get_city('',$search, '', $order_type, $config['per_page'],$limit_end);  
			
			//fetch manufacturers data into arrays
			$data['country'] = $this->country_model->get_country();
			
			//fetch manufacturers data into arrays
			$data['state'] = array();
		}
		      
		$config['total_rows'] = $data['count_city'];
		
		
        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'admin/city/list';
        $this->load->view('includes/template', $data);  

    }//index
	public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
		    //form validation
			$this->form_validation->set_rules('state_id', 'state_id', 'required');
            $this->form_validation->set_rules('city_name', 'city_name', 'required');
			
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                if(!$this->city_model->is_city_exists($this->input->post('city_name'),$this->input->post('stateId'),0))
                {
                    $data_to_store = array(
                        'stateId' => $this->input->post('state_id'),
                        'name' => $this->input->post('city_name'),
                    );
                    //if the insert has returned true then we show the flash message
                    $cityid = $this->city_model->store_city($data_to_store);
                }
                else
                    $cityid=0;
                
                if($cityid){
					$data['flash_message'] = TRUE;
                }else{
                    $data['flash_message'] = FALSE; 
                }

            }

        }
		//fetch country data to populate the select field
        $data['country'] = $this->country_model->get_country();
		
		$data['state'] = array();
		//load the view
        $data['main_content'] = 'admin/city/add';
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
			$this->form_validation->set_rules('country_id', 'country_id', 'required');
            $this->form_validation->set_rules('city_name', 'city_name', 'required');
			$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
    
                $data_to_store = array(
					'stateId' => $this->input->post('state_id'),
                    'name' => $this->input->post('city_name'),
                );
                //if the insert has returned true then we show the flash message
                if($this->city_model->update_city($id, $data_to_store) == TRUE){
					$this->session->set_flashdata('flash_message', 'updated');
                }else{
                    $this->session->set_flashdata('flash_message', 'not_updated');
                }
                redirect('admin/city/update/'.$id.'');

            }//validation run

        }
		$data['city'] = $this->city_model->get_city_by_id($id);
		//fetch country data to populate the select field
        $data['country'] = $this->country_model->get_country();
		
//print_r($data['city']);
		$data['state'] =$this->state_model->get_state($data['city'][0]['country_id']);
        //aanganvadi data 
        
        //load the view
        $data['main_content'] = 'admin/city/edit';
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
        $this->city_model->delete_city($id);
		$data_to_send = "id=".$id;
		redirect('admin/city');
    }//edit

}
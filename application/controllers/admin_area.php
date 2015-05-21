<?php
class Admin_area extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('area_model');
		$this->load->model('country_model');        
		$this->load->model('state_model');
        $this->load->model('city_model');
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
		//$perPage = 20;
        $data['pagingoption'] = get_paging_options();
        if($this->input->get('pagingval') != "")
            $perPage  = $this->input->get('pagingval');
        else
            $perPage = $data['pagingoption'][0];
        
		$data['perpage'] = $perPage;
        //all the posts sent by the view
		$country_id = $this->input->get('country_id');
		$stateId = $this->input->get('state_id');
        $city_id = $this->input->get('city_id');
        $perpagePost = $this->input->get('perpage');
		if($perpagePost != '')
		{
			$perPage = $perpagePost;
		}
		$data['perpage'] = $perPage;
        $data['pagingval'] = $perPage;
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
        $config['base_url'] = base_url().'admin/area/page?'.http_build_query($_GET);
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
        $page = $this->uri->segment(3);
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
            
            if($city_id !== 0){
                $filter_session_data['city_selected'] = $city_id;
            }else{
                $city_id = $this->session->userdata('city_selected');
            }
            $data['city_selected'] = $city_id;
			
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
			
			$data['count_area']= $this->area_model->count_area($city_id,$search);
			$data['area'] = $this->area_model->get_area($city_id,$search, '', $order_type, $config['per_page'],$limit_end);
			
			//fetch manufacturers data into arrays
			$data['country'] = $this->country_model->get_country();
			
			//fetch manufacturers data into arrays
			$data['state'] = $this->state_model->get_state($country_id);
            
            //fetch manufacturers data into arrays
            $data['city'] = $this->city_model->get_state($city_id);
		}
		else
		{
			$data['country_selected'] = $country_id;
			$data['state_selected'] = $stateId;
            $data['city_selected'] = $city_id;
			//pre selected options
			$data['search_string_selected'] = '';
			$data['order'] = 'id';
			
			$data['count_area']= $this->area_model->count_area('',$search);
			$data['area'] = $this->area_model->get_area('',$search, '', $order_type, $config['per_page'],$limit_end);
			
			//fetch manufacturers data into arrays
			$data['country'] = $this->country_model->get_country();
			
			//fetch manufacturers data into arrays
			$data['state'] = array();
            
            //fetch manufacturers data into arrays
            $data['city'] = array();
		}
		      
		$config['total_rows'] = $data['count_area'];
		
		
        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'admin/area/list';
        $this->load->view('includes/template', $data);  

    }//index
	public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
		    //form validation
			$this->form_validation->set_rules('city_id', 'city_id', 'required');
            $this->form_validation->set_rules('area_name', 'area_name', 'required');
			
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                if(!$this->city_model->is_city_exists($this->input->post('city_name'),$this->input->post('stateId'),0))
                {
                    $data_to_store = array(
                        'city_id' => $this->input->post('city_id'),
                        'area_name' => $this->input->post('area_name'),
                    );
                    //if the insert has returned true then we show the flash message
                    $areaid = $this->area_model->store_area($data_to_store);
                }
                else
                    $areaid=0;
                
                if($areaid){
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
		//load the view
        $data['main_content'] = 'admin/area/add';
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
            $this->form_validation->set_rules('city_id', 'city_id', 'required');
            $this->form_validation->set_rules('area_name', 'area_name', 'required');
			$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
    
                $data_to_store = array(
                                       'city_id' => $this->input->post('city_id'),
                                       'area_name' => $this->input->post('area_name'),
                );
                //if the insert has returned true then we show the flash message
                if($this->area_model->update_area($id, $data_to_store) == TRUE){
					$this->session->set_flashdata('flash_message', 'updated');
                }else{
                    $this->session->set_flashdata('flash_message', 'not_updated');
                }
                redirect('admin/area/update/'.$id.'');

            }//validation run

        }
		$data['area'] = $this->area_model->get_area_by_id($id);
        //echo "<pre>";print_r($data['area']);echo"</pre>";
		//fetch country data to populate the select field
        $data['country'] = $this->country_model->get_country();
		
//print_r($data['city']);
        $data['city'] =$this->city_model->get_city($data['area'][0]['state_id']);
		$data['state'] =$this->state_model->get_state($data['area'][0]['country_id']);
        
        //aanganvadi data 
        
        //load the view
        $data['main_content'] = 'admin/area/edit';
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
        $this->area_model->delete_area($id);
		$data_to_send = "id=".$id;
		redirect('admin/area');
    }//edit

}
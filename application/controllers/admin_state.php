<?php
class Admin_state extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('state_model');        
		$this->load->model('country_model');
        $this->load->model('city_model');
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
		$perPage = 20;
		$data['perpage'] = $perPage;
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
        $config['base_url'] = base_url().'admin/state/page?'.http_build_query($_GET);
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
			
			$data['count_state']= $this->state_model->count_state($country_id,$search);
			$data['state'] = $this->state_model->get_state($country_id,$search, '', $order_type, $config['per_page'],$limit_end);  
		}
		else
		{
			$data['country_selected'] = $country_id;
			//pre selected options
			$data['search_string_selected'] = '';
			$data['order'] = 'id';
			
			$data['count_state']= $this->state_model->count_state('',$search);
			$data['state'] = $this->state_model->get_state('',$search, '', $order_type, $config['per_page'],$limit_end);  
		}
		      
		$config['total_rows'] = $data['count_state'];
		//fetch manufacturers data into arrays
        $data['country'] = $this->country_model->get_country();
        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'admin/state/list';
        $this->load->view('includes/template', $data);  

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
    /*public function fetchCity()
    {
        //echo $_REQUEST['jilloid'];exit;
        if($_REQUEST['stateid'] != "")
            $data = $this->city_model->get_city($_REQUEST['stateid']);
        else
            $data = array();
        
        $html='<option selected="selected" value="">Select</option>';
        for($i=0;$i<count($data);$i++)
        {
            $html .= '<option value="'.$data[$i]['id'].'">'.$data[$i]['name'].'</option>';
        }
        echo $html;exit;
        //print_r($data);exit;
    }*/
	public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
		    //form validation
			$this->form_validation->set_rules('country_id', 'country_id', 'required');
            $this->form_validation->set_rules('state_name', 'state_name', 'required');
			
			
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                if(!$this->state_model->is_state_exists($this->input->post('state_name'),$this->input->post('country_id'),0))
                {
                    $data_to_store = array(
                        'country_id' => $this->input->post('country_id'),
                        'name' => $this->input->post('state_name'),
                    );
                    $stateid=$this->state_model->store_state($data_to_store);
                }
                else
                    $stateid=0;
                    
                //if the insert has returned true then we show the flash message
                if($stateid){
					$data['flash_message'] = TRUE;
                }else{
                    $data['flash_message'] = FALSE; 
                }

            }

        }
		//fetch country data to populate the select field
        $data['country'] = $this->country_model->get_country();
		//load the view
        $data['main_content'] = 'admin/state/add';
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
            $this->form_validation->set_rules('state_name', 'state_name', 'required');
			$this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
    
                $data_to_store = array(
					'country_id' => $this->input->post('country_id'),
                    'name' => $this->input->post('state_name'),
                );
                //if the insert has returned true then we show the flash message
                if($this->state_model->update_state($id, $data_to_store) == TRUE){
					$this->session->set_flashdata('flash_message', 'updated');
                }else{
                    $this->session->set_flashdata('flash_message', 'not_updated');
                }
                redirect('admin/state/update/'.$id.'');

            }//validation run

        }
		//fetch country data to populate the select field
        $data['country'] = $this->country_model->get_country();
        //aanganvadi data 
        $data['state'] = $this->state_model->get_state_by_id($id);
        //load the view
        $data['main_content'] = 'admin/state/edit';
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
        $this->state_model->delete_state($id);
		$data_to_send = "id=".$id;
		redirect('admin/state');
    }//edit

}
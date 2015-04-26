<?php
class Admin_dashboard extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('products_model');
		$this->load->model('country_model');        
		$this->load->model('state_model'); 
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
		$perPage = 20;
		$data['perpage'] = $perPage;
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
        $config['base_url'] = base_url().'admin/city/page?'.http_build_query($_GET);
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
		
		
        $plans = $this->products_model->get_all_plans();
        $data['plans'] = $plans;
			
        $allstates = $this->products_model->get_all_state();
        for($i=0;$i<count($allstates);$i++)
        {
            for($j=0;$j<count($plans);$j++)
            {
                $allstates[$i]['allplans'][$plans[$j]['id']] = $this->products_model->getplan_count($allstates[$i]['state'],$plans[$j]['id'],'state');
            }
            //get all cities start
            $allstates[$i]['allcities'] = $this->products_model->get_all_cities($allstates[$i]['state']);
            for($k=0;$k<count($allstates[$i]['allcities']);$k++)
            {
                for($j=0;$j<count($plans);$j++)
                {
                    $allstates[$i]['allcities'][$k]['allplans'][$plans[$j]['id']] = $this->products_model->getplan_city_count($allstates[$i]['allcities'][$k]['city'],$allstates[$i]['state'],$plans[$j]['id'],'city');
                }
                //get all area start
                $allstates[$i]['allcities'][$k]['allareas'] = $this->products_model->get_all_area($allstates[$i]['state'],$allstates[$i]['allcities'][$k]['city']);//print_r($allstates[$i]['allcities'][$k]['allareas']);exit;
                for($l=0;$l<count($allstates[$i]['allcities'][$k]['allareas']);$l++)
                {
                    for($j=0;$j<count($plans);$j++)
                    {
                        $allstates[$i]['allcities'][$k]['allareas'][$l]['allplans'][$plans[$j]['id']] = $this->products_model->getplan_area_count($allstates[$i]['allcities'][$k]['allareas'][$l]['dealerName'],$allstates[$i]['allcities'][$k]['city'],$allstates[$i]['state'],$plans[$j]['id'],'dealerName');
                    }
                }
            }
            
        }
        $data['allstates'] = $allstates;
        //echo "<pre>";
        //print_r($data['allstates']);exit;
        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'admin/dashboard/list';
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
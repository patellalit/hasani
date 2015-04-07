<?php
class Admin_location extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('location_model');
		$this->load->model('customers_model');
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
		//$country_id = $this->input->get('country_id');
		//$stateId = $this->input->get('state_id');
        //$city_id = $this->input->get('city_id');
        $perpagePost = $this->input->get('perpage');
		if($perpagePost != '')
		{
			$perPage = $perpagePost;
		}
		$data['perpage'] = $perPage;
		$currentpagePost = $this->input->get('per_page');
		
        $search = $this->input->get('search_string');
		if($search != '')
		{			
			$data['search']=$search;
		}
		else
		{
			$data['search']='';
		}
        
        $searchin = $this->input->get('search_in');
        if($searchin != '')
        {
            $data['searchin']=$searchin;
        }
        else
        {
            $data['searchin']='';
        }
        
        $searchstatus = $this->input->get('status_in');
        if($searchstatus != '')
        {
            $data['searchstatus']=$searchstatus;
        }
        else
        {
            $data['searchstatus']='';
        }
        
        $date = $this->input->get('date_start');
        if($date != '')
        {
            $date = $data['date_start']=date('Y-m-d',strtotime($date));
        }
        else
        {
            $date = date('Y-m-d');
            //$date = '2015-03-01';
            $data['date_start']=$date;
        }
        $date_end = $this->input->get('date_end');
        if($date_end != '')
        {
            $date_end = $data['date_end']=date('Y-m-d',strtotime($date_end));
        }
        else
        {
            $data['date_end']='';
        }
        
        $order = $this->input->get('order');
		if($order == '')
			$order="lt.id";
        
        $order_type = $this->input->get('order_type'); 

        //pagination settings
        $config['per_page'] = $perPage;
        $config['base_url'] = base_url().'admin/location/page?'.http_build_query($_GET);
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
                $order_type = 'DESC';
            }
        }
        //make the data type var avaible to our view
        $data['order_type_selected'] = $order_type;        
		
        
		$filter_session_data['order'] = null;
		$filter_session_data['order_type'] = null;
		$this->session->set_userdata($filter_session_data);
		
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
        
        $data['count_location']= $this->location_model->count_location($date,$date_end,$search,$searchin);
        $data['location'] = $this->location_model->get_location($date,$date_end,$search,$searchin, $order, $order_type, $config['per_page'],$limit_end);
        //echo "<pre>";print_r($data['dsr']);exit;
		$config['total_rows'] = $data['count_location'];
		
		
        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'admin/location/list';
        $this->load->view('includes/template', $data);
        /*echo "<pre>";
        print_r($data['claim']);
        echo "</pre>";*/

    }//index
    
    public function view()
    {
        $id = $this->uri->segment(4);
        $data['dsr'] = $this->dsr_model->get_dsr_products($id);
        //echo "<pre>";print_r($data['dsr'] );exit;
        //$data['main_content'] = 'admin/claim/view';
        $html = $this->load->view('admin/dsr/view', $data,true);
        echo $html;exit;
        //print_r($data['claim']);
    }

}
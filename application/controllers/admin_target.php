<?php
class Admin_target extends CI_Controller {
 
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
        $this->load->model('target_model');
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
		//$country_id = $this->input->get('country_id');
		//$stateId = $this->input->get('state_id');
        //$city_id = $this->input->get('city_id');
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
			$order="t.id";
        
        $order_type = $this->input->get('order_type'); 

        //pagination settings
        $config['per_page'] = $perPage;
        $gets = $_GET;
        unset($gets['per_page']);
        $config['base_url'] = base_url().'admin/target/page?'.http_build_query($gets);
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
        
        $data['count_target']= $this->target_model->count_target($date,$date_end,$search,$searchin);
        $data['target'] = $this->target_model->get_target($date,$date_end,$search,$searchin, $order, $order_type, $config['per_page'],$limit_end);
			
		$config['total_rows'] = $data['count_target'];
		
		
        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'admin/target/list';
        $this->load->view('includes/template', $data);
        /*echo "<pre>";
        print_r($data['claim']);
        echo "</pre>";*/

    }//index
}
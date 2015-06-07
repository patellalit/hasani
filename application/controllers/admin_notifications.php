<?php
class Admin_notifications extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('notification_model');
        $this->load->helper('common_helper');
		//$this->load->model('customers_model');
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
        $perpagePost = $this->input->get('pagingval');
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
			$order="nu.notification_id";
        
        $order_type = $this->input->get('order_type'); 

        //pagination settings
        $config['per_page'] = $perPage;
        $gets = $_GET;
        unset($gets['per_page']);
        $config['base_url'] = base_url().'admin/notifications/page?'.http_build_query($gets);
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
        if($page =='')
        {
            $page=1;
        }//echo $page;
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
        
        $data['count_notifications']= $this->notification_model->count_notifications($date,$date_end,$search,$searchin);
        $data['notificatins'] = $this->notification_model->get_notifications($date,$date_end,$search,$searchin, $order, $order_type, $limit_end,$config['per_page']);
        //echo "<pre>";print_r($data['dsr']);exit;
		$config['total_rows'] = $data['count_notifications'];
		
		
        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'admin/notifications/list';
        $this->load->view('includes/template', $data);
        /*echo "<pre>";
        print_r($data['claim']);
        echo "</pre>";*/

    }//index
    public function notification_csv()
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
        $perpagePost = $this->input->get('pagingval');
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
            $order="nu.notification_id";
        
        $order_type = $this->input->get('order_type');
        
        //pagination settings
        $config['per_page'] = $perPage;
        $gets = $_GET;
        unset($gets['per_page']);
        $config['base_url'] = base_url().'admin/notifications/page?'.http_build_query($gets);
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
        if($page =='')
        {
            $page=1;
        }//echo $page;
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
        
        $data['count_notifications']= $this->notification_model->count_notifications($date,$date_end,$search,$searchin);
        $data['notificatins'] = $this->notification_model->get_notifications($date,$date_end,$search,$searchin, $order, $order_type, $limit_end,$config['per_page']);
        //echo "<pre>";print_r($data['dsr']);exit;
        $config['total_rows'] = $data['count_notifications'];
        
        $file = fopen("notification.csv","w");
        fputcsv($file,array('Id','From user','To user','Message','Date'));
        
        foreach ($data['notificatins'] as $line)
        {
            if($line['message_datetime']!='')
                $date = date('d/m/Y h:i A',strtotime($line['message_datetime']));
            else
                $date='';
            $arr = array($line['notification_id'],$line['from_user_first_name'].' '.$line['from_user_last_name'],$line['to_user_first_name'].' '.$line['to_user_last_name'],$line['message'],$date);
            fputcsv($file,$arr);
            
        }
        
        fclose($file);
        echo $filename = "notification.csv";exit;
        
        //initializate the panination helper
        //$this->pagination->initialize($config);
        
        //load the view
        //$data['main_content'] = 'admin/notifications/list';
        //$this->load->view('includes/template', $data);
        /*echo "<pre>";
         print_r($data['claim']);
         echo "</pre>";*/
        
    }//index
    public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
            $this->form_validation->set_rules('message', 'message', 'required');
            
            
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $get_all_ids = $this->notification_model->get_all_member($this->input->post('roles'),$this->input->post('states'));
                $ids = array();
                foreach($get_all_ids as $id)
                {
                    $ids[] = $id['id'];
                }
                //print_r($get_all_ids);
                $cur_user = $this->session->all_userdata();
                $data_to_store = array(
                                       'message' => $this->input->post('message'),
                                       'user_id' => $cur_user['login_user']['id'],
                                       'created_at' => date('Y-m-d H:i:s'),
                                       );
                $stateid=$this->notification_model->add_notification_api($data_to_store,$ids);
                
                //if the insert has returned true then we show the flash message
                if($stateid){
                    $data['flash_message'] = TRUE;
                }else{
                    $data['flash_message'] = FALSE;
                }
                
            }
            
            
        }
        $data['roles'] = $this->notification_model->get_all_role();
        $data['states'] = $this->notification_model->get_all_state();
        //load the view
        $data['main_content'] = 'admin/notifications/add';
        $this->load->view('includes/template', $data);  //echo "lsdfhads";exit;
    }
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
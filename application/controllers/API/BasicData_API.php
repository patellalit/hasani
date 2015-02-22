<?php
class BasicData_API extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('region_model');
		$this->load->model('firm_model');
		$this->load->model('products_model');


		$user_id = (int)$this->input->get('user_id');
		if($user_id <= 0){
			$data = array();
			$data['status'] = 1;
			$data['message'] = "You are not authorised user.";
			$this->json_response($data);
		}
    }

	function json_response($data){
		header('Content-Type: application/json; charset=UTF8');
		echo trim(json_encode($data));exit;
	}

	/**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function city_state_list(){
		
		$data = array();
		$data['status'] = 1;
		$citystates = $this->region_model->get_city_state();
		$response = array();
		$states_id = array();
		$states = array();
		$cities = array();
		foreach($citystates as $citystate){
			if(!in_array($citystate["state_id"],$states_id)){
				if(!empty($states_id)){
					$states = array_merge($states,array("cities"=>$cities));
					$response[] = $states;
					$cities = array();
					$states = array();
				}
				$states_id[] = $citystate["state_id"];
				$states = array("state_id"=>$citystate["state_id"],"state_name"=>$citystate["state_name"]);
			}
			$cities[] = array("city_id"=>$citystate["city_id"],"city_name"=>$citystate["city_name"]);
		}
		
		$states = array_merge($states,array("cities"=>$cities));
		$response[] = $states;

		$data['data']['region'] = $response;
		$this->json_response($data);
	}
	
	/**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function firm_list(){
		$data = array();
		$data['status'] = 1;
		
		$firms = $this->firm_model->get_firm_api();
		
		$data['data']['firms'] = $firms;
		$this->json_response($data);
	}

    	/**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function package_plan_list(){
		$data = array();
		$data['status'] = 1;
		
		$products = $this->products_model->get_planss_with_package_api();
		
		$response = array();
		$packages_id = array();
		$packages = array();
		$plans = array();
		foreach($products as $product){
			if(!in_array($product["package_id"],$packages_id)){
				if(!empty($packages_id)){
					$packages = array_merge($packages,array("plans"=>$plans));
					$response[] = $packages;
					$plans = array();
					$packages = array();
				}
				$packages_id[] = $product["package_id"];
				$packages = array("package_id"=>$product["package_id"],"package_name"=>$product["package_name"]);
			}
			$plans[] = array("plan_id"=>$product["plan_id"],"plan_name"=>$product["plan_name"],"price"=>$product["price"]);
		}
		
		$packages = array_merge($packages,array("plans"=>$plans));
		$response[] = $packages;
		
		$data['data']['products'] = $response;
		$this->json_response($data);
	}
}

                            
                            
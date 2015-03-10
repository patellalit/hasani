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
		$this->load->model('basic_model');

		$user_id = (int)$this->input->get('user_id');
		if($user_id <= 0){
			$data = array();
			$data['status'] = 0;
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
		$states = $this->region_model->get_states_by_country(1);
		$state_index=0;$city_index=0;
		foreach($states as $state){
			$cities = $this->region_model->get_city_by_state($state["state_id"]);
			$response[$state_index] = array("state_id"=>$state["state_id"],"state_name"=>$state["state_name"]);
			$city_index = 0;
			foreach($cities as $city){
				$areas = $this->region_model->get_area_by_city($city["city_id"]);
				$response[$state_index]["cities"][$city_index] = array("city_id"=>$city["city_id"],"city_name"=>$city["city_name"]);
				foreach($areas as $area){
					$response[$state_index]["cities"][$city_index]["areas"][] = array("area_id"=>$area["area_id"],"area_name"=>$area["area_name"]);
				}
				$city_index++;
			}
			$state_index++;
		}
		
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
	
	/**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function role_list(){
		$data = array();
		$data['status'] = 1;
		
		$firms = $this->basic_model->get_role_api();
		
		$data['data']['roles'] = $firms;
		$this->json_response($data);
	}
	
	/**
	 * Load the main view with all the current model model's data.
	 * @return void
	 */
	public function service_center_list(){
		$data = array();
		$data['status'] = 1;
	
		$service_centers = $this->basic_model->get_service_center_api();
	
		$data['data']['service_centers'] = $service_centers;
		$this->json_response($data);
	}
}
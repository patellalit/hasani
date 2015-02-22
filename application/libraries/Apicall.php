<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apicall {

	protected $api_base_url;
	protected $ci;
    public function __construct($params=null)
    {
		$this->ci =& get_instance();
        $this->api_base_url = $this->ci->config->item("api_base_url");
    }
	
	function call($method, $url, $data = false,$print_respose=false)
	{
		$user_info = $this->ci->session->userdata('login_user');
		
		$url = $this->api_base_url."/".$url;

		$curl = curl_init();

		switch ($method)
		{
		    case "POST":
				$data_post = array();
				$data_post["user_id"]=$user_info["id"];
				if($print_respose)
						echo http_build_query($data);
				$url = sprintf("%s?%s", $url, http_build_query($data_post));

		        curl_setopt($curl, CURLOPT_POST, 1);

		        if ($data)
		            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				if($print_respose)
						echo $url;
		        break;
		    default:
		        if ($data){
					$data["user_id"]=$user_info["id"];
					if($print_respose)
						echo http_build_query($data);
		            $url = sprintf("%s?%s", $url, http_build_query($data));
					if($print_respose)
						echo $url;
				}else{
					$data = array();
					$data["user_id"]=$user_info["id"];
					if($print_respose)
						echo http_build_query($data);
					$url = sprintf("%s?%s", $url, http_build_query($data));
					if($print_respose)
						echo $url;
				}
		}

		// Optional Authentication:
		//curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		//curl_setopt($curl, CURLOPT_USERPWD, "username:password");

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		$result = curl_exec($curl);

		curl_close($curl);

		$response = null;
		if($print_respose)
			echo $result;
		if($json_data = json_decode($result,TRUE)){
			$response = $json_data;
		}else{
			echo $result;
		}
		return $json_data;
	}
}

                            
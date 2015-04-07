<!DOCTYPE html> 
<html lang="en-US">
<head>
  <title>Hasani Group Admin</title>
  <meta charset="utf-8">
  <link href="<?php echo base_url(); ?>assets/css/admin/global.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url(); ?>assets/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css">
  <script src="<?php echo base_url(); ?>assets/js/jquery-1.7.1.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/jquery-ui/jquery-ui.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-datepicker.js"></script>
<link href="<?php echo base_url(); ?>assets/css/admin/datepicker3.css" rel="stylesheet" type="text/css">
	<script src="<?php echo base_url(); ?>assets/js/admin.js"></script>
<style>
.navbar-inner{
background-image:none;
background-color:#fff;
}
.navbar .nav > li > a, .navbar .nav > li > a:hover{
color:#000 !important;
text-shadow:none !important;
}
.navbar .nav .active > a, .navbar .nav .active > a:hover {
    background-color: rgba(0, 0, 0, 0.5);
    color: #ffffff !important;
    text-decoration: none;
}
</style>
</head>
<?php 
$login_user = $this->session->userdata('login_user');

?>
<body>
	<div class="navbar navbar-fixed-top">
	  <div class="navbar-inner">
	    <div class="container">
	      <a class="brand" style="padding:0;"><img src="<?php echo base_url(); ?>assets/img/admin/logo-small.png" style="height:36px;" /></a>
	      <ul class="nav">
			<li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding:0;"><img src="<?php echo base_url(); ?>assets/img/admin/my-account.png" alt="User" title="My Account"/> &nbsp;&nbsp;&nbsp;</a>
	          <ul class="dropdown-menu">
	            <li>
					<a href="javascript:void(0);">Welcome <?php echo $login_user["first_name"]." ".$login_user["last_name"]?></a>
				</li>
				<!--<li>
					<a href="<?php echo base_url(); ?>admin/changepassword">Change Password</a>	              
				</li>-->
				<li>
					<a href="<?php echo base_url(); ?>admin/logout">Logout</a>
	            </li>
	          </ul>
	        </li>
			<li <?php if($this->uri->segment(2) == 'registered'){echo 'class="active"';}?>>
	          <a href="<?php echo base_url(); ?>admin/registered/users">Registered CDKEY</a>
	        </li>

	        <li <?php if($this->uri->segment(2) == 'users'){echo 'class="active"';}?>>
	          <a href="<?php echo base_url(); ?>admin/users">Users</a>
	        </li>
			
			<li <?php if($this->uri->segment(2) == 'dealers'){echo 'class="active"';}?>>
	          <a href="<?php echo base_url(); ?>admin/dealers">Dealers</a>
	        </li>
            <?php /*<li <?php if($this->uri->segment(2) == 'Country'){echo 'class="active"';}?>>
                <a href="<?php echo base_url(); ?>admin/country">Country</a>
            </li> */ ?>
            <li <?php if($this->uri->segment(2) == 'claim'){echo 'class="active"';}?>>
            	<a href="<?php echo base_url(); ?>admin/claim">Claim</a>
            </li>
           <li class="dropdown">
            	<a href="#" class="dropdown-toggle" data-toggle="dropdown">Masters <b class="caret"></b></a>
            	<ul class="dropdown-menu">
		            <li>
						<a href="<?php echo base_url(); ?>admin/state">State</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>admin/city">City</a>
					</li>
					<li>
						<a href="<?php echo base_url(); ?>admin/area">Area</a>
					</li>
<li>
<a href="<?php echo base_url(); ?>admin/servicecenter">Service Center</a>
</li>
				</ul>
			</li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reports <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    <li>
                        <a href="<?php echo base_url(); ?>admin/target">Target</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>admin/trainee">Trainee</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>admin/dsr">DSR</a>
                    </li>
                    <li>
                        <a href="<?php echo base_url(); ?>admin/location">Location</a>
                    </li>
                </ul>
            </li>
	      </ul>
	    </div>
	  </div>
	</div>
                            
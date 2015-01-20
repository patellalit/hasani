<!DOCTYPE html> 
<html lang="en-US">
<head>
  <title>CoilChem Admin</title>
  <meta charset="utf-8">
  <link href="<?php echo base_url(); ?>assets/css/admin/global.css" rel="stylesheet" type="text/css">
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
<body>
	<div class="navbar navbar-fixed-top">
	  <div class="navbar-inner">
	    <div class="container">
	      <a class="brand" style="padding:0;"><img src="<?php echo base_url(); ?>assets/img/admin/logo-small.png" style="height:36px;" /></a>
	      <ul class="nav">
	        <li <?php if($this->uri->segment(2) == 'customers'){echo 'class="active"';}?>>
	          <a href="<?php echo base_url(); ?>admin/customers">Customers</a>
	        </li>
	        <li <?php if($this->uri->segment(2) == 'users'){echo 'class="active"';}?>>
	          <a href="<?php echo base_url(); ?>admin/users">Users</a>
	        </li>
	        <li class="dropdown">
	          <a href="#" class="dropdown-toggle" data-toggle="dropdown">System <b class="caret"></b></a>
	          <ul class="dropdown-menu">
	            <li>
	              <a href="<?php echo base_url(); ?>admin/logout">Logout</a>
	            </li>
	          </ul>
	        </li>
	      </ul>
	    </div>
	  </div>
	</div>	

	<div class="container top">
      
      <ul class="breadcrumb">
        <li>
          <a href="<?php echo site_url("admin"); ?>">
            <?php echo ucfirst($this->uri->segment(1));?>
          </a> 
          <span class="divider">/</span>
        </li>
        <li>
          <a href="<?php echo site_url("admin").'/'.$this->uri->segment(2); ?>">
            <?php echo ucfirst($this->uri->segment(2));?>
          </a> 
          <span class="divider">/</span>
        </li>
        <li class="active">
          <a href="#">Update</a>
        </li>
      </ul>
      
      <div class="page-header">
        <h2>
          Updating <?php echo ucfirst($this->uri->segment(2));?>
        </h2>
      </div>

 
      <?php
      //flash messages
      if($this->session->flashdata('flash_message')){
        if($this->session->flashdata('flash_message') == 'updated')
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> user updated with success.';
          echo '</div>';       
        }else{
          echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Username / email / phone </strong> already exists.';
          echo '</div>';          
        }
      }
          
          $option_country = array(''=>'select country');
          foreach($country as $row)
          {
              $option_country[$row['id']]=$row['country_name'];
          }
          $option_state = array(''=>'select state');
          foreach($state as $row)
          {
              $option_state[$row['id']]=$row['name'];
          }
          $option_city = array(''=>'select city');
          foreach($city as $row)
          {
              $option_city[$row['id']]=$row['name'];
          }
          
          $option_area = array(''=>'select area');
          foreach($area as $row)
          {
              $option_area[$row['id']]=$row['area_name'];
          }
      ?>
      
      <?php
      //form data
      $attributes = array('class' => 'form-horizontal', 'id' => '');

      //form validation
      echo validation_errors();

      echo form_open('admin/users/update/'.$this->uri->segment(4).'', $attributes);
      ?>
        <fieldset>
          <div class="control-group">
            <label for="inputError" class="control-label">First Name</label>
            <div class="controls">
              <input type="text" id="" name="first_name" value="<?php echo $user[0]['first_name']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
		  <div class="control-group">
            <label for="inputError" class="control-label">Last Name</label>
            <div class="controls">
              <input type="text" id="" name="last_name" value="<?php echo $user[0]['last_name']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">Company Mobile</label>
            <div class="controls">
              <input type="text" id="" name="mobile" value="<?php echo $user[0]['mobile']; ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>
		  <div class="control-group">
            <label for="inputError" class="control-label">Company Email</label>
            <div class="controls">
              <input type="text" id="" name="email" value="<?php echo $user[0]['email_address']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">Personal Mobile</label>
            <div class="controls">
              <input type="text" id="" name="personal_phone" value="<?php echo $user[0]['personal_phone']; ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>
		  <div class="control-group">
            <label for="inputError" class="control-label">Personal Email</label>
            <div class="controls">
              <input type="text" id="" name="personal_email" value="<?php echo $user[0]['personal_email']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
		  <div class="control-group">
            <label for="inputError" class="control-label">Address</label>
            <div class="controls">
              <input type="text" id="" name="address" value="<?php echo $user[0]['address']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
		  <div class="control-group">
            <label for="inputError" class="control-label">Password</label>
            <div class="controls">
              <input type="text" id="" name="password" value="">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>
 
          <div class="control-group">
            <label for="inputError" class="control-label">Role</label>
            <div class="controls">
              <?php
                  $role_options = array();
                  foreach($roles as $role)
                  {
                      $role_options[$role['id']] = $role['role_name'];
                      
                  }
				$js = 'id="role" onChange="fillparent(this.value,\''.base_url('admin').'/users/getparent/\')"';
				echo form_dropdown('role', $role_options, $user[0]['role'],$js);
			?>
            </div>
          </div>
<div class="control-group">
<label for="inputError" class="control-label">Parent</label>
<div class="controls">
<?php
    $parent_options = array();
    foreach($parents as $parent)
    {
        $parent_options[$parent['id']] = $parent['first_name'].' '.$parent['last_name'];
        
    }
    $js1 = 'id="parent"';
    
    echo form_dropdown('parent', $parent_options, $user[0]['parent'],$js1);
    ?>
</div>
</div>

		  <div class="isd-toggle" <?php if($user[0]['role'] != 7) echo 'style="display:none;"'; ?>>
			<div class="control-group">
		        <label for="inputError" class="control-label">Dealer Name</label>
		        <div class="controls">
		          <input type="text" id="" name="ol_name" value="<?php echo $user[0]['ol_name']; ?>" >
		        </div>
		      </div>
		      <div class="control-group">
		        <label for="inputError" class="control-label">Dealer Area</label>
		        <div class="controls">
		          <input type="text" id="" name="ol_area" value="<?php echo $user[0]['ol_area']; ?>">
		        </div>
		      </div> 
		  </div>
<div class="control-group">
<label for="inputError" class="control-label">Country</label>
<div class="controls">
<?php echo form_dropdown('country_id',$option_country,$user[0]['country_id'],'class="span2 width230" id="country_id" onchange="fetchState(this.value,\''.base_url().'admin/state/fetchState\')"'); ?>
</div>
</div>
<div class="control-group">
<label for="inputError" class="control-label">State</label>
<div class="controls">
<?php echo form_dropdown('state_id',$option_state,$user[0]['state_id'],'class="span2 width230" id="state_id"  onchange="fetchCity(this.value,\''.base_url().'admin/state/fetchCity\')"'); ?>
</div>
</div>
<div class="control-group">
<label for="inputError" class="control-label">City</label>
<div class="controls">
<?php echo form_dropdown('city_id',$option_city,$user[0]['city_id'],'class="span2 width230" id="city_id" onchange="fetchArea(this.value,\''.base_url().'admin/state/fetchArea\')"'); ?>
</div>
</div>
<div class="control-group">
<label for="inputError" class="control-label">Area</label>
<div class="controls">
<?php echo form_dropdown('area_id',$option_area,$user[0]['area_id'],'class="span2 width230" id="area_id"'); ?>
</div>
</div>

          <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save changes</button>
<button class="btn" type="button" onclick="document.location='<?php echo site_url("admin").'/users/'; ?>'">Cancel</button>
          </div>
        </fieldset>

      <?php echo form_close(); ?>

    </div>
     

                            
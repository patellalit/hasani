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
            echo '<strong>Oh snap!</strong> change a few things up and try submitting again.';
          echo '</div>';          
        }
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
            <label for="inputError" class="control-label">Username</label>
            <div class="controls">
              <input type="text" readonly="readonly" id="" name="username" value="<?php echo $user[0]['user_name']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
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
            <label for="inputError" class="control-label">Password</label>
            <div class="controls">
              <input type="text" id="" name="password" value="">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div> 
		  <div class="control-group">
            <label for="inputError" class="control-label">Email</label>
            <div class="controls">
              <input type="text" id="" name="email" value="<?php echo $user[0]['email_address']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">Mobile</label>
            <div class="controls">
              <input type="text" id="" name="mobile" value="<?php echo $user[0]['mobile']; ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>
          <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save changes</button>
            <button class="btn" type="reset">Cancel</button>
          </div>
        </fieldset>

      <?php echo form_close(); ?>

    </div>
     
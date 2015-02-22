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
          <a href="#">New</a>
        </li>
      </ul>
      
      <div class="page-header">
        <h2>
          Adding <?php echo ucfirst($this->uri->segment(2));?>
        </h2>
      </div>
 
      <?php
      //flash messages
      if(isset($flash_message)){
        if($flash_message == TRUE)
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> new Customer created with success.';
          echo '</div>';       
        }else{
          echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Customer email address </strong> already exists.';
          echo '</div>';          
        }
      }
      ?>
      
      <?php
      //form data
      $attributes = array('class' => 'form-horizontal', 'id' => '');
      //form validation
      echo validation_errors();
      
      echo form_open('admin/customers/add', $attributes);
      ?>
        <fieldset>
          <div class="control-group">
            <label for="inputError" class="control-label">Customer Name</label>
            <div class="controls">
              <input type="text" id="" name="customer_name" value="<?php echo set_value('customer_name'); ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
		  <div class="control-group">
            <label for="inputError" class="control-label">O/L Name</label>
            <div class="controls">
              <input type="text" id="" name="ol_name" value="<?php echo set_value('ol_name'); ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
		  <div class="control-group">
            <label for="inputError" class="control-label">O/L Address</label>
            <div class="controls">
              <input type="text" id="" name="ol_address" value="<?php echo set_value('ol_address'); ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">O/L Area</label>
            <div class="controls">
              <input type="text" id="" name="ol_area" value="<?php echo set_value('ol_area'); ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>    
		  <div class="control-group">
            <label for="inputError" class="control-label">Email</label>
            <div class="controls">
              <input type="text" id="" name="email" value="<?php echo set_value('email'); ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>      
          <div class="control-group">
            <label for="inputError" class="control-label">Mobile</label>
            <div class="controls">
              <input type="text" id="" name="mobile" value="<?php echo set_value('mobile'); ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>
          
          
          <div class="control-group">
            <label for="inputError" class="control-label">CST Number</label>
            <div class="controls">
              <input type="text" id="" name="cst_number" value="<?php echo set_value('cst_number'); ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">CST Date</label>
            <div class="controls">
              <input type="date" id="" name="cst_date" value="<?php echo set_value('cst_date'); ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>    
		  <div class="control-group">
            <label for="inputError" class="control-label">GST Number</label>
            <div class="controls">
              <input type="text" id="" name="gst_number" value="<?php echo set_value('gst_number'); ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>      
          <div class="control-group">
            <label for="inputError" class="control-label">GST Date</label>
            <div class="controls">
              <input type="date" id="" name="gst_date" value="<?php echo set_value('gst_date'); ?>">
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
     

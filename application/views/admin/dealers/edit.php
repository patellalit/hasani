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
            echo '<strong>Well done!</strong> customer updated with success.';
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

      echo form_open('admin/customers/update/'.$this->uri->segment(4).'', $attributes);
      ?>
        <fieldset>
		  <div class="control-group">
            <label for="inputError" class="control-label">Customer Name</label>
            <div class="controls">
              <input type="text" id="" name="customer_name" value="<?php echo $customers[0]['customer_name']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
		  <div class="control-group">
            <label for="inputError" class="control-label">O/L Name</label>
            <div class="controls">
              <input type="text" id="" name="ol_name" value="<?php echo $customers[0]['ol_name']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
		  <div class="control-group">
            <label for="inputError" class="control-label">O/L Address</label>
            <div class="controls">
              <input type="text" id="" name="ol_address" value="<?php echo $customers[0]['ol_address']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">O/L Area</label>
            <div class="controls">
              <input type="text" id="" name="ol_area" value="<?php echo $customers[0]['ol_area']; ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>    
		  <div class="control-group">
            <label for="inputError" class="control-label">Email</label>
            <div class="controls">
              <input type="text" id="" name="email" value="<?php echo $customers[0]['email']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>      
          <div class="control-group">
            <label for="inputError" class="control-label">Mobile</label>
            <div class="controls">
              <input type="text" id="" name="mobile" value="<?php echo $customers[0]['mobile']; ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>
          
          
          <div class="control-group">
            <label for="inputError" class="control-label">CST Number</label>
            <div class="controls">
              <input type="text" id="" name="cst_number" value="<?php echo $customers[0]['cst_number']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">CST Date</label>
            <div class="controls">
              <input type="date" id="" name="cst_date" value="<?php echo $customers[0]['cst_date']; ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>    
		  <div class="control-group">
            <label for="inputError" class="control-label">GST Number</label>
            <div class="controls">
              <input type="text" id="" name="gst_number" value="<?php echo $customers[0]['gst_number']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>      
          <div class="control-group">
            <label for="inputError" class="control-label">GST Date</label>
            <div class="controls">
              <input type="date" id="" name="gst_date" value="<?php echo $customers[0]['gst_date']; ?>">
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
     

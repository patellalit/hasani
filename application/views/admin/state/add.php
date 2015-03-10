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
            echo '<strong>Well done!</strong> new state created with success.';
          echo '</div>';       
        }else{
          echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>State </strong> already exists.';
          echo '</div>';          
        }
      }
          $option_country = array(''=>'select country');
          foreach($country as $row)
          {
              $option_country[$row['id']]=$row['country_name'];
          }
      ?>
      
      <?php
      //form data
      $attributes = array('class' => 'form-horizontal', 'id' => '');
      //form validation
      echo validation_errors();
      
      echo form_open('admin/state/add', $attributes);
      ?>
        <fieldset>
          <div class="control-group">
            <label for="inputError" class="control-label">Country</label>
            <div class="controls">
<?php echo form_dropdown('country_id',$option_country,set_value('country_id'),'class="span2 width230" id="country_id"'); ?>
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">State</label>
            <div class="controls">
                <input type="text" id="state_name" name="state_name" value="<?php echo set_value('state_name'); ?>" >
            </div>
          </div>
		  <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save changes</button>
            <button class="btn" type="button" onclick="document.location='<?php echo site_url("admin").'/state/'; ?>'">Cancel</button>
          </div>
        </fieldset>

      <?php echo form_close(); ?>

    </div>
     

                            
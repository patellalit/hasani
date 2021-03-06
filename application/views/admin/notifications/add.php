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
            echo '<strong>Well done!</strong> Notification added with success.';
          echo '</div>';       
        }else{
          echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo 'Error occured. Please try again.';
          echo '</div>';          
        }
      }

      ?>
      
      <?php
      //form data
      $attributes = array('class' => 'form-horizontal', 'id' => '');
      //form validation
      echo validation_errors();
      
      echo form_open('admin/notifications/add', $attributes);
          $role_options = array('0'=>'Select All');
          
          foreach($roles as $role)
          {
              $role_options[$role['id']] = $role['role_name'];
          }
          $state_options = array('0'=>'Select All');
          
          foreach($states as $state)
          {
              $state_options[$state['id']] = $state['name'];
          }
      ?>
        <fieldset>
<div class="control-group">
<label for="inputError" class="control-label">State</label>
<div class="controls">
<?php echo form_dropdown('states',$state_options,'',''); ?>
</div>
</div>
            <div class="control-group">
            <label for="inputError" class="control-label">Role</label>
            <div class="controls">
<?php echo form_dropdown('roles',$role_options,'',''); ?>
            </div>
            </div>
          <div class="control-group">
            <label for="inputError" class="control-label">Message</label>
            <div class="controls">
                <textarea id="message" name="message" cols="50" rows="4"></textarea>
            </div>
          </div>
		  <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save changes</button>
            <button class="btn" type="button" onclick="document.location='<?php echo site_url("admin").'/notifications/'; ?>'">Cancel</button>
          </div>
        </fieldset>

      <?php echo form_close(); ?>

    </div>
     

                            
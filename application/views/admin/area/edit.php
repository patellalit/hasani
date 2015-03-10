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
            echo '<strong>Well done!</strong> area updated with success.';
          echo '</div>';       
        }else{
          echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Oh snap!</strong> change a few things up and try submitting again.';
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
      ?>
      
      <?php
      //form data
      $attributes = array('class' => 'form-horizontal', 'id' => '');

      //form validation
      echo validation_errors();

      echo form_open('admin/area/update/'.$this->uri->segment(4).'', $attributes);
         // print_r($state);
      ?>
        <fieldset>
          <div class="control-group">
            <div class="control-group">
            <label for="inputError" class="control-label">Country</label>
            <div class="controls">
            <?php echo form_dropdown('country_id',$option_country,'','class="span2 width230" id="country_id"  onchange="fetchState(this.value,\''.base_url().'admin/state/fetchState\')"'); ?>
            </div>
            </div>
<div class="control-group">
<label for="inputError" class="control-label">State</label>
<div class="controls">
<?php echo form_dropdown('state_id',$option_state,$area[0]['stateId'],'class="span2 width230" id="state_id"'); ?>
</div>
</div>
<div class="control-group">
<label for="inputError" class="control-label">City</label>
<div class="controls">
<?php echo form_dropdown('city_id',$option_city,$area[0]['city_id'],'class="span2 width230" id="city_id"'); ?>
</div>
</div>
<div class="control-group">
<label for="inputError" class="control-label">Area</label>
<div class="controls">
<input type="text" id="area_name" name="area_name" value="<?php echo $area[0]['area_name'] ?>" >
</div>
</div>



          <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save changes</button>
<button class="btn" type="button" onclick="document.location='<?php echo site_url("admin").'/country/'; ?>'">Cancel</button>
          </div>
        </fieldset>

      <?php echo form_close(); ?>

    </div>
     

                            
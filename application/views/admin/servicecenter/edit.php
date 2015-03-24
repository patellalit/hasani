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
          if($this->session->flashdata('flash_message')){
              if($this->session->flashdata('flash_message') == 'updated')
              {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> service center updated with success.';
          echo '</div>';       
        }else{
          echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Servicecenter </strong> already exists.';
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
      
      echo form_open('admin/servicecenter/update/'.$this->uri->segment(4).'', $attributes);
      ?>
        <fieldset>
          <div class="control-group">
            <label for="inputError" class="control-label">Country</label>
            <div class="controls">
                <?php echo form_dropdown('country_id',$option_country,$servicecenter[0]['country_id'],'class="span2 width230" id="country_id" onchange="fetchState(this.value,\''.base_url().'admin/state/fetchState\')"'); ?>
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">State</label>
            <div class="controls">
            <?php echo form_dropdown('state_id',$option_state,$servicecenter[0]['state_id'],'class="span2 width230" id="state_id"  onchange="fetchCity(this.value,\''.base_url().'admin/state/fetchCity\')"'); ?>
            </div>
          </div>
<div class="control-group">
<label for="inputError" class="control-label">City</label>
<div class="controls">
<?php echo form_dropdown('city_id',$option_city,$servicecenter[0]['city_id'],'class="span2 width230" id="city_id" onchange="fetchArea(this.value,\''.base_url().'admin/state/fetchArea\')"'); ?>
</div>
</div>
<div class="control-group">
<label for="inputError" class="control-label">Area</label>
<div class="controls">
<?php echo form_dropdown('area_id',$option_area,$servicecenter[0]['area_id'],'class="span2 width230" id="area_id"'); ?>
</div>
</div>
          <div class="control-group">
            <label for="inputError" class="control-label">Service center name</label>
            <div class="controls">
                <input type="text" id="servicecenter_name" name="servicecenter_name" value="<?php echo $servicecenter[0]['name']; ?>" >
            </div>
          </div>
<div class="control-group">
<label for="inputError" class="control-label">Service center address</label>
<div class="controls">
<input type="text" id="servicecenter_address" name="servicecenter_address" value="<?php echo $servicecenter[0]['address']; ?>" >
</div>
</div>

<div class="control-group">
<label for="inputError" class="control-label">Zipcode</label>
<div class="controls">
<input type="text" id="zipcode" name="zipcode" value="<?php echo $servicecenter[0]['zipCode']; ?>" >
</div>
</div>

<div class="control-group">
<label for="inputError" class="control-label">Contact</label>
<div class="controls">
<input type="text" id="contactNo" name="contactNo" value="<?php echo $servicecenter[0]['contactNo']; ?>" >
</div>
</div>

<div class="control-group">
<label for="inputError" class="control-label">Email</label>
<div class="controls">
<input type="text" id="email" name="email" value="<?php echo $servicecenter[0]['emailId']; ?>" >
</div>
</div>
		  <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save changes</button>
            <button class="btn" type="button" onclick="document.location='<?php echo site_url("admin").'/state/'; ?>'">Cancel</button>
          </div>
        </fieldset>

      <?php echo form_close(); ?>

    </div>
     

                            
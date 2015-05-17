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
          
          /*$option_country = array(''=>'select country');
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
          */
          $option_plan = array(''=>'select plan');
          foreach($plan as $row)
          {
              $option_plan[$row['id']]=$row['plan_name'];
          }
      ?>
      
      <?php
      //form data
      $attributes = array('class' => 'form-horizontal', 'id' => '');

      //form validation
      echo validation_errors();

      echo form_open('admin/registered/users/edit/'.$this->uri->segment(5).'', $attributes);
      ?>
        <fieldset>
          <div class="control-group">
            <label for="inputError" class="control-label">CDKEY</label>
            <div class="controls">
              <input type="text" id="cdkey" name="cdkey" value="<?php echo $user[0]['cdkey']; ?>" readonly="readonly" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
		  <div class="control-group">
            <label for="inputError" class="control-label">Customer Name</label>
            <div class="controls">
              <input type="text" id="customer_name" name="customer_name" value="<?php echo $user[0]['customerName']; ?>"  >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">Phone Number</label>
            <div class="controls">
              <input type="text" id="phoneNo" name="phoneNo" value="<?php echo $user[0]['phoneNo']; ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>
		  <div class="control-group">
            <label for="inputError" class="control-label">Model No</label>
            <div class="controls">
              <input type="text" id="modelNo" name="modelNo" value="<?php echo $user[0]['modelNo']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">IMEI No</label>
            <div class="controls">
              <input type="text" id="imeiNo" name="imeiNo" value="<?php echo $user[0]['imeiNo']; ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>
		  <div class="control-group">
            <label for="inputError" class="control-label">Bill No</label>
            <div class="controls">
              <input type="text" id="billNo" name="billNo" value="<?php echo $user[0]['billNo']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
		  <div class="control-group">
            <label for="inputError" class="control-label">Purchase Date</label>
            <div class="controls">
              <input type="text" id="purchaseDate" name="purchaseDate" value="<?php echo $user[0]['purchaseDate']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
		  <div class="control-group">
            <label for="inputError" class="control-label">Bill Amount</label>
            <div class="controls">
              <input type="text" id="billAmount" name="billAmount" value="<?php echo $user[0]['billAmount']; ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>
 
          <div class="control-group">
            <label for="inputError" class="control-label">Dealer Name</label>
            <div class="controls">
              <input type="text" id="dealerName" name="dealerName" value="<?php echo $user[0]['dealerName']; ?>">
            </div>
          </div>
<div class="control-group">
<label for="inputError" class="control-label">Customer Address</label>
<div class="controls">
<input type="text" id="customerAddress" name="customerAddress" value="<?php echo $user[0]['customerAddress']; ?>">
</div>
</div>


			<div class="control-group">
		        <label for="inputError" class="control-label">Plan</label>
		        <div class="controls">
<?php echo form_dropdown('plan',$option_plan,$user[0]['planid'],'id="plan"'); ?>
		        </div>
		      </div>
<!-- <div class="control-group">
<label for="inputError" class="control-label">Package</label>
<div class="controls">
<input type="text" id="package" name="package" value="<?php echo $user[0]['package']; ?>" >
</div>
</div> -->
		      <div class="control-group">
		        <label for="inputError" class="control-label">IMEI No2</label>
		        <div class="controls">
		          <input type="text" id="imeiNo2" name="imeiNo2" value="<?php echo $user[0]['imeiNo2']; ?>">
		        </div>
		      </div> 

<div class="control-group">
<label for="inputError" class="control-label">Plan Date</label>
<div class="controls">
<input type="text" id="planDate" name="planDate" value="<?php echo $user[0]['planDate']; ?>">
</div>
</div>

<div class="control-group">
<label for="inputError" class="control-label">State</label>
<div class="controls">
<input type="text" id="state" name="state" value="<?php echo $user[0]['state']; ?>">
</div>
</div>

<div class="control-group">
<label for="inputError" class="control-label">City</label>
<div class="controls">
<input type="text" id="city" name="city" value="<?php echo $user[0]['city']; ?>">
</div>
</div>

<div class="control-group">
<label for="inputError" class="control-label">Area</label>
<div class="controls">
<input type="text" id="area" name="area" value="<?php echo $user[0]['area']; ?>">
</div>
</div>


          <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save changes</button>
<button class="btn" type="button" onclick="document.location='<?php echo site_url("admin").'/users/'; ?>'">Cancel</button>
          </div>
        </fieldset>
<script>
$(function() {
  $( "#purchaseDate" ).datepicker({"format": "yyyy-mm-dd"}).on('changeDate', function(e){
                                                             $(this).datepicker('hide');
                                                             });
  $( "#planDate" ).datepicker({"format": "yyyy-mm-dd"}).on('changeDate', function(e){
                                                               $(this).datepicker('hide');
                                                               });
});

</script>
      <?php echo form_close(); ?>

    </div>
     

                            
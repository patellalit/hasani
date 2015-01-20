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
            echo '<strong>Well done!</strong> product updated with success.';
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

      echo form_open('admin/products/update/'.$this->uri->segment(4).'', $attributes);
      ?>
        <fieldset>
          <div class="control-group">
            <label for="inputError" class="control-label">Item</label>
            <div class="controls">
              <input type="text" id="" name="item" value="<?php echo $product[0]['item']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
		  <div class="control-group">
            <label for="inputError" class="control-label">Description</label>
            <div class="controls">
              <input type="text" id="" name="description" value="<?php echo $product[0]['description']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">U/M</label>
            <div class="controls">
              <input type="text" id="" name="um" value="<?php echo $product[0]['um']; ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>          
          <div class="control-group">
            <label for="inputError" class="control-label">Cost Price ($)</label>
            <div class="controls">
              <input type="text" id="" name="cost_price" value="<?php echo $product[0]['cost_price']; ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>
		  <div class="control-group">
            <label for="inputError" class="control-label">Wholesale Price ($)</label>
            <div class="controls">
              <input type="text" name="sell_price_wholesale" value="<?php echo $product[0]['sell_price_wholesale']; ?>">
              <!--<span class="help-inline">OOps</span>-->
            </div>
          </div>
		  <div class="control-group">
            <label for="inputError" class="control-label">Wholesale Max Parameters Price ($)</label>
            <div class="controls">
              <input type="text" name="sell_price_wholesale_max" value="<?php echo $product[0]['sell_price_wholesale_max']; ?>">
              <!--<span class="help-inline">OOps</span>-->
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">Retail Price ($)</label>
            <div class="controls">
              <input type="text" name="sell_price_retail" value="<?php echo $product[0]['sell_price_retail']; ?>">
              <!--<span class="help-inline">OOps</span>-->
            </div>
          </div>
		  <div class="control-group">
            <label for="inputError" class="control-label">Retail Max Parameters ($)</label>
            <div class="controls">
              <input type="text" name="sell_price_retail_max" value="<?php echo $product[0]['sell_price_retail_max']; ?>">
              <!--<span class="help-inline">OOps</span>-->
            </div>
          </div>
          <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save changes</button>
            <button class="btn" type="reset">Cancel</button>
          </div>
        </fieldset>

      <?php echo form_close(); ?>

    </div>
     

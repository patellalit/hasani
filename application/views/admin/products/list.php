    <div class="container top">

      <ul class="breadcrumb">
        <li>
          <a href="<?php echo site_url("admin"); ?>">
            <?php echo ucfirst($this->uri->segment(1));?>
          </a> 
          <span class="divider">/</span>
        </li>
        <li class="active">
          <?php echo ucfirst($this->uri->segment(2));?>
        </li>
      </ul>

      <div class="page-header users-header">
        <h2>
          <?php echo ucfirst($this->uri->segment(2));?> 
		  <a href="javascript:void(0)" onclick="$('#csv-upload-container').toggle()" class="btn btn-primary">Upload CSV</a>
          <a href="<?php echo site_url("admin").'/'.$this->uri->segment(2); ?>/add" class="btn btn-success">Add a new</a>
        </h2>
      </div>

	<div style="display:none;" id="csv-upload-container" class="row">
		<div class="span12 columns">
          <div class="well">
		<div class="page-header users-header">
		<h4>Upload CSV File</h4>
		</div>
		<?php echo form_open_multipart('admin/products/csv');?>

			<strong>Select CSV File:</strong> <input type="file" name="csv_file"><br /><br /><input type="submit" value="Upload" class="btn btn-info" />
		</form>
		</div>
		</div>
	</div>
      
      <div class="row">
        <div class="span12 columns">
          <div class="well">
           
            <?php
           
            $attributes = array('class' => 'form-inline reset-margin', 'id' => 'myform');
           
            //save the columns names in a array that we will use as filter         
            $options_products = array();    
            foreach ($products as $array) {
              foreach ($array as $key => $value) {
                $options_products[$key] = $key;
              }
              break;
            }

            echo form_open('admin/products', $attributes);
     
              echo form_label('Search:', 'search_string');
              echo form_input('search_string', $search_string_selected, 'style="width: 170px;
height: 26px;"');

              echo form_label('Order by:', 'order');
              echo form_dropdown('order', $options_products, $order, 'class="span2"');

              $data_submit = array('name' => 'mysubmit', 'class' => 'btn btn-primary', 'value' => 'Go');

              $options_order_type = array('Asc' => 'Asc', 'Desc' => 'Desc');
              echo form_dropdown('order_type', $options_order_type, $order_type_selected, 'class="span1"');

              echo form_submit($data_submit);

            echo form_close();
            ?>

          </div>

          <table class="table table-striped table-bordered table-condensed">
            <thead>
              <tr>
                <th class="header">#</th>
				<th class="yellow header headerSortDown">Item</th>                
				<th class="yellow header headerSortDown">Description</th>
                <th class="green header">U/M</th>
                <th class="red header">Cost</th>
                <th class="red header">Retail<br />Price</th>
				<th class="red header">Retail Max<br />Parameters</th>
				<th class="red header">Wholesale<br />Price</th>
				<th class="red header">Wholesale Max<br />Parameters Price</th>
                <th class="red header">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($products as $row)
              {
                echo '<tr>';
                echo '<td>'.$row['id'].'</td>';
				echo '<td>'.$row['item'].'</td>';
                echo '<td>'.$row['description'].'</td>';
                echo '<td>'.$row['um'].'</td>';
                echo '<td>$'.$row['cost_price'].'</td>';
                echo '<td>$'.$row['sell_price_retail'].'</td>';
                echo '<td>$'.$row['sell_price_retail_max'].'</td>';
				echo '<td>$'.$row['sell_price_wholesale'].'</td>';
                echo '<td>$'.$row['sell_price_wholesale_max'].'</td>';
                echo '<td class="crud-actions">
                  <a href="'.site_url("admin").'/products/update/'.$row['id'].'" class="btn btn-info">view & edit</a>  
                  <a href="'.site_url("admin").'/products/delete/'.$row['id'].'" class="btn btn-danger">delete</a>
                </td>';
                echo '</tr>';
              }
              ?>      
            </tbody>
          </table>

          <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>

      </div>
    </div>

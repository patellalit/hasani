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
          <a  href="<?php echo site_url("admin").'/'.$this->uri->segment(2); ?>/add" class="btn btn-success">Add a new</a>
        </h2>
      </div>
      
      <div class="row">
        <div class="span12 columns">
          <div class="well">
           
            <?php
            
            
            $attributes = array('class' => 'form-inline reset-margin', 'id' => 'myform','method'=>'GET');
            echo form_open('admin/city', $attributes);
     
              echo form_label('Search:', 'search_string');
              echo form_input('search_string', $search_string_selected, 'style="width: 170px;height: 26px;"');

              $data_submit = array('name' => 'mysubmit', 'class' => 'btn btn-primary', 'value' => 'Go');
              
              echo '<input type="hidden" id="sort_order" name="order" value="'.$order.'" />';
              echo '<input type="hidden" id="sort_order_type" name="order_type" value="'.$order_type_selected.'" />';
              echo form_submit($data_submit);

            echo form_close();
            ?>

          </div>

          <table class="table table-striped table-bordered table-condensed">
            <thead>
              <tr>
                <th class="header">#</th>
                <th class="yellow header">City</th>
				<th class="yellow header">State</th>
                <th class="red header">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
                  foreach($city as $row)
              {
                echo '<tr>';
                echo '<td>'.$row['id'].'</td>';
                echo '<td>'.$row['name'].'</td>';
				echo '<td>'.$row['state_name'].'</td>';
				echo '<td class="crud-actions">
                  <a href="'.site_url("admin").'/city/update/'.$row['id'].'" class="btn btn-info">view & edit</a>
                  <a href="'.site_url("admin").'/city/delete/'.$row['id'].'" class="btn btn-danger" onclick="return confirmDelete(this);">delete</a>
                </td>';
                echo '</tr>';
              }
              ?>      
            </tbody>
          </table>

          <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>

      </div>
    </div>
 <script>
	$(function() {
		$(".sort").click(function(){
			var sort_dir = $(this).attr("data-order-dir");
			sort_dir = (sort_dir == "Asc")?"Desc":"Asc";
			$("#sort_order_type").val(sort_dir);
			$("#sort_order").val($(this).attr("data-order"));
			$("#myform").submit();
		});
	});
</script>
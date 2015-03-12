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
      
      <div class="row">
        <div class="span12 columns">
          <div class="well">
           
            <?php
                if($date_end!='')
                $end = date('d-m-Y',strtotime($date_end));
                else
                $end='';
            $attributes = array('class' => 'form-inline reset-margin', 'id' => 'myform','method'=>'GET');
            echo form_open('admin/claim', $attributes);
     
              echo form_label('Search:', 'search_string');
              echo form_input('search_string', $search_string_selected, 'style="width: 170px;height: 26px;"');
                
                echo form_label('From:', 'date_start');
                echo form_input('date_start', date('d-m-Y',strtotime($date_start)), 'style="width: 170px;height: 26px;" id="date_start"');
                
                echo form_label('To:', 'date_end');
                echo form_input('date_end', $end, 'style="width: 170px;height: 26px;" id="date_end"');

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
                <th class="yellow header">customer name</th>
				<th class="yellow header">customer address</th>
                <th class="red header">remarks</th>
<th class="red header">User</th>
<th class="red header">Date</th>
<!-- <th class="red header">status</th> -->

              </tr>
            </thead>
            <tbody>
              <?php
                  foreach($claim as $row)
              {
                echo '<tr>';
                echo '<td>'.$row['id'].'</td>';
                echo '<td>'.$row['customer_name'].'</td>';
				echo '<td>'.$row['customer_address'].'</td>';
                echo '<td>'.$row['remarks'].'</td>';
                echo '<td>'.$row['user_name'].'</td>';
                echo '<td>'.date('d/m/Y',strtotime($row['modified_at'])).'</td>';
                //echo '<td>'.$row['status'].'</td>';
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
      $( "#date_start" ).datepicker({"format": "dd-mm-yyyy"});
      $( "#date_end" ).datepicker({"format": "dd-mm-yyyy"});
      
		$(".sort").click(function(){
			var sort_dir = $(this).attr("data-order-dir");
			sort_dir = (sort_dir == "Asc")?"Desc":"Asc";
			$("#sort_order_type").val(sort_dir);
			$("#sort_order").val($(this).attr("data-order"));
			$("#myform").submit();
		});
	});
</script>
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
                $options_searchin = array(''=>'Select','claim_id'=>'Claim Id','customer_name'=>'Customer Name','user_name'=>'User Name','service_center'=>'Service Center','recieve_person_name'=>'Recieve person name','recieve_person_phone'=>'Recieve person phone','jobsheet_no'=>'Jobsheet no','state'=>'State','city'=>'City','area'=>'Area');
                
                $options_status = array('0'=>'Select','1'=>'Pending','2'=>'Pickup','3'=>'Submit to Service Center','4'=>'Pickup from Service Center','5'=>'Submit to Customer');
                
                if($date_end!='')
                $end = date('d-m-Y',strtotime($date_end));
                else
                $end='';
            $attributes = array('class' => 'form-inline reset-margin', 'id' => 'myform','method'=>'GET');
            echo form_open('admin/claim', $attributes);
     
              
                
                echo form_label('From:', 'date_start');
                echo form_input('date_start', date('d-m-Y',strtotime($date_start)), 'style="width: 170px;height: 26px;" id="date_start"');
                
                echo form_label('To:', 'date_end');
                echo form_input('date_end', $end, 'style="width: 100px;height: 26px;" id="date_end"');
                
                echo form_label('Search:', 'search_string');
                echo form_input('search_string', $search_string_selected, 'style="width: 100px;height: 26px;"');
                
                echo form_label('In:', 'search_in');
                echo form_dropdown('search_in',$options_searchin,$searchin,'id="search_in" style="width:100px"');
                
                echo form_label('Status', 'status_in');
                echo form_dropdown('status_in',$options_status,$searchstatus,'id="status_in" style="width:100px"');
                
                

              $data_submit = array('name' => 'mysubmit', 'class' => 'btn btn-primary', 'value' => 'Go');
              
              echo '<input type="hidden" id="sort_order" name="order" value="'.$order.'" />';
              echo '<input type="hidden" id="sort_order_type" name="order_type" value="'.$order_type_selected.'" />';
              echo form_submit($data_submit);

            echo form_close();
                
                $statusarray=array(1 => "Pending",
                                   2 => "Pickup",
                                   3 => "Submit to Service Center",
                                   4 => "Pickup from Service Center",
                                   5 => "Submit to Customer");
            ?>

          </div>

          <table class="table table-striped table-bordered table-condensed">
            <thead>
              <tr>
                <th class="header">#</th>
                <th class="yellow header"><a href="javascript:void(0)" class="sort" data-order="ct.claim_id" data-order-dir="<?php echo $order_type_selected?>">Claim id</a></th>
                <th class="yellow header"><a href="javascript:void(0)" class="sort" data-order="pr.customerName" data-order-dir="<?php echo $order_type_selected?>">Customer name</a></th>
				<!-- <th class="yellow header">customer address</th>
                <th class="red header">remarks</th>-->
                <th class="red header"><a href="javascript:void(0)" class="sort" data-order="m.first_name" data-order-dir="<?php echo $order_type_selected?>">User name</a></th>
                <th class="red header"><a href="javascript:void(0)" class="sort" data-order="pr.customerName" data-order-dir="<?php echo $order_type_selected?>">Service center</a></th>
                <th class="red header"><a href="javascript:void(0)" class="sort" data-order="ct.submit_to_person_name" data-order-dir="<?php echo $order_type_selected?>">Receive Person Name</a></th>
                <th class="red header"><a href="javascript:void(0)" class="sort" data-order="ct.submit_to_person_phone" data-order-dir="<?php echo $order_type_selected?>">Receive Person Phone</a></th>
                <th class="red header"><a href="javascript:void(0)" class="sort" data-order="ct.jobsheet_no" data-order-dir="<?php echo $order_type_selected?>">Jobsheet No</a></th>
                <th class="red header"><a href="javascript:void(0)" class="sort" data-order="ct.modified_at" data-order-dir="<?php echo $order_type_selected?>">Date</a></th>
                <th class="red header">status</th>

              </tr>
            </thead>
            <tbody>
              <?php
              foreach($claim as $row)
              {
                echo '<tr>';
                echo '<td>'.$row['id'].'</td>';
                echo '<td>'.$row['claim_id'].'</td>';
                echo '<td>'.$row['customer_name'].'</td>';
				//echo '<td>'.$row['customer_address'].'</td>';
                //echo '<td>'.$row['remarks'].'</td>';
                
                echo '<td>'.$row['user_name'].'</td>';
                if($row['service_center']=='')
                  echo '<td>-</td>';
                else
                  echo '<td>'.$row['service_center'].'</td>';
                
                if($row['submit_to_person_name']=='')
                    echo '<td>-</td>';
                else
                    echo '<td>'.$row['submit_to_person_name'].'</td>';
                
                if($row['submit_to_person_phone']=='')
                    echo '<td>-</td>';
                else
                    echo '<td>'.$row['submit_to_person_phone'].'</td>';
                
                echo '<td>'.$row['jobsheet_no'].'</td>';
                echo '<td>'.date('d/m/Y',strtotime($row['modified_at'])).'</td>';
                echo '<td>'.$statusarray[$row['status']].'</td>';
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
      $( "#date_start" ).datepicker({"format": "dd-mm-yyyy"}).on('changeDate', function(e){
                                                                 $(this).datepicker('hide');
                                                                 });
      $( "#date_end" ).datepicker({"format": "dd-mm-yyyy"}).on('changeDate', function(e){
                                                               $(this).datepicker('hide');
                                                               });
      
      
      
		$(".sort").click(function(){
			var sort_dir = $(this).attr("data-order-dir");
			sort_dir = (sort_dir == "Asc")?"Desc":"Asc";
			$("#sort_order_type").val(sort_dir);
			$("#sort_order").val($(this).attr("data-order"));
			$("#myform").submit();
		});
	});
</script>
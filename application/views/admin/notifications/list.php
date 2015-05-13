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
                $options_searchin = array(''=>'Select','nm.notification_id'=>'Id','m2.first_name'=>'To User','m1.first_name'=>'From User','nm.message'=>'Message');
                
                if($date_end!='')
                {
                    $end = date('d-m-Y',strtotime($date_end));
                    $start = date('d-m-Y',strtotime($date_start));
                }
                else
                {
                    $end='';
                    $start='';
                }
                
            $attributes = array('class' => 'form-inline reset-margin', 'id' => 'myform','method'=>'GET');
            echo form_open('admin/notifications', $attributes);
                
                echo form_label('From:', 'date_start');
                echo form_input('date_start', $start, 'style="width: 170px;height: 26px;" id="date_start"');
                
                echo form_label('To:', 'date_end');
                echo form_input('date_end', $end, 'style="width: 100px;height: 26px;" id="date_end"');
                
                echo form_label('Search:', 'search_string');
                echo form_input('search_string', $search_string_selected, 'style="width: 100px;height: 26px;"');
                
                echo form_label('In:', 'search_in');
                echo form_dropdown('search_in',$options_searchin,$searchin,'id="search_in" style="width:100px"');
                
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

                <th class="yellow header"><a href="javascript:void(0)" class="sort" data-order="nm.notification_id" data-order-dir="<?php echo $order_type_selected?>">ID</a></th>
                <th class="yellow header"><a href="javascript:void(0)" class="sort" data-order="m1.first_name" data-order-dir="<?php echo $order_type_selected?>">From user</a></th>
                <th class="yellow header"><a href="javascript:void(0)" class="sort" data-order="m2.first_name" data-order-dir="<?php echo $order_type_selected?>">To user</a></th>
                <th class="red header"><a href="javascript:void(0)" class="sort" data-order="nm.message" data-order-dir="<?php echo $order_type_selected?>">Message</a></th>
                <th class="red header"><a href="javascript:void(0)" class="sort" data-order="nm.created_at" data-order-dir="<?php echo $order_type_selected?>">Date</a></th>


              </tr>
            </thead>
            <tbody>
              <?php
                  //print_r($notificatins);exit;
              foreach($notificatins as $row)
              {
                  if($row['message_datetime']!='')
                      $date = date('d/m/Y h:i A',strtotime($row['message_datetime']));
                  else
                      $date='';
                echo '<tr>';
                echo '<td>'.$row['notification_id'].'</td>';
                echo '<td>'.$row['from_user_first_name'].' '.$row['from_user_last_name'].'</td>';
                  echo '<td>'.$row['to_user_first_name'].' '.$row['to_user_last_name'].'</td>';
                echo '<td>'.$row['message'].'</td>';
                echo '<td>'.$date.'</td>';
                echo '</tr>';
              }
              ?>      
            </tbody>
          </table>

          <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>

      </div>
    </div>
 <script>
function showmodal(url)
{
    $.ajax({
           url : url,
           type: "POST",
           data : '',
           success:function(data, textStatus, jqXHR)
           {
           //alert(data);
           $('#modalview .modal-content').html(data);
           $('#modalview').modal();
           
           },
           error: function(jqXHR, textStatus, errorThrown)
           {
           alert("error"+textStatus);
           }
           });

    
}
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
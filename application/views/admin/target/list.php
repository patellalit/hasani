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
                $options_searchin = array(''=>'Select','customer_name'=>'Customer Name','user_name'=>'User Name');
                
                
                
                if($date_end!='')
                $end = date('d-m-Y',strtotime($date_end));
                else
                $end='';
            $attributes = array('class' => 'form-inline reset-margin', 'id' => 'myform','method'=>'GET');
            echo form_open('admin/target', $attributes);
     
              
                
                echo form_label('From:', 'date_start');
                echo form_input('date_start', date('d-m-Y',strtotime($date_start)), 'style="width: 170px;height: 26px;" id="date_start"');
                
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
                <th class="red header"><a href="javascript:void(0)" class="sort" data-order="m.first_name" data-order-dir="<?php echo $order_type_selected?>">User name</a></th>
                <th class="red header"><a href="javascript:void(0)" class="sort" data-order="p.customerName" data-order-dir="<?php echo $order_type_selected?>">Customer name</a></th>
                <th class="red header"><a href="javascript:void(0)" class="sort" data-order="t.modified_at" data-order-dir="<?php echo $order_type_selected?>">Date</a></th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($target as $row)
              {
                echo '<tr>';
                  echo '<td>'.$row['id'].'</td>';
                echo '<td>'.$row['user_name'].'</td>';
                echo '<td>'.$row['customer_name'].'</td>';
                  echo '<td>'.date('d-m-Y',strtotime($row['modified_at'])).'</td>';
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
<div class="modal fade" id="modalview1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">


</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<div id="modalview" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            
        </div>
    </div>
</div>
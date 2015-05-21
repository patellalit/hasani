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
                $options_searchin = array(''=>'Select','customer_name'=>'Customer Name','user_name'=>'User Name','trainee_name'=>'Trainee Name','trainee_mobile'=>'Trainee Mobile');
                
                
                
                if($date_end!='')
                $end = date('d-m-Y',strtotime($date_end));
                else
                $end='';
            $attributes = array('class' => 'form-inline reset-margin', 'id' => 'myform','method'=>'GET');
            echo form_open('admin/trainee', $attributes);
     
              
                
                echo form_label('Search:', 'search_string');
                echo form_input('search_string', $search_string_selected, 'style="width: 100px;height: 26px;"');
                
                echo form_label('In:', 'search_in');
                echo form_dropdown('search_in',$options_searchin,$searchin,'id="search_in" style="width:100px"');
                
                
                

              $data_submit = array('name' => 'mysubmit', 'class' => 'btn btn-primary', 'value' => 'Go');
              
              echo '<input type="hidden" id="sort_order" name="order" value="'.$order.'" />';
              echo '<input type="hidden" id="sort_order_type" name="order_type" value="'.$order_type_selected.'" />';
                echo '<input type="hidden" id="pagingval" name="pagingval" value="'.$pagingval.'" />';
              echo form_submit($data_submit);

            echo form_close();
                
                        ?>

          </div>

          <table class="table table-striped table-bordered table-condensed">
            <thead>
              <tr>
                <th class="header">#</th>
                <th class="red header"><a href="javascript:void(0)" class="sort" data-order="m.first_name" data-order-dir="<?php echo $order_type_selected?>">User name</a></th>
                <th class="red header"><a href="javascript:void(0)" class="sort" data-order="c.customer_name" data-order-dir="<?php echo $order_type_selected?>">Customer name</a></th>
                <th class="red header"><a href="javascript:void(0)" class="sort" data-order="trainee.trainee_name" data-order-dir="<?php echo $order_type_selected?>">Trainee Name</a></th>
                <th class="red header"><a href="javascript:void(0)" class="sort" data-order="trainee.trainee_mobile" data-order-dir="<?php echo $order_type_selected?>">Trainee Mobile</a></th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($trainee as $row)
              {
                echo '<tr>';
                  echo '<td>'.$row['id'].'</td>';
                echo '<td>'.$row['user_name'].'</td>';
                echo '<td>'.$row['customer_name'].'</td>';
                  echo '<td>'.$row['trainee_name'].'</td>';
                echo '<td>'.$row['trainee_mobile'].'</td>';
                echo '</tr>';
              }
              ?>      
            </tbody>
          </table>
            <div class="pagination">
                <div style="width:50%;float:left;text-align:left">
                    <select id="pagingoption" name="pagingoption" onchange="submitpaging(this.value)">
                    <?php
                        for($i=0;$i<count($pagingoption);$i++)
                        {
                            $selected = $pagingoption[$i]==$pagingval?"selected='selected'":"";
                            echo '<option '.$selected.' value="'.$pagingoption[$i].'">'.$pagingoption[$i].'</option>';
                        }
                        ?>
                    </select>
                </div>
                <div style="width:50%;float:right;text-align:right">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
            </div>
      </div>
    </div>
 <script>
function submitpaging(val)
{
    $('#pagingval').val(val);
    $('#myform').submit();
}
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
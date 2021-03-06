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
        </h2>
      </div>
      
      <div class="row">
        <div class="span12 columns">
          <div class="well">
           
            <?php
            //save the columns names in a array that we will use as filter         
            $options_users = array(
				"all"=>"ALL",
				"l.cdkey"=>"CDKEY",
				"p.customerName"=>"Customer Name",
				"p.phoneNo"=>"Phone Number",
				"p.modelNo"=>"Model No",
				"p.modelName"=>"Model Name",
				"p.imeiNo"=>"IMEI No",
				"p.billNo"=>"Bill No",
				"p.purchaseDate"=>"Purchase Date",
				"p.billAmount"=>"Bill Amount",
				"p.dealerName"=>"Dealer Name",
				"p.customerAddress"=>"Customer Address",
				"p.state"=>"State",
				"p.city"=>"City",
				"p.area"=>"Area",
				"pp.plan_name"=>"Plan",
				"p.imeiNo2"=>"IMEI No2",
				"p.planDate"=>"Plan Date",
			);
                
            $options_plans = array(''=>'Select plans');
            foreach($plans as $plan)
            {
                $options_plans[$plan['id']] = $plan['plan_name'];
            }
            
            $attributes = array('class' => 'form-inline reset-margin','method'=>'GET', 'id' => 'myform',"style"=>"float:left;");
            echo form_open('admin/registered/users', $attributes);

              echo form_label('From Date:', 'search_date');
              echo form_input('search_from_date', $search_from_date_selected, 'style="width: 170px;
height: 26px;" id="search_from_date" readonly="readonly"');

echo form_label('To Date:', 'search_date');
              echo form_input('search_to_date', $search_to_date_selected, 'style="width: 170px;
height: 26px;" id="search_to_date" readonly="readonly"');

			  echo form_label('Search:', 'search_string');
              echo form_input('search_string', $search_string_selected, 'style="width: 170px;
height: 26px;"');

              echo form_label('In:', 'search_in');
              echo form_dropdown('search_in', $options_users, $search_in_selected, 'class="span2"');
              echo "<br><br>";
              echo form_label('Plan', 'selected_plan');
              echo form_dropdown('selected_plan', $options_plans, $selected_plan, 'class="span2"');
              $data_submit = array('name' => 'mysubmit', 'class' => 'btn btn-primary', 'value' => 'Go');

//              $options_order_type = array('Asc' => 'Asc', 'Desc' => 'Desc');
 //             echo form_dropdown('order_type', $options_order_type, $order_type_selected, 'class="span1"');
			  echo '<input type="hidden" id="sort_order" name="order" value="'.$order.'" />';
			  echo '<input type="hidden" id="sort_order_type" name="order_type" value="'.$order_type_selected.'" />';
            echo '<input type="hidden" id="pagingval" name="pagingval" value="'.$pagingval.'" />';
              echo form_submit($data_submit);
            echo '&nbsp;<input type="button" id="getcsv" name="getcsv" value="Generate CSV" onclick="generatecsv(\''.base_url('admin/users/registered_user_list_csv').'\')" class="btn btn-primary" onclick="" />';
            echo form_close();
            ?>
<table class="table table-striped table-bordered table-condensed filter-total">
<tr>
<th>&nbsp;</th>
<th>Filter</th>
<th>Today</th>
<th><?php echo date("d-m-Y",strtotime($search_date_1))?></th>
<th><?php echo date("d-m-Y",strtotime($search_date_2))?></th>
<th><?php echo date("d-m-Y",strtotime($search_date_3))?></th>
</tr>
<tr>
<th align="right">Registered:</th>
<td ><?php echo $count_users?></td>
<td ><?php echo $count_users_0?></td>
<td><?php echo $count_users_1?></td>
<td><?php echo $count_users_2?></td>
<td><?php echo $count_users_3?></td>
</tr>
<tr>
<th align="right">Amount:</th>
<td><?php echo $total_price?></td>
<td><?php echo $total_price_0?></td>
<td><?php echo $total_price_1?></td>
<td><?php echo $total_price_2?></td>
<td><?php echo $total_price_3?></td>
</tr>
<tr>
<th align="right">Handset Amount:</th>
<td><?php echo $total_bill?></td>
<td><?php echo $total_bill_0?></td>
<td><?php echo $total_bill_1?></td>
<td><?php echo $total_bill_2?></td>
<td><?php echo $total_bill_3?></td>
</tr>
</table>
<div class="clear"></div>
          </div>



		
          <table class="table table-striped table-bordered table-condensed">
            <thead>
              <tr>
                <th class="header">#</th>

				<th class="yellow header headerSortDown"><a href="javascript:void(0)" class="sort" data-order="l.cdkey" data-order-dir="<?php echo $order_type_selected?>">CDKEY</a></th>
				<th class="green header"><a href="javascript:void(0)" class="sort" data-order="p.customerName" data-order-dir="<?php echo $order_type_selected?>">Customer Name</a></th>
                <th class="green header"><a href="javascript:void(0)" class="sort" data-order="p.phoneNo" data-order-dir="<?php echo $order_type_selected?>">Phone Number</a></th>
                <th class="red header"><a href="javascript:void(0)" class="sort" data-order="p.modelNo" data-order-dir="<?php echo $order_type_selected?>">Model No</a></th>
                <th class="red header"><a href="javascript:void(0)" class="sort" data-order="p.modelName" data-order-dir="<?php echo $order_type_selected?>">Model Name</a></th>
				<th class="red header"><a href="javascript:void(0)" class="sort" data-order="p.imeiNo" data-order-dir="<?php echo $order_type_selected?>">IMEI No</a></th>
				<th class="red header"><a href="javascript:void(0)" class="sort" data-order="p.billNo" data-order-dir="<?php echo $order_type_selected?>">Bill No</a></th>
				<th class="red header"><a href="javascript:void(0)" class="sort" data-order="p.purchaseDate" data-order-dir="<?php echo $order_type_selected?>">Purchase Date</a></th>
				<th class="red header"><a href="javascript:void(0)" class="sort" data-order="p.billAmount" data-order-dir="<?php echo $order_type_selected?>">Bill Amount</a></th>
				<th class="red header"><a href="javascript:void(0)" class="sort" data-order="p.dealerName" data-order-dir="<?php echo $order_type_selected?>">Dealer Name</a></th>
				<th class="red header"><a href="javascript:void(0)" class="sort" data-order="p.customerAddress" data-order-dir="<?php echo $order_type_selected?>">Customer Address</a></th>
				<th class="red header"><a href="javascript:void(0)" class="sort" data-order="p.state" data-order-dir="<?php echo $order_type_selected?>">State</a></th>
				<th class="red header"><a href="javascript:void(0)" class="sort" data-order="p.city" data-order-dir="<?php echo $order_type_selected?>">City</a></th>
				<th class="red header"><a href="javascript:void(0)" class="sort" data-order="p.area" data-order-dir="<?php echo $order_type_selected?>">Area</a></th>
				<th class="red header"><a href="javascript:void(0)" class="sort" data-order="pp.plan_name" data-order-dir="<?php echo $order_type_selected?>">Plan</a></th>
				<th class="red header"><a href="javascript:void(0)" class="sort" data-order="p.imeiNo2" data-order-dir="<?php echo $order_type_selected?>">IMEI No2</a></th>
				<th class="red header"><a href="javascript:void(0)" class="sort" data-order="p.planDate" data-order-dir="<?php echo $order_type_selected?>">Plan Date</a></th>
				<th class="red header">PDF<br>Generate</th>
<th class="red header">Edit</th>
              </tr>
            </thead>
            <tbody>
              <?php
				$index = 1;
              foreach($users as $row)
              {
                echo '<tr>';
                echo '<td>'.$index.'</td>';
				echo '<td>'.$row['cdkey'].'</td>';
				echo '<td>'.$row['customerName'].'</td>';
				echo '<td>'.$row['phoneNo'].'</td>';
                echo '<td>'.$row['modelNo'].'</td>';
                echo '<td>'.$row['modelName'].'</td>';
				echo '<td>'.$row['imeiNo'].'</td>';
				echo '<td>'.$row['billNo'].'</td>';
                echo '<td>'.$row['purchaseDate'].'</td>';
                echo '<td>'.$row['billAmount'].'</td>';
				echo '<td>'.$row['dealerName'].'</td>';
				echo '<td>'.$row['customerAddress'].'</td>';
                echo '<td>'.$row['state'].'</td>';
                echo '<td>'.$row['city'].'</td>';
				echo '<td>'.$row['area'].'</td>';
				echo '<td>'.$row['package_name']." ".$row['plan_name'].'</td>';
                echo '<td>'.$row['imeiNo2'].'</td>';
                echo '<td>'.$row['planDate'].'</td>';
                echo '<td><a href="javascript:void(0)" onclick="generatePdf('.$row['registraion_id'].');">PDF</a></td>';
                echo '<td><a href="'.base_url('admin/registered/users/edit/'.$row['prid']).'">Edit</a></td>';
	
                echo '</tr>';
				$index++;
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
          <?php echo ''.$this->pagination->create_links().''; ?>
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
function generatecsv(url){
    $.ajax({
           type: "GET",
           url: url,
           data: $('#myform').serialize(),
           success: function(data){
           //alert('<?php echo base_url() ?>'+data);
            window.location.href='<?php echo base_url() ?>'+data;
                //alert(data);
           },
           error: function(xhr, desc, err){
                alert('err'+desc);
           },
        
           
           });
}
$(function() {
  
$( "#search_from_date" ).datepicker({
			changeMonth: true,
                        "dateFormat": "dd-mm-yy",
			onClose: function( selectedDate ) {
				$( "#search_to_date" ).datepicker( "option", "minDate", selectedDate );
			}
		});
		$( "#search_to_date" ).datepicker({
			changeMonth: true,
                        "dateFormat": "dd-mm-yy",
			onClose: function( selectedDate ) {
				//$( "#search_from_date" ).datepicker( "option", "maxDate", selectedDate );
			}
		});
$(".sort").click(function(){
	var sort_dir = $(this).attr("data-order-dir");
	sort_dir = (sort_dir == "Asc")?"Desc":"Asc";
	$("#sort_order_type").val(sort_dir);
	$("#sort_order").val($(this).attr("data-order"));
	$("#myform").submit();
});
});
function generatePdf(Id){
	if(confirm("Are you sure want to regenerate PDF?")){
		$.get('<?php echo site_url("admin")?>/generate-pdf/'+Id,function(data){
			alert(data);
		});
	}
}
</script>
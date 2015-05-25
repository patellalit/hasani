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
            //save the columns names in a array that we will use as filter         
            $options_users = array(
				"all"=>"ALL",
				"m.first_name"=>"First Name",
				"m.last_name"=>"Last Name",
				"m.mobile"=>"Company Phone",
				"m.email_address"=>"Company Email",
				"m.personal_phone"=>"Personal Phone",
				"m.personal_email"=>"Personal Email",
				"m.address"=>"Address",
				"r.role_name"=>"Role",
				/*"m.ol_name"=>"O/L Name",
				"m.ol_area"=>"O/L Area",*/
			);
            
            $attributes = array('class' => 'form-inline reset-margin', 'id' => 'myform','method'=>'GET');
            echo form_open('admin/users', $attributes);
     
              echo form_label('Search:', 'search_string');
              echo form_input('search_string', $search_string_selected, 'style="width: 170px;
height: 26px;"');

              echo form_label('Search In:', 'search_in');
              echo form_dropdown('search_in', $options_users, $search_in_selected, 'class="span2"');

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
				<th class="yellow header"><a href="javascript:void(0)" class="sort" data-order="m.first_name" data-order-dir="<?php echo $order_type_selected?>">First Name</a></th>
				<th class="yellow header"><a href="javascript:void(0)" class="sort" data-order="m.last_name" data-order-dir="<?php echo $order_type_selected?>">Last Name</a></th>                
				<th class="green header"><a href="javascript:void(0)" class="sort" data-order="m.mobile" data-order-dir="<?php echo $order_type_selected?>">Company Phone</a></th>
                <th class="green header"><a href="javascript:void(0)" class="sort" data-order="m.email_address" data-order-dir="<?php echo $order_type_selected?>">Company Email</a></th>
				<th class="green header"><a href="javascript:void(0)" class="sort" data-order="m.personal_phone" data-order-dir="<?php echo $order_type_selected?>">Personal Phone</a></th>
                <th class="green header"><a href="javascript:void(0)" class="sort" data-order="m.personal_email" data-order-dir="<?php echo $order_type_selected?>">Personal Email</a></th>
                <th class="red header"><a href="javascript:void(0)" class="sort" data-order="m.address" data-order-dir="<?php echo $order_type_selected?>">Address</a></th>
                <th class="red header"><a href="javascript:void(0)" class="sort" data-order="r.role_name" data-order-dir="<?php echo $order_type_selected?>">Role</a></th>
                <th class="red header">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($users as $row)
              {
                echo '<tr>';
                echo '<td>'.$row['id'].'</td>';
				echo '<td>'.$row['first_name'].'</td>';
				echo '<td>'.$row['last_name'].'</td>';
				echo '<td>'.$row['mobile'].'</td>';
                echo '<td>'.$row['email_address'].'</td>';
                echo '<td>'.$row['personal_phone'].'</td>';
                echo '<td>'.$row['personal_email'].'</td>';
                echo '<td>'.$row['address'].'</td>';
                echo '<td>'.$row['role_name'].'</td>';
                echo '<td class="crud-actions">
                  <a href="'.site_url("admin").'/users/update/'.$row['id'].'" class="btn btn-info">view & edit</a>  
                  <a href="'.site_url("admin").'/users/delete/'.$row['id'].'" class="btn btn-danger" onclick="return confirmDelete(this);">delete</a>
                </td>';
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
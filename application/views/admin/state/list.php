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
            echo form_open('admin/state', $attributes);
     
              echo form_label('Search:', 'search_string');
              echo form_input('search_string', $search_string_selected, 'style="width: 170px;height: 26px;"');

              $data_submit = array('name' => 'mysubmit', 'class' => 'btn btn-primary', 'value' => 'Go');
              
              echo '<input type="hidden" id="sort_order" name="order" value="'.$order.'" />';
              echo '<input type="hidden" id="sort_order_type" name="order_type" value="'.$order_type_selected.'" />';
                echo '<input type="hidden" id="pagingval" name="pagingval" value="'.$pagingval.'" />';
              echo form_submit($data_submit);
echo '&nbsp;<input type="button" id="getcsv" name="getcsv" value="Generate CSV" onclick="generatecsv(\''.base_url('admin/state/state_csv').'\')" class="btn btn-primary" onclick="" />';
            echo form_close();
            ?>

          </div>

          <table class="table table-striped table-bordered table-condensed">
            <thead>
              <tr>
                <th class="header">#</th>
				<th class="yellow header">State</th>
                <th class="yellow header">Country</th>
                <th class="red header">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($state as $row)
              {
                echo '<tr>';
                echo '<td>'.$row['id'].'</td>';
                echo '<td>'.$row['name'].'</td>';
				echo '<td>'.$row['country_name'].'</td>';
				echo '<td class="crud-actions">
                  <a href="'.site_url("admin").'/state/update/'.$row['id'].'" class="btn btn-info">view & edit</a>
                  <a href="'.site_url("admin").'/state/delete/'.$row['id'].'" class="btn btn-danger" onclick="return confirmDelete(this);">delete</a>
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
                    <?php echo $this->pagination->create_links(); ?>
                </div>
            </div>

      </div>
    </div>
 <script>
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
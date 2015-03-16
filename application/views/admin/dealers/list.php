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
           $options_searchin = array(''=>'Select','c.customer_name'=>'Customer Name','c.ol_name'=>'O/L Name','c.ol_address'=>'O/L Address','city.name'=>'O/L City','c.mobile'=>'Mobile','c.email'=>'Email','c.cst_number'=>'CST Number','c.cst_date'=>'CST Date','c.gst_number'=>'GST Number','c.gst_date'=>'GST Date');
                //$searchin='';
            $attributes = array('class' => 'form-inline reset-margin', 'id' => 'myform','method'=>'GET');
           
            //save the columns names in a array that we will use as filter
                //echo "<pre>";
                //print_r($dealers);exit;
            $options_users = array();    
            foreach ($dealers as $array) {
              foreach ($array as $key => $value) {
                $options_users[$key] = $key;
              }
              break;
            }

            echo form_open('admin/dealers', $attributes);
     
              echo form_label('Search:', 'search_string');
              echo form_input('search_string', $search_string_selected, 'style="width: 170px;
height: 26px;"');
                              
              echo form_label('In:', 'search_in');
              echo form_dropdown('search_in',$options_searchin,$search_in,'id="search_in" style="width:100px"');

              echo form_label('Order by:', 'order');
              echo form_dropdown('order', $options_users, $order, 'class="span2"');

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
				<th class="yellow header headerSortDown">Customer Name</th>                
				<th class="green header">O/L Name</th>
                <th class="green header">O/L Address</th>
                <th class="red header">O/L City</th>
                <th class="red header">Mobile</th>
                <th class="red header">Email</th>
                
                <th class="green header">CST Number</th>
                <th class="red header">CST Date</th>
                <th class="red header">GST Number</th>
                <th class="red header">GST Date</th>
                
                <th class="red header">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach($dealers as $row)
              {
                echo '<tr>';
                echo '<td>'.$row['id'].'</td>';
				echo '<td>'.$row['customer_name'].'</td>';
				echo '<td>'.$row['ol_name'].'</td>';
                echo '<td>'.$row['ol_address'].'</td>';
				echo '<td>'.$row['city_name'].'</td>';
				echo '<td>'.$row['mobile'].'</td>';
				echo '<td>'.$row['email'].'</td>';
                echo '<td>'.$row['cst_number'].'</td>';
				echo '<td>'.$row['cst_date'].'</td>';
				echo '<td>'.$row['gst_number'].'</td>';
				echo '<td>'.$row['gst_date'].'</td>';
                echo '<td class="crud-actions">
                  <a href="'.site_url("admin").'/dealers/update/'.$row['id'].'" class="btn btn-info">view & edit</a>  
                  <a href="'.site_url("admin").'/dealers/delete/'.$row['id'].'" class="btn btn-danger">delete</a>
                </td>';
                echo '</tr>';
              }
              ?>      
            </tbody>
          </table>

          <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>

      </div>
    </div>

                            
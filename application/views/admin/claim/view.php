<div class="modal-header">Claim detail</div>
<div class="modal-body">
<div class="">
<?php
    $statusarray=array(1 => "Pending",
                       2 => "Pickup",
                       3 => "Submit to Service Center",
                       4 => "Pickup from Service Center",
                       5 => "Submit to Customer");
    ?>
      <div class="row">
        <div class="span12 columns">
          <table class="table table-striped table-bordered table-condensed">
            <thead>
              <tr>

                <th class="yellow header">status</th>
                <th class="yellow header">date</th>
                <th class="red header">remarks</th>
                <th class="red header">user name</th>

              </tr>
            </thead>
            <tbody>
              <?php
              foreach($claim as $row)
              {
                echo '<tr>';
                echo '<td>'.$statusarray[$row['status']].'</td>';
                echo '<td>'.date('d/m/Y',strtotime($row['modified_at'])).'</td>';
                echo '<td>'.$row['remarks'].'</td>';
                echo '<td>'.$row['user_name'].'</td>';
                echo '</tr>';
              }
              ?>      
            </tbody>
          </table>

      </div>
    </div>
</div>
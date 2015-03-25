<div class="modal-header">Claim detail</div>
<div class="modal-body">
<div class="">
<?php
    
    ?>
      <div class="row">
        <div class="span12 columns">
          <table class="table table-striped table-bordered table-condensed">
            <thead>
              <tr>

                <th class="yellow header">Product id</th>
                <th class="yellow header">Item</th>
                <th class="red header">Package name</th>
                <th class="red header">Qty</th>
                <th class="red header">Price</th>
                <th class="red header">Total</th>


              </tr>
            </thead>
            <tbody>
              <?php
                  $finaltotal=0;
              foreach($dsr[0]['products'] as $row)
              {
                  $finaltotal = $finaltotal + ($row['qty']*$row['price']);
                echo '<tr>';
                echo '<td>'.$row['product_id'].'</td>';
                echo '<td>'.$row['item'].'</td>';
                echo '<td>'.$row['package_name'].'</td>';
                echo '<td>'.$row['qty'].'</td>';
                echo '<td>'.$row['price'].'</td>';
                echo '<td>'.$row['qty']*$row['price'].'</td>';
                echo '</tr>';
              }
              ?>
<tr><td colspan="5" style="text-align:right">Total</td><td><?php echo $finaltotal; ?></td></tr>
            </tbody>
          </table>

      </div>
    </div>
</div>
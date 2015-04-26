

<link href="<?php echo base_url(); ?>assets/css/admin/dashboard.css" rel="stylesheet" type="text/css">

<link href="<?php echo base_url(); ?>assets/css/admin/dashboard_daily.css" rel="stylesheet" type="text/css">
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
       

          
 <div id="testdiv1" style="position:absolute;visibility:hidden;background-color:white;layer-background-color:white; z-index: 1;"></div>
 

   <div id="content">
     
 
 	<div class="whiteCnt">
    <div class="blueHd"><!--ISD Activation Report<span>Date: 30/03/2015  to  30/03/2015	--></span></div>
	   <table border="0" cellpadding="0" cellspacing="0" width="100%" class="tableLW" name="mainTable" id="mainTable">
	   <tbody> 
	   <tr>
	       <th>Region</th>
	       <th>Cluster</th>
	       <th>Dealer</th>
	       <th>ISD</th>
                <?php
                    $i=0;
                    foreach($plans as $plan)
                    {?>
                        <th><div class="verticalText">p<?php echo $plan['id'] ?></div></th>
                    <?php
                    }
                ?>


    
	  </tr>
     
   
		<!-- india total row  -->
		<tr class="inr">
	   
	       <td colspan="3">&nbsp;India Total</td>
	       <td>&nbsp;</td>				
            <?php
                
                foreach($plans as $plan)
                {
                ?>
                    <td><?php echo $plan['count'] ?></td>
                <?php
                }
                ?>




			<!-- <td>&nbsp;</td> -->
		</tr>

		<!-- region total row  -->
<?php
    $row=1;
    
    for($i=0;$i<count($allstates);$i++)
        {
            $regionid = $row;
        ?>
            <tr class="d11111" name="region0" id="<?php echo $regionid; ?>" onclick="toggle_visibility('regiontag','<?php echo $regionid; ?>', &#39;lnk1&#39;, &#39;coll1&#39;, &#39;exp1&#39;);">
                <td colspan="3">
                    &nbsp;<img id="exp1" src="<?php echo base_url(); ?>assets/img/admin/down.png" width="10px;">
                    <img id="coll1" src="<?php echo base_url(); ?>assets/img/admin/right.png" width="7px;" style="display: none;">
                    <input id="lnk1" type="hidden" value="[-]"><?php echo $allstates[$i]['state'] ?></td>
                    <td>&nbsp;</td>
                    <?php
                    foreach($plans as $plan)
                    {
                        ?>
                        <td><a target="_blank" href="<?php echo base_url('admin/registered/users').'?search_string='.$allstates[$i]['state'].'&search_in=p.state' ?>"><?php echo $allstates[$i]['allplans'][$plan['id']]['count']; ?></a></td>
                        <?php
                    }
        ?>
            </tr>
            <?php
                $row++;
                for($k=0;$k<count($allstates[$i]['allcities']);$k++)
                {
                    $cityid = $row;
                    ?>
                    <tr class="d1111" name="cluster0" expandregiontag="<?php echo $regionid; ?>" regiontag="<?php echo $regionid; ?>" id="<?php echo $cityid ?>" onclick="toggle_visibility('clustertag', '<?php echo $cityid; ?>', 'lnk2', 'coll2', 'exp2');">

                    <td>&nbsp;</td>
                    <td colspan="2">&nbsp;<img id="exp2" src="<?php echo base_url(); ?>assets/img/admin/down.png" width="10px;">
                        <img id="coll2" src="<?php echo base_url(); ?>assets/img/admin/right.png" width="7px;" style="display: none;">
                        <input id="lnk2" type="hidden" value="[-]"><?php echo $allstates[$i]['allcities'][$k]['city'] ?></td>
                    <td>&nbsp;</td>
                    <?php
                        foreach($plans as $plan)
                        {
                            ?>
                            <td><a  target="_blank" href="<?php echo base_url('admin/registered/users').'?search_string='.$allstates[$i]['allcities'][$k]['city'].'&search_in=p.city' ?>"><?php echo $allstates[$i]['allcities'][$k]['allplans'][$plan['id']]['count']; ?></a></td>
                    <?php
                        }
                        ?>
                    </tr>
                    <?php
                        $row++;
                        for($l=0;$l<count($allstates[$i]['allcities'][$k]['allareas']);$l++)
                        {
                            $areaid = $row;
                            ?>
                            <tr class="d111" name="location0" regiontag="<?php echo $regionid; ?>" expandclustertag="<?php echo $cityid ?>" clustertag="<?php echo $cityid ?>" id="<?php echo $areaid ?>" onclick="toggle_visibility('locationtag', '<?php echo $areaid ?>', 'lnk3', 'coll3', 'exp3');">

                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td colspan="2">
                                    &nbsp;<img id="exp3" src="<?php echo base_url(); ?>assets/img/admin/down.png" width="10px;">
                                    <img id="coll3" src="<?php echo base_url(); ?>assets/img/admin/right.png" width="7px;" style="display: none;">
                                    <input id="lnk3" type="hidden" value="[-]"><?php echo $allstates[$i]['allcities'][$k]['allareas'][$l]['dealerName'] ?></td>
                                    <?php
                                        foreach($plans as $plan)
                                        {
                                            ?>
                                    <td><a target="_blank" href="<?php echo base_url('admin/registered/users').'?search_string='.$allstates[$i]['allcities'][$k]['allareas'][$l]['dealerName'].'&search_in=p.dealerName' ?>"><?php echo $allstates[$i]['allcities'][$k]['allareas'][$l]['allplans'][$plan['id']]['count']; ?></a></td>
                                    <?php
                                        }
                                        ?>
                                </tr>
                            <?php
                                $row++;
                        }
                    ?>
                    <?php
                        $row++;
                }
            ?>

        <?php
            $row++;
        }
?>

							
	   </tbody>
    </table>
    <div class="h25"></div>
      <div class="tblbot">
      <div class="lft">
</div>

  </div>
	<div class="pagin">
   
    </div>
    <div class="clr"></div>
</div>
    </div>
          <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>

      </div>
    </div>
 <script>
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
<script>
function toggle_visibility(tagName, tagId, linkId, colImgId, expImgId) {
    
    //alert("calling toggle_visibility...");
    //alert("rowNames: " +rowNames);
    //alert("rowNames, length: " +rowNames.length);
    
    if(document.getElementById(linkId).value == "[-]"){
        
        //alert("collapsing...");
        //hide rows....
        var rowNames=$("tr["+tagName+"="+tagId+"]");
        
        for(var j=0; j<rowNames.length; j++) {
            var rowId = rowNames[j].id;
            //alert("rowId: " +rowId);
            document.getElementById(rowId).style.display ="none";
            
            if(document.getElementById("coll" +rowId) !=null) {
                
                document.getElementById("coll" +rowId).style.display ="";
            }
            if(document.getElementById("exp" +rowId) !=null) {
                
                document.getElementById("exp" +rowId).style.display ="none";
            }
            if(document.getElementById("lnk" +rowId) !=null) {
                
                document.getElementById("lnk" +rowId).value ="[+]";
            }
        }
        
        //hide - image and show + image...
        document.getElementById(colImgId).style.display ="";
        document.getElementById(expImgId).style.display ="none";
        
        //change input type value...
        document.getElementById(linkId).value ="[+]";
        
    } else {
        
        //alert("expanding...");
        //show rows...
        var rowNames=$("tr[expand"+tagName+"="+tagId+"]");
        
        for(var j=0; j<rowNames.length; j++) {
            var rowId = rowNames[j].id;
            //alert("rowId: " +rowId);
            document.getElementById(rowId).style.display ="";
            
            /*
             if(document.getElementById("coll" +rowId) !=null) {
             
             document.getElementById("coll" +rowId).style.display ="none";
             }
             if(document.getElementById("exp" +rowId) !=null) {
             
             document.getElementById("exp" +rowId).style.display ="";
             }
             if(document.getElementById("lnk" +rowId) !=null) {
             
             document.getElementById("lnk" +rowId).value ="[-]";
             }
             */
        }
        //show - image and hide + image...
        document.getElementById(colImgId).style.display ="none";
        document.getElementById(expImgId).style.display ="";
        
        //change input type value...
        document.getElementById(linkId).value ="[-]";
    }
}

</script>
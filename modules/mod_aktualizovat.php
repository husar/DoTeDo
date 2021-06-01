

<div class="page-content">
      
      <?php
    $file_name = "txt files/lastUpdate.txt";
    $handle = fopen($file_name, 'w');
        
    fwrite($handle,date("Y-m-d H:i:s"));
    fclose($handle);
        
    
$countCommand=0;
$countItem=0;
$invoices_to_script_command="";
$invoices_to_script_item="";
$query1="SELECT vyrobny_prikaz FROM kabelaze WHERE qty_r > 0";
$apply1=mysqli_query($connect,$query1);
while($result1=mysqli_fetch_array($apply1)){
    if($result1['vyrobny_prikaz']!=""){
	    $invoices_to_script_command.="'".$result1['vyrobny_prikaz']."',";
        $countCommand++;
    }
}
$query2="SELECT itemid FROM kabelaze_bj WHERE pocet > 0";
$apply2=mysqli_query($connect,$query2);
while($result2=mysqli_fetch_array($apply2)){
    if($result2['itemid']!=""){
        $invoices_to_script_item.="'".$result2['itemid']."',";
        $countItem++;
    }
}
    
$invoices_to_script_command=substr($invoices_to_script_command,0,strlen($invoices_to_script_command)-1);;
$invoices_to_script_item=substr($invoices_to_script_item,0,strlen($invoices_to_script_item)-1);;


?>
       
 <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-tasks" aria-hidden="true"></i>Aktualizujú sa údaje</div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="Zbaliť/Rozbaliť" title=""> </a>
                                            
                                        </div>
                                    </div>
                                    <div class="portlet-body"> <br><br>
<div id="progress"></div>
<div id="progressBJ"></div>									   
								
                                       
									
                                    </div>
                                </div>
						
 </div>
<script>
function ajax_post(x){
    // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    var url = "script.php";
    // Create some variables we need to send to our PHP file
	var emails=[<?php echo $invoices_to_script_command;?>];
	var count_emails=emails.length;
	
	var run_again="yes";
	var delay_php=1000;
	if (count_emails==x) {
    
	run_again="no";
	}
	var emails_param="";
	
	
	var post_data="find_prodid=<?php echo "_".date("dmY");?>&vyrobny_prikaz="+emails[x-1]+"&aktualizovat=1";
   
    hr.open("POST", url, true);
    // Set content type header information for sending url encoded variables in the request
    hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
			/*var text=document.getElementById("status").value;
			document.getElementById("status").value = text+return_data;*/
		if(run_again=="yes"){
			
			setTimeout(function(){
				ajax_post(x+1);
			}, delay_php);
		}
		if(run_again=="no"){
		document.getElementById("progress").innerHTML ="Výrobné príkazy: Dokončené &#9989;";	
        
		}
			
			
	    }
    }
    // Send the data to PHP now... and wait for response to update the status div
    hr.send(post_data); // Actually execute the request
    document.getElementById("progress").innerHTML = "Výrobné príkazy: "+x+"/"+count_emails+"<img src='images/progress_bar.gif'>";
}
   <?php 
    
    
    if ($countCommand>0){
        echo "ajax_post(1);";
    }
    
    ?>
    
    function ajax_items(x){
    // Create our XMLHttpRequest object
    var hr = new XMLHttpRequest();
    var url = "script.php";
    // Create some variables we need to send to our PHP file
	var emails=[<?php echo $invoices_to_script_item;?>];
        
	var count_emails=emails.length;
	var run_again="yes";
	var delay_php=1000;
	if (count_emails==x) {
    
	run_again="no";
	}
	var emails_param="";
	
	
	var post_data="find_itemid=<?php echo "_".date("dmY");?>&itemid="+emails[x-1]+"&aktualizovat=1";
    
    hr.open("POST", url, true);
    // Set content type header information for sending url encoded variables in the request
    hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    // Access the onreadystatechange event for the XMLHttpRequest object
    hr.onreadystatechange = function() {
	    if(hr.readyState == 4 && hr.status == 200) {
		    var return_data = hr.responseText;
            
			/*var text=document.getElementById("status").value;
			document.getElementById("status").value = text+return_data;*/
		if(run_again=="yes"){
			
			setTimeout(function(){
				ajax_items(x+1);
			}, delay_php);
		}
		if(run_again=="no"){
		document.getElementById("progressBJ").innerHTML ="Položky: Dokončené &#9989;";	
        
		}
			
			
	    }
    }
    // Send the data to PHP now... and wait for response to update the status div
    hr.send(post_data); // Actually execute the request
    document.getElementById("progressBJ").innerHTML = "Položky: "+x+"/"+count_emails+"<img src='images/progress_bar.gif'>";
}
    <?php
    
    if($countItem>0){
        echo "ajax_items(1);";
    }
    
    
    ?>
</script>
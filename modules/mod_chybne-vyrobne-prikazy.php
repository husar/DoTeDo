<?php 
deleteWrongCommand(); 
  
?>
     
<?php
$countCommand=0;
$countItem=0;
$invoices_to_script_command="";
$query="SELECT vyrobny_prikaz FROM kabelaze_chybne ";
$apply=mysqli_query($connect,$query);
while($result=mysqli_fetch_array($apply)){
    if($result['vyrobny_prikaz']!=""){
	    $invoices_to_script_command.="'".$result['vyrobny_prikaz']."',";
        $countCommand++;
    }
}
$invoices_to_script_command=substr($invoices_to_script_command,0,strlen($invoices_to_script_command)-1);
    
    
?>
      <div class="page-content">

      <div class="portlet box blue ">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-search"></i> Vyhľadávanie
											</div>
                                      
                                    </div>
                                    <div class="portlet-body form">
                                       <?php updateWrongCommand();   ?>
                                        <form class="form-horizontal" role="form" method="get" enctype="multipart/form-data" accept-charset="utf-8">
                                            <div class="form-body">
                                                <div class="form-group">
												     <input type="hidden" name="modul" value="chybne-vyrobne-prikazy">	
													
                                                    <div class="col-md-4">
                                                       <br>
                                                        <input class="form-control" type="text"  name="search_text" placeholder="Hľadaný výraz" value="<?php echo $_GET['search_text']; ?>">	
													</div>
                                                    <div class="col-md-2">
                                                    Vyhľadať podľa:
                                                    <select name="searchby" class="form-control">
                                                        
                                                        <option value="vyrobny_prikaz" <?php echo($_GET['searchby']=="vyrobny_prikaz"?"selected":"") ?>>Výrobný príkaz</option>
                                                        <option value="itemid" <?php echo($_GET['searchby']=="itemid"?"selected":"") ?>>Číslo položky</option>
                                                        
                                                    </select>
                                                    </div>
                                                </div>                                              
                                            </div>
                                            
                                                <div class="form-actions right1">
                                                   <button type="submit" class="btn blue">Vyhľadať</button>
                                                </div>
                                        </form>
                                    </div>
                                </div> 
                                <?php if(isset($_GET['update'])) {
                                
                                    $query="SELECT * FROM kabelaze_chybne WHERE id='".$_GET['update']."'";
                                    $apply_zaznamy=mysqli_query($connect,$query);
				                    $result=mysqli_fetch_array($apply_zaznamy);
                                    
    
                                ?>
                                
                                <div class="portlet box blue ">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-search"></i>Upraviť <?php echo ($result['vyrobny_prikaz']!=""?$result['vyrobny_prikaz']:$result['itemid']); ?>
											</div>
                                      
                                    </div>
                                    <div class="portlet-body form">
                                        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data" accept-charset="utf-8">
                                            <div class="form-body">
                                                <div class="form-group">
												     <input type="hidden" name="modul" value="chybne-vyrobne-prikazy">
												     <input type="hidden" name="os_cislo" value="<?php echo $result['vp_os_cislo'] ?>">	
													
                                                    <div class="col-md-4">
                                                       Výrobný príkaz:
                                                        <input class="form-control" type="text"  name="vp" value="<?php echo $result['vyrobny_prikaz']; ?>">	
													</div>
                                                   
                                                    <div class="col-md-2">
                                                    Dátum a čas pridania:
                                                     <input class="form-control" type="datetime" readonly="true" name="datum_cas" value="<?php echo $result['datum_cas']; ?>">
                                                    </div>
                                                    <div class="col-md-2">
                                                    Číslo položky:
                                                     <input class="form-control" type="text" name="itemid" value="<?php echo $result['itemid']; ?>">
                                                    </div>
                                                    <div class="col-md-2">
                                                    Počet:
                                                     <input class="form-control" type="text" name="qty" value="<?php echo $result['qty']; ?>">
                                                    </div>
                                                </div>                                              
                                            </div>
                                            
                                                <div class="form-actions right1">
                                                   <button type="submit" name="update_wrong_command" class="btn blue">Upraviť</button>
                                                   <a type="button" class="btn blue" href="index.php?modul=chybne-vyrobne-prikazy">Zrušiť</a>
                                                </div>
                                        </form>
                                    </div>
                                </div> 
                                <?php } ?>
       
 <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-tasks" aria-hidden="true"></i>Výrobné príkazy</div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="Zbaliť/Rozbaliť" title=""> </a>
                                            
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    <div class="portlet-body">
                                    <div id="progress"></div>
									   <div class="table-responsive tableFixHead">
                                            <table class="table table-bordered">
                                               <thead>
                                                    <tr>
														<th>ID</th>
												        <th>Výrobný príkaz</th>
												        <th>Os. číslo (Majster)</th>
												        <th>Dátum a čas pridania</th>
                                                        <th>Itemid</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
												<?php
                                                $page = (int) (!isset($parameter[1]) ? 1 : $parameter[1]);
                                                $limit = 100;
                                                $url="index.php?modul=chybne-vyrobne-prikazy";
                                                $startpoint = ($page * $limit) - $limit;
                                                $c=$connect;
                                                $find_by="";
                                                    
												$query_zaznamy="SELECT * FROM kabelaze_chybne ";
                                                if(isset($_GET['search_text']) && $_GET['search_text']!=""){
                                                    $query_zaznamy.="WHERE LOWER(".$_GET['searchby'].") LIKE LOWER('%".$_GET['search_text']."%') ";
                                                    $sqlPagination.="WHERE ".$_GET['searchby']." LIKE ('%".$_GET['search_text']."%') ";
                                                }
                                                $query_zaznamy.="ORDER BY datum_cas ";
                                                $query_zaznamy.="LIMIT $startpoint, $limit";
												$apply_zaznamy=mysqli_query($connect,$query_zaznamy);
												while($result_zaznamy=mysqli_fetch_array($apply_zaznamy)){
                                                    removeDuplicatesWrong($result_zaznamy['id'],$result_zaznamy['vyrobny_prikaz']);
                                                ?>
												<tr>
														<td> <?php echo $result_zaznamy['id']; ?></td>
														<td> <?php echo $result_zaznamy['vyrobny_prikaz']; ?></td>
														<td> <?php echo $result_zaznamy['vp_os_cislo']; ?></td>
														<td> <?php echo $result_zaznamy['datum_cas']; ?></td>
                                                        <td> <?php echo $result_zaznamy['itemid']; ?></td>
                                                        <td>
                                                        <form method="post"> 
                                                                
                                                                <input type="hidden" name="id" value="<?php echo $result_zaznamy['id'] ?>">
                                                                <button type="button" class="btn"  title="Vymazať" name="delete"   onclick="location.href='index.php?modul=chybne-vyrobne-prikazy&delete=<?php echo $result_zaznamy['id'] ?>';"><i class="fa fa-trash" ></i></button>
                                                                
                                                                <button type="button" class="btn"  title="Upraviť" name="update"   onclick="location.href='index.php?modul=chybne-vyrobne-prikazy&update=<?php echo $result_zaznamy['id']?>';"><i class="fa fa-edit" ></i></button>
                                                            </form>
                                                                </td>
                                                    </tr>
												<?php } ?>	
													
                                                </tbody>
                                            </table>
											<?php	echo "<center>".pagination("kabelaze_chybne ".$sqlPagination,$limit,$page,$url,$c)."</center>"; 
                                           ?>
															
                                        </div>
								
                                       
									
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
	
	
	var post_data="find_prodid=<?php echo "_".date("dmY");?>&vyrobny_prikaz="+emails[x-1]+"&aktualizovat=0";
   
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
        //location.replace("index.php?modul=vyrobne-prikazy");
		}
			
			
	    }
    }
    // Send the data to PHP now... and wait for response to update the status div
    hr.send(post_data); // Actually execute the request
    document.getElementById("progress").innerHTML = "Výrobné príkazy: "+x+"/"+count_emails+"<img src='images/progress_bar.gif'>";
    document.getElementById("answer").innerHTML = hr.responseText;
}

    <?php
    
    if($countCommand>0){
        echo "ajax_post(1);";
    }
    
    ?>
</script>
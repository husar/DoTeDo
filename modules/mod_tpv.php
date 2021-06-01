<div class="page-content">
<?php
  
$serverName = "dbserver"; 
$connectionInfo = array( "Database"=>"MKEM_AX2012R3_PROD", "UID"=>"production1", "PWD"=>"production1", "CharacterSet"  => 'UTF-8');
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if($conn){//echo "ok";
}
else{die( print_r( sqlsrv_errors(), true));}
    
    include "functions.php";
    
?>
<div class="portlet box blue ">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-search"></i> Vyhľadávanie
											</div>
                                      
                                    </div>
                                    <div class="portlet-body form">
                                        <form class="form-horizontal" role="form" method="get" enctype="multipart/form-data" accept-charset="utf-8">
                                            <div class="form-body">
                                                <div class="form-group">
												     <input type="hidden" name="modul" value="polozky">	
													
                                                    <div class="col-md-4">
                                                       <br>
                                                        <input class="form-control" type="text"  name="search_text" placeholder="Hľadaný výraz" value="<?php echo $_GET['search_text']; ?>">	
													</div>
                                                    <div class="col-md-2">
                                                    Vyhľadať podľa:
                                                    <select name="searchby" class="form-control">

                                                        <option value="prodid" <?php echo($_GET['searchby']=="prodid"?"selected":"") ?>>Výrobný príkaz</option>
                                                        <option value="eset" <?php echo($_GET['searchby']=="eset"?"selected":"") ?>>Číslo esady</option>
                                                        <option value="eset_name" <?php echo($_GET['searchby']=="eset_name"?"selected":"") ?>>Názov esady</option>
                                                        <option value="itemid" <?php echo($_GET['searchby']=="itemid"?"selected":"") ?>>Číslo položky</option>
                                                        <option value="itemid_name" <?php echo($_GET['searchby']=="itemid_name"?"selected":"") ?>>Názov položky</option>
                                                        <option value="cd" <?php echo($_GET['searchby']=="cd"?"selected":"") ?>>CD číslo</option>
                                                        
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
                 
 <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-tasks" aria-hidden="true"></i>Položky</div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="Zbaliť/Rozbaliť" title=""> </a>
                                            
                                        </div>
                                    </div>
                                    
                                    <div class="portlet-body">
									   <div class="table-responsive tableFixHead" id="your_content">
                                            <table class="table table-bordered">
                                               <thead>
                                                    <tr>
														<th >ID</th>
                                                        <th>Výrobný príkaz</th>
                                                        <th>Číslo esady</th>
                                                        <th>Názov esady</th>
                                                        <th>CD</th>
                                                        <th>Nálepka</th>
                                                        <th>Návod</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
												<?php
                                                $page = (int) (!isset($parameter[1]) ? 1 : $parameter[1]);
                                                $limit = 100;
                                                $url="?modul=tpv";
                                                $startpoint = ($page * $limit) - $limit;
                                                $c=$connect;
                                                $find_by="WHERE balenie='-1' AND stav_vyroby!=-2 ";
                                                
                                                if(isset($_GET['search_text']) || $_GET['search_text']!=""){
                                                    $find_by.=" AND LOWER(".$_GET['searchby'].") LIKE LOWER('%".$_GET['search_text']."%') ";
                                                }
												$query_zaznamy="SELECT * FROM polozky ".$find_by."ORDER BY ";
                                                
                                                    $query_zaznamy.="priorita DESC, dlvdate, stav_vyroby DESC ";
                                                    $sqlPagging=$find_by."ORDER BY priorita DESC, dlvdate, stav_vyroby DESC ";
                                                
                                                    
												
                                                $query_zaznamy.="LIMIT $startpoint, $limit";
												$apply_zaznamy=mysqli_query($connect,$query_zaznamy);
                                            
                                            while($result_zaznamy=mysqli_fetch_array($apply_zaznamy)){
                                                removeDuplicates($result_zaznamy['id'],$result_zaznamy['prodid'],$result_zaznamy['eset'],$result_zaznamy['itemid']);     
                                                  
                                                    $BJ=($result_zaznamy['BJ']==1?"font-weight:bold; ":" ");
                                                    switch ($result_zaznamy['stav_vyroby']) {
                                                        case 1:
                                                            $state_color="style=\"background: #fcbd4a; ".$BJ." \" title=\"Chýbajúce CD, alebo modul\"";
                                                            break;
                                                        case 0:
                                                            $state_color="style=\"background: #f57a7a; ".$BJ." \" title=\"Chýba CD a modul\"";
                                                            break;
                                                        case -1:
                                                            $state_color="style=\"background: #9999f4; ".$BJ." \" title=\"Zabalené/Ukončené\"";
                                                            break;
                                                        default:
                                                            $state_color="style=\"background: #76eb92; ".$BJ." \" title=\"Pripravené na balenie\"";
                                                            break;
                                                    }
                                                ?>
												<tr <?php echo $state_color; ?> >
														<td> <?php echo $result_zaznamy['id']; ?></td>
														<td> <?php echo $result_zaznamy['prodid']; ?></td>
														<td> <?php echo $result_zaznamy['eset']; ?></td>
                                                        <td> <?php echo $result_zaznamy['eset_name']; ?></td>
                                                        <td>
                                                            <a onclick="location.href='index.php?modul=polozky&search_text=<?php echo $result_zaznamy['prodid']; ?>&searchby=prodid';" ><?php echo $result_zaznamy['cd'];?>
                                                            </a> 
                                                            <?php  
                                                                if($result_zaznamy['cd']==""){
                                                                    echo "Neobsahuje CD";
                                                                }
                                                            ?>
                                                        </td>
                                                            <?php
                                                    
                                                                $query="SELECT type FROM history WHERE id=(SELECT MAX(id) FROM history WHERE artikel LIKE '".$result_zaznamy['eset']."')";
                                                                $apply_query=mysqli_query($nicelabel,$query);
                                                                $result_nalepka=mysqli_fetch_array($apply_query);
                                                            ?>
                                                        <td>
                                                            <?php 
                                                                
                                                                switch($result_nalepka['type']){
                                                                    case 1:
                                                                        echo "&#10067;";
                                                                        break;
                                                                    case 2:
                                                                        echo "&#9989;";
                                                                        break;
                                                                    default:
                                                                        echo "&#10060;";
                                                                        break;
                                                                }
                                                            
                                                            ?>
                                                        </td>
                                                        <?php
                                                    
                                                            $query_tab_1="SELECT PICTOGRAMMANUAL FROM dbo.PRODTABLE WHERE PRODID = '".$result_zaznamy['prodid']."' ";
												            $apply_tab_1=sqlsrv_query($conn,$query_tab_1);
                                                            $result_tab_1=sqlsrv_fetch_array($apply_tab_1);
                                                    
                                                        ?>
														<td><?php echo $result_tab_1['PICTOGRAMMANUAL']==1030?"&#9989;":"&#10060;" ?></td>
                                                    </tr>
                                                   
												<?php } ?>	
													
                                                </tbody>
                                            </table>
											<?php	echo "<center>".pagination("polozky ".$sqlPagging,$limit,$page,$url,$c)."</center>"; ?>
															
                                        </div>
                                       
									
                                    </div>
                                   
                                </div>
						
 </div>
 










 <script type="text/javascript">
 function printPage(){
        var tableData = '<table border="1" style="font-size:10px">'+document.getElementsByTagName('table')[0].innerHTML+'</table>';
        var data = '<button onclick="window.print()">Vytlačiť tabuľku</button>'+tableData;       
        myWindow=window.open('','','width=auto,height=auto');
        myWindow.innerWidth = screen.width;
        myWindow.innerHeight = screen.height;
        myWindow.screenX = 0;
        myWindow.screenY = 0;
        myWindow.document.write(data);
        myWindow.focus();
    };
 </script>
 

<div class="page-content">
<?php
  
    $serverName = "dbserver"; 
$connectionInfo = array( "Database"=>"MKEM_AX2012R3_PROD", "UID"=>"production1", "PWD"=>"production1", "CharacterSet"  => 'UTF-8');
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if($conn){//echo "ok";
}
else{die( print_r( sqlsrv_errors(), true));}
    
    include "functions.php";
    
    updateCommand();
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
												     <input type="hidden" name="modul" value="hotove">	
													
                                                    <div class="col-md-4">
                                                       <br>
                                                        <input class="form-control" type="text"  name="search_text" placeholder="Hľadaný výraz" value="<?php echo $_GET['search_text']; ?>">	
													</div>
                                                    <div class="col-md-2">
                                                    Vyhľadať podľa:
                                                    <select name="searchby" class="form-control">
                                                        
                                                        <?php if($_SESSION['prava']=="cd"){ ?>
                                                        <option value="cd" <?php echo($_GET['searchby']=="cd"?"selected":"") ?>>CD číslo</option>
                                                        <?php }if($_SESSION['prava']=="moduly"){ ?>
                                                        <option value="moduly" <?php echo($_GET['searchby']=="moduly"?"selected":"") ?>>Číslo modulu</option>
                                                        <?php } ?>
                                                        <option value="itemid" <?php echo($_GET['searchby']=="itemid"?"selected":"") ?>>Číslo položky</option>
                                                        <option value="itemid_name" <?php echo($_GET['searchby']=="itemid_name"?"selected":"") ?>>Názov položky</option>
                                                        <option value="eset" <?php echo($_GET['searchby']=="eset"?"selected":"") ?>>Číslo esady</option>
                                                        <option value="eset_name" <?php echo($_GET['searchby']=="eset_name"?"selected":"") ?>>Názov esady</option>
                                                        <option value="prodid" <?php echo($_GET['searchby']=="prodid"?"selected":"") ?>>Výrobný príkaz</option>
                                                        
                                                        
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
                                    <?php if($_SESSION['prava']=="balenie"){ ?>
                                    <div class="table-responsive tableFixHead">
                                            <table class="table table-bordered">
                                               <thead>
                                                    <tr >
														<th >ID</th>
												        <th>Priorita</th>
												        <th>Priorita (Os. č.)</th>
												        <th>Balenie</th>
                                                        <th>Posledná úprava balenie</th>
                                                        <th>Potvrdil (Os. č.)</th>
                                                        <th>Výrobný príkaz</th>
                                                        <th>Číslo esady</th>
                                                        <th>Názov esady</th>
                                                        <th>Počet ks</th>
                                                        <th>Dátum dodania</th>
                                                        <th>CD</th>
                                                        <th>Posledná úprava CD</th>
                                                        <th>Moduly</th>	
                                                        <th>Posledná úprava moduly</th>
                                                        <th>Číslo položky</th>	
                                                        <th>Názov položky</th>
                                                        <th>Dátum vloženia</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
												<?php
                                                $page = (int) (!isset($parameter[1]) ? 1 : $parameter[1]);
                                                $limit = 100;
                                                $url="?modul=hotove";
                                                $startpoint = ($page * $limit) - $limit;
                                                $c=$connect;
                                                $find_by="";
                                                $find_by="WHERE balenie='1' AND balenie_updated >= NOW() - INTERVAL 3 DAY AND stav_vyroby!=-2 ";
                                                
                                                if(isset($_GET['search_text']) || $_GET['search_text']!=""){
                                                    $find_by.=" AND LOWER(".$_GET['searchby'].") LIKE LOWER('%".$_GET['search_text']."%') ";
                                                }
                                            
												$query_zaznamy="SELECT * FROM polozky ".$find_by."ORDER BY ";
                                                $query_zaznamy.=($_SESSION['prava']=="balenie")?"stav_vyroby DESC, dlvdate ":"dlvdate, stav_vyroby DESC ";
                                                    $query_zaznamy.="LIMIT $startpoint, $limit";
                                                    $sqlPagging=$find_by."ORDER BY ".(($_SESSION['prava']=="balenie")?"stav_vyroby DESC, dlvdate ":"dlvdate, stav_vyroby DESC ");
												$apply_zaznamy=mysqli_query($connect,$query_zaznamy);
												while($result_zaznamy=mysqli_fetch_array($apply_zaznamy)){
                                                    $BJ=($result_zaznamy['BJ']==1?"font-weight:bold; ":" ");
                                                    switch ($result_zaznamy['stav_vyroby']) {
                                                        case 1:
                                                            $state_color="style=\"background: #fcbd4a; ".$BJ." \" title=\"Chýbajúce CD, alebo modul\"";
                                                            break;
                                                        case 2:
                                                            $state_color="style=\"background: #76eb92; ".$BJ." \" title=\"Pripravené na balenie\"";
                                                            break;
                                                        case -1:
                                                            $state_color="style=\"background: #9999f4; ".$BJ." \" title=\"Zabalené/Ukončené\"";;
                                                            break;
                                                        default:
                                                            $state_color="style=\"background: #f57a7a; ".$BJ." \" title=\"Chýba CD a modul\"";;
                                                            break;
                                                    }
                                                ?>
												<tr <?php echo $state_color; ?> >
														<td> <?php echo $result_zaznamy['id']; ?></td>
														<td>
                                                        
                                                        <input type="checkbox" name="priorita" value="1" <?php echo ($result_zaznamy['priorita']==1)?"checked ":"";?><?php echo ($_SESSION['prava']=="priority")?"":"disabled=\"true\""; ?>   onchange='this.form.submit()'>
                                                        
                                                        </td>
                                                        <td> <?php echo $result_zaznamy['priorita_os_cislo']; ?></td>
                                                        <form method="post" id="sampleForm" class="sampleForm">
                                                           <input type="hidden" name="id_polozka" value="<?php echo $result_zaznamy['id'] ?>">                   <input type="hidden" name="balenie" value="0">
                                                           <input type="hidden" name="vp" value="<?php echo $result_zaznamy['prodid'] ?>">
                                                       
                                                           <td><input type="checkbox" name="balenie" value="1" <?php echo ($result_zaznamy['balenie']==1)?"checked ":"";?><?php echo ($_SESSION['prava']=="balenie")?"":"disabled=\"true\""; ?>  onchange='this.form.submit()'>
                                                            </td>
                                                            <td><?php echo $result_zaznamy['balenie_updated']; ?></td>
                                                            <td><?php echo $result_zaznamy['balenie_person_id']; ?></td>
                                                       
                                                            
                                                             
                                                                         
                                                        </form>
                                                        <td> <?php echo $result_zaznamy['prodid']; ?></td>
                                                        <td> <?php echo $result_zaznamy['eset']; ?></td>
                                                        <td> <?php echo $result_zaznamy['eset_name']; ?></td>
                                                        
                                                        <td> <?php echo $result_zaznamy['qty']; ?></td>
                                                        <td> <?php echo $result_zaznamy['dlvdate']; ?></td>
                                                        <td>
                                                            <?php 

                                                                echo $result_zaznamy['cd']; 

                                                                if($result_zaznamy['cd']!=""){

                                                            ?>
                                                            <input type="checkbox" name="cd" value="1" <?php echo ($result_zaznamy['cd_hotove']==1)?"checked ":"";?><?php echo ($_SESSION['prava']=="cd")?"":"disabled=\"true\""; ?>   onchange='this.form.submit()'>
                                                            <?php }else{
                                                                    echo "Neobsahuje CD";
                                                                } ?>
                                                            </td>
                                                            <td> <?php echo $result_zaznamy['cd_updated']; ?></td>
                                                        
                                                            <td>
                                                            <?php 

                                                                if($result_zaznamy['moduly']!=""){
                                                                if($_SESSION['prava']=="moduly"){?>
                                                                <a href="http://kontrolamodulov.mkem.sk/index.php?modul=prehlad&search=<?php echo $result_zaznamy['prodid']; ?>&search_option=2" target="_blank"><?php echo $result_zaznamy['moduly']; ?>
                                                                </a><?php }else{ echo $result_zaznamy['moduly']; } ?>
                                                            <input type="checkbox" name="moduly" value="1" <?php echo ($result_zaznamy['moduly_hotove']==1)?"checked ":"";?><?php echo ($_SESSION['prava']=="moduly")?"":"disabled=\"true\""; ?>  onchange='this.form.submit()'>
                                                            <?php }else{
                                                                    echo "Neobsahuje modul";
                                                                } ?>
                                                            </td>
                                                            <td> <?php echo $result_zaznamy['moduly_updated']; ?></td>
														<td> <?php echo $result_zaznamy['itemid'];; ?></td>
														<td> <?php echo $result_zaznamy['itemid_name']; ?></td>
														
                                                        <td> <?php echo $result_zaznamy['date_inserted']; ?></td>
                                                        
                                                        
                                                    </tr>
												<?php } ?>	
													
                                                </tbody>
                                            </table>
											<?php	echo "<center>".pagination("polozky ".$sqlPagging,$limit,$page,$url,$c)."</center>"; ?>
															
                                        </div>
                                        <?php }else{ ?>
									   <div class="table-responsive tableFixHead">
                                            <table class="table table-bordered">
                                               <thead>
                                                    <tr>
														<th >ID</th>
                                                       <?php if($_SESSION['prava']=="kontrola"){ ?>
                                                        <th>Číslo esady</th>
                                                        <th>Názov esady</th>
                                                        <th>Výrobný príkaz</th>
                                                        <th>Dátum vloženia</th>
                                                        <th>Dátum dodania</th>
                                                        <?php } ?>
												        <th>Priorita</th>
												        <th>Priorita (Os. č.)</th>
												        <?php if($_SESSION['prava']=="cd" || $_SESSION['prava']=="kontrola" || $_SESSION['prava']=="tpv"){ ?>
												        <th>CD</th>
                                                        <th>Posledná úprava CD</th>
                                                        <th>Potvrdil (Os. č.)</th>
                                                        <?php } 
                                                        if($_SESSION['prava']=="moduly" || $_SESSION['prava']=="kontrola"){ ?>
                                                        <th>Moduly</th>	
                                                        <th>Posledná úprava moduly</th>
                                                        <th>Potvrdil (Os. č.)</th>
                                                        <?php } 
                                                        if($_SESSION['prava']=="priority" && $_SESSION['prava']!="kontrola"){ 
                                                        ?>
                                                        <th>Číslo esady</th>
                                                        <th>Názov esady</th>
                                                        <?php } ?>
                                                        <th>Počet ks</th>
                                                        <?php  if($_SESSION['prava']!="kontrola"){?>
                                                        <th>Dátum dodania</th>
                                                        <?php } ?>
                                                        <th>Číslo položky</th>	
                                                        <th>Názov položky</th>
                                                        <?php if($_SESSION['prava']!="priority" && $_SESSION['prava']!="kontrola"){ ?>
                                                        <th>Číslo esady</th>
                                                        <th>Názov esady</th>
                                                        <?php } if($_SESSION['prava']!="kontrola"){?>
                                                        <th>Výrobný príkaz</th>
                                                        <th>Dátum vloženia</th>
                                                        <?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
												<?php
                                                $page = (int) (!isset($parameter[1]) ? 1 : $parameter[1]);
                                                $limit = 100;
                                                $url="?modul=hotove";
                                                $startpoint = ($page * $limit) - $limit;
                                                $c=$connect;
                                                $find_by="";
                                                    
                                                if($_SESSION['prava']=="cd"){
                                                    $find_by="WHERE cd !='' AND cd_hotove='1' AND stav_vyroby!=-2 AND cd_updated >= NOW() - INTERVAL 7 DAY ";
                                                }elseif($_SESSION['prava']=="moduly"){
                                                    $find_by="WHERE moduly !='' AND moduly_hotove='1' AND stav_vyroby!=-2 AND moduly_updated >= NOW() - INTERVAL 3 DAY ";
                                                }else{
                                                    $find_by="WHERE balenie='1' AND stav_vyroby!=-2 AND balenie_updated >= NOW() - INTERVAL 3 DAY ";
                                                }
                                                if(isset($_GET['search_text']) || $_GET['search_text']!=""){
                                                    $find_by.=" AND LOWER(".$_GET['searchby'].") LIKE LOWER('%".$_GET['search_text']."%') ";
                                                }
                                            
												$query_zaznamy="SELECT * FROM polozky ".$find_by."ORDER BY ";
                                                if($_SESSION['prava']=="cd"){
                                                    $query_zaznamy.="cd_updated DESC ";
                                                }elseif($_SESSION['prava']=="moduly"){
                                                    $query_zaznamy.="moduly_updated DESC ";
                                                }else{
                                                    $query_zaznamy.="dlvdate, stav_vyroby DESC ";
                                                }
                                                    $query_zaznamy.="LIMIT $startpoint, $limit";
                                                    $sqlPagging=$find_by."ORDER BY ".(($_SESSION['prava']=="balenie")?"stav_vyroby DESC, dlvdate ":"dlvdate, stav_vyroby DESC ");
												$apply_zaznamy=mysqli_query($connect,$query_zaznamy);
												while($result_zaznamy=mysqli_fetch_array($apply_zaznamy)){
                                                    $BJ=($result_zaznamy['BJ']==1?"font-weight:bold; ":" ");
                                                    switch ($result_zaznamy['stav_vyroby']) {
                                                        case 1:
                                                            $state_color="style=\"background: #fcbd4a; ".$BJ." \" title=\"Chýbajúce CD, alebo modul\"";
                                                            break;
                                                        case 2:
                                                            $state_color="style=\"background: #76eb92; ".$BJ." \" title=\"Pripravené na balenie\"";
                                                            break;
                                                        case -1:
                                                            $state_color="style=\"background: #9999f4; ".$BJ." \" title=\"Zabalené/Ukončené\"";;
                                                            break;
                                                        default:
                                                            $state_color="style=\"background: #f57a7a; ".$BJ." \" title=\"Chýba CD a modul\"";;
                                                            break;
                                                    }
                                                ?>
												<tr <?php echo $state_color; ?> >
														<td> <?php echo $result_zaznamy['id']; ?></td>
														<?php if($_SESSION['prava']=="kontrola"){ ?>
														    <td> <?php echo $result_zaznamy['eset']; ?></td>
                                                            <td> <?php echo $result_zaznamy['eset_name']; ?></td>
                                                            <td> <?php echo $result_zaznamy['prodid']; ?></td>
                                                            <td> <?php echo $result_zaznamy['date_inserted']; ?></td>
                                                            <td> <?php echo $result_zaznamy['dlvdate']; ?></td>
														<?php } ?>
														<form method="post" id="sampleForm" class="sampleForm">
                                                       <input type="hidden" name="id_polozka" value="<?php echo $result_zaznamy['id'] ?>">                                                                         
                                                       <?php if($_SESSION['prava']=="cd"){ ?>
                                                       <input type="hidden" name="cd" value="0">
                                                       <?php } ?>
                                                       <?php if($_SESSION['prava']=="moduly"){ ?>
                                                       <input type="hidden" name="moduly" value="0">
                                                       <?php } ?>
                                                       <?php if($_SESSION['prava']=="priority"){ ?>
                                                       <input type="hidden" name="priorita" value="0">
                                                       <?php } ?>
                                                       
														<td>
                                                        
                                                        <input type="checkbox" name="priorita" value="1" <?php echo ($result_zaznamy['priorita']==1)?"checked ":"";?><?php echo ($_SESSION['prava']=="priority")?"":"disabled=\"true\""; ?>   onchange='this.form.submit()'>
                                                        
                                                        </td>
                                                        <td><?php echo $result_zaznamy['priorita_os_cislo']; ?></td>
                                                       
                                                       <?php if($_SESSION['prava']=="cd" || $_SESSION['prava']=="kontrola" || $_SESSION['prava']=="tpv"){ ?>
                                                        <td>
                                                        
                                                                
                                                            <a onclick="location.href='index.php?modul=hotove&cd_itemid=<?php echo $result_zaznamy['cd'] ?>';" id="showVP"><?php echo $result_zaznamy['cd'];?>
                                                            </a>  
                                                            
                                                          <?php  if($result_zaznamy['cd']!=""){
                                                            
                                                        ?>
                                                        <input type="checkbox" name="cd" value="1" <?php echo ($result_zaznamy['cd_hotove']==1)?"checked ":"";?><?php echo ($_SESSION['prava']=="cd" && $result_zaznamy['stav_vyroby']!=-1)?"":"disabled=\"true\""; ?>   onchange='this.form.submit()'>
                                                        <?php }else{
                                                                echo "Neobsahuje CD";
                                                            } ?>
                                                        </td>
                                                        
                                                        <td> <?php echo $result_zaznamy['cd_updated']; ?></td>
                                                        <td><?php echo $result_zaznamy['cd_person_id']; ?></td>
                                                        <?php } ?>
                                                        <?php if($_SESSION['prava']=="moduly" || $_SESSION['prava']=="kontrola"){ ?>
                                                        <td>
                                                        <?php 
                                                                   
                                                            if($result_zaznamy['moduly']!=""){
                                                            if($_SESSION['prava']=="moduly"){?>
                                                            <a href="http://kontrolamodulov.mkem.sk/index.php?modul=prehlad&search=<?php echo $result_zaznamy['prodid']; ?>&search_option=2" target="_blank"><?php echo $result_zaznamy['moduly']; ?>
                                                            </a><?php }else{ echo $result_zaznamy['moduly']; } ?>
                                                        <input type="checkbox" name="moduly" value="1" <?php echo ($result_zaznamy['moduly_hotove']==1)?"checked ":"";?><?php echo ($_SESSION['prava']=="moduly"  && $result_zaznamy['stav_vyroby']!=-1)?"":"disabled=\"true\""; ?>  onchange='this.form.submit()'>
                                                        <?php }else{
                                                                echo "Neobsahuje modul";
                                                            } ?>
                                                        </td>
                                                        <td> <?php echo $result_zaznamy['moduly_updated']; ?></td>
                                                        <td><?php echo $result_zaznamy['moduly_person_id']; ?></td>
                                                        <?php } ?>
                                                                                                      </form>
                                                        <?php if($_SESSION['prava']=="priority" && $_SESSION['prava']!="kontrola"){ ?>
                                                        <td> <?php echo $result_zaznamy['eset']; ?></td>
                                                        <td> <?php echo $result_zaznamy['eset_name']; ?></td>
                                                        <?php } ?>
                                                        <td> <?php echo $result_zaznamy['qty']; ?></td>
                                                        <?php if($_SESSION['prava']!="kontrola"){ ?>
                                                        <td> <?php echo $result_zaznamy['dlvdate']; ?></td>
                                                        <?php } ?>
														<td> <?php echo $result_zaznamy['itemid'];; ?></td>
														<td> <?php echo $result_zaznamy['itemid_name']; ?></td>
                                                        <?php if($_SESSION['prava'] != "priority" && $_SESSION['prava']!="kontrola"){ ?>
                                                        <td> <?php echo $result_zaznamy['eset']; ?></td>
                                                        <td> <?php echo $result_zaznamy['eset_name']; ?></td>
                                                        <?php } ?>
                                                        <?php if($_SESSION['prava']!="kontrola"){?>
                                                        <td> <?php echo $result_zaznamy['prodid']; ?></td>
                                                        <td> <?php echo $result_zaznamy['date_inserted']; ?></td>
                                                        <?php } ?>
                                                        
                                                        
                                                    </tr>
												<?php } ?>	
													
                                                </tbody>
                                            </table>
											<?php	echo "<center>".pagination("polozky ".$sqlPagging,$limit,$page,$url,$c)."</center>"; ?>
															
                                        </div>
								
                                       <?php } ?>
									
                                    </div>
                                </div>
						
 </div>
<?php if(isset($_GET['cd_itemid'])){ ?>

       <?php 
        $query_tab_1="SELECT TOP (1) PRODID FROM dbo.PRODTABLE WHERE ITEMID = '".$_GET['cd_itemid']."' AND PRODSTATUS = 4";
        $apply_tab_1=sqlsrv_query($conn,$query_tab_1);
        $result_vp_cd=sqlsrv_fetch_array($apply_tab_1);
        
        ?>
        <script>

        alert("<?php echo $result_vp_cd['PRODID']; ?>");
        location.replace("index.php?modul=hotove");
            
        </script>
        
        
<?php } ?>
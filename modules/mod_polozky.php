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
    deleteItem();
    stopVP();
    
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
                                                        
                                                        <?php if($_SESSION['prava']=="cd"){ ?>
                                                        <option value="cd" <?php echo($_GET['searchby']=="cd"?"selected":"") ?>>CD číslo</option>
                                                        <?php }if($_SESSION['prava']=="moduly"){ ?>
                                                        <option value="moduly" <?php echo($_GET['searchby']=="moduly"?"selected":"") ?>>Číslo modulu</option>
                                                        <?php } if($_SESSION['prava']!="balenie"){?>
                                                        <option value="itemid" <?php echo($_GET['searchby']=="itemid"?"selected":"") ?>>Číslo položky</option>
                                                        <option value="itemid_name" <?php echo($_GET['searchby']=="itemid_name"?"selected":"") ?>>Názov položky</option>
                                                        <?php } ?>
                                                        <option value="prodid" <?php echo($_GET['searchby']=="prodid"?"selected":"") ?>>Výrobný príkaz</option>
                                                        <option value="eset" <?php echo($_GET['searchby']=="eset"?"selected":"") ?>>Číslo esady</option>
                                                        <?php if($_SESSION['prava']!="balenie"){ ?>
                                                        <option value="eset_name" <?php echo($_GET['searchby']=="eset_name"?"selected":"") ?>>Názov esady</option>
                                                        <?php } ?>
                                                        
                                                        
                                                        
                                                    </select>
                                                    </div>
                                                    <?php if($_SESSION['prava']=="priority"){ ?>
    
                                                        <div class="col-md-2">
                                                    Zoradiť podľa:
                                                    <select name="orderby" class="form-control">
                                                        <option value="">Zvoľte možnosť</option>
                                                        <option value="dlvdate" <?php echo($_GET['orderby']=="dlvdate"?"selected":"") ?>>Dátum dodania</option>
                                                        <option value="date_inserted" <?php echo($_GET['orderby']=="date_inserted"?"selected":"") ?>>Dátum vloženia</option><option value="itemid" <?php echo($_GET['orderby']=="itemid"?"selected":"") ?>>Číslo položky</option>
                                                        <option value="itemid_name" <?php echo($_GET['orderby']=="itemid_name"?"selected":"") ?>>Názov položky</option>
                                                        <option value="eset" <?php echo($_GET['orderby']=="eset"?"selected":"") ?>>Číslo esady</option>
                                                        <option value="prodid" <?php echo($_GET['orderby']=="prodid"?"selected":"") ?>>Výrobný príkaz</option>
                                                        
                                                        
                                                    </select>
                                                    </div>
                                                    <div class="col-md-2">Dátum vloženia:
                                                        <input id="datum" class="form-control" type="date"  name="datum_vlozenia" placeholder="Dátum vloženia" value="<?php echo $_GET['datum_vlozenia']; ?>"><a class="input-group-addon" style="background-color: #e7505a; border-color: #e7505a;" onclick="document.getElementById('datum').value = ''">
                                                        <i style="font-weight: bold; color: white;">X</i>
                                                    </a>	
												    </div>
                                                    <div class="col-md-2">
                                                    <br>
													    <input type="radio" name="srt" value="vzostupne" <?php echo ($_GET['srt'] != "zostupne")? 'checked':''?>> Vzostupne <br>
                                                        <input type="radio" name="srt" value="zostupne" <?php echo ($_GET['srt'] == "zostupne")? 'checked':''?>> Zostupne
                   
													</div>
                                                

                                                 <?php } ?>
                                                </div>                                              
                                            </div>
                                            
                                                <div class="form-actions right1">
                                                   <button type="submit" class="btn blue">Vyhľadať</button>
                                                   <?php if($_SESSION['prava']=="priority"){ ?>
                                                   <a class="btn blue" href="javascript:void(0);" onclick="printPage();">Vytlačiť záznamy <i class="fa fa-print" aria-hidden="true"></i></a> 
                                                   <?php } ?>
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
                                                        <th>Chyba</th>
                                                        <th>Potvrdil (Os. č.)</th>
                                                        <th>Výrobný príkaz</th>
                                                        <th>Číslo esady</th>
                                                        <th>Názov esady</th>
                                                        <th>Počet ks</th>
                                                        <th>Dátum dodania</th>
                                                        <th>CD</th>
                                                        <th>Moduly</th>
                                                        <th>Číslo položky</th>	
                                                        <th>Názov položky</th>
                                                        <th>Dátum vloženia</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
												<?php
                                                $page = (int) (!isset($parameter[1]) ? 1 : $parameter[1]);
                                                $limit = 100;
                                                $url="?modul=polozky";
                                                $startpoint = ($page * $limit) - $limit;
                                                $c=$connect;
                                                $find_by="";
                                                $find_by="WHERE balenie='-1' AND stav_vyroby!=-2 ";
                                                
                                                if(isset($_GET['search_text']) || $_GET['search_text']!=""){
                                                    $find_by.=" AND LOWER(".$_GET['searchby'].") LIKE LOWER('%".$_GET['search_text']."%') ";
                                                }
												$query_zaznamy="SELECT * FROM polozky ".$find_by."ORDER BY ";
                                                $query_zaznamy.=($_SESSION['prava']=="balenie")?" stav_vyroby DESC, priorita DESC, dlvdate ":"priorita DESC, dlvdate, stav_vyroby DESC ";
                                                    $query_zaznamy.="LIMIT $startpoint, $limit";
												$apply_zaznamy=mysqli_query($connect,$query_zaznamy);
												$sqlPagging=$find_by."ORDER BY ".(($_SESSION['prava']=="balenie")?" stav_vyroby DESC, priorita DESC, dlvdate ":"priorita DESC, dlvdate, stav_vyroby DESC ");
                                                
                                            
                                            while($result_zaznamy=mysqli_fetch_array($apply_zaznamy)){
                                                    removeDuplicates($result_zaznamy['id'],$result_zaznamy['prodid'],$result_zaznamy['eset'],$result_zaznamy['itemid']);
                                                    if($_SESSION['prava']=="moduly"){
                                                        checkModules($result_zaznamy['prodid'],$result_zaznamy['moduly'], $result_zaznamy['id']);
                                                    }
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
														<td>
                                                        
                                                        <input type="checkbox" name="priorita" value="1" <?php echo ($result_zaznamy['priorita']==1)?"checked ":"";?><?php echo ($_SESSION['prava']=="priority")?"":"disabled=\"true\""; ?>   onchange='this.form.submit()'>
                                                        
                                                        </td>
                                                        <td><?php echo $result_zaznamy['priorita_os_cislo']; ?></td>
                                                        <form method="post" id="sampleForm" class="sampleForm">
                                                           <input type="hidden" name="id_polozka" value="<?php echo $result_zaznamy['id'] ?>">
                                                           <input type="hidden" name="balenie" value="0">
                                                           <input type="hidden" name="vp" value="<?php echo $result_zaznamy['prodid'] ?>">
                                                       
                                                       
														
                                                       <td><input type="checkbox" name="balenie" value="1" <?php echo ($result_zaznamy['balenie']==1)?"checked ":"";?><?php echo ($_SESSION['prava']=="balenie" && $result_zaznamy['stav_vyroby']==2 && $result_zaznamy['chyba']==1)?"":"disabled=\"true\""; ?>  onchange='this.form.submit()'></td>
                                                       </form>
                                                       <form method="post">
                                                       <input type="hidden" name="id_polozka" value="<?php echo $result_zaznamy['id'] ?>">
                                                       <td method="post" style="width: 180px;">
                                                       <select name="chyba" id="chyba" class=" form-control" onchange='this.form.submit()' 
                                                          <?php if($result_zaznamy['chyba']==1){echo "style=\"background: #64c37b\"";}elseif($result_zaznamy['chyba']==0){echo "style=\"background: #ffffff\"";}else{echo "style=\"background: #f57a7a\"";}?>>
                                                           <option value="0" style="background: #ffffff" <?php echo($result_zaznamy['chyba']==0)?"selected":"" ?>>Vyberte možnosť</option>
                                                           <option value="1" style="background: #76eb92" <?php echo($result_zaznamy['chyba']==1)?"selected":"" ?>>Bez chyby</option>
                                                           <option value="2" style="background: #f57a7a" <?php echo($result_zaznamy['chyba']==2)?"selected":"" ?>>Nesprávny počet</option>
                                                           <option value="3" style="background: #f57a7a" <?php echo($result_zaznamy['chyba']==3)?"selected":"" ?>>Nesprávne moduly</option>
                                                           <option value="4" style="background: #f57a7a" <?php echo($result_zaznamy['chyba']==4)?"selected":"" ?>>Iná chyba</option>
                                                       </select></td> 
                                                       </form>
                                                       <td><?php echo $result_zaznamy['balenie_person_id']; ?></td>
                                                        
                                                        
                                                        
                                                        
                                                        <td> <?php echo $result_zaznamy['prodid']; ?></td>
                                                        <td> <?php echo $result_zaznamy['eset']; ?></td>
                                                        <td > <?php echo $result_zaznamy['eset_name']; ?></td>
                                                        
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
                                    
									   <div class="table-responsive tableFixHead" id="your_content">
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
                                                        <?php } 
                                                        if($_SESSION['prava']=="priority"){
                                                        ?>
                                                        <th>Stopnúť VP</th>
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
                                                        <?php if($_SESSION['prava']=="priority" || $_SESSION['prava']=="kontrola"){ ?>
                                                        <!--<th>Skladom</th>-->
                                                        <?php } if($_SESSION['prava']!="kontrola"){?>
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
                                                $url="?modul=polozky";
                                                $startpoint = ($page * $limit) - $limit;
                                                $c=$connect;
                                                $find_by="";
                                                    
                                                if($_SESSION['prava']=="cd"){
                                                    $find_by="WHERE cd !='' AND cd_hotove='-1' AND stav_vyroby!=-2 ";
                                                }elseif($_SESSION['prava']=="moduly"){
                                                    $find_by="WHERE moduly !='' AND moduly_hotove='-1' AND stav_vyroby!=-2 ";
                                                }else{
                                                    $find_by="WHERE balenie='-1' AND stav_vyroby!=-2 ";
                                                }
                                                if(isset($_GET['search_text']) || $_GET['search_text']!=""){
                                                    $find_by.=" AND LOWER(".$_GET['searchby'].") LIKE LOWER('%".$_GET['search_text']."%') ";
                                                }
                                                if(isset($_GET['datum_vlozenia']) && $_GET['datum_vlozenia']!=""){
                                                    $find_by.=" AND DATE(date_inserted)='".$_GET['datum_vlozenia']."' ";
                                                }
												$query_zaznamy="SELECT * FROM polozky ".$find_by."ORDER BY ";
                                                if($_GET['orderby']!=""){
                                                    $query_zaznamy.= $_GET['orderby']." ";
                                                    $sqlPagging=$find_by."ORDER BY ".$_GET['orderby']." ";
                                                    if($_GET['srt']=="zostupne"){
                                                        $query_zaznamy.="DESC ";
                                                        $sqlPagging.="DESC ";
                                                    }
                                                }else{
                                                    $query_zaznamy.=($_SESSION['prava']=="balenie")?" stav_vyroby DESC, priorita DESC, dlvdate ":"priorita DESC, dlvdate, stav_vyroby DESC ";
                                                    $sqlPagging=$find_by."ORDER BY ".(($_SESSION['prava']=="balenie")?" stav_vyroby DESC, priorita DESC, dlvdate ":"priorita DESC, dlvdate, stav_vyroby DESC ");
                                                }
                                                    
												
                                                $query_zaznamy.="LIMIT $startpoint, $limit";
												$apply_zaznamy=mysqli_query($connect,$query_zaznamy);
                                            
                                            while($result_zaznamy=mysqli_fetch_array($apply_zaznamy)){
                                                removeDuplicates($result_zaznamy['id'],$result_zaznamy['prodid'],$result_zaznamy['eset'],$result_zaznamy['itemid']);     
                                                    if($_SESSION['prava']=="moduly"){
                                                        checkModules($result_zaznamy['prodid'],$result_zaznamy['moduly'], $result_zaznamy['id']);
                                                    }
                                                
                                                $query_bj="SELECT kabelaze_bj.pocet FROM kabelaze_bj INNER JOIN kabelaze_bj_esady ON kabelaze_bj.itemid=kabelaze_bj_esady.itemid_bj WHERE kabelaze_bj_esady.eset='".$result_zaznamy['eset']."' ";
                                                        $apply_bj=mysqli_query($connect,$query_bj);
                                                        $result_bj=mysqli_fetch_array($apply_bj);
                                                
                                                
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
                                                        <td> <a onclick="javascript: return confirm('Naozaj chcete stopnúť <?php echo $result_zaznamy['prodid']; ?>?'); " href="index.php?modul=polozky&stop=<?php echo $result_zaznamy['id']; ?>" class="btn red" style="margin:auto; display:block;" title="Stopnúť VP"><b>STOP</b> <i class="fa fa-ban fa-lg" aria-hidden="true"></i></a> </td>
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
                                                        
                                                                
                                                            <a onclick="location.href='index.php?modul=polozky&cd_itemid=<?php echo $result_zaznamy['cd']; ?>&qty=<?php echo $result_zaznamy['qty']; ?>&dlvdate=<?php echo $result_zaznamy['dlvdate']; ?>';" id="showVP"><?php echo $result_zaznamy['cd'];?>
                                                            </a> 
                                                        <?php  
                                                            if($result_zaznamy['cd']!=""){
                                                            
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
                                                        <?php
                                                
                                                        if($_SESSION['prava']=="priority" || $_SESSION['prava']=="kontrola"){
                                                        
                                                            
                                                        ?>
                                                        
                                                        <!--<td>
                                                           <a data-toggle="modal" data-target="#BJModal" onclick="location.href='index.php?modul=polozky&eset=<?php echo $result_zaznamy['eset']; ?>&pocet=<?php echo $result_bj['pocet']; ?>';">   
                                                        <?php echo $result_bj['pocet']; ?>
                                                        </a>
                                                        </td>-->
                                                        
                                                        <?php } ?>
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
 
 <?php

    if(isset($_GET['eset']) && isset($_GET['pocet'])){ ?>
       
       <div class="modal fade" id="BJModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
       <h1>Odpis položky č. <?php echo $_GET['eset']; ?> zo skladu</h1>    
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
         <form method="post">
          <div class="modal-body">
           <label for="pocet">Koľko kusov si prajete odpísať?</label>
            <input class="form-control" name="pocet" type="number" min=0 max="<?php echo $_GET['pocet']; ?>">
          </div>
          <div class="modal-footer">
                
                    <input type="hidden" name="eset" value="<?php echo $_GET['eset']; ?>">
                    <button type="submit" name="odpis" class="btn btn-primary" formaction="index.php?modul=polozky">Odpísať</button>
                    <button type="submit" name="nothing" class="btn btn-secondary" formaction="index.php?modul=polozky">Zrušiť</button>
                    
          </div>
          </form>
    </div>
  </div>
</div>
        
 <?php   }
odpisBJKabelaze();
?>


<script type="text/javascript">
$(document).ready(function(){
    $('.modal').modal('show');
});
</script>


<?php if($_SESSION['prava']=="moduly" || $_SESSION['prava']=="balenie"){ 

?>
<script>
window.onload = function() {
    if(!window.location.hash) {
        window.location = window.location + '#loaded';
        window.location.reload();
    }
}
</script>
<?php } ?>

<?php if(isset($_GET['cd_itemid'])){ ?>

       <?php 
        $query_tab_1="SELECT TOP (1) PRODID FROM dbo.PRODTABLE WHERE ITEMID = '".$_GET['cd_itemid']."' AND (PRODSTATUS = 4 OR PRODSTATUS=2) AND QTYSCHED = ".$_GET['qty']." AND DLVDATE='".$_GET['dlvdate']."'";
        $apply_tab_1=sqlsrv_query($conn,$query_tab_1);
        $result_vp_cd=sqlsrv_fetch_array($apply_tab_1);
        
        ?>
        <script>

        alert("<?php echo $result_vp_cd['PRODID']; ?>");
        if(!window.location.hash) {
            window.location = window.location + '#loaded';
        }
        location.replace("index.php?modul=polozky");
        </script>
        
        
<?php } ?>


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
 

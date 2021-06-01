<?php

include "../connect.ini.php";
include "../includes/functions.php";

$serverName = "dbserver"; 
$connectionInfo = array( "Database"=>"MKEM_AX2012R3_PROD", "UID"=>"production1", "PWD"=>"production1", "CharacterSet"  => 'UTF-8');
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if($conn){echo "ok";}
else{die( print_r( sqlsrv_errors(), true));}
?>
<form class="form-horizontal" role="form" method="POST" action="" enctype="multipart/form-data">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Výrobný príkaz</label>
                                                    <div class="col-md-9">
													
													<input class="form-control" size="16" type="text" placeholder="Výrobný príkaz" id="vyrobny_prikaz" name="vyrobny_prikaz" value="" autofocus="autofocus" required>
                                                        
													</div>
														
                                                </div>
                                            </div>
                                            <div class="form-actions right1">
                                                
                                                <button type="submit" class="btn blue" name="find_prodid">Zaznamenať</button>
                                            </div>
                                        </form>
<table>
										<thead>
												
											</thead>
											<tbody>
												<?php
								if(isset($_POST['find_prodid'])){
                                    
                                        /*Vyberie z tabulky PRODTABLE data potrebne do tabulky kabelaze*/
											    $query_tab_1="SELECT ITEMID, QTYSCHED FROM dbo.PRODTABLE WHERE ITEMID = '".$_POST['vyrobny_prikaz']."'";
												$apply_tab_1=sqlsrv_query($conn,$query_tab_1);
                                    
												while($result_tab_1=sqlsrv_fetch_array($apply_tab_1)){
                                                    
                                                    $query_insert_tab_1="UPDATE kabelaze SET itemid='".$result_tab_1['ITEMID']."', qty='".intval($result_tab_1['QTYSCHED'])."', qty_r='".intval($result_tab_1['QTYSCHED'])."' WHERE vyrobny_prikaz='".$_POST['vyrobny_prikaz']."'";
                                                   $apply_insert_tab_1=mysqli_query($connect,$query_insert_tab_1);
                                                    /*Vyberie data z tabulky mkem_vp a vlozi ich do tabulky polozky*/
                                                   echo $query_select_from_mkem_vp = "SELECT * FROM dbo.mkem_vp WHERE ITEMID = '".$result_tab_1['ITEMID']."'";
                                                   $apply_select_from_mkem_vp=sqlsrv_query($conn,$query_select_from_mkem_vp);
                                    
												   while($result_select_from_mkem_vp=sqlsrv_fetch_array($apply_select_from_mkem_vp)){
                                                /*Zisti ci sa v tabulke polozky uz polozka s danym VP nenachadza ak nie tak ju prida*/                                                if(!checkCommandInItems($result_select_from_mkem_vp['PRODID'],$result_tab_1['ITEMID'])){
                                                    
                                                        $itemid=$result_tab_1['ITEMID'];
                                                        $itemid_name=$result_select_from_mkem_vp['ITEMID_NAME'];
                                                        $prodstatus=$result_select_from_mkem_vp['PRODSTATUS' ];
                                                        $dlvdate =$result_select_from_mkem_vp['DLVDATE'] ->format('Y-m-d H:i:s');
                                                        $qty=intval($result_select_from_mkem_vp['QTYSCHED']);
                                                        $prodid=$result_select_from_mkem_vp['PRODID'];
                                                        $eset=$result_select_from_mkem_vp['ESADA'];
                                                        $eset_name=$result_select_from_mkem_vp['NAME'];
                                                        $bomqty=intval($result_select_from_mkem_vp['BOMQTY']);
                                                    
                                                        
                                                             
                                                       
                                                       $query_insert_to_polozky="INSERT INTO polozky(itemid, itemid_name, prodstatus, dlvdate, qty, prodid, date_inserted, eset, eset_name, bomqty) ";
                                                       $query_insert_to_polozky.="VALUES ('".$itemid."','".$itemid_name."','".$prodstatus."','".$dlvdate."','".$qty."','".$prodid."',NOW(), '".$eset."','".$eset_name."','".$bomqty."')";
                                                       $apply_insert_to_polozky=mysqli_query($connect,$query_insert_to_polozky);
                                                    
                                                       $query_update_qty_r = "UPDATE kabelaze SET qty_r=qty_r-".($qty*$bomqty)." WHERE id_kabelaz=(SELECT MIN(id_kabelaz) WHERE itemid ='".$itemid."') LIMIT 1 ";
                                                        $apply_update_qty_r=mysqli_query($connect,$query_update_qty_r);
                                                    
                                                       }
                                                       
                                                    if($apply_insert_tab_1 && $apply_insert_to_polozky && $apply_update_qty_r){
                                                        
                                                        echo "ok";
                                                        
                                                    }else{
                                                        
                                                        echo "nic";
                                                        
                                                    }
                                                        
                                                    }
                                                    
                                                } 
                                    
                                }?>
												
												
											</tbody>
											
										</table>
										
										
										
										
										
										
<?php

echo $_POST['itemid']."-";
$connect = mysqli_connect('localhost','root','','objednavky');
mysqli_query($connect,"set names 'utf8'");
error_reporting(0);



$serverName = "dbserver"; 
$connectionInfo = array( "Database"=>"MKEM_AX2012R3_PROD", "UID"=>"production1", "PWD"=>"production1", "CharacterSet"  => 'UTF-8');
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if($conn){//echo "ok";
}
else{die( print_r( sqlsrv_errors(), true));}




function testConnection(){
    echo "ahoj";
}


function checkCommandInItems($vp,$itemid){
    global $connect;
    $query = "SELECT prodid, itemid FROM polozky";
    $result = mysqli_query($connect,$query);
    
    while($row = mysqli_fetch_assoc($result)){
        
        if($row['prodid'] == $vp && $row['itemid']==$itemid){
            return true;
        }
        
    }
    
    return false;
}

function updateCommand(){
    global $connect;

    if(isset($_POST['cd'])){
         
        $stav=($_POST['cd']==1?1:-1);
        $query_zaznamy="UPDATE polozky SET cd='".$_POST['cd']."', cd_updated=NOW(), stav_vyroby=stav_vyroby+".$stav." WHERE id='".$_POST['id_polozka']."'";       
        $apply_zaznamy=mysqli_query($connect,$query_zaznamy);
        unset($_POST['cd']);
        
    }
    if(isset($_POST['moduly'])){
         
        $stav=($_POST['moduly']==1?1:-1);
        $query_zaznamy="UPDATE polozky SET moduly='".$_POST['moduly']."', moduly_updated=NOW(), stav_vyroby=stav_vyroby+".$stav." WHERE id='".$_POST['id_polozka']."'";       
        $apply_zaznamy=mysqli_query($connect,$query_zaznamy);
        unset($_POST['moduly']);
        
    }  
    if(isset($_POST['balenie'])){
         
        $stav=($_POST['balenie']==1?1:-1);
        $query_zaznamy="UPDATE polozky SET balenie='".$_POST['balenie']."', balenie_updated=NOW(), stav_vyroby=stav_vyroby+".$stav." WHERE id='".$_POST['id_polozka']."'";       
        $apply_zaznamy=mysqli_query($connect,$query_zaznamy);
          
        unset($_POST['balenie']);
        
    } 
        
    }

function insertCommand(){
    
    global $connect;
    
    if(isset($_POST['vyrobny_prikaz'])){
    
        $command = mysqli_real_escape_string($connect,$_POST['vyrobny_prikaz']);
        
        if(!commandExist()){

            $query_insert = "INSERT INTO kabelaze(vyrobny_prikaz, datum_cas) ";
            $query_insert .= "VALUES('".$command."',NOW()) ";
            $apply_insert=mysqli_query($connect,$query_insert);

            if($apply_insert){

                echo '<div class="alert alert-success">Údaje boli zaznamenané.</div>';
                echo '<script>var snd = new Audio("sound effects/success.mp3");
    snd.play();</script>';

            }else{
                
                echo '<div class="alert alert-danger">Údaje sa nepodarilo zaznamenať.</div>';
                
                $file_name = "txt files/orders.txt";
                $handle = fopen($file_name, 'r');
                $content = fread($handle,filesize($file_name));

                fclose($handle);

                $handle = fopen($file_name, 'w');
                $content.= $command."       | ".date('Y-m-d H:i:s')."\n";

                fwrite($handle,$content);
                fclose($handle);
                echo '<script>var snd = new Audio("sound effects/failure.mp3");
                snd.play();</script>';
                
            }
            
        }else{
            
            echo '<div class="alert alert-danger">Zadaný výrobný príkaz už existuje!</div>';
            echo '<script>var snd = new Audio("sound effects/failure.mp3");
                snd.play();</script>';
            
        }
        
    }
    
}

function commandExist(){
    global $connect;
    $query = "SELECT vyrobny_prikaz FROM kabelaze";
    $result = mysqli_query($connect,$query);
    
    while($row = mysqli_fetch_assoc($result)){
        
        if($row['vyrobny_prikaz'] == $_POST['vyrobny_prikaz']){
            return true;
        }
        
    }
    
    return false;
}
function checkCommandInItems($vp,$itemid){
    global $connect;
    $query = "SELECT prodid, itemid FROM polozky";
    $result = mysqli_query($connect,$query);
    
    while($row = mysqli_fetch_assoc($result)){
        
        if($row['prodid'] == $vp && $row['itemid']==$itemid){
            return true;
        }
        
    }
    
    return false;
}


?><?php

						if(isset($_POST['find_prodid'])){
                                        /*Vyberie z tabulky PRODTABLE data potrebne do tabulky kabelaze*/
                           
											   $query_tab_1="SELECT id_kabelaz,itemid,qty FROM kabelaze WHERE itemid = '".$_POST['itemid']."'";
												$apply_tab_1=mysqli_query($connect,$query_tab_1);
                                    
												while($result_tab_1=mysqli_fetch_array($apply_tab_1)){
                                                    
                                                    $query_insert_tab_1="UPDATE kabelaze SET qty_r='".$result_tab_1['qty']."' WHERE id_kabelaz='".$result_tab_1['id_kabelaz']."'";
                                                   $apply_insert_tab_1=mysqli_query($connect,$query_insert_tab_1);
                                                    /*Vyberie data z tabulky mkem_vp a vlozi ich do tabulky polozky*/
                                                    $query_select_from_mkem_vp = "SELECT * FROM dbo.mkem_vp WHERE ITEMID = '".$_POST['itemid']."'";
                                                   $apply_select_from_mkem_vp=sqlsrv_query($conn,$query_select_from_mkem_vp);
                                    
												   while($result_select_from_mkem_vp=sqlsrv_fetch_array($apply_select_from_mkem_vp)){
                                                /*Zisti ci sa v tabulke polozky uz polozka s danym VP nenachadza ak nie tak ju prida*/
												if(!checkCommandInItems($result_select_from_mkem_vp['PRODID'],$result_tab_1['ITEMID'])){
                                                    
                                                       $itemid=$result_tab_1['itemid'];
                                                       $itemid_name=$result_select_from_mkem_vp['ITEMID_NAME'];
                                                      $prodstatus=$result_select_from_mkem_vp['PRODSTATUS' ];
                                                       $dlvdate =$result_select_from_mkem_vp['DLVDATE'] ->format('Y-m-d H:i:s');
                                                       $qty=intval($result_select_from_mkem_vp['QTYSCHED']);
                                                       $prodid=$result_select_from_mkem_vp['PRODID'];
                                                       $eset=$result_select_from_mkem_vp['ESADA'];
                                                       $eset_name=$result_select_from_mkem_vp['NAME'];
                                                $bomqty=intval($result_select_from_mkem_vp['BOMQTY']);
                                                    
                                                            $state=0;
                                                            $query_cd = "SELECT ITEMID FROM dbo.mkem_esady_kusovnik WHERE ESADA = '".$result_select_from_mkem_vp['ESADA']."' AND ITEMID_NAME LIKE ('CD%')"; 
                                                            $apply_query_cd=sqlsrv_query($conn,$query_cd);
                                                            $result_cd=sqlsrv_fetch_array($apply_query_cd);
                                                            $state=($result_cd['ITEMID']=="")?$state+1:$state;
                                                            $moduls = "";
                                                            $query_modul = "SELECT ITEMID FROM dbo.mkem_esady_kusovnik WHERE ESADA = '".$result_select_from_mkem_vp['ESADA']."' AND ITEMID_NAME LIKE ('MODUL%')"; 
                                                            $apply_query_modul=sqlsrv_query($conn,$query_modul);
                                                            while($result_modul=sqlsrv_fetch_array($apply_query_modul)){
                                                                
                                                                $moduls.=$result_modul['ITEMID'].", ";
                                                                
                                                            }
                                                            $state=($moduls=="")?$state+1:$state;
                                                       
                                                       $query_insert_to_polozky="INSERT INTO polozky(itemid, itemid_name, prodstatus, dlvdate, qty, prodid, date_inserted, eset, eset_name, bomqty, cd, moduly, stav_vyroby) ";
                                                       $query_insert_to_polozky.="VALUES ('".$itemid."','".$itemid_name."','".$prodstatus."','".$dlvdate."','".$qty."','".$prodid."',NOW(), '".$eset."','".$eset_name."','".$bomqty."', '".$result_cd['ITEMID']."', '".$moduls."','".$state."')";
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
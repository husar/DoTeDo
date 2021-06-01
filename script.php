<?php
//include "includes/functions.php";
echo $_POST['vyrobny_prikaz']."-";
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

function checkCommandInItems($vp){
    global $connect;
    $query = "SELECT prodid FROM polozky";
    $result = mysqli_query($connect,$query);
    
    while($row = mysqli_fetch_assoc($result)){
        
        if($row['prodid'] == $vp){
            return true;
        }
        
    }
    
    return false;
}

function itemExist($item){
    global $connect;
    $query = "SELECT itemid FROM kabelaze_bj WHERE itemid='".$item."' ";
    $result = mysqli_query($connect,$query);
    
    if(mysqli_num_rows($result)>0){
        return true;
    }
    
    return false;
}

function chybneCommandExist($vp){
    global $connect;
    $query = "SELECT * FROM kabelaze_chybne WHERE vyrobny_prikaz='".$vp."' ";
    $result = mysqli_query($connect,$query);
    
    if(mysqli_num_rows($result)>0){
        return true;
    }
    
    return false;
}

function esetExist($eset){
    global $connect;
    $query = "SELECT * FROM kabelaze_bj_esady WHERE eset='".$eset."' ";
    $result = mysqli_query($connect,$query);
    
    if(mysqli_num_rows($result)>0){
        return true;
    }
    
    return false;
}

function deleteCommand(){
    
    global $connect;
    
    $query="DELETE FROM kabelaze WHERE qty_r <= '0' ";
    mysqli_query($connect,$query);
            
    
}

?><?php
								if(isset($_POST['find_prodid'])){
                                    
                                        /*Vyberie z tabulky PRODTABLE data potrebne do tabulky kabelaze*/
											    $query_tab_1="SELECT ITEMID, QTYSCHED, PRODSTATUS FROM dbo.PRODTABLE WHERE PRODID = '".$_POST['vyrobny_prikaz']."'";
												$apply_tab_1=sqlsrv_query($conn,$query_tab_1);
                                                if(!sqlsrv_has_rows($apply_tab_1) && $_POST['aktualizovat']==0){
                                                    $query = "SELECT * FROM kabelaze WHERE vyrobny_prikaz = '".$_POST['vyrobny_prikaz']."'";
                                                    $apply_zaznamy=mysqli_query($connect,$query);
												    $result_zaznamy=mysqli_fetch_array($apply_zaznamy);
                                                    $query = "INSERT INTO kabelaze_chybne(vyrobny_prikaz,datum_cas,itemid, vp_os_cislo) VALUES ('".$result_zaznamy['vyrobny_prikaz']."','".$result_zaznamy['datum_cas']."','".$result_zaznamy['itemid']."','".$result_zaznamy['vp_os_cislo']."') ";
                                                    $apply_zaznamy_insert_chybne=mysqli_query($connect,$query);
                                                    if(!$apply_zaznamy_insert_chybne || !$apply_zaznamy){
                                                        $file_name = "txt files/orders.txt";
                                                        $handle = fopen($file_name, 'r');
                                                        $content = fread($handle,filesize($file_name));

                                                        fclose($handle);

                                                        $handle = fopen($file_name, 'w');
                                                        $content.= $_POST['vyrobny_prikaz']."       | ".date('Y-m-d H:i:s')."\n";

                                                        fwrite($handle,$content);
                                                        fclose($handle);
                                                    }
                                                    $query_insert_tab_1="UPDATE kabelaze SET qty_r='-123456' WHERE vyrobny_prikaz='".$_POST['vyrobny_prikaz']."'";
                                                    mysqli_query($connect,$query_insert_tab_1);
                                                }else{
                                                
												while($result_tab_1=sqlsrv_fetch_array($apply_tab_1)){
                                                    if($result_tab_1['PRODSTATUS']==7){
                                                        $query_insert_tab_1="UPDATE kabelaze SET qty_r='-123456' WHERE vyrobny_prikaz='".$_POST['vyrobny_prikaz']."'";
                                                        mysqli_query($connect,$query_insert_tab_1);
                                                    }else{
                                                        $query_insert_tab_1="UPDATE kabelaze SET itemid='".$result_tab_1['ITEMID']."', qty='".intval($result_tab_1['QTYSCHED'])."', qty_r='".intval($result_tab_1['QTYSCHED'])."' WHERE vyrobny_prikaz='".$_POST['vyrobny_prikaz']."'";
                                                        $apply_insert_tab_1=mysqli_query($connect,$query_insert_tab_1);
                                                        /*Vyberie data z tabulky mkem_vp a vlozi ich do tabulky polozky*/
                                                        $query_select_from_mkem_vp = "SELECT * FROM dbo.mkem_vp WHERE ITEMID = '".$result_tab_1['ITEMID']."'";
                                                        $apply_select_from_mkem_vp=sqlsrv_query($conn,$query_select_from_mkem_vp);
                                                    }
                                                if(!sqlsrv_has_rows($apply_select_from_mkem_vp) && $_POST['aktualizovat']==0){
                                                    $query = "SELECT * FROM kabelaze WHERE vyrobny_prikaz = '".$_POST['vyrobny_prikaz']."'";
                                                    $apply_zaznamy=mysqli_query($connect,$query);
												    $result_zaznamy=mysqli_fetch_array($apply_zaznamy);
                                                    $query = "INSERT INTO kabelaze_chybne(vyrobny_prikaz,datum_cas,itemid, vp_os_cislo) VALUES ('".$result_zaznamy['vyrobny_prikaz']."','".$result_zaznamy['datum_cas']."','".$result_zaznamy['itemid']."','".$result_zaznamy['vp_os_cislo']."') ";
                                                    $apply_zaznamy_insert_chybne=mysqli_query($connect,$query);
                                                    if(!$apply_zaznamy_insert_chybne || !$apply_zaznamy){
                                                        $file_name = "txt files/orders.txt";
                                                        $handle = fopen($file_name, 'r');
                                                        $content = fread($handle,filesize($file_name));

                                                        fclose($handle);

                                                        $handle = fopen($file_name, 'w');
                                                        $content.= $_POST['vyrobny_prikaz']."       | ".date('Y-m-d H:i:s')."\n";

                                                        fwrite($handle,$content);
                                                        fclose($handle);
                                                    }
                                                    $query_insert_tab_1="UPDATE kabelaze SET qty_r='-123456' WHERE vyrobny_prikaz='".$_POST['vyrobny_prikaz']."'";
                                                    mysqli_query($connect,$query_insert_tab_1);
                                                }else{
                                    
												   while($result_select_from_mkem_vp=sqlsrv_fetch_array($apply_select_from_mkem_vp)){
                                                /*Zisti ci sa v tabulke polozky uz polozka s danym VP nenachadza ak nie tak ju prida*/
												if(!checkCommandInItems($result_select_from_mkem_vp['PRODID']) && !strpos($result_select_from_mkem_vp['ITEMID_NAME']," 2-")>0 && !strpos($result_select_from_mkem_vp['ITEMID_NAME']," 3-")>0 && (!strpos($result_select_from_mkem_vp['ITEMID_NAME']," 4-")>0 || $result_select_from_mkem_vp['ITEMID']=="11040641") && !strpos($result_select_from_mkem_vp['ITEMID_NAME']," 1-")>0){
                                                    
                                                       $itemid=$result_tab_1['ITEMID'];
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
                                                    
                                                } 
                                                }
                                    
                                }


if(isset($_POST['find_itemid'])){
                                        /*Vyberie z tabulky PRODTABLE data potrebne do tabulky kabelaze*/
                           
											    if($_POST['aktualizovat']==0){
                                                    $query_select_item="SELECT * FROM kabelaze WHERE itemid='".$_POST['itemid']."' AND (vyrobny_prikaz='' OR vyrobny_prikaz IS NULL) ORDER BY datum_cas";
                                                }else{
                                                   $query_select_item="SELECT * FROM kabelaze_bj WHERE itemid='".$_POST['itemid']."' ORDER BY datum_cas"; 
                                                }
                                                $apply_select_item=mysqli_query($connect,$query_select_item);
    
                                                while($result_select_item=mysqli_fetch_array($apply_select_item)){
                                                    $query_tab_1="SELECT * FROM dbo.mkem_vp WHERE ITEMID = '".$_POST['itemid']."' ";
                                                    $apply_tab_1=sqlsrv_query($conn,$query_tab_1);
                                                    while($result_tab_1=sqlsrv_fetch_array($apply_tab_1)){
                                                        if(!checkCommandInItems($result_tab_1['PRODID']) && !strpos($result_tab_1['ITEMID_NAME']," 2-")>0 && !strpos($result_tab_1['ITEMID_NAME']," 3-")>0 && (!strpos($result_select_from_mkem_vp['ITEMID_NAME']," 4-")>0 || $result_select_from_mkem_vp['ITEMID']=="11040641") && !strpos($result_tab_1['ITEMID_NAME']," 1-")>0){
                                                            $itemid=$result_tab_1['ITEMID'];
                                                            $itemid_name=$result_tab_1['ITEMID_NAME'];
                                                            $prodstatus=$result_tab_1['PRODSTATUS' ];
                                                            $dlvdate =$result_tab_1['DLVDATE'] ->format('Y-m-d H:i:s');
                                                            $qty=intval($result_tab_1['QTYSCHED']);
                                                            $prodid=$result_tab_1['PRODID'];
                                                            $eset=$result_tab_1['ESADA'];
                                                            $eset_name=$result_tab_1['NAME'];
                                                            $bomqty=intval($result_tab_1['BOMQTY']);
                                                    
                                                            $state=0;
                                                            $query_cd = "SELECT ITEMID FROM dbo.mkem_esady_kusovnik WHERE ESADA = '".$result_tab_1['ESADA']."' AND ITEMID_NAME LIKE ('CD%')"; 
                                                            $apply_query_cd=sqlsrv_query($conn,$query_cd);
                                                            $result_cd=sqlsrv_fetch_array($apply_query_cd);
                                                            $state=($result_cd['ITEMID']=="")?$state+1:$state;
                                                            $moduls = "";
                                                            $query_modul = "SELECT ITEMID FROM dbo.mkem_esady_kusovnik WHERE ESADA = '".$result_tab_1['ESADA']."' AND ITEMID_NAME LIKE ('MODUL%')"; 
                                                            $apply_query_modul=sqlsrv_query($conn,$query_modul);
                                                            while($result_modul=sqlsrv_fetch_array($apply_query_modul)){
                                                                
                                                                $moduls.=$result_modul['ITEMID'].", ";
                                                                
                                                            }
                                                            $state=($moduls=="")?$state+1:$state;
                                                            $query_insert_to_polozky="INSERT INTO polozky(itemid, itemid_name, prodstatus, dlvdate, qty, prodid, date_inserted, eset, eset_name, bomqty, cd, moduly, stav_vyroby, BJ) ";
                                                            $query_insert_to_polozky.="VALUES ('".$itemid."','".$itemid_name."','".$prodstatus."','".$dlvdate."','".$qty."','".$prodid."',NOW(), '".$eset."','".$eset_name."','".$bomqty."', '".$result_cd['ITEMID']."', '".$moduls."','".$state."',1)";
                                                            $apply_insert_to_polozky=mysqli_query($connect,$query_insert_to_polozky);
                                                            
                                                            $query_set_bj_kabelaze="UPDATE kabelaze_bj SET pocet=pocet-".$qty." ";
                                                            mysqli_query($connect,$query_set_bj_kabelaze);
                                                        }
                                                    }
                                                
                                                    
                                                    if(!itemExist($result_select_item['itemid']) && $_POST['aktualizovat']==0){
                                                        
                                                        $query_insert_bj="INSERT INTO kabelaze_bj (itemid, pocet, datum_cas) ";
                                                        $query_insert_bj.="VALUES ('".$result_select_item['itemid']."', '".$result_select_item['qty']."', '".$result_select_item['datum_cas']."') ";
                                                        $apply_insert_bj=mysqli_query($connect, $query_insert_bj);
                                                        
                                                    }elseif($_POST['aktualizovat']==0){
                                                        $query_update_bj="UPDATE kabelaze_bj SET pocet=pocet+".$result_select_item['qty'].", datum_cas=NOW() WHERE itemid=".$result_select_item['itemid']." ";
                                                        $apply_update_bj=mysqli_query($connect, $query_update_bj);
                                                    }
                                                    
                                                    $query_esady="SELECT ESADA FROM dbo.mkem_esady_kusovnik_ajneaktivne WHERE ITEMID='".$_POST['itemid']."' ";
                                                    $apply_esady=sqlsrv_query($conn,$query_esady);
                                                    
                                                    if(sqlsrv_has_rows($apply_esady)){
                                                        while($result_esady=sqlsrv_fetch_array($apply_esady)){
                                                            if(!esetExist($result_esady['ESADA'])){
                                                                $query_insert_kabelaze_bj_esady="INSERT INTO kabelaze_bj_esady(itemid_bj,eset) VALUES('".$_POST['itemid']."', '".$result_esady['ESADA']."')";
                                                                mysqli_query($connect,$query_insert_kabelaze_bj_esady);
                                                            }
                                                        }
                                                    }
                                                //}
                                                    if($_POST['aktualizovat']==0){
                                                        $query_insert_tab_1="UPDATE kabelaze SET qty_r='-123456' WHERE id_kabelaz=".$result_select_item['id_kabelaz']." ";
                                                        mysqli_query($connect,$query_insert_tab_1);
                                                        deleteCommand();
                                                    }
                                                }
}?>

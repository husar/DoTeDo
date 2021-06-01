<?php
$serverName = "dbserver"; 
$connectionInfo = array( "Database"=>"MKEM_AX2012R3_PROD", "UID"=>"production1", "PWD"=>"production1", "CharacterSet"  => 'UTF-8');
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if($conn){echo "ok";}
else{die( print_r( sqlsrv_errors(), true));}
?>
<table>
										<thead>
												
											</thead>
											<tbody>
												<?php
												
				
				
												//MySQL QUERY: select * from dbo.mkem_paletizacia_polozky LIMIT 10,20
												//SQL QUERY: SELECT * FROM (SELECT TOP 30 *, ROW_NUMBER() OVER (ORDER BY (SELECT 1)) AS rnum  FROM dbo.mkem_paletizacia_polozky) a WHERE rnum > 10 dbo.PRODTABLE
											     $query1="SELECT dbo.mkem_esady_kusovnik.ESADA AS esada FROM dbo.mkem_esady_kusovnik INNER JOIN dbo.PRODTABLE ON dbo.mkem_esady_kusovnik.ITEMID = dbo.PRODTABLE.ITEMID WHERE PRODID = 'VP314318'";
												 $apply1=sqlsrv_query($conn,$query1);
												 while($result1=sqlsrv_fetch_array($apply1)){
												    $query2="SELECT ITEMID, DLVDATE, QTYSCHED FROM dbo.PRODTABLE WHERE ITEMID='".$result1['esada']."' AND PRODSTATUS = '4' ORDER BY DLVDATE";
                                                    $apply2=sqlsrv_query($conn,$query2);
                                                    while($result2=sqlsrv_fetch_array($apply2)){
                                                        
												?>
												
												<tr>
													<td><?php echo $result2['ITEMID']; ?></td>
													<td><?php echo $result2['DLVDATE']->format('Y/m/d'); ?></td>
													<td><?php echo $result2['QTYSCHED']; ?></td>
													
													
												</tr>
												
												<?php } 
                                                    } ?>
												
												
											</tbody>
											
										</table>
										
										
										
										
										
										
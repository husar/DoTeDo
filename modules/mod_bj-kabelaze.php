<div class="page-content">


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
												     <input type="hidden" name="modul" value="bj-kabelaze">	
													
                                                    <div class="col-md-4">
                                                      Číslo položky:
                                                       <br>
                                                        <input class="form-control" type="text"  name="search_text" placeholder="Hľadaný výraz" value="<?php echo $_GET['search_text']; ?>">	
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
                                            <i class="fa fa-tasks" aria-hidden="true"></i>BJ položky</div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="Zbaliť/Rozbaliť" title=""> </a>
                                            
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    <div class="portlet-body">
									   <div class="table-responsive tableFixHead">
                                            <table class="table table-bordered">
                                               <thead>
                                                    <tr>
														<th>ID</th>
												        <th>Číslo položky</th>
												        <th>Skladom</th>
												        <th>Dátum a čas pridania</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
												<?php
                                                $page = (int) (!isset($parameter[1]) ? 1 : $parameter[1]);
                                                $limit = 100;
                                                $url="index.php?modul=bj-kabelaze";
                                                $startpoint = ($page * $limit) - $limit;
                                                $c=$connect;
                                                $find_by="";
                                                    
												$query_zaznamy="SELECT * FROM kabelaze_bj ";
                                                if(isset($_GET['search_text']) && $_GET['search_text']!=""){
                                                    $query_zaznamy.="WHERE LOWER(itemid) LIKE LOWER('%".$_GET['search_text']."%') ";
                                                    $sqlPagination.="WHERE ".$_GET['searchby']." LIKE ('%".$_GET['search_text']."%') ";
                                                }
                                                $query_zaznamy.="ORDER BY datum_cas ";
                                                $query_zaznamy.="LIMIT $startpoint, $limit";
												$apply_zaznamy=mysqli_query($connect,$query_zaznamy);
												while($result_zaznamy=mysqli_fetch_array($apply_zaznamy)){
                                                ?>
												<tr >
														<td> <?php echo $result_zaznamy['id']; ?></td>
														<td> <?php echo $result_zaznamy['itemid']; ?></td>
														<td> <?php echo $result_zaznamy['pocet']." ks"; ?></td>
														<td> <?php echo $result_zaznamy['datum_cas']; ?></td>
                                                    </tr>
												<?php } ?>	
													
                                                </tbody>
                                            </table>
											<?php	echo "<center>".pagination("bj_kabelaze ".$sqlPagination,$limit,$page,$url,$c)."</center>"; 
                                           ?>
															
                                        </div>
								
                                       
									
                                    </div>

                                </div>
						
 </div>
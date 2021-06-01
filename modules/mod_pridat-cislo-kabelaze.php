<div class="page-content">
<?php
    include "functions.php";
    
    insertItem();
    
?>
 <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-plus" aria-hidden="true"></i> Pridať číslo kabeláže</div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="Zbaliť/Rozbaliť" title=""> </a>
                                            
                                        </div>
                                    </div>
                                    <div class="portlet-body">
									
								
                                       <form class="form-horizontal" role="form" method="POST" action="" enctype="multipart/form-data">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Číslo kabeláže</label>
                                                    <div class="col-md-2">
													
													<input class="form-control" size="16" type="text" placeholder="Číslo kabeláže" id="vyrobny_prikaz" name="itemid" value="" autofocus="autofocus" required pattern="11\d{6}">
                                                        
													</div>
														
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Počet kusov</label>
                                                    <div class="col-md-2">
													
													<input class="form-control" size="16" type="number" placeholder="Počet kusov" id="vyrobny_prikaz" name="qty" value="" autofocus="autofocus" required >
                                                        
													</div>
														
                                                </div>
                                            </div>
                                            <div class="form-actions right1">
                                                
                                                <button type="submit" class="btn blue" name="insert_item">Zaznamenať</button>
                                            </div>
                                        </form>
									
                                    </div>
                                </div>
						
 </div>
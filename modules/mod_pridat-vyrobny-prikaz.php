<div class="page-content">
<?php
    include "functions.php";
    
    insertCommand();
    
?>
 <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-plus" aria-hidden="true"></i> Pridať výrobný príkaz</div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="Zbaliť/Rozbaliť" title=""> </a>
                                            
                                        </div>
                                    </div>
                                    <div class="portlet-body">
									
								
                                       <form class="form-horizontal" role="form" method="POST" action="" enctype="multipart/form-data">
                                            <div class="form-body">
                                                <div class="form-group">
                                                    <label class="col-md-3 control-label">Výrobný príkaz</label>
                                                    <div class="col-md-4">
													
													<input id="focus_target" class="form-control" size="16" type="text" placeholder="Výrobný príkaz" id="vyrobny_prikaz" name="vyrobny_prikaz" value="" autofocus="autofocus" required >
                                                        
													</div>
														
                                                </div>
                                            </div>
                                            <div class="form-actions right1">
                                                
                                                <button type="submit" class="btn blue" name="insert_command">Zaznamenať</button>
                                            </div>
                                        </form>
									
                                    </div>
                                </div>
						
 </div>
 
<script>

var alwaysFocusedInput = document.getElementById( "focus_target" );

alwaysFocusedInput.addEventListener( "blur", function() {
  setTimeout(() =>{
    alwaysFocusedInput.focus();
  }, 0);
});
    
</script>
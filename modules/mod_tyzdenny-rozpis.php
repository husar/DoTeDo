<div class="page-content">
<?php

    include "functions.php";
    
    $result_query=selectMinAndMaxDlvdateWeek();
    $min_week=$result_query['min_week'];
    $max_week=$result_query['max_week'];
    
?>
<div class="portlet box blue ">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-search"></i> Rozpis podľa týždňa
											</div>
                                      
                                    </div>
                                    <div class="portlet-body form">
                                        <form class="form-horizontal" role="form" method="get" enctype="multipart/form-data" accept-charset="utf-8">
                                            <div class="form-body">
                                                <div class="form-group">
												     <input type="hidden" name="modul" value="tyzdenny-rozpis">	
													
                                                    <div class="col-md-2">
                                                    Týždeň:
                                                    <select name="week" class="form-control" onchange='this.form.submit()'>
                                                        <option value=""></option>
                                                    <?php 
                                                        
                                                        while($min_week<=$max_week){ 
                                                            
                                                            $days=firstAndLastDayOfWeek($min_week);
                                                            
                                                        ?>
                                                        <option value="<?php echo $min_week; ?>" <?php echo $min_week==$_GET['week']?"selected":""; ?>><?php echo $days[0]." - ".$days[1]; ?></option>
                                                    <?php 
                                                    $min_week++;                                 
                                                    } ?>
                                                    </select>
                                                    
                                                    </div>
                                                </div>                                              
                                            </div>
                                        </form>
                                    </div>
                                </div> 
                                <div class="portlet box blue">
                                    <div class="portlet-title">
                                        <div class="caption">
                                           <?php $days= $_GET['week']!=""?firstAndLastDayOfWeek($_GET['week']):"";?>
                                            <i class="fa fa-tasks" aria-hidden="true"></i><?php echo $days[0]." - ".$days[1]; ?></div>
                                        <div class="tools">
                                            <a href="javascript:;" class="collapse" data-original-title="Zbaliť/Rozbaliť" title=""> </a>
                                            
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    <div class="portlet-body">
									   <div class="table-responsive tableFixHead">
                                            <table class="table table-bordered">
                                               <thead>
                                                    <tr>
												        <th>Modul</th>
												        <th>Potrebný počet</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
												<?php
                                                    
                                                    $query="SELECT moduly, SUM(qty) as sucet FROM polozky WHERE moduly!='' AND moduly_hotove!=1 AND stav_vyroby!=-2 ";
                                                    $query.= $_GET['week']!=""?"AND WEEK(dlvdate)=".$_GET['week']." ":"";
                                                    $query.="GROUP BY moduly ";
                                                    $apply_query=mysqli_query($connect,$query);
                                                    while($row=mysqli_fetch_array($apply_query)){
                                                    
                                                ?>
                                                
                                                <tr>
                                                    <td><?php echo $row['moduly']; ?></td>
                                                    <td><?php echo $row['sucet']; ?></td>
                                                </tr>
                                                
                                                <?php } ?>
													
                                                </tbody>
                                            </table>
											
															
                                        </div>
								
                                       
									
                                    </div>

                                </div>      
                 
 
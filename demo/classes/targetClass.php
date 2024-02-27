<?php


class targetClass
{

    function targetTempltemplete($target_name,$target,$header_fa,$weekly_target,$daily_target,$monthly_target,$adminactions="",$array=array(),$main_target="",$i=0,$results="",$str="",$pper=0)
    {
        $variance=$monthly_target-$target;
        $percentage=round(($monthly_target/$target)*100);
        $percentage=$target_name=="Savings"?round(($pper/12)*100):$percentage;
        $data=$this->getCaret($target,$monthly_target,$variance,$percentage);
        $caret_txt=$data["caret_txt"];$caret=$data["caret"];$per_txt=$data["per_txt"];

        $target_text=$target>10000?$this->moneyFormat($target):$target;
        $weekly_target=$target>10000?$this->moneyFormat($weekly_target):$weekly_target;
        $daily_target=$target>10000?$this->moneyFormat($daily_target):$daily_target;
        $variance=$target>10000?$this->moneyFormat($variance):$variance;
        $monthlytarget=$target>10000?$this->moneyFormat($monthly_target):$monthly_target;
        $target_text=$target_name=="Savings"?$pper."%":$target_text;

        if($target_name=="Savings")
        {
            $variance=$pper-12;
            $variance=$variance."%";
        }

        ?>
        <section class="content">
            <div class="container-fluid">
                <!-- Info boxes -->
                <div class="row">
                    <div class="col-lg-3">
                        <h4 class="info-box-text"><?php echo $target_name;?> Target</h4>
                        <div class="info-box">
                            <span class="info-box-icon bg-info elevation-1"><i class="<?php echo $header_fa;?>"></i></span>
                            <div class="info-box-content" style="padding-bottom:10px !important">
                                
                                <span class="info-box-number">
                  <?php
                  echo $target_text;
                  ?>
                 
                </span>
                                <?php
                                echo $adminactions;
                                ?>

                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-lg-3">
                        <h4 class="info-box-text" style="color:#20c997 !important">This Week</h4>
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-danger elevation-1"><i class="<?php echo $header_fa;?>"></i></span>

                            <div class="info-box-content">
                                
                                <span class="info-box-number">
                  <?php
                  echo $weekly_target;
                  ?>
                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->

                    <!-- fix for small devices only -->
                  

                    <div class="col-lg-3">
                        <h4 class="info-box-text" style="color:#20c997 !important">Today</h4>
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-success elevation-1"><i class="<?php echo $header_fa;?>"></i></span>

                            <div class="info-box-content">
                                                                <span class="info-box-number">
                  <?php
                  echo $daily_target;
                  ?>
                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-lg-3">
                        <h4 class="info-box-text" style="color:#20c997 !important">Variance</h4>
                        <div class="info-box mb-3">
                            <span class="info-box-icon bg-warning elevation-1"><i class="<?php echo $header_fa;?>"></i></span>

                            <div class="info-box-content">                               
                                <span class="info-box-number <?php echo $caret_txt;?>">
                <i class="<?php echo $caret;?>"></i>
                   <?php
                   echo $variance;
                   ?>
                </span>
                            </div>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>

                    <!-- /.col -->
                </div>
                <!-- /.row -->

                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">

                                <h5 class="card-title text-red">Overall
                                
                                    <?php
                                    if(strlen($adminactions)>2)
                                    {
                                        ?>
                                        <span title="View" class="badge badge-info" style="cursor: pointer;" onclick="view('<?php echo $i;?>')">
                      <i class="ti-book"></i>
                  </span>
                                        <?php
                                    }
                                    else
                                    {
                                        echo " <span title=\"View\" class=\"badge badge-info\" style=\"cursor: pointer;\" onclick=\"view1('$i')\">
                      <i class=\"ti-book\"></i>
                  </span>";
                                    }
                                    ?>
                                    
                                </h5><br>
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped <?php echo $per_txt;?>" role="progressbar" style="width: <?php echo $percentage;?>%" aria-valuenow="<?php echo $percentage;?>" aria-valuemin="0" aria-valuemax="100"><?php echo $monthlytarget;?></div>
                                </div>
                                <div class="card" style="display: none" id="<?php echo 'users1x'.$i;?>">
                                
                                    <?php
                                   
                                    for ($i=0;$i<count($array);$i++)
                                    {
                                        $username=$array[$i]["username"];
                                        $user_monthly_target=$array[$i]["monthly_target"];
                                        $charged=$array[$i]["charged"];

                                        $this->specialistsProgress($main_target,$user_monthly_target,$username,$charged,$target_name);
                                    }
                                    ?>
                                </div>
                                <div class="card b" style="display: none" id="">
                                    <?php
                                    for($j=1;$j<4;$j++)
                                    {
                                        $myuser=$_SESSION["user_id"];
                                        $newdate = date("Y-m", strtotime("-$j months"));
                                        $user_monthly_target=$results->showExcelKPI($myuser,$newdate,$str);
                                        $this->specialistsProgress($main_target,$user_monthly_target,$newdate);
                                    }
                                    ?>
                                </div>

                            </div>

                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->

                <!-- Main row -->

                <!-- /.row -->
            </div><!--/. container-fluid -->
        </section><hr>
        <?php
    }

    function specialistsProgress($target,$monthly_target,$username,$charged="",$target_name="")
    {
        $percentage=round(($monthly_target/$target)*100);
        $myvariance=$monthly_target-$target;
        $myvariance=$target>10000?$this->moneyFormat($myvariance):$myvariance;
        $monthlytarget=$target>10000?$this->moneyFormat($monthly_target):$monthly_target;
        $perc=0;
        $myvariance1="";
        $myvariance=" ($myvariance)";
        if($target_name=="Savings")
        {
            $perc=$charged>0?round(($monthlytarget/$charged)*100):0;
            $perc=is_nan($perc)?0:$perc;
            $perc=is_infinite($perc)?0:$perc;
            $percentage=round(($perc/12)*100);
            $myvariance=" ($monthlytarget / $charged)";
            $myvariance1="<span style='color: rebeccapurple'>(".$perc."%)</span>";
            $pp=$perc-12;
            $monthlytarget=$pp."%";

        }
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">

                        <h5 class="card-title text-red">
                            <?php echo $username.$myvariance.$myvariance1;?>
                        </h5><br>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: <?php echo $percentage;?>%" aria-valuenow="<?php echo $percentage;?>" aria-valuemin="0" aria-valuemax="100"><?php echo $monthlytarget;?></div>
                        </div>

                    </div>
                    <!-- /.card-header -->

                    <!-- ./card-body -->

                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <?php
    }
    function getCaret($target,$monthly_target,$variance,$percentage)
    {

        $data["per_txt"]="bg-success";
        $data["caret_txt"]="text-success";
        $data["caret"]="ti-arrow-up";
        if($target==$monthly_target)
        {
            $data["caret_txt"]="text-warning";
            $data["caret"]="ti-angle-left";
        }
        elseif ($variance<0)
        {
            $data["caret_txt"]="text-danger";
            $data["caret"]="ti-arrow-down";
        }
        if($percentage<50)
        {
            $data["per_txt"]="bg-danger";
        }
        elseif ($percentage==50)
        {
            $data["per_txt"]="bg-warning";
        }
        elseif ($percentage>50 && $percentage<75)
        {
            $data["per_txt"]="bg-info";
        }

        return $data;

    }
    function moneyFormat($amount)
    {
        return number_format($amount,2,'.',' ');
    }
}
?>
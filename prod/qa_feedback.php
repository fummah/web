<?php
$lastmonth=date("Y-m",strtotime("-1 month"));
$dat="%".$lastmonth."%";
try {
$tott=$control->viewQACSQADescr($dat);
if($tott>0)
{
    $arr_users=$control->viewPendingQAUsers($dat);

    foreach ($arr_users as $au)
    {
        $myusername=$au["username"];

        foreach ($control->viewQAClaims($dat,$myusername) as $rrt)
        {
            $claim_id=$rrt["claim_id"];
            $position=$rrt["position"];
        $avai=count($control->viewAvailableQAFeedback($dat,$claim_id));
			if($avai<1)
			{
            $improvement=$control->getMyImprovement($claim_id);
            $control->callinsertQAFeedback($claim_id,$lastmonth,$position,$myusername,$improvement);
			}
        }

    }

}
}
catch (Exception $x)
{

}
?>
<div class="container" style="border: 1px dashed lightgrey; background-color: whitesmoke; padding-top: 5px">
    <div class="row">
        <div class="col-md-4"><h3>QA Feedback Sessions</h3></div>
        <div class="col-md-4">
            <?php
            $ischeck="checked";
        if($control->isTopLevel())
        {
            $ischeck="";
            ?>
            <label>
                <input name="status" value="0" id="emergency1" type="radio" checked />
                <span>Active / Ready</span>
            </label>
            <?php

        }
            ?>
            <label>
                <input name="status" value="1" id="emergency2" type="radio" <?php echo $ischeck;?>/>
                <span>Pending</span>
            </label>
            <label>
                <input name="status" value="2" id="emergency3" type="radio"/>
                <span>Completed</span>
            </label>
        </div>

        <div class="col-md-4" style="color: cadetblue !important;">
            <select id="dates" aria-label="Select" style="color: cadetblue !important;">
                <?php
                foreach ($control->viewFeedbackQADates() as $yy)
                {
                    $lastmonth=$yy["month_entered"];
                ?>
                <option value="<?php echo $lastmonth;?>" style="color: cadetblue !important;"><?php echo $lastmonth;?></option>
                <?php
                }
                ?>
            </select>
        </div>


    <div>

    </div>

</div>
</div>
<div class="container" style="border: 1px solid lightgrey; border-radius: 10px; margin-top: 5px">
    <div class="row">
    <div class="col-md-2" id="menu1">

    </div>
    <div class="col-md-10 scroll" style="border-left: 1px dashed lightgrey;height: 500px;overflow:scroll">
        <table class="uk-table uk-table-striped">
            <thead>
            <tr>
                <th>Claim Number</th>
                <th>Area of improvement</th>
                <th>Action plan</th>
                <th>QA/TL comments</th>
                <th></th>
            </tr>
            </thead>
            <tbody id="dda">
            <tr><td colspan="5"><p class="uk-text-danger">No Selection</p> </td></tr>

            </tbody>
        </table>
    </div>
<div id="bbotm" style="display: none; width: 50%; position: relative; margin-left: auto; margin-right: auto;padding-top:10px;">
        <?php
        if($control->isClaimsSpecialist())
        {
            echo "<button data='cs_action' id='cs_action' class=\"uk-button uk-button-secondary mybtns\">Confirm</button>";
        }
        else
        {
            echo "<button data='controller_action' id='controller_action' class=\"uk-button uk-button-secondary mybtns\">Send to CS</button>";
            echo "<button data='close_qa' id='close_qa' style='display: none' class=\"uk-button uk-button-secondary mybtns\">Close Now</button>";
            echo " <div id=\"resultstxt\" class=\"uk-text-success\" style=\"font-weight: bolder\"></div>";
        }

        ?>
    <div id="wait" style="font-weight: bolder" class="uk-text-success"></div>
    <input type="hidden" id="dusername">

    </div>

    </div>
</div>
</body>
</html>
<script>
    $(document).ready(function() {
        $('select').formSelect();
    } );
</script>
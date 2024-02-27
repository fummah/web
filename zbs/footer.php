<p style="text-align: center; vertical-align: middle;color:red;font-size:10px">Logged As : <i><b><?php echo $db->loggedAS();?></b></i> 
<?php
if($db->isTopLevel())
{
?>
<br><br><button class="uk-button uk-button-default" type="button" uk-toggle="target: #change-group"><span uk-icon="users"></span> Group <?php echo $db->getGroupName();?></button>
<?php
}
?>
</p><hr class="uk-hr">
<p style="text-align: center; vertical-align: middle;color:brown;font-size:10px;">Powered By <i><b>TF Solutions</b></i></p>

<div id="change-group" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="uk-modal-title">Switch Group</h2>
		<form action="" method="POST">
        <div class="uk-margin">
            <select class="uk-select" name="groups" REQUIRED>
			<option value="">Select Group</option>
			<?php
			foreach($db->getGroups() as $grow)
			{
				$gid=$grow["group_id"];
				$gname=$grow["group_name"];
                echo "<option value='$gid>$gname'>Group $gname</option>";
			}
               ?>
            </select>
        </div>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
            <button class="uk-button uk-button-primary" name="submitgroup" type="submit">Switch</button>
        </p>
		</form>
    </div>
</div>

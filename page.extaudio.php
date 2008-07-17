<?php
//Copyright (C) ATL Telecom Ltd 2008 Nick Lewis
//
//This program is free software; you can redistribute it and/or
//modify it under the terms of the GNU General Public License
//as published by the Free Software Foundation; either version 2
//of the License, or (at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.

$action = isset($_REQUEST['action'])?$_REQUEST['action']:'';


//if submitting form, update database
if ($action == 'edit') {
	$extaudiofields = array(
						array($_REQUEST['lineoutvol'],'lineoutvol'),
						array($_REQUEST['pagingvol'],'pagingvol'),
						array($_REQUEST['capturevol'],'capturevol'),
						array($_REQUEST['muzakvol'],'muzakvol')
						);

	$compiled = $db->prepare('UPDATE extaudio SET value = ? WHERE variable = ?');
	$result = $db->executeMultiple($compiled,$extaudiofields);
	if(DB::IsError($result)) {
		echo $action.'<br>';
		die($result->getMessage());
	}

	//indicate 'need reload' link in header.php
	needreload();
}

//get all rows relating to selected account
$sql = "SELECT * FROM extaudio";
$extaudiorows = $db->getAll($sql);
if(DB::IsError($extaudiorows)) {
die($extaudiorows->getMessage());
}

//create a set of variables that match the items in extaudio[0]
foreach ($extaudiorows as $extaudio) {
	${trim($extaudio[0])} = $extaudio[1];
}

?>

<form name="extaudio" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" onsubmit="return general_onsubmit();">
<input type="hidden" name="display" value="extaudio"/>
<input type="hidden" name="action" value="edit"/>

<h5><?php echo _("External Audio Settings")?></h5>
<p>
	<a href=# class="info"><?php echo _("Master line-out volume (%) ")?><span><br>
<?php echo _("0% - 100%: The volume of the output relative to the maximum volume")?><br>
<?php echo _("Used for setting master volume for music and paging to a public address system")?><br>
	</span></a>
	<input type="text" size="2" name="lineoutvol" value="<?php  echo htmlspecialchars($lineoutvol)?>"/>
</p>
<ul>
<li>
	<a href=# class="info"><?php echo _("Line-out paging volume (%) ")?><span><br>
<?php echo _("0% - 100%: The volume of the paging relative to the maximum volume")?><br>
<?php echo _("Used for setting paging volume to a public address system")?><br>
	</span></a>
	<input type="text" size="2" name="pagingvol" value="<?php  echo htmlspecialchars($pagingvol)?>"/>
</li>
<li>
	<a href=# class="info"><?php echo _("Line-out music volume (%) ")?><span><br>
<?php echo _("0% - 100%: The volume of the passthrough music relative to the maximum volume")?><br>
<?php echo _("Used for setting passthrough music volume to a public address system")?><br>
	</span></a>
	<input type="text" size="2" name="muzakvol" value="<?php  echo htmlspecialchars($muzakvol)?>"/>
</li>
</ul>
<p>
	<a href=# class="info"><?php echo _("Music-on-Hold capture volume (%) ")?><span><br>
<?php echo _("0% - 100%: The volume of the music-on-hold capture relative to the maximum volume")?><br>
<?php echo _("Used for setting volume of music-on-hold captured from external input")?><br>
	</span></a>
	<input type="text" size="2" name="capturevol" value="<?php  echo htmlspecialchars($capturevol)?>"/>
</p>
<h6>
	<input name="Submit" type="submit" value="<?php echo _("Submit Changes")?>">
</h6>
<script language="javascript">
<!--

var theForm = document.extaudio;

function general_onsubmit() {
	var msgInvalidPercent = "<?php echo _('Please enter an whole number percentage between 0 and 100'); ?>";

	defaultEmptyOK = false;
	if (!isInteger(theForm.lineoutvol.value) || (theForm.lineoutvol.value > 100) || (theForm.lineoutvol.value < 0))
		return warnInvalid(theForm.lineoutvol, msgInvalidPercent);
	if (!isInteger(theForm.pagingvol.value) || (theForm.pagingvol.value > 100) || (theForm.pagingvol.value < 0))
		return warnInvalid(theForm.pagingvol, msgInvalidPercent);
	if (!isInteger(theForm.muzakvol.value) || (theForm.muzakvol.value > 100) || (theForm.muzakvol.value < 0))
		return warnInvalid(theForm.muzakvol, msgInvalidPercent);
	if (!isInteger(theForm.capturevol.value) || (theForm.capturevol.value > 100) || (theForm.capturevol.value < 0))
		return warnInvalid(theForm.capturevol, msgInvalidPercent);

	return true;
}

//-->
</script>
</form>


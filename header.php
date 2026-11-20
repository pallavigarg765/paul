<? 
	$page = $_SERVER['SCRIPT_NAME'];
	if(strstr($page,'/index.php') || strstr($page,'/login.php') || strstr($page,'/viewByDbId.php') || strstr($page, 'webOnly.php') || strstr($page, 'saveToWeb.php')){
		$class1 = "g";
	}else{
		$class1 = "f";
	}
	/*if(strstr($page,'/property.php') || strstr($page,'/addproperty.php') || strstr($page,'/editproperty.php')|| strstr($page,'/propertydetail.php')){
		$class2 = "g";
	}else{
		$class2 = "f";
	}
	if(strstr($page,'/contact.php') || strstr($page,'/createcontact.php') || strstr($page,'/editcontact.php') || strstr($page,'/viewcontact.php')){
		$class3 = "g";
	}else{
		$class3 = "f";
	}*/
	if(strstr($page,'/contact.php') || strstr($page,'/createcontact.php') || strstr($page,'/editcontact.php') || strstr($page,'/viewcontact.php') || 
	strstr($page,'/property.php') || strstr($page,'/addproperty.php') || strstr($page,'/editproperty.php')|| strstr($page,'/propertydetail.php') || 
	strstr($page,'/search.php') || strstr($page,'/searchresults.php') || strstr($page,'/advancesearch.php') || strstr($page,'/searchproperty.php') || strstr($page,'/quicksearchresults.php') || strstr($page,'/advancesearchresults.php') || strstr($page,'/searchpropertyresults.php')){
		$class4 = "g";
	}else{
		$class4 = "f";
	}
	if(strstr($page,'/user.php')){
		$class5 = "g";
	}else{
		$class5 = "f";
	}
	if(strstr($page,'/county.php') || strstr($page,'/country.php') || strstr($page,'/users.php') || strstr($page,'/state.php') || strstr($page,'/types.php') || strstr($page,'/baneip.php') || strstr($page,'/admin.php') || strstr($page,'/crmReport.php')){
		$class6 = "g";
	}else{
		$class6 = "f";
	}	
?>
<script type="text/javascript">
 
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-870212-1']);
  _gaq.push(['_trackPageview']);
 
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
 
</script>
<script type="text/javascript" src="js/ajax.js"></script>
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/ajax-dynamic-list.js"></script>
<script type="text/javascript" src="js/FormValidation.js"></script>


<script>
	jQuery.noConflict(); 
	function do_search(){
		window.location = 'quicksearchresults.php?pid='+document.getElementById("pid").value;
	}
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top"><br />
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="35%" align="left" valign="top" style="padding-left:15px;"><a href="index.php"><h1>CRM  </h1></a></td>
        <td width="65%" align="center" valign="top"><table width="98%" border="0" cellpadding="0" cellspacing="0">
          <tbody>
            <? if($_SESSION['userid'] != ''){?>
			<tr>
              <td width="39%" align="left" valign="top" nowrap="nowrap" class="Welcome"> Welcome, <strong><?=$_SESSION['username']?></strong> &nbsp;&nbsp;[ <a href="logout.php" class="utilsLink">Logout</a> ]</td>
              <td width="61%" align="right"><a href="user.php" class="myArea"></a>			    
				<table width="100%" border="0" cellspacing="0" cellpadding="1">
					<tr>
						<td width="55%" align="right" class="Welcome">
							<strong>Qucik Find By Property ID: </strong>
						</td>
						<td width="39%" align="left">
							<input name="pid" type="text" class="form" id="pid" onkeyup="ajax_showOptions(this, 'id', event, document.getElementById('chkIncludeCh').checked);"/>
						</td>
						<td width="6%" align="left">
							<input type="submit" name="Submit" value="Search" class="button" onclick="do_search();"/>
						</td>
					</tr>
					<tr>
						<td></td>
						<td class="Welcome">
							Include children? <input type="checkbox" id="chkIncludeCh" />
						</td>
						<td></td>
					</tr>
				</table>
			  </td>
            </tr>
			<? }?>
          </tbody>
        </table></td>
      </tr>
    </table>
    <br /></td>
  </tr>
  <tr>
    <td align="left" valign="top" class="nav-bg"><? if($_SESSION['userid'] != ''){?><table width="675" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="103" align="left"></td>
        <td align="center" valign="middle" width="92"><a href="index.php" class="<?=$class1?>">Home</a></td>
        <!--<td width="1"><? if($class1 != 'g' && $class2 != 'g'){?><img src="images/navsep.gif" width="1" height="21" /><? }?></td>
        <td align="center" valign="middle" width="92"><a href="property.php" class="<?=$class2?>">Property</a></td>
        <td width="1"><? if($class2 != 'g' && $class3 != 'g'){?><img src="images/navsep.gif" width="1" height="21" /><? }?></td>
        <td align="center" valign="middle" width="92"><a href="contact.php" class="<?=$class3?>">Contacts</a></td>
        --><td width="1"><? if($class1 != 'g' && $class4 != 'g'){?><img src="images/navsep.gif" width="1" height="21" /><? }?></td>
        <td align="center" valign="middle" width="90"><a href="search.php" class="<?=$class4?>">Search</a></td>
		<?php 
		if(($_SESSION['userid'] == 1) || ($_SESSION['userid'] == 15) || ($_SESSION['userid'] == 16) || ($_SESSION['userid'] == 5) || ($_SESSION['userid'] == 7)){
		?>
			<td width="1">
				<?php 
				if($class4 != 'g' && ($_SESSION['userid'] ==1 && $class6 != 'g')) {
					?>
					<img src="images/navsep.gif" width="1" height="21" />
					<?php 
				}
				?>
			</td>
	        <td align="center" valign="middle" width="88">
	        	<a href="admin.php" class="<?=$class6?>">Admin</a>
	        </td>
		<? 
		}
		?>
		<td width="1"><? if($class5 != 'g' && $class6 != 'g'){?><img src="images/navsep.gif" width="1" height="21" /><? }?></td>
        <td align="center" valign="middle" width="95"><a href="user.php" class="<?=$class5?>">My Account</a></td>
	 </tr>
    </table><? }?></td>
  </tr>
</table>
<?php 
include('include/dbcon.php');
include('include/functions.php');

$comma = "'";
$tnotes = '';

$db = new DBConn();
$type = $_REQUEST['type'];
$property_id = $_REQUEST['property_id'];
if(isset($_REQUEST['id']) && intval($_REQUEST['id'])){
	$db->query('DELETE FROM notes WHERE id='.$_REQUEST['id']);
}

$lastProp = $_COOKIE['lastProp'];

$parentId = $property_id;
$p = "";
$sql = "SELECT is_child FROM property WHERE id = '".$lastProp."'";
if($res = mysql_query($sql)){
	if(mysql_num_rows($res) > 0){
		$row = mysql_fetch_array($res);
		if($row['is_child'] == '0'){
			$isParent = 1;
			
		}else{
		
			$isParent = 0;
			$hasChildren = false;
			$sqlGetParent = "SELECT parent_id FROM parent_child WHERE child_id = '".$lastProp."'";
			if($resGetParent = mysql_query($sqlGetParent)){
				if(mysql_num_rows($resGetParent) > 0){
					$rowGetParent = mysql_fetch_array($resGetParent);
					$parentId = $rowGetParent['parent_id'];
				}
			}else{
				$tnotes .=  mysql_error();
				die;
			}
		}
	}
}else{
	$tnotes .=  mysql_error();
	die;
}

$sqlnotesChild = "";
if($isParent){
	$p = "";
	$sqlnotes = "SELECT * FROM notes WHERE type='".$type."' and property_id=".$parentId;
}else{	
	$p = "Parent ";
	$sqlnotes = "SELECT * FROM notes WHERE type='".$type."' and property_id=".$parentId;
	$sqlnotesChild = "SELECT * FROM notes WHERE type='".$type."' and property_id=".$lastProp;
}
$nresult = $db->query($sqlnotes) or die(mysql_error()." ".$sqlnotes);

$tnotes .= '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="listview">
			<tr bgcolor="#D0E1F4" height="20">
			  <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
			  <td height="25" colspan="3" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a">'.$p.' Notes&nbsp;&nbsp; </div>                        </td>
			  <td width="11%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a">User&nbsp;&nbsp; </div></td>
			  <td width="14%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a">Date Modified&nbsp;&nbsp; </div>                        </td>
			  <td width="10%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><span class="a">Action</span>&nbsp;&nbsp; <a href="#" onclick="window.open('.$comma.'notes.php?property_id='.$parentId.'&type='.$type.'&op=1'.$comma.','.$comma.noteswin.$comma.','.$comma.'height=190,width=310,scrollbars=0'.$comma.');return false;" class="n">Add New</a> </td>
			  <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
			</tr>';
//$tnotes .= "<tr><td>lastProp ".$lastProp."<br />"."isParent ".$isParent."<br />"."sqlnotes ".$sqlnotes."<br />"."sqlnotesChild ".$sqlnotesChild."<br /></td></tr>";
while($nrow = $db->fetchArray($nresult)){
	if($nrow['userid'] == '')
		$nrow['userid'] = 1;
	$tnotes .= '<tr height="20">
			  <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
			  <td height="25" colspan="3" nowrap="nowrap" class="listViewThS1" scope="col">'.$nrow['text'].'</td>
			  <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">'.ReturnName('user','username','id',$nrow['userid']).'</td>
			  <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">'.date("m/d/Y",strtotime($nrow['datentime'])).'</td>
			  <td width="10%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"> <a href="#" onclick="window.open('.$comma.'notes.php?id='.$nrow['id'].'&property_id='.$parentId.'&type='.$type.'&op=2'.$comma.','.$comma.'noteswin'.$comma.','.$comma.'height=190,width=310,scrollbars=0'.$comma.');return false;" class="n">Edit</a> | <a href="#" onClick="if(confirm('.$comma.'Are you sure want to delete this notes?'.$comma.')){deleteNotes('.$comma.$nrow['id'].$comma.','.$comma.$type.$comma.','.$comma.$parentId.$comma.');return false;}else{return false};" class="n">Delete</a> </td>
			  <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
			</tr>';
}
$tnotes .= '</table>';
if($sqlnotesChild != ""){
	$nresult = $db->query($sqlnotesChild) or die(mysql_error()." ".$sqlnotesChild);
	$tnotes .= '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="listview">
			<tr bgcolor="#D0E1F4" height="20">
			  <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
			  <td height="25" colspan="3" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a"> Notes&nbsp;&nbsp; </div>                        </td>
			  <td width="11%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a">User&nbsp;&nbsp; </div></td>
			  <td width="14%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap; float:left" width="100%" align="left" class="a">Date Modified&nbsp;&nbsp; </div>                        </td>
			  <td width="10%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><span class="a">Action</span>&nbsp;&nbsp; <a href="#" onclick="window.open('.$comma.'notes.php?property_id='.$lastProp.'&type='.$type.'&op=1'.$comma.','.$comma.noteswin.$comma.','.$comma.'height=190,width=310,scrollbars=0'.$comma.');return false;" class="n">Add New</a> </td>
			  <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
			</tr>';
	while($nrow = $db->fetchArray($nresult)){
		if($nrow['userid'] == '')
			$nrow['userid'] = 1;
		$tnotes .= '<tr height="20">
				  <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
				  <td height="25" colspan="3" nowrap="nowrap" class="listViewThS1" scope="col">'.$nrow['text'].'</td>
				  <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">'.ReturnName('user','username','id',$nrow['userid']).'</td>
				  <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">'.date("m/d/Y",strtotime($nrow['datentime'])).'</td>
				  <td width="10%" height="25" nowrap="nowrap" class="listViewThS1" scope="col"> <a href="#" onclick="window.open('.$comma.'notes.php?id='.$nrow['id'].'&property_id='.$lastProp.'&type='.$type.'&op=2'.$comma.','.$comma.'noteswin'.$comma.','.$comma.'height=190,width=310,scrollbars=0'.$comma.');return false;" class="n">Edit</a> | <a href="#" onClick="if(confirm('.$comma.'Are you sure want to delete this notes?'.$comma.')){deleteNotes('.$comma.$nrow['id'].$comma.','.$comma.$type.$comma.','.$comma.$lastProp.$comma.');return false;}else{return false};" class="n">Delete</a> </td>
				  <td width="2%" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
				</tr>';
	}
	$tnotes .= '</table>';
}

echo $tnotes;
?>

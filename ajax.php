<?php 
mysql_connect("localhost", "root","Securel0gi!12aa");
mysql_select_db("planitlo_crm");
include "include/functions.php";
if($_POST['action']=='prop_update')
{
	$propID=$_POST['propID'];
	
	$sel="SELECT is_show FROM property WHERE id='".$propID."'";
	$ex=mysql_query($sel);
	$fetch=mysql_fetch_array($ex);
	
	if($fetch['is_show']=='Y')
	{
		$sql="UPDATE  property SET is_show='N' WHERE id='".$propID."'";
	}
	else 
	{
		$sql="UPDATE  property SET is_show='Y' WHERE id='".$propID."'";
	}
	if($exe=mysql_query($sql))
	{
		echo "true";
	}
}
if($_POST['action']=='delete_prop')
{
	$propID=$_POST['propID'];	
	$sql="DELETE FROM property WHERE id='".$propID."'";
	if($exe=mysql_query($sql))
	{
		echo "true";
	}
}
if(isset($_REQUEST["oper"])&& $_REQUEST["oper"]=="add")
{
	$tag_name=$_REQUEST["tag_name"];	
	$category_id = $_REQUEST['is_child'];
	$color_code =$_REQUEST['color_code'];
	$propertyID=$_REQUEST['propertyID'];
	if($tag_name!='')
	{
		$sql="SELECT * FROM `tags` WHERE tag_name='".$tag_name."'";
		$exe=mysql_query($sql);
		
		if(mysql_num_rows($exe)>0)
		{ 
			$fetch=mysql_fetch_array($exe); 
			$tag_id=$fetch["tag_id"];	
			$error=1;
		}
		else 
		{
			$tag_insert="INSERT INTO `tags` SET `tag_name`='".$tag_name."',`color_code`='".$color_code."',`category_id`='".$category_id."'";
			$tag_exe=mysql_query($tag_insert);
			$tag_id=mysql_insert_id();
			
			$insert="INSERT INTO `properties_tags_relationships` SET `tag_id`='".$tag_id."',`property_id`='".$propertyID."'";
			mysql_query($insert);
			$error=0;
		}			
	}	
	echo $error;
}
if(isset($_REQUEST["oper"])&& $_REQUEST["oper"]=="remove")
{
	$relation_id=$_REQUEST["relation_id"];
	$property_id=$_REQUEST["property_id"];

	$sql="DELETE FROM properties_tags_relationships WHERE p_relation_id='".$relation_id."'";
	mysql_query($sql);
	
	echo loadTagsByPropertyID($property_id);
	
}
function getallchildtagsx($tag_id)
{
	$sel="SELECT * FROM tags WHERE is_child='".$tag_id."'";
	$exsel=mysql_query($sel);
	static $arr=array();
	if(mysql_num_rows($exsel)>0)
	{
		while($rows=mysql_fetch_array($exsel))
		{
			$arr[]=$rows['tag_id'];
			$selx="SELECT * FROM tags WHERE is_child='".$rows['tag_id']."'";
			$exselx=mysql_query($selx);
			if(mysql_num_rows($exselx)>0)
			{				
				getallchildtagsx($rows['tag_id']);
			}
		}
	}
	return $arr;
}
if(isset($_REQUEST["oper"])&& $_REQUEST["oper"]=="singleRem")
{
	$tag_id=$_REQUEST["tag_id"];
	
	$sel="SELECT * FROM tags WHERE is_child='".$tag_id."'";
	$exsel=mysql_query($sel);
	$get_tags=array();
	if(mysql_num_rows($exsel)>0)
	{
		$get_tags=getallchildtagsx($tag_id);
		array_push($get_tags,$tag_id);
		$tags=implode(',',$get_tags);
	}
	else 
	{
		$tags=$tag_id;
	}
	
	$tagDel="DELETE FROM tags WHERE tag_id IN($tags)";
	mysql_query($tagDel);
	
	$relSql="DELETE FROM properties_tags_relationships WHERE tag_id IN($tags)";
	mysql_query($relSql);
	
	/*
	if(mysql_num_rows($exsel)>0)
	{
		$ctagDel="DELETE FROM tags WHERE is_child='".$tag_id."'";
		mysql_query($ctagDel);
		
		$ptagDel="DELETE FROM tags WHERE tag_id='".$tag_id."'";
		mysql_query($ptagDel);
	}
	else 
	{
		$sql="DELETE FROM tags WHERE tag_id='".$tag_id."'";
		mysql_query($sql);
	}
	
	$relSql="DELETE FROM properties_tags_relationships WHERE tag_id='".$tag_id."'";
	mysql_query($relSql);
	
	*/
}
?>
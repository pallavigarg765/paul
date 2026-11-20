<?php 
mysql_connect("localhost", "root","Securel0gi!12aa");
mysql_select_db("planitlo_crm");
include "include/functions.php";
if($_REQUEST['action']=='tag_view')
{
$property_id=$_REQUEST['property_id']; 
/*$sql="SELECT DISTINCT(T.tag_name),T.tag_id,T.color_code,C.category FROM `tags` AS T
											 JOIN `tag_categories` AS C ON C.id=T.category_id 
									ORDER BY tag_name ASC"; */
$sql="SELECT DISTINCT(T.tag_name),T.tag_id,T.color_code,T.category_id FROM `tags` AS T ORDER BY T.tag_name ASC";
$exe=mysql_query($sql);

$propertyTotags="SELECT * FROM `properties_tags_relationships` WHERE property_id='".$property_id."'";
$propertyExe=mysql_query($propertyTotags);
$tags=array();
$i=0;
while($rows=mysql_fetch_array($propertyExe))
{
	$tags[$i]=$rows['tag_id'];
	$i=$i+1;
}
?>
<span class="clossTag">X</span>
<div id="tagBlockDiv<?php echo $property_id; ?>" class="tagBlockDiv" style="min-height:209px;">

<span class="tagTtl">Search tags</span>  
<div class="doubleField">
	<div class="bx">
		<input type="text" placeholder="Search by tag name"  id="searchTags_<?php echo $property_id; ?>" class="searchTags txtFld" />
		<input type="hidden" name="propertyHiddenID" class="propertyHiddenID" id="propertyHiddenID_<?php echo $property_id; ?>" value="<?php echo $property_id; ?>" />
	</div>
	<div class="bx">
		<div class="autoFill">
			<p class="noCategoryMsg error"></p>
			<input type="text" id="tagCategoryID" placeholder="Search by tag category" class="txtFld"/>
			<span style="display:none;" class="button" id="catAdd">Add</span>
			<div class="" id="tagAutofill">
				<div id="tagCategoryPlaceholder"></div>
				<span id="addLoader" style="display:none;"></span>
				<input type="hidden" id="srcPropertyID" value="<?php echo $property_id; ?>" />
				<input type="hidden" class="hiddenCategory" />
			</div>
		</div>
	</div>
	<div class="spacer"></div>
</div>
<div id="tag_listID" class="heightN">
<div class="tagBlockDiv" style="border:1px solid #000;">
	<ul class="tagColorTable tableCell4 tblhead">
		<li>
			<div class="cel1">Tags</div>
			<div class="cel2">Color</div>
			<div class="cel3">Category </div>
			<div class="cel4">&nbsp;</div>
		</li>
	</ul>
	<div class="tagColorTbleScroll">
		<ul class="tagColorTable tableCell4">
		<?php 
		while($v=mysql_fetch_array($exe))
		{
			if($v['color_code']!='')
			{
				$tag_color=$v['color_code'];
			}
			else 
			{
				$tag_color="#FFFFFF";
			}
		?>
			<li id="remForAjax_<?php echo $v['tag_id']; ?>">
				<div class="cel1">
					<input type="checkbox" value="<?php echo $v["tag_id"]; ?>" <?php if(in_array($v["tag_id"],$tags)==TRUE) { ?> checked <?php } ?> onclick="add_tags('<?php echo $property_id; ?>')" /><?php echo $v["tag_name"]; ?>
				</div>
				<div class="cel2">
				 <input class='simple_color_pickerxx' id="color_picker_<?php echo $v["tag_id"]; ?>" type="hidden" name="color_code" value="<?php echo $tag_color; ?>"/>
				</div>
				<div class="cel3">
					<?php 
					if($v['category_id']==0) { 
						$category_name="Uncategorized";
					} 
					else 
					{
						$csql="SELECT C.category FROM `tag_categories` AS C WHERE C.id='".$v['category_id']."'";
						$cexe=mysql_query($csql);
						$crows=mysql_fetch_array($cexe);
						$category_name=$crows['category'];						
					}
					echo $category_name; 
					?>
				</div>
				<div class="cel4">
					<a href="javascript:;" id="edit_<?php echo $v['tag_id'];?>_<?php echo $property_id; ?>" class="n doEdit">Edit</a>&nbsp; | &nbsp;
					<a href="javascript:;" id="del_<?php echo $v['tag_id']; ?>_<?php echo $property_id; ?>" class="n doDelete">Delete</a>					
				</div>
				<script type="text/javascript">
					 jQuery(document).ready(function(){ 
						jQuery("#color_picker_<?php echo $v["tag_id"]; ?>").colorPicker({ 
							onColorChange : function(id, newValue) { 
								jQuery.ajax({ 
									type : 'POST',
									url : 'ajax_tag.php',					
									data : 
									{ 
										tag_id :  '<?php echo $v["tag_id"]; ?>',
										color_code : newValue,
										action : 'change_color'
									},
									success : function(data)
									{
										jQuery.ajax({ 
											type : 'POST',
											url : 'ajax_tag.php',					
											data : 
											{ 
												property_id : '<?php echo $property_id; ?>',
												action : 'loadAllTagByproperty',
											},	
											success : function(res)
											{
												jQuery(".propertyTagID").html(res);
											}
										});
									}				
								});
							} 
						});
					 });
				</script>	
			</li>	
		<?php
		}
		?>
		</ul>
	</div>
	<div class="spacer"></div>
	</div>
</div>
	<div class="adTagBtns nwAddTagBtn">
		<div id="label_block_<?php echo $property_id; ?>" class="btnBX">
			<div><a href="javascript:;" onclick="lightbox_open();" class="add_new_tag adTAG button sm"><i class="fa fa-plus"></i> Add new tag</a></div>
		</div>	
		<a href="javascript:;" class="apply adTAG button sm applyAndClose" id="doapply_<?php echo $property_id; ?>_close">Apply &amp; Close</a>
		<a href="javascript:void(0);" class="apply adTAG button sm applyAndContinue" id="doapply_<?php echo $property_id; ?>_continue">Apply &amp; Continue</a>
	</div>
</div>
<?php 
}
?>

<?php
if($_REQUEST['action']=='search_tags')
{
	$sql="SELECT DISTINCT(T.tag_name),T.tag_id,T.color_code,T.category_id";
	if($_POST['tagCategory']!='')
	{
		$sql.=" ,C.category ";
	}
	$sql.=" FROM `tags` AS T ";	
	if($_POST['tagCategory']!='')
	{
	$sql.=" JOIN tag_categories AS C ON C.id=T.category_id"; 
	}
	if($_POST['searchTags']!='')
	{
		$sql.=" WHERE T.tag_name LIKE '%".$_POST['searchTags']."%'";
	}
	if($_POST['tagCategory']!='')
	{	
		$sql.=" WHERE C.id ='".$_POST['tagCategory']."'";
	}
	
$sql.=" ORDER BY T.tag_name ASC";
$exe=mysql_query($sql);
$property_id=$_REQUEST['property_id']; 

$propertyTotags="SELECT * FROM `properties_tags_relationships` WHERE property_id='".$property_id."'";
$propertyExe=mysql_query($propertyTotags);
$tags=array();
$i=0;
while($rows=mysql_fetch_array($propertyExe))
{
	$tags[$i]=$rows["tag_id"];
	$i=$i+1;
}
?>
	<div class="tagBlockDiv" style="border:1px solid #000;min-height:209px;">
		<ul class="tagColorTable tblhead tableCell4">
			<li>
				<div class="cel1">Tags</div>
				<div class="cel2">Color </div>
				<div class="cel3">Category</div>
				<div class="cel4"></div>
			</li>
		</ul>
		<div class="tagColorTbleScroll">
			<ul class="tagColorTable tableCell4">
			<?php 
			if(mysql_num_rows($exe)>0)
			{
				while($v=mysql_fetch_array($exe))
				{
					if($v['color_code']!='')
					{
						$tag_color=$v['color_code'];
					}
					else 
					{
						$tag_color="#FFFFFF";
					}
				?>
					<li id="remForAjax_<?php echo $v['tag_id']; ?>">
						<div class="cel1">
							<input type="checkbox" value="<?php echo $v["tag_id"]; ?>" <?php if(in_array($v["tag_id"],$tags)==TRUE) { ?> checked <?php } ?> onclick="add_tags('<?php echo $property_id; ?>')"><?php echo $v["tag_name"]; ?>
						</div>
						<div class="cel2">
							<input class='simple_color_pickerxx' id="color_picker_<?php echo $v["tag_id"]; ?>" type="hidden" name="color_code" value="<?php echo $tag_color; ?>"/>
						</div>
						<div class="cel3">
						<?php 
							if($v['category_id']==0) { 
								$category_name="Uncategorized";
							} 
							else 
							{
								$csql="SELECT C.category FROM `tag_categories` AS C WHERE C.id='".$v['category_id']."'";
								$cexe=mysql_query($csql);
								$crows=mysql_fetch_array($cexe);
								$category_name=$crows['category'];						
							}
							echo $category_name; 
						?>
						</div>
						<div class="cel4">
							<a href="javascript:;" id="edit_<?php echo $v['tag_id'];?>_<?php echo $property_id; ?>" class="n doEdit">Edit</a>&nbsp; | &nbsp;
							<a href="javascript:;" id="del_<?php echo $v['tag_id']; ?>_<?php echo $property_id; ?>" class="n doDelete">Delete</a>					
						</div>	
					</li>
					<script type="text/javascript">
					 jQuery(document).ready(function(){ 
						jQuery("#color_picker_<?php echo $v["tag_id"]; ?>").colorPicker({ 
							onColorChange : function(id, newValue) { 
								jQuery.ajax({ 
									type : 'POST',
									url : 'ajax_tag.php',					
									data : 
									{ 
										tag_id :  '<?php echo $v["tag_id"]; ?>',
										color_code : newValue,
										action : 'change_color'
									},
									success : function(data)
									{
										jQuery.ajax({ 
											type : 'POST',
											url : 'ajax_tag.php',					
											data : 
											{ 
												property_id : '<?php echo $property_id; ?>',
												action : 'loadAllTagByproperty',
											},	
											success : function(res)
											{
												jQuery(".propertyTagID").html(res);
											}
										});
									}				
								});
							} 
						});
					 });
				</script>
				<?php
				}
			}
			else 
			{
			?>
				<li>Tags not found.</li>
			<?php 	
			}
			?>
			</ul>
		</div>
		<div class="spacer"></div>
	</div>
<?php 
}
?>
<?php
if($_REQUEST['action']=='loadAgain')
{
	$property_id=$_REQUEST['property_id']; 
	//$sql="SELECT DISTINCT(`tag_name`),`tag_id`,`color_code`,`category_id` FROM `tags`  ORDER BY tag_name ASC";
	//$sql="SELECT DISTINCT(T.tag_name),T.tag_id,T.color_code,C.category FROM `tags` AS T JOIN `tag_categories` AS C ON C.id=T.category_id ORDER BY tag_name ASC";
	$sql="SELECT DISTINCT(T.tag_name),T.tag_id,T.color_code,T.category_id FROM `tags` AS T ORDER BY T.tag_name ASC";
	$exe=mysql_query($sql);

	$propertyTotags="SELECT * FROM `properties_tags_relationships` WHERE property_id='".$property_id."'";
	$propertyExe=mysql_query($propertyTotags);
	$tags=array();
	$i=0;
	while($rows=mysql_fetch_array($propertyExe))
	{
		$tags[$i]=$rows['tag_id'];
		$i=$i+1;
	}
?>
<div id="tag_listID_<?php echo $property_id; ?>" class="heightN">
<div class="tagBlockDiv" style="border:1px solid #000;">
	<ul class="tagColorTable tblhead tableCell4">
		<li>
			<div class="cel1">Tags</div>
			<div class="cel2">Color </div>
			<div class="cel3" >Category </div>
			<div class="cel4" > </div>
		</li>
	</ul>
	<div class="tagColorTbleScroll">
		<ul class="tagColorTable tableCell4">
		<?php 
		while($v=mysql_fetch_array($exe))
		{
			if($v['color_code']!='')
			{
				$tag_color=$v['color_code'];
			}
			else 
			{
				$tag_color="#FFFFFF";
			}
		?>
			<li id="remForAjax_<?php echo $v['tag_id']; ?>">
				<div class="cel1">
					<input type="checkbox" value="<?php echo $v["tag_id"]; ?>" <?php if(in_array($v["tag_id"],$tags)==TRUE) { ?> checked <?php } ?> onclick="add_tags('<?php echo $property_id; ?>')" /><?php echo $v["tag_name"]; ?>
				</div>
				<div class="cel2">
					<input class='simple_color_pickerxx' id="color_picker_<?php echo $v["tag_id"]; ?>" type="hidden" name="color_code" value="<?php echo $tag_color; ?>"/>
				</div>
				<div class="cel3">
					<?php 
					if($v['category_id']==0)
					{ 
						$category_name="Uncategorized";
					} 
					else 
					{
						$csql="SELECT C.category FROM `tag_categories` AS C WHERE C.id='".$v['category_id']."'";
						$cexe=mysql_query($csql);
						$crows=mysql_fetch_array($cexe);
						$category_name=$crows['category'];						
					}
					echo $category_name; 
					?>
				</div>
				<div class="cel4">
					<a href="javascript:;" id="edit_<?php echo $v['tag_id'];?>_<?php echo $property_id; ?>" class="n doEdit">Edit</a>&nbsp; | &nbsp;
					<a href="javascript:;" id="del_<?php echo $v['tag_id']; ?>_<?php echo $property_id; ?>" class="n doDelete">Delete</a>					
				</div>				
			</li>
			<script type="text/javascript">
					jQuery(document).ready(function(){  
						jQuery("#color_picker_<?php echo $v["tag_id"]; ?>").colorPicker({ 
							onColorChange : function(id, newValue) { 
								jQuery.ajax({ 
									type : 'POST',
									url : 'ajax_tag.php',					
									data : 
									{ 
										tag_id :  '<?php echo $v["tag_id"]; ?>',
										color_code : newValue,
										action : 'change_color'
									},
									success : function(data)
									{
										jQuery.ajax({ 
											type : 'POST',
											url : 'ajax_tag.php',					
											data : 
											{ 
												property_id : '<?php echo $property_id; ?>',
												action : 'loadAllTagByproperty',
											},	
											success : function(res)
											{
												jQuery(".propertyTagID").html(res);
											}
										});
									}				
								});
							} 
						});
					 });
				</script>			
		<?php
		}
		?>
		</ul>
	</div>
	<div class="spacer"></div>
</div>
<?php 
}
?>	
<?php 
if($_REQUEST['action']=='tag_apply')
{

	$tags=$_REQUEST['tags']; 
	$propertyID=$_REQUEST['property_id'];
	
	$del="DELETE FROM properties_tags_relationships WHERE property_id='".$propertyID."'";
	mysql_query($del);
	
	$tagArray=explode(',',$tags);
	if(count($tagArray)>0)
	{
		for($i=0;$i<count($tagArray);$i++)
		{
			$sql="SELECT * FROM properties_tags_relationships WHERE tag_id='".$tagArray[$i]."'  AND property_id='".$propertyID."'";
			$exe=mysql_query($sql);
			if(mysql_num_rows($exe)==0)
			{
				$tagsRelationshipsQuery="INSERT INTO properties_tags_relationships SET tag_id='".$tagArray[$i]."',
																					property_id='".$propertyID."'";
				mysql_query($tagsRelationshipsQuery);
			}
		}
	}
	echo loadTagsByPropertyID($propertyID);
}
?>
<?php 
if($_REQUEST['action']=='view_allTags')
{
	$sql="SELECT * FROM `tags` WHERE `is_child`=0 ORDER BY tag_name ASC";
	$exe=mysql_query($sql);
	?>
	<div class="tagBlockDiv" style="width:100%;">
		<ul class="tagColorTable tblhead">
			<li>
				<div class="cel1">Tags</div>
				<div class="cel2">Color Code</div>
				<div class="cel3">Action</div>
			</li>
		</ul>
		<div class="tagColorTbleScroll">
			<ul class="tagColorTable">
			<?php 
			while($rows=mysql_fetch_array($exe))
			{
				if($rows['color_code']!='')
				{
					$tag_color=$rows['color_code'];
				}
				else 
				{
					$tag_color="#FFFFFF";
				}
			?>
				<li id="remForAjax_<?php echo $rows['tag_id']; ?>">
					<div class="cel1">
						<?php echo $rows['tag_name']; ?>
					</div>
					<div class="cel2">
						&nbsp;&nbsp;<span style="width:32px; height:12px; border:1px solid #C5C5C5; background-color:<?php echo $tag_color; ?>"></span>
					</div>
					<div class="cel3"></div>
					<?php /* ?><a href="javascript:;" id="del_<?php echo $rows['tag_id']; ?>" class="doDelete"><i class="fa fa-close"></i></a><a href="javascript:;" id="edit_<?php echo $rows['tag_id'];?>" class="doEdit"><i class="fa fa-pencil"></i></a> <?php */ ?>
					
				<?php 
				$sql="SELECT * FROM `planitlo_crm`.`tags` WHERE  `is_child`='".$rows['tag_id']."'";
				$ex=mysql_query($sql);
				if(mysql_num_rows($ex)>0)
				{
					getallChildtags($rows['tag_id']);
				}
				?>
				<div class="spacer"></div>
				</li>
			<?php 
			}
			?>
			</ul>
		</div>
		<div class="spacer"></div>
	</div>
<?php 
}
?>
<?php 
if($_REQUEST['action']=='change_color')
{
	$tag_id=$_REQUEST['tag_id'];
	$color_code=$_REQUEST['color_code'];
	
	$sql="UPDATE tags SET color_code='".$color_code."' WHERE tag_id='".$tag_id."'";
	mysql_query($sql);
}
?>
<?php 
if($_REQUEST['action']=='edit')
{
	
	$tag_id=$_POST['tag_id'];
	$sql="SELECT * FROM `tags` WHERE tag_id='".$tag_id."'";
	$exe=mysql_query($sql);
	$rows=mysql_fetch_array($exe);
	
	if($rows['category_id']!=0)
	{
		$p_sql="SELECT * FROM `tag_categories` WHERE `id`='".$rows['category_id']."'";   
		$p_exe=mysql_query($p_sql);
		$p_rows=mysql_fetch_array($p_exe);
		$category=$p_rows['category']; 
	}
	else 
	{
		$category='';
	}
	
	echo json_encode(array(
			'tag_id'=>$rows['tag_id'],
			'tag_name'=>$rows['tag_name'],			
			'category_id'=>$rows['category_id'],
			'category_name'=>$category,
			'color_code'=>$rows['color_code']
		));
}
?>
<?php 
if($_REQUEST["action"]=="loadAllTagByproperty")
{
	$propertyID=$_REQUEST['property_id'];
	echo loadTagsByPropertyID($propertyID);
}
?>
<?php
if($_REQUEST['action']=='actionEdit')
{
	$tag_id=$_POST['tag_id'];
	$tag_name=$_POST['tag_name'];
	$category_id=$_POST['category_id'];
	$color_code=$_POST['color_code'];
	
	$sqlx="UPDATE `tags` SET tag_name='".$tag_name."',color_code='".$color_code."',category_id='".$category_id."' WHERE tag_id='".$tag_id."'";
	mysql_query($sqlx);	
}
?>
<?php 
if(isset($_REQUEST["action"])&& $_REQUEST["action"]=="autocomplete")
{
	$tags=$_REQUEST['tags'];
	$sql="SELECT DISTINCT(`tag_name`),`tag_id` FROM `tags` ";
	if($tags!='')
	{
	$sql.="WHERE `tag_name` LIKE '%".$tags."%'";
	}
	$sql.="ORDER BY tag_name ASC";
	$exe=mysql_query($sql);
?>
<div class="tagBlockDiv">
	<div  class="heightN">
		<ul>
		<?php
		if(mysql_num_rows($exe)>0)
		{
			while($v=mysql_fetch_array($exe))
			{
				$relToProperty="SELECT COUNT(`property_id`) AS no_of_property FROM `properties_tags_relationships` WHERE `tag_id`='".$v["tag_id"]."'";
				$relExe=mysql_query($relToProperty);
				$rows=mysql_fetch_array($relExe);
			?>
				<li class="addToSearch" id="addToTag_<?php echo $v["tag_id"]; ?>"><?php echo $v["tag_name"]; ?> &nbsp;(<?php echo $rows['no_of_property']; ?>)</li>
			<?php
			}
		}
		else 
		{
		?>
			<li>No tags are available.</li>
		<?php
		}
		?>
		</ul>
	</div>
	<?php 
	if(mysql_num_rows($exe)>0)
	{
	?>
		<div>
			<a href="javascript:;" onclick="manage_open();" class="manage_tag adTAG"><i class="fa fa fa-cog"></i> Manage tags</a>
		</div>
	
	<?php 
	}
	?>
</div>
<?php 
}
?>
<?php
if($_REQUEST['oper']=='getSingleTag')
{
	$tag_id=$_POST['tag_id'];
	
	$sql="SELECT * FROM tags WHERE tag_id='".$tag_id."'";
	$exe=mysql_query($sql);
	$rows=mysql_fetch_array($exe);
	
	if($rows['color_code']!='')
	{
		$tag_color=$rows['color_code'];
	}
	else 
	{
		$tag_color="#F5F5F5";
	}
	
	$relToProperty="SELECT COUNT(`property_id`) AS no_of_property FROM `properties_tags_relationships` WHERE `tag_id`='".$tag_id."'";
	$relExe=mysql_query($relToProperty);
	$relRowsCnt=mysql_fetch_array($relExe);
?>
	<div class="singleTag getID" style="background-color:<?php echo $tag_color; ?>" id="getID_<?php echo $rows['tag_id']; ?>"><?php echo $rows['tag_name']; ?>
		<span><?php echo $relRowsCnt['no_of_property']; ?></span>
		<a  href="javascript:void(0);">
			<span class="close" onclick="removedGetID(<?php echo $rows['tag_id']; ?>);">X</span>
		</a>
		<input type="hidden" name="hidTagIDArr[]" value="<?php echo $tag_id; ?>" />
	</div>
<?php 
}
?>
<?php 
if($_REQUEST['action']=='hierarchical')
{
	$sel="SELECT * FROM `planitlo_crm`.`tag_categories` WHERE status='Y' ORDER BY category ASC";
	$exe=mysql_query($sel);
	$mode=$_REQUEST['mode'];
	$tag_id=$_REQUEST['tag_id'];
?>	
<div class="selCatg">
	<span id="sel_parent" class="sel_parent"></span>
	<ul class="subSelCatg">
		<li class="tags"><span id="tag_0" class="emptySpan">Select Category</span></li>
	<?php 
	while($rows=mysql_fetch_array($exe))
	{
	?>
		<li class="tags">
			<span id="tag_<?php echo $rows['id']; ?>">
				<b><?php echo $rows['category']; ?></b>
			</span>		
		</li>	
	<?php 
	}
	?>
	</ul>
</div>
<?php 
}
?>
<?php 
if($_REQUEST['action']=='autoCompleteCategory')
{
	if($_POST['keywords']!='')
	{
		$sql="SELECT * FROM tag_categories";
		$sql.=" WHERE category LIKE '".$_POST['keywords']."%'";
		$sql.=" ORDER BY category ASC LIMIT 0,10";
	
		
		$check="SELECT * FROM tag_categories WHERE category ='".$_POST['keywords']."'";
		$execute=mysql_query($check);
		$nos=mysql_num_rows($execute);
		
		if($nos==0) { $flag=1; }else{ $flag=0; }
		
		$exe=mysql_query($sql);
		$str="";
		
		if(mysql_num_rows($exe)>0)
		{		
			$str.="<ul class='categoryListing'>";
			while($rows=mysql_fetch_array($exe))
			{
				$cnt="SELECT * FROM tags WHERE category_id='".$rows['id']."'";
				$ex=mysql_query($cnt);
				$tot_nos=mysql_num_rows($ex);
				
				$str.="<li id='remove_tagcategory_".$rows['id']."'  class='autoCategoryListing'><span id='cat_".$rows['id']."'>".$rows['category']."&nbsp;(".$tot_nos.")</span><a href='javascript:;' id='delete_".$rows['id']."' class='doCategoryDel'>Delete</a></li>";			
			}
			$str.="</ul>";
		}
		else 
		{
			$str="";
		}
	}
	else 
	{
		$sql="SELECT * FROM tag_categories";
		$sql.=" ORDER BY category ASC";
	
		$exe=mysql_query($sql);
		$str="";
		
		if(mysql_num_rows($exe)>0)
		{		
			$str.="<ul class='categoryListing'>";
			while($rows=mysql_fetch_array($exe))
			{
				$cnt="SELECT * FROM tags WHERE category_id='".$rows['id']."'";
				$ex=mysql_query($cnt);
				$tot_nos=mysql_num_rows($ex);
				
				$str.="<li id='remove_tagcategory_".$rows['id']."'  class='autoCategoryListing'><span id='cat_".$rows['id']."'>".$rows['category']."&nbsp;(".$tot_nos.")</span><a href='javascript:;' id='delete_".$rows['id']."' class='doCategoryDel'>Delete</a></li>";			
			}
			$str.="</ul>";
			$flag=0;
		}
		else 
		{
			$str="";
			$flag=1;
		}
		
	}
	echo json_encode(array('list'=>$str,'flag'=>$flag));	
}
?>
<?php 
if($_REQUEST['action']=='autoCompleteCategoryCRUD')
{
	if($_POST['keywords']!='')
	{
		$sql="SELECT * FROM tag_categories";
		$sql.=" WHERE category LIKE '".$_POST['keywords']."%'";
		$sql.=" ORDER BY category ASC LIMIT 0,10";
	
		
		$check="SELECT * FROM tag_categories WHERE category ='".$_POST['keywords']."'";
		$execute=mysql_query($check);
		$nos=mysql_num_rows($execute);
		
		if($nos==0) { $flag=1; }else{ $flag=0; }
		
		$exe=mysql_query($sql);
		$str="";
			
		$str.="<ul class='categoryListing'>";
		while($rows=mysql_fetch_array($exe))
		{
			$cnt="SELECT * FROM tags WHERE category_id='".$rows['id']."'";
			$ex=mysql_query($cnt);
			$tot_nos=mysql_num_rows($ex);			
			$str.="<li id='dremove_tagcategory_".$rows['id']."' class='autoCategoryListingCRUD'><span id='dcat_".$rows['id']."'>".$rows['category']."&nbsp;(".$tot_nos.")</span><a href='javascript:;' id='ddelete_".$rows['id']."' class='doCategoryDel doCategoryDelCRUD'>Delete</a></li>";		
		}
		$str.="</ul>";		
	}
	else 
	{
		$sql="SELECT * FROM tag_categories";
		$sql.=" ORDER BY category ASC";
	
		$exe=mysql_query($sql);
		$str="";
		if(mysql_num_rows($exe)>0)
		{
			$str.="<ul class='categoryListing'>";
			while($rows=mysql_fetch_array($exe))
			{
				$cnt="SELECT * FROM tags WHERE category_id='".$rows['id']."'";
				$ex=mysql_query($cnt);
				$tot_nos=mysql_num_rows($ex);			
				$str.="<li id='dremove_tagcategory_".$rows['id']."' class='autoCategoryListingCRUD'><span id='dcat_".$rows['id']."'>".$rows['category']."&nbsp;(".$tot_nos.")</span><a href='javascript:;' id='ddelete_".$rows['id']."' class='doCategoryDel doCategoryDelCRUD'>Delete</a></li>";		
			}
			$str.="</ul>";
			$flag=0;
			
		}
		else 
		{
			$str="";
			$flag=1;
		}
		
	}
	echo json_encode(array('list'=>$str,'flag'=>$flag));	
}
?>
<?php 
if($_REQUEST['action']=='addCategory')
{
	$arr=array();
	$category=$_REQUEST['category'];
	if($category!='')
	{
		$check="SELECT id,category FROM tag_categories WHERE category='".$category."'";
		$checkExe=mysql_query($check);
		
		if(mysql_num_rows($checkExe)>0)
		{
			$resultSet=mysql_fetch_array($checkExe);
			$flag=0;
			$categoryID=$resultSet['id'];
		}
		else 
		{
			$sql="INSERT INTO tag_categories SET category='".$category."',posted_time='".time()."'";
			mysql_query($sql);
			$categoryID=mysql_insert_id();
			$flag=1;
		}
	}
	else 
	{
		$flag=2;
	}
	$arr['flag']=$flag;
	$arr['categoryID']=$categoryID;
	echo json_encode($arr);
}
?>
<?php 
if($_REQUEST["action"]=="delete_category")
{
	$categoryID=$_REQUEST["categoryID"];
	
	$sql="DELETE FROM tag_categories WHERE id='".$categoryID."'";
	mysql_query($sql);	
	
	$TagsSql="UPDATE tags SET category_id=0 WHERE category_id='".$categoryID."'";
	mysql_query($TagsSql);	
}
?>
<?php 
if($_REQUEST["action"]=="categoryEdit")
{
	$c_id=$_REQUEST["c_id"];
	
	$p_sql="SELECT * FROM `tag_categories` WHERE `id`='".$c_id."'";   
	$p_exe=mysql_query($p_sql);
	$p_rows=mysql_fetch_array($p_exe);
	$category=$p_rows['category']; 

	echo json_encode(
		array(
			'category_id'=>$p_rows['id'],
			'category_name'=>$category
		)
	);
}
?>
<?php
if($_REQUEST['action']=='actionEditCategory')
{
	$category=$_POST['category'];
	$category_id=$_POST['category_id'];
	
	$sel="SELECT * FROM `tag_categories` WHERE category='".$category."' AND id!='".$category_id."'";
	$exe=mysql_query($sel);
	
	if(mysql_num_rows($exe)>0)
	{
		$flag=1;
	}
	else 
	{
		$sqlx="UPDATE `tag_categories` SET category='".$category."' WHERE id='".$category_id."'";
		mysql_query($sqlx);		
		$flag=0;
	}
	echo $flag;	
}
?>




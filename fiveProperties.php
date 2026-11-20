<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>CRM</title>
		<script type="text/javascript" src="js/jquery.js"></script>
		<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
		<link type="text/css" href="css/base/jquery.ui.all.css" rel="stylesheet" />
		<style>
			#left{
				float: left;
				width: 400px;
				height: 500px;
				overflow-y: scroll;
				border: 1px solid gray;
				margin: 30px;
				padding: 20px;
			}
			#right{
				float: left;
				width: 400px;
				height: 500px;
				overflow-y: scroll;
				border: 1px solid gray;
				margin: 30px;
				padding: 20px;
			}
			.addProp{
				cursor: pointer;
				width: 370px;
				height: 120px;
			}
			#wrap{
				width: 1010px;
				margin: 0 auto;
			}
		</style>
		<script type="text/javascript">
			$(document).ready(function(){
				$(".addProp").click(function(){
					var category = $("#hidCategory").val();
					var pos = $("#hidPos").val();
					var propName = $(this).attr("id");
					if(propName != ""){
						var r = confirm("This will insert the selected property on the empty spot. Are you sure that you want to use this property?");
						if (r == true){
							jQuery.post("ajaxV.php", { 
									task: "addClickedToEmpty",
									category: category,
									propName: propName,
									pos: pos
								},function(data){
									alert(data);
									window.close();
							}); 
						}
					}
				});
			});
		</script>
	</head>
	<body bgcolor="#000000" text="silver" link="#666666" vlink="#666666" alink="#666666" background="images/background.jpg">
		<div id="wrap">
			<?php	                                       			 
			$link = mysql_connect("localhost", "root", "Securel0gi!12aa");
			if($link){
				mysql_select_db("planitlo_locations");
				$category = mysql_real_escape_string($_GET['cat']);
				$pos = mysql_real_escape_string($_GET['pos']);
				$category = trim($category, " ");
				$pos = trim($pos, " ");
				$selProps = array();
				
				$sql = "SELECT * FROM sortings WHERE cat_name = '".$category."'";
				if($res = mysql_query($sql)){
					$row = mysql_fetch_array($res);
					$arrAllProps = explode(",", $row['sorting']);
					$selProps = $arrAllProps;
				}else{				
					echo mysql_error();
					die;
				}
				
				$page = ceil($pos / 6);
				
				$start = $pos % 6;
				$start = $pos - $start;
				$start = $start - 1;
				$end = $start + 6;
				?>
				<input type="hidden" value="<?php echo $category ?>" id="hidCategory" />
				<input type="hidden" value="<?php echo $pos ?>" id="hidPos" />
				<div style="margin-left: 30px;margin-top: 30px;">
					Select one property from the right side and it will fill the empty position in the category.
				</div>
				<div id="left">
					<?php	                                       			 
					echo "<div style='text-align: center'>Properties on page: ".$page."</div><br /><br />";
					echo $start." ".$end;
					for($i = $start; $i < $end; $i++){
						if(isset($selProps[$i])){
							$sql = "SELECT prop_loc, tn_domain_loc, thumb01, thumb02, thumb03 FROM prop_info WHERE prop_name = '".$selProps[$i]."'";
							if($res = mysql_query($sql)){
								$row = mysql_fetch_array($res);
								if($i == ($pos - 1)){
									?>
									<div class="addProp" style="vertical-align: middle; text-align: center;">
										Empty spot. This place will be filled after selecting on one the properties. 
									</div>
									<?php	                                       			
								}else{
									?>
									<div id="<?php echo $row['prop_loc'] ?>" class="addProp">
										<div>
											<?php echo $selProps[$i] ?>
										</div>
										<img src="<?php echo $row['tn_domain_loc'].$row['thumb01'] ?>" alt="" width="120" />
										<img src="<?php	echo $row['tn_domain_loc'].$row['thumb02'] ?>" alt="" width="120" />
										<img src="<?php echo $row['tn_domain_loc'].$row['thumb03'] ?>" alt="" width="120" />
									</div>
									<?php	                                       			
								}
							}
						}
					}
					?>
				</div>
				<div id="right">
					<?php	                                       			 
					$i = 0;
					foreach($arrAllProps as $prop){
						$i++;
						$p = ceil($i / 6);
						//if($p != $page){
						 	$sql = "SELECT prop_loc, tn_domain_loc, thumb01, thumb02, thumb03 FROM prop_info WHERE prop_name = '".$prop."'";
							if($res = mysql_query($sql)){
								$row = mysql_fetch_array($res);
								if($i == ($pos + 1)){
									?>
									<div class="addProp" style="vertical-align: middle; text-align: center;">
										Empty spot. This place will be filled after selecting on one the properties.
									</div>
									<?php 
								}
								?>
								<div id="<?php echo $prop ?>" class="addProp">
									<div>
										<?php echo $prop ?>
									</div>
									<img src="<?php echo $row['tn_domain_loc'].$row['thumb01'] ?>" alt="" width="120" />
									<img src="<?php	echo $row['tn_domain_loc'].$row['thumb02'] ?>" alt="" width="120" />
									<img src="<?php	echo $row['tn_domain_loc'].$row['thumb03'] ?>" alt="" width="120" />
								</div>
								<?php	                                       			
								if(($i % 6) == 0){
									echo "<div style='text-align: center'>Page: ".($i / 6)."</div><br /><br />";
								}	
							}else{
								echo mysql_error(); 
								die;
							}
						//}
					}
					?>
				</div>
				<div style="clear: both"></div>
				<div style="margin-right: 34px;text-align: right;">
					<input type="button" id="save" value="Save" style="margin-left: 30px;" />
				</div>
				<?php	                                       			 
			}
			?>
		</div>
	</body>
</html>
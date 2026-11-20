<html>
	<head>
		<script type="text/javascript" src="js/sorttable.js"></script>
		<style>
			/* Sortable tables */
			table.sortable thead {
				background-color:#eee;
				color:#666666;
				font-weight: bold;
				cursor: default;
			}
		</style>
		<title>Check category</title>
	</head>
	<body>
		<?php	                                       					                                       			 
		$link = mysql_connect('localhost', 'root', 'Securel0gi!12aa');
		mysql_select_db("planitlo_locations", $link);
		if(isset($_GET['cat']) && ($_GET['cat'] != "")){
			//$sql = "SELECT * FROM sorting WHERE category1 = '".$_GET['cat']."' ORDER BY Priority ASC";
			
			$sql = "SELECT DISTINCT *
				FROM prop_info 
				INNER JOIN sorting 
				ON prop_info.prop_loc = sorting.prop_loc
				WHERE sorting.category1 = '".$_GET['cat']."'
				ORDER BY prop_info.prop_name3, sorting.Priority DESC";
			
			if($res = mysql_query($sql)){
				?>
				<b>Category: <?php	                                       					                                       			 echo $_GET['cat'] ?></b>
				<table border='0' cellpadding='2' class="sortable">
					<tr>
						<th>Property</th>
						<th>Order</th>
						<th>Prop name 3</th>
					</tr>
				<?php	                                       					                                       			
				$c = 0;
				$page = 0;
				while($row = mysql_fetch_array($res)){
					$c++;
					?>
					<tr>
						<td>
							<a href="https://planitlocations.com/search/searchid.php?id=<?php	                                       					                                       			 echo $row['prop_loc'] ?>&id6=&id2=&id7=&id3=&id8=&id4=&id9=&id5=&id10=&B1=Submit" target="_blank">
								<?php	                                       					                                       			 echo $row['prop_loc']; ?>
							</a>
						</td>
						<td>
							<?php	                                       					                                       			 echo $row['Priority']; ?>
						</td>
						<td>
							<?php	                                       					                                       			 
							if($row['prop_name3'] != ""){
								echo "True";
							}else{
								echo "False";
							}
							?>
						</td>
					</tr>
					<?php	                                       					                                       			 
					if($c % 6 == 0){
						$page++;
						echo "<tr><td colspan='3' align='center'> ~ ".$page." ~ </td></tr>";
					}
				}
				//echo "<tr><td colspan='2' align='center'> ~ ".($page + 1)." ~ </td></tr>";
			}
		}
		?>
	</body>
<html>
 				<? 
				  	session_start();
					$current_page = 'searchresults.php';
					$criteria = "";
					
					$sql_list_query = "SELECT distinct property.id, property.property_id, property.company,property.city,contact.id as cid,contact.firstname,contact.lastname FROM property,contact
									  where contact.id=(SELECT contact_id FROM contact_to_property WHERE property_id=property.id LIMIT 0,1) AND property.active=1 ";
					
					// Simple Search Criteria
					
					//echo $sql_list_query;
					   
					// Paging Details
					$sql_list_query .= $_REQUEST['query'];
					$go = 1;
					$perpage = 10;
					if(isset($_REQUEST['go']) && $_REQUEST['go'] != '')
						$go = $_REQUEST['go'];
					if($go > 1){
						$chkgo = "?go=".$go;
					}
					$offset = (($go * $perpage) - $perpage);
					$result_total = $db->query($sql_list_query) or die(mysql_error()." ".$sql_list_query);
					$totalrecs = $db->getNumRows($result_total);
					$sql_list_query .= " limit $offset,$perpage";
					$result = $db->query($sql_list_query) or die(mysql_error()." ".$sql_list_query);
					$count=$db->getNumRows($result);
					$total_pages = ceil($totalrecs / $perpage);
					$start_var = $offset + 1;
					$end_var_records = $count + (($go-1)*$perpage);
					$end_var = $start_var + $perpage - 1;
					$paging_var = "";
					// Build Previous Pagin Link 
					if($go > 1){ 
						$prev = ($go - 1); 
						$paging_var .= "<a href=\"".$current_page."?go=".$prev."\" style='text-decoration:none'><img src='images/previous.gif' alt='Previous' align='absmiddle' border='0' width='8' height='11' /> </a>"; 
					} 
					// Build Paging Link 
					//for($i = 1; $i <= $total_pages; $i++){
						$paging_var .=  "<span> (".($offset+1)." - ".($offset+$perpage)." of ".$totalrecs.") </span>"; 
					//} 
					// Build Next Paging Link 
					if($go < $total_pages){ 
						$next = ($go + 1); 
						$paging_var .=  "<a href=\"".$current_page."?go=".$next."\"  style='text-decoration:none'><img src='images/next.gif' alt='Next' align='absmiddle' border='0' width='8' height='11' /> </a>"; 
					}
					$result = $db->query($sql_list_query) or die($sql_list_query);
				  
				  echo '<table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="left" valign="top" class="moduleTitle">Search Results </td>
                      <td align="left" valign="top">&nbsp;</td>
                    </tr>
                  </table>
				  
                    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                      <tr>
                        <td align="left" valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="listview">
                          <tr>
                            <td width="13" height="18" align="left" valign="top">&nbsp;</td>
                            <td width="107" align="left" valign="top">&nbsp;</td>
                            <td colspan="5" align="right" valign="top"><span style="padding-right:10px;">
                              $paging_var
                            </span></span></td>
                          </tr>
                          <tr bgcolor="#D0E1F4" height="20">
                            <td height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                            <td height="25" nowrap="nowrap" class="listViewThS1" scope="col"><div style="white-space: nowrap;" width="100%" align="left"> <strong>Property ID&nbsp;</strong>&nbsp; </div></td>
                            <td width="123" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong> Name</strong>&nbsp;</td>
                            <td width="130" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Company</strong></td>
                            <td width="271" height="25" align="left" nowrap="nowrap" class="listViewThS1" scope="col"><strong>City</strong></td>
                            <td width="85" height="25" nowrap="nowrap" class="listViewThS1" scope="col"><strong>Action</strong>&nbsp;&nbsp;</td>
                            <td width="21" height="25" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                          </tr>';
                           
			$numrows = $db->getNumRows($result);
			while($lrow = $db->fetchArray($result)){
			echo '
                          <tr class="grid" height="20">
                            <td width="13" height="20" nowrap="nowrap" class="listViewThS1" scope="col">&nbsp;</td>
                            <td width="107" nowrap="nowrap" scope="col" class="n">'.$lrow['property_id'].'
                                </td>
                            <td height="25" nowrap="nowrap" class="n" scope="col"><a href="propertydetail.php?id='.$lrow['id'].'" class="n">
                              '.$lrow['firstname'].'
                              '.$lrow['lastname'].'
                            </a></td>
                            <td height="25" nowrap="nowrap" class="n" scope="col">'.$lrow['company'].'</td>
                            <td height="25" nowrap="nowrap" class="n" scope="col">'.$lrow['city'].'</td>
                            <td width="85" height="20" nowrap="nowrap" scope="col"><a href="propertydetail.php?id='.$lrow['id'].'" class="n"> View </a> | <a href="editproperty.php?id='.$lrow['id'].'" class="n">Edit</a></td>
                          	<td width="21" height="25" nowrap="nowrap" class="col" scope="col">&nbsp;</td>
						  </tr>';
                          }if($numrows < 1){ echo '<tr><td class="formtxt" colspan="6"> No Records against your search criteria.</td></tr>';}
                       echo '</table></td>
                      </tr>
                    </table>';
					
					?>
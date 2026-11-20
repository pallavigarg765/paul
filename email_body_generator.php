<? 
	include('include/security.php');
	include('include/dbcon.php');
	include('include/functions.php');
	$db = new DBConn();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>CRM</title>
<link href="css/crmstyle.css" rel="stylesheet" type="text/css" />
<link type="text/css" href="css/technocss.css" rel="stylesheet" />	
<link rel="stylesheet" href="simple_color_picker_master/css/colorPicker.css" type="text/css" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style type="text/css">
	label.fldttl22 {
    color: green;
    margin-top: 10px;
    display: block;
    margin-bottom: 10px;
    font-size: 12px;
}
.input-group {
  margin-bottom: 10px;
  display: flex;
  gap: 12px;
  align-items: center;
}
.pad-l-5
{
  padding-left: 5px;
  padding-top: 5px;
}
.clipboarddiv {
  background-color: #e8e8e845;
}

.break-lines {
  max-width: 100%;
  white-space: normal; /* Ensures text wraps inside the div */
  word-wrap: break-word; /* Breaks long words if necessary */
  word-break: break-word; /* Ensures long words are broken */
  overflow-wrap: break-word; /* Modern equivalent for word breaking */
}
.flexclass {
    display: flex;
    align-items: center;
    gap: 10px; /* Adjust spacing between the link and button */
}
.nocontent
{
  text-align: center;
  padding: 168px 0;
}
.copyatt {
  font-size: 10px;
}
</style>
<script>
  function manageButtons() {
    $('.input-group').each(function (index) {
        var $inputGroup = $(this);
        // Remove any existing buttons to prevent duplication
        $inputGroup.find('.remove-btn, .addField').remove();

        if (index === $('.input-group').length - 1) {
            // Last input group: Add only the "Add" button
            $inputGroup.append('<a href="javascript:;" class="addField"><img src="images/add.png" alt="Add New" width="16" height="16" border="0"></a>');
        } else {
            // For all previous input groups: Add only the "Remove" button
            $inputGroup.append('<a href="javascript:;" class="remove-btn"><img src="images/close_dashboard.gif" alt="Remove" width="16" height="16" border="0"></a>');
        }
    });
  }
        $(document).ready(function () {
            manageButtons();
            // Add new input field
            $(document).on('click', '.addField', function () {
                $('#inputContainer').append(`
                    <div class="input-group">
                        <input type="text" name="pid[]" id="pid[]" placeholder="Enter value" onkeyup="ajax_showOptions(this,'id',event);" />
                    </div>
                `);
                manageButtons();
            });
            
            // Remove input field
            $(document).on('click', '.remove-btn', function () {
                $(this).parents('.input-group').remove();
                manageButtons();
            });

            // Form submission handler
            $('#submitForm').click(function (e) {
                // Serialize form data
                e.preventDefault();
                const formData = $('#dynamicForm').serialize();

                // Send data via AJAX
                $.ajax({
                    url: 'email_body_generator_ajax.php', // Replace with your server endpoint
                    type: 'POST',
                    data: formData,
                    success: function (response) {
                      if (response && response!="") {
                        $('#outputText').html(response);
                        $('#copyButton').show();
                      } else {
                        $('#outputText').html('<div class="nocontent"> No content found! </div>');
                        $('#copyButton').hide();
                      }
                    },
                    error: function (xhr, status, error) {
                        alert('Error submitting form!');
                        console.error(error); // Log any errors
                    }
                });
            });

            $(document).on('click', '.copyatt', function () {
                // Collect content from one div (data-copyid) or several divs (data-copyall, comma separated)
                const ids = ($(this).attr('data-copyall') || $(this).attr('data-copyid')).split(',');
                const parts = [];
                $.each(ids, function (i, id) {
                  const $content = $('#outputText').find('#' + id);
                  if ($content.length) {
                    parts.push($content.html());
                  }
                });
                // Create a temporary element to hold the content
                const $temp = $('<div>').css({
                  fontSize: '12px', // Match font size
                  lineHeight: 'inherit', // Match line height
                  fontFamily: 'inherit', // Match font family
                });
                $temp.append(parts.join('<br>')); // Copy the inner HTML (with formatting)

                // Append it to the body temporarily
                $('body').append($temp);

                // Select the content
                const range = document.createRange();
                range.selectNodeContents($temp[0]);
                const selection = window.getSelection();
                selection.removeAllRanges();
                selection.addRange(range);

                // Copy the selected content
                try {
                  const successful = document.execCommand('copy');
                  if (!successful) {
                    alert('Failed to copy content.');
                  }
                } catch (err) {
                  console.error('Copy failed', err);
                }

                // Clean up: remove selection and the temporary element
                selection.removeAllRanges();
                $temp.remove();
              });
        });
    </script>
</head>
<body>
<? include("header.php"); ?>
<table width="99%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td colspan="4" align="left" valign="top" nowrap="nowrap"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="border">
        <tbody>
          <tr>
            <td width="100%" align="left" valign="top" class="lastview"><b style="white-space: nowrap;">Email Body Generator</b> </td>
          </tr>
          <tr>
            <td align="left" valign="top">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td align="center" valign="top" class="side-nav"><? include('sidenav.php');?></td>
                  <td align="left" valign="top" class="mar">

                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td align="left" valign="top" class="moduleTitle">Quick Email Creator</td>
                      
                    </tr>
                  </table>
                  <form id="dynamicForm">
                    <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" style="float: left;" class="listview listview1">
                      <thead>
                        <tr bgcolor="#D0E1F4" height="20">
                          <th style="padding: 5px">
                            
                              <font face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular">
                                <b>Input Section</b>
                              </font>
                            
                          </th>
                        </tr>
                      </thead>
                      <tbody>
					  		        <tr>
                          <td align="left" nowrap="nowrap" class="listViewThS1 listViewThS6 pad-l-5" scope="col">
                            <div class="tagTtl">Property ID</div>
                            <div id="inputContainer">
                              <div class="input-group">
                                  <input type="text" name="pid[]" id="pid[]" placeholder="Enter value" onkeyup="ajax_showOptions(this,'id',event);" />
                              </div>
                          </div>
                            <p>Copy Paste Here (one property id per line):</p>
                            <textarea name="pidTextarea" id="pidTextarea" rows="10" cols="50"></textarea>
                            <br>
                            <br>
                            <button type="button" class="button" id="submitForm">Find Property Data</button>
                            <br>
                            <br>
                            <div class="checkbox-group">
                                <p><b>Show Options</b></p>
                                <label for="show_options_show_property_id"><input id="show_options_show_property_id" type="checkbox" checked value="true" name="show_options[show_property_id]">Show Property ID<br></label>
                                <label for="show_options_show_city"><input id="show_options_show_city" type="checkbox" checked value="true" name="show_options[show_city]">Show City Name<br></label>
                                <label for="show_options_show_link_to_property"><input id="show_options_show_link_to_property" type="checkbox" checked value="true" name="show_options[show_link_to_property]">Show Link to Property<br></label>
                                <label for="show_options_show_link_to_zip"><input id="show_options_show_link_to_zip" type="checkbox" checked value="true" name="show_options[show_link_to_zip]">Show Link to Zip<br></label>
                                <label for="show_options_show_film_permit"><input id="show_options_show_film_permit" type="checkbox" checked value="true" name="show_options[show_film_permit]">Show Film Permit<br></label>
                                <label for="show_options_show_category"><input id="show_options_show_category" type="checkbox" checked value="true" name="show_options[show_category]">Show All Categories<br></label>
                            </div>
                            <div class="checkbox-group">
                                <p><b>Advanced Features</b></p>
                                <label for="show_options_show_thumbnails"><input id="show_options_show_thumbnails" type="checkbox" value="true" name="show_options[show_thumbnails]">Show Thumbnails<br></label>
                                <label for="show_options_show_address"><input id="show_options_show_address" type="checkbox" value="true" name="show_options[show_address]">Show Address, City, State, Zip<br></label>
                                <label for="show_options_show_owner_name"><input id="show_options_show_owner_name" type="checkbox" value="true" name="show_options[show_owner_name]">Show Owner's Name<br></label>
                                <label for="show_options_show_phone"><input id="show_options_show_phone" type="checkbox" value="true" name="show_options[show_phone]">Show All Phone Numbers<br></label>
                                <label for="show_options_show_email"><input id="show_options_show_email" type="checkbox" value="true" name="show_options[show_email]">Show Email<br></label>
                                <label for="show_options_show_crm_link"><input id="show_options_show_crm_link" type="checkbox" value="true" name="show_options[show_crm_link]">Show CRM Link<br></label>
                                <label for="show_options_show_line"><input id="show_options_show_line" type="checkbox" value="true" name="show_options[show_line]">Show Separating Line<br></label>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </form>
                    <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0" style="float: left;" class="listview listview1">
                      <thead>
                        <tr bgcolor="#D0E1F4" height="20">
                          <th style="padding: 5px">
                            
                              <font face="Arial,Helvetica,Geneva,Swiss,SunSans-Regular">
                                <b>Output Text</b>
                              </font>
                            
                          </th>
                        </tr>
                      </thead>
                      <tbody>
					  		        <tr>
                          <td align="left" nowrap="nowrap" class="listViewThS1 listViewThS6 pad-l-5" scope="col">
                            <div class="pad-l-5 clipboarddiv break-lines" id="outputText">
                                <div class="nocontent"> No content found! </div>
                            </div>
                            <br><br>
                           
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table></td>
          </tr>
        </tbody>
      </table></td>
    </tr>
  </tbody>
</table>
<? include("footer.php");?>
</body>
</html>

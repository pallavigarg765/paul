<html>
	<head>
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				$("#btnCheck").click(function(){
					var value = $("#txtPhone").val();
					
					var first = "";
					var second = "";
					var third = "";
					
					var arrValues = value.split("-");
					if(arrValues.length == 3){
						first = arrValues[0];
						second = arrValues[1];
						third = arrValues[2];
					}else{
						var arrSpaces = value.split(" ");
						if(arrSpaces.length == 3){
							first = arrSpaces[0];
							second = arrSpaces[1];
							third = arrSpaces[2];
						}else{
							first = value.substring(0, 3);
							second = value.substring(3, 6);
							third = value.substring(6, 10);
						}
					}
					var phone = "(" + first + ") " + second + "-" + third;
					$("#res").html(phone);
				});
			});
		</script>
	</head>
	<body>
		<p>
		Changes the phone number from <br />1234567890 or<br />123-456-7890 or<br />123 456 7890 to<br />(123) 456-7890
		</p>
		<input type="text" id="txtPhone" class="txtPhone" />
		<input type="button" id="btnCheck" value="Fix phone" />
		<br />
		Result
		<div id="res">
		
		</div>
	</body>
</html>

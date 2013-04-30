<!--http code starts here-->
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EDGE" />
<link rel="stylesheet" type='text/css' href='format.css' />
<script type='text/javascript' src='jquery.js'></script>
<script type='text/javascript' src='scripts.js'></script>
<script type='text/javascript'>
	var str = '<?php require_once("curl.php") ; echo $result; ?>';
</script>
</head>
<body>
	<h3>Urban Concentration Rank</h3>
	<form>
		<div>Search Country: 
		<select id = "countries">
		</select>
		</div>
	</form>
	<table style = "background: #AAFFAA;">
		<tr>
			<th style = "width: 100px;">
				<form>
					<input type = "button" id = "rankSort" value = "Rank (ASC)" style = "height : 25px; width : 95px;" />
				</form>
			</th>
			<th style = "width: 300px;">Country</th>
			<th style = "width: 160px;">City Population</th>
			<th style = "width: 160px;">Country Population</th>
			<th style = "width: 160px;">Urban Concentration</th>
		</tr>
	</table>
	<div class = "container">
		<table id = "UrbanConTable">
		</table>
	</div>
</body>
</html>
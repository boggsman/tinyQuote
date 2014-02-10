<?php
session_start();

if (isset($_GET["reset"])) {
	session_destroy();
	header('Location: '.$_SERVER["PHP_SELF"].'');
	exit;
	}
	
if ( ! isset($_SESSION["stockArray"])) {
	$_SESSION["stockArray"][] = "INTC";
	}
	
if (isset($_GET["newStock"])) {
	array_push($_SESSION["stockArray"], $_GET["newStock"]);
	}
	
if (isset($_GET["deleteStock"])) {
	$key = array_search($_GET["deleteStock"], $_SESSION['stockArray']);
	unset($_SESSION['stockArray'][$key]);
	}

function getQuote($symbol) 
{
	$symbol  = urlencode( trim( substr(strip_tags($symbol),0,7) ) ); 
	$yahooCSV = "http://finance.yahoo.com/d/quotes.csv?s=$symbol&f=sl1d1t1c1ohgvpnbaejkr&o=t";
	$csv = fopen($yahooCSV,"r");

	if($csv) 
	{
		list($quote['symbol'], $quote['last'], $quote['date'], $quote['timestamp'], $quote['change'], $quote['open'],
		$quote['high'], $quote['low'], $quote['volume'], $quote['previousClose'], $quote['name'], $quote['bid'],
		$quote['ask'], $quote['eps'], $quote['YearLow'], $quote['YearHigh'], $quote['PE']) = fgetcsv($csv, ','); 
  
		fclose($csv);
  
		return $quote; 
	} 
	else 
	{
		return false;
	}
}


?>



<!doctype html>
<head>
	<meta charset="utf-8" />
	<title>Stock Quotes</title>
	<link rel="stylesheet" href="style.css" />
</head>
<body>

<div class="table1">
<form name="addNew" method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<table>
	<tbody>
		<thead>
			<tr>
				<th>STOCK</th>
				<th>NAME</th>
				<th>PRICE</th>
				<th></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th colspan="3"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?reset">reset</a></th>
				<td></td>
			</tr>
		</tfoot>
		<?php
		
		foreach ($_SESSION["stockArray"] as $thisStock) {
		$last = getQuote($thisStock);
		echo '<tr>';
		echo '	<td>'.$thisStock.'</td>';
		echo '	<td>'.$last["name"].'</td>';
		echo '	<td>'.$last["last"].'</td>';
		echo '	<td><a href="'.$_SERVER['PHP_SELF'].'?deleteStock='.$thisStock.'">delete</a></td>';
		echo '</tr>';
		}
		
		?>
		<tr>
			<td><input type="text" name="newStock" size="3" /></td>
			<td></td>
			<td></td>
			<td><a title="Submit" onclick="document.addNew.submit();" id="add" href="#">add</a></td>
		</tr>
	</tbody>
</table>
<form>
</div>



</body>
</html>
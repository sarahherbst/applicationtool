<?php
//Including Database configuration file
require_once('connection.inc.php');
require_once('function.inc.php');

//Search box value assigning to variable
$berlinbuch = $_POST['searchberlinbuch'];
$eberswalde = $_POST['searcheberswalde'];
$badsaarow = $_POST['searchbadsaarow'];
$april = $_POST['searchapril'];
$oktober = $_POST['searchoktober'];

// Wenn in beiden Input-Feldern etwas angewählt ist
if (($berlinbuch !== 'empty' or $eberswalde !== 'empty' or $badsaarow !== 'empty') and ($april !== 'empty' or $oktober !== 'empty')) {
	
	//Search query
	$query = "SELECT * FROM ausb_infos
	WHERE (ausb_campus LIKE '%$berlinbuch%'
	OR ausb_campus LIKE '%$eberswalde%'
	OR ausb_campus LIKE '%$badsaarow%')
	AND (ausb_beginn LIKE '%$april%'
	OR ausb_beginn LIKE '%$oktober%')";


//Query execution

	$ExecQuery = mysqli_query($db, $query);

	//Fetching result from database

	while ($ergebnis = mysqli_fetch_array($ExecQuery)) {

		$ausb_id		= $ergebnis['ausb_id'];
		$ausbildungsgaenge = fc_select_ausb('all',array('ausb_status', 'ausb_id'), array('1', $ausb_id), '', '');
		$row_ausbildungsgaenge = mysqli_fetch_object($ausbildungsgaenge);

		include 'ergebnis.php';
	}

	// Anzahl der Bewertungen berechnen
	$numAusb 	= mysqli_num_rows($ExecQuery);

	if ($numAusb < 1)
		{
		    echo '<div class="col-md-12"><p>';
		    echo 'Für deine Auswahl sind leider keine Ausbildungsgänge hinterlegt.';
		    echo '</p></div>';
		}

	}

	// Wenn nur ein Standort ausgewählt ist (und kein Ausbildungsbeginn)
	else if (($berlinbuch !== 'empty' or $eberswalde !== 'empty' or $badsaarow !== 'empty') and ($april == 'empty' and $oktober == 'empty')) {
	
	//Search query
	$query = "SELECT * FROM ausb_infos
	WHERE ausb_campus LIKE '%$berlinbuch%'
	OR ausb_campus LIKE '%$eberswalde%'
	OR ausb_campus LIKE '%$badsaarow%'";


//Query execution

	$ExecQuery = mysqli_query($db, $query);

	//Fetching result from database

	while ($ergebnis = mysqli_fetch_array($ExecQuery)) {

		$ausb_id		= $ergebnis['ausb_id'];
		$ausbildungsgaenge = fc_select_ausb('all',array('ausb_status', 'ausb_id'), array('1', $ausb_id), '', '');
		$row_ausbildungsgaenge = mysqli_fetch_object($ausbildungsgaenge);

		include 'ergebnis.php';
	}

	// Anzahl der Bewertungen berechnen
	$numAusb 	= mysqli_num_rows($ExecQuery);

	if ($numAusb < 1)
		{
		    echo '<div class="col-md-12"><p>';
		    echo 'Für deine Auswahl sind leider keine Ausbildungsgänge hinterlegt.';
		    echo '</p></div>';
		}

	}

	// Wenn nur ein Ausbildungsbeginn ausgewählt ist (und kein Standort)
	else if (($berlinbuch == 'empty' and $eberswalde == 'empty' and $badsaarow == 'empty') and ($april !== 'empty' or $oktober !== 'empty')) {
	
	//Search query
	$query = "SELECT * FROM ausb_infos
	WHERE ausb_beginn LIKE '%$april%'
	OR ausb_beginn LIKE '%$oktober%'";


//Query execution

	$ExecQuery = mysqli_query($db, $query);

	//Fetching result from database

	while ($ergebnis = mysqli_fetch_array($ExecQuery)) {

		$ausb_id		= $ergebnis['ausb_id'];
		$ausbildungsgaenge = fc_select_ausb('all',array('ausb_status', 'ausb_id'), array('1', $ausb_id), '', '');
		$row_ausbildungsgaenge = mysqli_fetch_object($ausbildungsgaenge);

		include 'ergebnis.php';
	}

	// Anzahl der Bewertungen berechnen
	$numAusb 	= mysqli_num_rows($ExecQuery);

	if ($numAusb < 1)
		{
		    echo '<div class="col-md-12"><p>';
		    echo 'Für deine Auswahl sind leider keine Ausbildungsgänge hinterlegt.';
		    echo '</p></div>';
		}

	} 

	// wenn kein Feld ausgewählt ist
	else {

	$ausbildungsgaenge = fc_select_ausb('all','ausb_status', '1', 'ausb_name', 'ASC');

	while ( $row_ausbildungsgaenge = mysqli_fetch_object($ausbildungsgaenge) ):

		include 'ergebnis.php';

	endwhile;
}
	

?>

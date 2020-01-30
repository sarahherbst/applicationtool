<?php
	//Datenbank-Connection
	require_once('connection.inc.php');

	// ////////////////////////////////////////
	// KONKRETE SELECT-FUNCTIONS //////////////
	// ////////////////////////////////////////

	/* $whereSelectors 	– gibt den Spaltennamen an, nach dem selektiert werden soll
	** $whereValues 	– gibt die Inhalte an, nach denen selektiert werden soll
	** $orderby 		– gibt an, nach welcher Spalte sortiert werden soll
	** $order 			– gibt an, in welcher Reihenfolge die Ausgabe sortiert werden soll (ASC/DESC)
	*/

	// Select Ausbildungsgang (ausb_infos)
	function fc_select_ausb($selectors, $whereSelectors, $whereValues, $orderby, $order) {
		global $db;

		if($selectors = 'all') { $selectors = '*'; }

		$sql = "SELECT $selectors FROM ausb_infos WHERE ";

		if(is_array($whereSelectors) & is_array($whereValues)) {
			$sql .= "$whereSelectors[0] = '" . $whereValues[0] . "'";
			$i = 0;
			foreach($whereSelectors as $key => $val) {
				if(!$i== 0) { $sql .= " AND $val = '" . $whereValues[$i] . "'"; }
				$i++;
			}
		} else {
			$sql .= "$whereSelectors = $whereValues";
		}

		if ($orderby != '') {
			$sql .= " ORDER BY $orderby $order";
		}
		$res = mysqli_query($db, $sql) or die(mysqli_error($db));
		return $res;
	}

	// Select Ausbildungsgang (ausb_arbeitgeber)
	function fc_select_arbeitgeber($selectors, $whereSelectors, $whereValues, $orderby, $order) {
		global $db;

		if($selectors = 'all') { $selectors = '*'; }

		$sql = "SELECT $selectors FROM ausb_arbeitgeber WHERE ";

		if(is_array($whereSelectors) & is_array($whereValues)) {
			$sql .= "$whereSelectors[0] = '" . $whereValues[0] . "'";
			$i = 0;
			foreach($whereSelectors as $key => $val) {
				if(!$i== 0) { $sql .= " AND $val = '" . $whereValues[$i] . "'"; }
				$i++;
			}
		} else {
			$sql .= "$whereSelectors = $whereValues";
		}

		if ($orderby != '') {
			$sql .= " ORDER BY $orderby $order";
		}
		$res = mysqli_query($db, $sql) or die(mysqli_error($db));
		return $res;
	}

	// ////////////////////////////////////////
	// ALLGEMEINE FUNCTIONS ///////////////////
	// ////////////////////////////////////////
		
	//MYSQL SIMPLE SELECT
	/* Die sql_select() - function selectiert je nach Bedarf Werte aus der Datenbank.
	** OPTIONEN: 
	** $selectors 	- Mögliche Werte: 'all' oder die gewünschten Spaltennamen einer Tabelle
	** $table 		- Gibt den Namen der Tabelle an
	** $orderby 	- Gibt den Spaltennamen nachdem sortiert werden soll an, ist der Wert '' wird der ORDER BY Befehl ignoriert
	** $order 		- Mögliche Werte: 'ASC', 'DESC'
	*/
	function fc_sql_select($selectors, $table, $orderby, $order) {
		global $db;

		if ($selectors = 'all') { $selectors = '*'; }
		if ($order == '') { 
			$sql = "SELECT $selectors FROM $table"; 
		} else {
			$sql = "SELECT $selectors FROM $table ORDER BY $orderby $order";
		}

		$res = mysqli_query($db, $sql) or die(mysqli_error($db));

		return $res;
	}

		
	/* //////////////////////////////////////////////////////////////////////// /*
	// GEBURTSDATUM ÜBERPRÜFUNG
	/* Alter berechnen
	*/
	function fc_alter($date) {
	    $dateOfBirth     = $date;
	    $today           = date("Y-m-d");
	    $diff            = date_diff(date_create($dateOfBirth), date_create($today));

	    return $diff->format('%y');
	}


	/*Prüfung Jahreingabe*/
	function jahr_kontrollieren($jahr) {
		if (preg_match('(19|20)[0-9]{2}', $jahr)) {
			$bol=1;
		} else {
			$bol=0;
		}
	}

	/* //////////////////////////////////////////////////////////////////////// /*
	/*Prüfung des Datumformates*/
	//Als Wert bitte das Datum eingeben, dabei das Format beachten: (JAHR-MM-TT).
	//Als Rückgabewert wird übergeben, ob ein VERGANGENES Datum existiert hat (true/false).
	//Array fängt mit 0 an! Also: 0=Jahr, 1=Monat und 2 =Tag
	function datum_kontrollieren($datum) {
		$temp=explode('-',$datum);
		//Die Anzahl der Tage des Monats wird ermittelt.
		switch($temp[1]) {
			case '1':   $ml=31;
						break;
			case '2':   //Es wird überprüft, ob das Jahr ein Schaltjahr ist.
						if($temp[0]%4==0) {
								$ml=29;
						}
						//Kein Schaltjahr.
						else {
							$ml=28;
						}
						break;
			case '3':   $ml=31;
						break;
			case '4':   $ml=30;
						break;
			case '5':   $ml=31;
						break;
			case '6':   $ml=30;
						break;
			case '7':   $ml=31;
						break;
			case '8':   $ml=31;
						break;
			case '9':   $ml=30;
						break;
			case '10':  $ml=31;
						break;
			case '11':  $ml=30;
						break;
			case '12':  $ml=31;
						break;
		}

		if($temp[0]==date('Y')) {
			if ($temp[1]>0  && $temp[1]<=date('m') ) {
				if($temp[2]>0 && ($temp[1]<date('m') || $temp[2]<=date('d')) )
					{
						$bol=1;
				} else {
					$bol=0;
				}
			} else {
				$bol=0;
			}
		} elseif($temp[0]>=0 && $temp[0]<date('Y')) {
			if($temp[1]>0 && $temp[1]<=12) {
					if($temp[2]>0 && $temp[2]<=$ml) {
						$bol=1;
					} else {
						$bol=0;
					}
			} else {
				$bol=0;
			}
		} else {
			$bol=0;
		}
		return $bol;
	}


?>
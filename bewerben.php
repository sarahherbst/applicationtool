<?php

	// Connection und Functions anfordern
	require_once('connection.inc.php');
	require_once('function.inc.php');
	require('data.php');

	// Header einfügen
	include 'header.php';

	// Fehlermeldungen abschalten
	error_reporting(0);
	
	// Ausbildungs-ID holen und DB filtern
	$ausb_id 				= $_GET['ausb_id'];
	$ausbildungsgaenge 		= fc_select_ausb('all','ausb_id', $ausb_id, '', '');
	$row_ausbildungsgaenge 	= mysqli_fetch_object($ausbildungsgaenge);
	$ausbildungsgang 		= $row_ausbildungsgaenge->ausb_name;
	$ausb_campus 			= $row_ausbildungsgaenge->ausb_campus;

	$alle_ausbildungen		= fc_sql_select('ausb_name', 'ausb_infos', '', '');
	$alle_ausbildungen		= mysqli_query($db, 'SELECT ausb_name FROM ausb_infos WHERE NOT ausb_id = '.$ausb_id);

	// Arbeitgeber filtern
	if($ausb_id == '1' || $ausb_id == '2' || $ausb_id == '3' || $ausb_id == '4' || $ausb_id == '8' || $ausb_id == '13' || $ausb_id == '14') {

		$sql_berlinbuch 	= fc_select_arbeitgeber('all', array('ausb_arbeitg_campus','ausb_arbeitg_beruf'), array('1', $ausb_id), '', '');
		$num_berlinbuch 	= mysqli_num_rows($sql_berlinbuch);

		$sql_eberswalde 	= fc_select_arbeitgeber('all', array('ausb_arbeitg_campus','ausb_arbeitg_beruf'), array('2', $ausb_id), '', '');
		$num_eberswalde 	= mysqli_num_rows($sql_eberswalde);

		$sql_badsaarow 		= fc_select_arbeitgeber('all', array('ausb_arbeitg_campus','ausb_arbeitg_beruf'), array('3', $ausb_id), '', '');
		$num_badsaarow 		= mysqli_num_rows($sql_badsaarow);
	}

	// Variablen setzen
	if (!isset($formular)) {
		$formular 		= '1';
	}
	if($ausb_id == '9') {
		if (!isset($ausbildungsart)) {
			$ausbildungsart 	= '';
		}
	}
	if (!isset($anrede)) {
		$anrede 		= '';
	}
	if (!isset($vorname)) {
		$vorname 			= '';
	}
	if (!isset($nachname)) {
		$nachname 			= '';
	}
	if (!isset($strasse)) {
		$strasse = '';
	}
	if (!isset($adresszusatz)) {
		$adresszusatz = '';
	}
	if (!isset($plz)) {
		$plz = '';
	}
	if (!isset($ort)) {
		$ort 		= '';
	}
	if (!isset($land)) {
		$land 	= '';
	}
	if (!isset($telefon)) {
		$telefon 	= '';
	}
	if (!isset($email)) {
		$email 		= '';
	}
	if (!isset($geburtsdatum)) {
		$geburtsdatum 		= '';
	}
	if (!isset($geburtsort)) {
		$geburtsort 	= '';
	}
	if (!isset($geburtsname)) {
		$geburtsname = '';
	}
	if (!isset($herkunftsland)) {
		$herkunftsland 	= '';
	}
	if (!isset($schulabschluss)) {
		$schulabschluss 	= '';
	}
	if (!isset($sonstiger_abschluss)) {
		$sonstiger_abschluss 	= '';
	}
	if (!isset($aufmerksam)) {
		$aufmerksam 	= '';
	}
	if (!isset($kommentar)) {
		$kommentar 	= '';
	}

	if (!isset($weitere_interessen)) {
		$weitere_interessen 	= '';
	}

	if($ausb_id == '1' || $ausb_id == '2' || $ausb_id == '3' || $ausb_id == '4' || $ausb_id == '8' || $ausb_id == '13' || $ausb_id == '14') {
		if (!isset($wunscharbeitgeber)) {
			$wunscharbeitgeber 	= '';
		}

		$wunscharbeitgeber = '';
		$wunscharbeitgeber_str = '';
	}

	$fehlerangabe = '';
	$fileupload = false;

	// Formular 1/2 einlesen
	if (isset($_POST['weiter'])) {
		// Variablen einlesen
		if($ausb_id == '9') {
			$ausbildungsart 	= $_POST['ausbildungsart'];
		}

		$anrede 			= $_POST['anrede'];
		$vorname 			= $_POST['vorname'];
		$nachname 			= $_POST['nachname'];
		$strasse 			= $_POST['strasse'];
		$adresszusatz 		= $_POST['adresszusatz'];
		$plz 				= $_POST['plz'];
		$ort 				= $_POST['ort'];
		$land 				= $_POST['land'];
		$email 				= $_POST['email'];
		$telefon 			= $_POST['telefon'];
		$geburtsdatum 		= $_POST['geburtsdatum'];
		$geburtsort 		= $_POST['geburtsort'];
		$geburtsname 		= $_POST['geburtsname'];
		$herkunftsland 		= $_POST['herkunftsland'];
		$schulabschluss 	= $_POST['schulabschluss'];
		$sonstiger_abschluss = $_POST['sonstiger_abschluss'];

		// Falls schon einmal Weiter und dann Zurück geklickt wurde
		$aufmerksam 		= $_POST['aufmerksam'];
		$kommentar 			= $_POST['kommentar'];
		$weitere_interessen = $_POST['weitere_interessen'];

		if($ausb_id == '1' || $ausb_id == '2' || $ausb_id == '3' || $ausb_id == '4' || $ausb_id == '8' || $ausb_id == '13' || $ausb_id == '14') {
			$wunscharbeitgeber_str 	= $_POST['wunscharbeitgeber'];
		}

		$geburtsdatum       = $_POST['geburtsdatum'];
		$orderdate          = explode('.', $geburtsdatum);
		$geburtsdatum       = $orderdate[2].'-'.$orderdate[1].'-'.$orderdate[0];

		$fehler				= 0;
		$fehlerangabe		= '';

		if (fc_alter($geburtsdatum) < 16) {
		$fehler++;
            $fehlerangabe .= '<div class="form-group col-md-12 mt-2 mb-1"><div class="alert alert-danger" role="alert">Eine Online-Bewerbung kann erst ab 16 Jahren erfolgen. Bitte sende uns deine Bewerbung auf dem Postweg zu.<button type="button" class="close" data-dismiss="alert" aria-label="Schließen"><span aria-hidden="true">&times;</span></button></div></div>';
            $geburtsdatum = $_POST['geburtsdatum'];
		} else {
		    $geburtsdatum = $_POST['geburtsdatum'];

		    // Zeige Formular #2
			$formular 		= '2';

			
		}

	}

	// Formular 2/2 absenden
	if (isset($_POST['absenden'])) {
		// Zeige Formular #2
		$formular 		= '2';

		// Variablen einlesen
		if($ausb_id == '9') {
			$ausbildungsart 	= $_POST['ausbildungsart'];
		}

		$anrede 			= $_POST['anrede'];
		$vorname 			= $_POST['vorname'];
		$nachname 			= $_POST['nachname'];
		$strasse 			= $_POST['strasse'];
		$adresszusatz 		= $_POST['adresszusatz'];
		$plz 				= $_POST['plz'];
		$ort 				= $_POST['ort'];
		$land 				= $_POST['land'];
		$email 				= $_POST['email'];
		$telefon 			= $_POST['telefon'];
		$geburtsdatum 		= $_POST['geburtsdatum'];
		$geburtsort 		= $_POST['geburtsort'];
		$geburtsname 		= $_POST['geburtsname'];
		$herkunftsland 		= $_POST['herkunftsland'];
		$schulabschluss 	= $_POST['schulabschluss'];
		$sonstiger_abschluss = $_POST['sonstiger_abschluss'];

		$aufmerksam 		= $_POST['aufmerksam'];
		$kommentar 			= $_POST['kommentar'];
		$weitere_interessen = $_POST['weitere_interessen'];
		$weitere_interessen = implode(', ', $weitere_interessen);

		if($ausb_id == '1' || $ausb_id == '2' || $ausb_id == '3' || $ausb_id == '4' || $ausb_id == '8' || $ausb_id == '13' || $ausb_id == '14') {
			$wunscharbeitgeber 	= $_POST['wunscharbeitgeber'];
		}

		$fehler 			= 0;

		if($ausb_id == '1' || $ausb_id == '2' || $ausb_id == '3' || $ausb_id == '4' || $ausb_id == '8' || $ausb_id == '13' || $ausb_id == '14') {
			$wunscharbeitgeber 	= implode(', ', $wunscharbeitgeber);
			$wunscharbeitgeber_str 	= $wunscharbeitgeber;
			$wunscharbeitgeber 	= explode(', ', $wunscharbeitgeber);

			// Validieren ob 3 Arbeitgeber ausgewählt wurden
			$wunscharbeitgeber_nr = count($wunscharbeitgeber);

			if ($wunscharbeitgeber_nr > 1) {
				$fehler++;
				$fehlerangabe = '<div class="alert alert-danger" role="alert">Bitte wählen Sie höchstens einen Wunscharbeitgeber aus.</div>';
			} elseif ($wunscharbeitgeber_nr < 1) {
				$fehler++;
				$fehlerangabe = '<div class="alert alert-danger" role="alert">Bitte wählen Sie einen Wunscharbeitgeber aus.</div>';
			}
		}
		
		if ($fehler == 0) {

			// Anschreiben hochladen		
			if ($_FILES['anschreiben']['error'] <= 0) {

				// Umwandlung 
				$TRANS = array ( 
				    "Ö" => "Oe", 
				    "ö" => "oe", 
				    "Ä" => "Ae", 
				    "ä" => "ae", 
				    "Ü" => "Ue", 
				    "ü" => "ue", 
				    "ß" => "ss", 
				    ); 

				$nachname_neu = strtr($nachname,$TRANS);

				// Das Upload-Verzeichnis
				$upload_folder 	= 'files/bewerbungen/'.$nachname_neu.'/';

				// prüfen, ob Verzeichnis vorhanden ist, ansonsten erstellen
				if (!is_dir($upload_folder)) {
					// Verzeichnis erstellen und auf Fehler prüfen
					if (!mkdir($upload_folder, 0777, true)) {
						$fehler++;
						$fehlerangabe .= '<div class="alert alert-danger" role="alert">Der Ordner für das Anschreiben konnte nicht erstellt werden.</div>';
					} else {
						// ZIP-Name vergeben
						$zipname = $nachname_neu.'_Bewerbungsunterlagen.zip';

						// Neue Instanz der ZipArchive Klasse erzeugen 
						$zip = new ZipArchive; 

						// Zip-Archiv erstellen 
						$ziparchive = $zip->open($upload_folder.$zipname, ZipArchive::CREATE);
					}
				} else {
					// Falls Ordner schon vorhanden, anderen erstellen
					$pfad_id = 1;
					do {
						$upload_folder = 'files/bewerbungen/'.$nachname_neu.'_'.$pfad_id.'/';
						$pfad_id++;
					}
					while(is_dir($upload_folder));

					// Verzeichnis erstellen und auf Fehler prüfen
					if (!mkdir($upload_folder, 0777, true)) {
						$fehler++;
						$fehlerangabe .= '<div class="alert alert-danger" role="alert">Der Ordner für das Anschreiben konnte nicht erstellt werden.</div>';
					} else {
						// ZIP-Name vergeben
						$zipname = $nachname_neu.'_Bewerbungsunterlagen.zip';

						// Neue Instanz der ZipArchive Klasse erzeugen 
						$zip = new ZipArchive; 

						// Zip-Archiv erstellen 
						$ziparchive = $zip->open($upload_folder.$zipname, ZipArchive::CREATE);
					}
				}

				// Dateiname und -endung
				$filename 	= pathinfo($_FILES['anschreiben']['name'], PATHINFO_FILENAME);
				$extension 	= strtolower(pathinfo($_FILES['anschreiben']['name'], PATHINFO_EXTENSION));

				//Überprüfung der Dateiendung
				$allowed_extensions = array('pdf', 'txt', 'doc', 'docx', 'odt');
				if (!in_array($extension, $allowed_extensions)) {
					$fehler++;
					$fehlerangabe .= '<div class="alert alert-danger" role="alert"><strong>Anschreiben:</strong> Ungültige Dateiendung. Nur pdf, txt, odt, doc und docx-Dateien sind erlaubt.</div>';
				}

				// Überprüfung der Dateigröße
				$max_size = 2000*1024; // max. Dateigröße: 2000 KB
				if ($_FILES['anschreiben']['size'] > $max_size) {
					$fehler++;
					$fehlerangabe .= '<div class="alert alert-danger" role="alert"><strong>Anschreiben:</strong> Bitte keine Dateien größer als 2 MB hochladen</div>';
				}

				// Überprüfung der Datei
				$allowed_types = ['application/pdf', 'text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.oasis.opendocument.text']; 
				if (!in_array($_FILES['anschreiben']['type'], $allowed_types)) {
					$fehler++;
					$fehlerangabe .= '<div class="alert alert-danger" role="alert"><strong>Anschreiben:</strong> Nur der Upload von den Dateiformaten pdf, txt, odt, doc und docx-Dateien ist gestattet. Stellen Sie sicher, dass Ihre Datei nicht beschädigt ist.</div>';
				}

				if ($fehler == 0) {
					$new_path = $upload_folder.$nachname_neu.'_Anschreiben'.'.'.$extension;

					// vorübergehendes Dokument verschieben
					move_uploaded_file($_FILES['anschreiben']['tmp_name'], $new_path);
					$anschreiben = $new_path;
					$anschreiben = str_replace('../', '', $anschreiben);

					if ($fehler == 0) {
						$fileupload = true;

						// in ZIP packen
						if ($ziparchive === true) { 
							$zip->addFile($anschreiben, $nachname_neu.'_Anschreiben'.'.'.$extension);
						}

					} else {
						$fehlerangabe .= '<div class="alert alert-danger" role="alert"><b>Anschreiben: </b>'.$fehlerangabe.'</div>';
					}
				}
			} else {
				$fehler++;
				$fehlerangabe = 'Es wurde keine Datei ausgewählt.';
			}

			// Lebenslauf hochladen		
			if ($_FILES['lebenslauf']['error'] <= 0) {

				// Dateiname und -endung
				$filename 	= pathinfo($_FILES['lebenslauf']['name'], PATHINFO_FILENAME);
				$extension 	= strtolower(pathinfo($_FILES['lebenslauf']['name'], PATHINFO_EXTENSION));

				//Überprüfung der Dateiendung
				$allowed_extensions = array('pdf', 'txt', 'doc', 'docx', 'odt');
				if (!in_array($extension, $allowed_extensions)) {
					$fehler++;
					$fehlerangabe .= '<div class="alert alert-danger" role="alert"><strong>Lebenslauf:</strong> Ungültige Dateiendung. Nur pdf, txt, odt, doc und docx-Dateien sind erlaubt.</div>';
				}

				// Überprüfung der Dateigröße
				$max_size = 2000*1024; // max. Dateigröße: 2000 KB
				if ($_FILES['lebenslauf']['size'] > $max_size) {
					$fehler++;
					$fehlerangabe .= '<div class="alert alert-danger" role="alert"><strong>Lebenslauf:</strong> Bitte keine Dateien größer als 2 MB hochladen</div>';
				}

				// Überprüfung der Datei
				$allowed_types = ['application/pdf', 'text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.oasis.opendocument.text']; 
				if (!in_array($_FILES['lebenslauf']['type'], $allowed_types)) {
					$fehler++;
					$fehlerangabe .= '<div class="alert alert-danger" role="alert"><strong>Lebenslauf:</strong> Nur der Upload von den Dateiformaten pdf, txt, odt, doc und docx-Dateien ist gestattet. Stellen Sie sicher, dass Ihre Datei nicht beschädigt ist.</div>';
				}

				if ($fehler == 0) {
					$new_path = $upload_folder.$nachname_neu.'_Lebenslauf'.'.'.$extension;

					// vorübergehendes Dokument verschieben
					move_uploaded_file($_FILES['lebenslauf']['tmp_name'], $new_path);
					$lebenslauf = $new_path;
					$lebenslauf = str_replace('../', '', $lebenslauf);

					if ($fehler == 0) {
						$fileupload = true;

						// in ZIP packen
						if ($ziparchive === true) { 
							$zip->addFile($lebenslauf, $nachname_neu.'_Lebenslauf'.'.'.$extension);
						}

					} else {
						$fehlerangabe .= '<div class="alert alert-danger" role="alert"><b>Lebenslauf: </b>'.$fehlerangabe.'</div>';
					}
				}
			} else {
				$fehler++;
				$fehlerangabe = 'Es wurde keine Datei ausgewählt.';
			}

			// Schulzeugnis hochladen		
			if ($_FILES['schulzeugnis']['error'] <= 0) {

				// Dateiname und -endung
				$filename 	= pathinfo($_FILES['schulzeugnis']['name'], PATHINFO_FILENAME);
				$extension 	= strtolower(pathinfo($_FILES['schulzeugnis']['name'], PATHINFO_EXTENSION));

				//Überprüfung der Dateiendung
				$allowed_extensions = array('pdf', 'txt', 'doc', 'docx', 'odt');
				if (!in_array($extension, $allowed_extensions)) {
					$fehler++;
					$fehlerangabe .= '<div class="alert alert-danger" role="alert"><strong>Schulzeugnis:</strong> Ungültige Dateiendung. Nur pdf, txt, odt, doc und docx-Dateien sind erlaubt.</div>';
				}

				// Überprüfung der Dateigröße
				$max_size = 2000*1024; // max. Dateigröße: 2000 KB
				if ($_FILES['schulzeugnis']['size'] > $max_size) {
					$fehler++;
					$fehlerangabe .= '<div class="alert alert-danger" role="alert"><strong>Schulzeugnis:</strong> Bitte keine Dateien größer als 2 MB hochladen</div>';
				}

				// Überprüfung der Datei
				$allowed_types = ['application/pdf', 'text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.oasis.opendocument.text']; 
				if (!in_array($_FILES['schulzeugnis']['type'], $allowed_types)) {
					$fehler++;
					$fehlerangabe .= '<div class="alert alert-danger" role="alert"><strong>Schulzeugnis:</strong> Nur der Upload von den Dateiformaten pdf, txt, odt, doc und docx-Dateien ist gestattet. Stellen Sie sicher, dass Ihre Datei nicht beschädigt ist.</div>';
				}

				if ($fehler == 0) {
					$new_path = $upload_folder.$nachname_neu.'_Schulzeugnis'.'.'.$extension;

					// vorübergehendes Dokument verschieben
					move_uploaded_file($_FILES['schulzeugnis']['tmp_name'], $new_path);
					$schulzeugnis = $new_path;
					$schulzeugnis = str_replace('../', '', $schulzeugnis);

					if ($fehler == 0) {
						$fileupload = true;

						// in ZIP packen
						if ($ziparchive === true) { 
							$zip->addFile($schulzeugnis, $nachname_neu.'_Schulzeugnis'.'.'.$extension);
						}
					} else {
						$fehlerangabe .= '<div class="alert alert-danger" role="alert"><b>Schulzeugnis: </b>'.$fehlerangabe.'</div>';
					}
				}
			}

			// Praktikumsbeurteilung hochladen		
			if ($_FILES['praktikumsbeurteilung']['error'] <= 0) {

				// Dateiname und -endung
				$filename 	= pathinfo($_FILES['praktikumsbeurteilung']['name'], PATHINFO_FILENAME);
				$extension 	= strtolower(pathinfo($_FILES['praktikumsbeurteilung']['name'], PATHINFO_EXTENSION));

				//Überprüfung der Dateiendung
				$allowed_extensions = array('pdf', 'txt', 'doc', 'docx', 'odt');
				if (!in_array($extension, $allowed_extensions)) {
					$fehler++;
					$fehlerangabe .= '<div class="alert alert-danger" role="alert"><strong>Praktikumsbeurteilung:</strong> Ungültige Dateiendung. Nur pdf, txt, odt, doc und docx-Dateien sind erlaubt.</div>';
				}

				// Überprüfung der Dateigröße
				$max_size = 2000*1024; // max. Dateigröße: 2000 KB
				if ($_FILES['praktikumsbeurteilung']['size'] > $max_size) {
					$fehler++;
					$fehlerangabe .= '<div class="alert alert-danger" role="alert"><strong>Praktikumsbeurteilung:</strong> Bitte keine Dateien größer als 2 MB hochladen</div>';
				}

				// Überprüfung der Datei
				$allowed_types = ['application/pdf', 'text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.oasis.opendocument.text']; 
				if (!in_array($_FILES['praktikumsbeurteilung']['type'], $allowed_types)) {
					$fehler++;
					$fehlerangabe .= '<div class="alert alert-danger" role="alert"><strong>Praktikumsbeurteilung:</strong> Nur der Upload von den Dateiformaten pdf, txt, odt, doc und docx-Dateien ist gestattet. Stellen Sie sicher, dass Ihre Datei nicht beschädigt ist.</div>';
				}

				if ($fehler == 0) {
					$new_path = $upload_folder.$nachname_neu.'_Praktikumsbeurteilung'.'.'.$extension;

					// vorübergehendes Dokument verschieben
					move_uploaded_file($_FILES['praktikumsbeurteilung']['tmp_name'], $new_path);
					$praktikumsbeurteilung = $new_path;
					$praktikumsbeurteilung = str_replace('../', '', $praktikumsbeurteilung);

					if ($fehler == 0) {
						$fileupload = true;

						// in ZIP packen
						if ($ziparchive === true) { 
							$zip->addFile($praktikumsbeurteilung, $nachname_neu.'_Praktikumsbeurteilung.'.$extension);
						}
					} else {
						$fehlerangabe .= '<div class="alert alert-danger" role="alert"><b>Praktikumsbeurteilung: </b>'.$fehlerangabe.'</div>';
					}
				}
			}

			// Arztbescheinigung hochladen		
			if ($_FILES['arztbescheinigung']['error'] <= 0) {

				// Dateiname und -endung
				$filename 	= pathinfo($_FILES['arztbescheinigung']['name'], PATHINFO_FILENAME);
				$extension 	= strtolower(pathinfo($_FILES['arztbescheinigung']['name'], PATHINFO_EXTENSION));

				//Überprüfung der Dateiendung
				$allowed_extensions = array('pdf', 'txt', 'doc', 'docx', 'odt');
				if (!in_array($extension, $allowed_extensions)) {
					$fehler++;
					$fehlerangabe .= '<div class="alert alert-danger" role="alert"><strong>Ärztliche Bescheinigung:</strong> Ungültige Dateiendung. Nur pdf, txt, odt, doc und docx-Dateien sind erlaubt.</div>';
				}

				// Überprüfung der Dateigröße
				$max_size = 2000*1024; // max. Dateigröße: 2000 KB
				if ($_FILES['arztbescheinigung']['size'] > $max_size) {
					$fehler++;
					$fehlerangabe .= '<div class="alert alert-danger" role="alert"><strong>Ärztliche Bescheinigung:</strong> Bitte keine Dateien größer als 2 MB hochladen</div>';
				}

				// Überprüfung der Datei
				$allowed_types = ['application/pdf', 'text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.oasis.opendocument.text']; 
				if (!in_array($_FILES['arztbescheinigung']['type'], $allowed_types)) {
					$fehler++;
					$fehlerangabe .= '<div class="alert alert-danger" role="alert"><strong>Ärztliche Bescheinigung:</strong> Nur der Upload von den Dateiformaten pdf, txt, odt, doc und docx-Dateien ist gestattet. Stellen Sie sicher, dass Ihre Datei nicht beschädigt ist.</div>';
				}

				if ($fehler == 0) {
					$new_path = $upload_folder.$nachname_neu.'_Arztbescheinigung'.'.'.$extension;

					// vorübergehendes Dokument verschieben
					move_uploaded_file($_FILES['arztbescheinigung']['tmp_name'], $new_path);
					$arztbescheinigung = $new_path;
					$arztbescheinigung = str_replace('../', '', $arztbescheinigung);

					if ($fehler == 0) {
						$fileupload = true;

						// in ZIP packen
						if ($ziparchive === true) { 
							$zip->addFile($arztbescheinigung, $nachname_neu.'_Arztbescheinigung.'.$extension);
						}
					} else {
						$fehlerangabe .= '<div class="alert alert-danger" role="alert"><b>Arztbescheinigung: </b>'.$fehlerangabe.'</div>';
					}
				}
			}

			// Optionale Unterlagen hochladen		
			if ($_FILES['optional']['error'] <= 0) {

				// Dateiname und -endung
				$filename 	= pathinfo($_FILES['optional']['name'], PATHINFO_FILENAME);
				$extension 	= strtolower(pathinfo($_FILES['optional']['name'], PATHINFO_EXTENSION));

				//Überprüfung der Dateiendung
				$allowed_extensions = array('pdf', 'txt', 'doc', 'docx', 'odt');
				if (!in_array($extension, $allowed_extensions)) {
					$fehler++;
					$fehlerangabe .= '<div class="alert alert-danger" role="alert"><strong>Optionale Unterlage:</strong> Ungültige Dateiendung. Nur pdf, txt, odt, doc und docx-Dateien sind erlaubt.</div>';
				}

				// Überprüfung der Dateigröße
				$max_size = 2000*1024; // max. Dateigröße: 2000 KB
				if ($_FILES['optional']['size'] > $max_size) {
					$fehler++;
					$fehlerangabe .= '<div class="alert alert-danger" role="alert"><strong>Optionale Unterlage:</strong> Bitte keine Dateien größer als 2 MB hochladen</div>';
				}

				// Überprüfung der Datei
				$allowed_types = ['application/pdf', 'text/plain', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.oasis.opendocument.text']; 
				if (!in_array($_FILES['optional']['type'], $allowed_types)) {
					$fehler++;
					$fehlerangabe .= '<div class="alert alert-danger" role="alert"><strong>Optionale Unterlage:</strong> Nur der Upload von den Dateiformaten pdf, txt, odt, doc und docx-Dateien ist gestattet. Stellen Sie sicher, dass Ihre Datei nicht beschädigt ist.</div>';
				}

				if ($fehler == 0) {
					$new_path = $upload_folder.$nachname_neu.'_Optionale-Unterlage'.'.'.$extension;

					// vorübergehendes Dokument verschieben
					move_uploaded_file($_FILES['optional']['tmp_name'], $new_path);
					$optional = $new_path;
					$optional = str_replace('../', '', $optional);

					if ($fehler == 0) {
						$fileupload = true;

						// in ZIP packen
						if ($ziparchive === true) { 
							$zip->addFile($optional, $nachname_neu.'_Optionale-Unterlage.'.$extension);
						}
					} else {
						$fehlerangabe .= '<div class="alert alert-danger" role="alert"><b>Optionale Unterlage: </b>'.$fehlerangabe.'</div>';
					}
				}

				// Zip-Archiv schließen 
	 			$zip->close(); 
			}

			// wenn keine Fehler vorhanden sind
			if ($fehler == 0 && $fileupload == true) {
				// E-Mail an Institut versenden
				// Include PHPMailer class
				require('phpmailer/PHPMailerAutoload.php');

				//Setup PHPMailer
				$mail 				= new PHPMailer;
				$mail->setLanguage('de', 'phpmailer/language/');
				$mail->CharSet 		='UTF-8';
				//$mail ->SMTPDebug = 3; 					// Enable verbose debug output
				$mail->isSMTP(); 						// Set mailer to use SMTP
				$mail->Host 		= $smtp_server; 	// Specify main and backup SMTP servers
				$mail->SMTPOptions 	= array(
					'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
					)
				);
				$mail->SMTPAuth 	= true; 			// Enable SMTP authentication
				$mail->Username 	= $smtp_user; 		// SMTP username
				$mail->Password 	= $smtp_passwort; 	// SMTP password
				$mail->SMTPSecure 	= 'ssl'; 			// Enable TLS encryption, `ssl` also accepted
				$mail->Port 		= $smtp_port; 		// TCP port to connect to
				$mail->isHTML(true);					// Set email format to html

				//Absender
				$mail->SetFrom($email_von, $institut);
				$mail->Sender 		= ($email_von);
				$mail->addReplyTo($email_zu, $institut);

				//Empfänger
				$name_empfaenger 	= $institut;
				$mail->addAddress($email_von, $name_empfaenger);
				$mail->addCustomHeader('BCC: '.$email_bcc.'');

				//Betreff
				$mail->Subject 		= 'Neue Bewerbung für den Ausbildungsgang '.$ausbildungsgang;

				//Nachricht
				$mail->Body    		= '<p style="color:#333;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;"><b>Folgende Daten für den Ausbildungsgang '.$ausbildungsgang.' wurden soeben übermittelt:</b></p>';
				$mail->Body 		.= '<p style="color:#333;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;">';
				$mail->Body 		.= $anrede.' '.$vorname.' '.$nachname.'<br />';
				$mail->Body 		.= 'Strasse: '.$strasse.'<br />';
				if (!$adresszusatz == '') {
					$mail->Body 	.= 'Adresszusatz: '.$adresszusatz.'<br />';
				}
				$mail->Body 		.= 'Ort: '.$ort.'<br />';
				$mail->Body 		.= 'Land: '.$land.'<br /><br />';
				$mail->Body 		.= 'E-Mail-Adresse: '.$email.'<br />';
				$mail->Body 		.= 'Telefonnummer: '.$telefon.'<br /><br />';
				$mail->Body 		.= 'Geburtsdatum: '.$geburtsdatum.'<br />';
				$mail->Body 		.= 'Geburtsort: '.$geburtsort.'<br />';
				$mail->Body 		.= 'Geburtsname: '.$geburtsname.'<br />';
				$mail->Body 		.= 'Herkunftsland: '.$herkunftsland.'<br /><br />';
				$mail->Body 		.= 'Schulabschluss: '.$schulabschluss.'<br />';
				if (!$sonstiger_abschluss == '') {
					$mail->Body 	.= 'Sonstiger Schulabschluss: '.$sonstiger_abschluss.'<br />';
				}
				if ($ausb_id == '9') {
					$mail->Body 	.= 'Ausbildungsart: '.$ausbildungsart.'<br />';
				}
				$mail->Body 		.= 'Interessiert sich für folgende weitere Berufe: '.$weitere_interessen.'<br />';
				if (!$aufmerksam == '') {
					$mail->Body 	.= 'Aufmerksam geworden auf den Ausbildungsgang durch: '.$aufmerksam.'<br />';
				}
				if (!$kommentar == '') {
					$mail->Body 	.= 'Kommentar: '.$kommentar.'<br /><br />';
				} 
				if($ausb_id == '1' || $ausb_id == '2' || $ausb_id == '3' || $ausb_id == '4' || $ausb_id == '8' || $ausb_id == '13' || $ausb_id == '14') {
					$mail->Body 	.= 'Wunscharbeitgeber: '.$wunscharbeitgeber_str;
				}
				$mail->Body 		.= '<br /><br /><a href="adg.webseiten.cc/bewerbertool/'.$upload_folder.$zipname.'" download>Download der Bewerbungsunterlagen</a>';
				$mail->Body 		.= '</p><br />';

				$mail->Body .= '<p style="color:#333;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;">'.$email_footer.'</p>';
				
				//E-Mail an Bewerber versenden
				if( !$mail->Send() ) {
					$fehler 		= '1';
					$fehlerangabe  .= '<div class="alert alert-danger alert-dismissible fade in" role="alert"><button class="close" type="button" data-dismiss="alert" aria-label="Schließen"><span aria-hidden="true">×</span></button>Fehler! Ihre Daten konnten leider nicht versendet werden. Bitte kontaktieren Sie den Websiteadministrator! <br>'.$mail->ErrorInfo.'</div>';
				} else {
					//Setup PHPMailer
					$mail2 				= new PHPMailer;
					$mail2->setLanguage('de', 'phpmailer/language/');
					$mail2->CharSet 		='UTF-8';
					//$mail2 ->SMTPDebug = 3; 					// Enable verbose debug output
					$mail2->isSMTP(); 						// Set mailer to use SMTP
					$mail2->Host 		= $smtp_server; 	// Specify main and backup SMTP servers
					$mail2->SMTPOptions 	= array(
						'ssl' => array(
							'verify_peer' => false,
							'verify_peer_name' => false,
							'allow_self_signed' => true
						)
					);
					$mail2->SMTPAuth 	= true; 			// Enable SMTP authentication
					$mail2->Username 	= $smtp_user; 		// SMTP username
					$mail2->Password 	= $smtp_passwort; 	// SMTP password
					$mail2->SMTPSecure 	= 'ssl'; 			// Enable TLS encryption, `ssl` also accepted
					$mail2->Port 		= $smtp_port; 		// TCP port to connect to
					$mail2->isHTML(true);					// Set email format to html

					//Absender
					$mail2->SetFrom($email_von, $institut);
					$mail2->Sender 		= ($email_von);
					$mail2->addReplyTo($email_zu, $institut);

					//Empfänger
					$name_empfaenger 	= $vorname.' '.$nachname;
					$mail2->addAddress($email, $name_empfaenger);

					//Betreff
					$mail2->Subject 	= 'Bestätigung Ihrer Angaben';

					//Nachricht
					$mail2->Body    	 = '<p style="color:#333;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;">Sehr geehrte(r) '.$vorname.' '.$nachname.', </p>';
					$mail2->Body 		.= '<p style="color:#333;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;">wir haben Ihre Bewerbungsunterlagen erhalten und werden uns in Kürze mit Ihnen in Verbindung setzen.</p>';
					// Campus abfragen
					$mail2->Body .= '<p style="color:#333;font-family:Helvetica,Arial,sans-serif;font-weight:400;line-height:1.3;">'.$email_footer.'</p>';

					if( !$mail2->Send() ) {
						$fehler 			 = '1';
						$fehlerangabe  		.= '<div class="alert alert-danger alert-dismissible fade in" role="alert"><button class="close" type="button" data-dismiss="alert" aria-label="Schließen"><span aria-hidden="true">×</span></button>Fehler! Es konnte leider keine Bestätigungsmail versendet werden. Bitte kontaktieren Sie den Websiteadministrator! <br>'.$mail->ErrorInfo.'</div>';
					} else {
						$fehlerangabe  .= '<h5>Vielen Dank für Ihre Bewerbung! Ihre Angaben wurden erfolgreich übertragen. Zusätzlich wurde Ihnen eine Bestätigungsmail an '.$email.' gesendet.</h5><br>';

						// Zeige Bestätigung
						$formular 		= '3';

					}
				}
			}
		}
	}

	// Bei Klick auf "Zurück"
	if (isset($_POST['zurueck'])) {
		// Variablen einlesen
		if($ausb_id == '9') {
			$ausbildungsart 	= $_POST['ausbildungsart'];
		}

		$anrede 			= $_POST['anrede'];
		$vorname 			= $_POST['vorname'];
		$nachname 			= $_POST['nachname'];
		$strasse 			= $_POST['strasse'];
		$adresszusatz 		= $_POST['adresszusatz'];
		$plz 				= $_POST['plz'];
		$ort 				= $_POST['ort'];
		$land 				= $_POST['land'];
		$email 				= $_POST['email'];
		$telefon 			= $_POST['telefon'];
		$geburtsdatum 		= $_POST['geburtsdatum'];
		$geburtsort 		= $_POST['geburtsort'];
		$geburtsname 		= $_POST['geburtsname'];
		$herkunftsland 		= $_POST['herkunftsland'];
		$schulabschluss 	= $_POST['schulabschluss'];
		$sonstiger_abschluss = $_POST['sonstiger_abschluss'];

		$aufmerksam 		= $_POST['aufmerksam'];
		$kommentar 			= $_POST['kommentar'];
		$weitere_interessen = $_POST['weitere_interessen'];
		$weitere_interessen = implode(', ', $weitere_interessen);

		if($ausb_id == '1' || $ausb_id == '2' || $ausb_id == '3' || $ausb_id == '4' || $ausb_id == '8' || $ausb_id == '13' || $ausb_id == '14') {
			if (!$wunscharbeitgeber == '') {
				$wunscharbeitgeber 	= $_POST['wunscharbeitgeber'];

				$wunscharbeitgeber 	= implode(', ', $wunscharbeitgeber);
				$wunscharbeitgeber_str 	= $wunscharbeitgeber;
			}
		}

		$formular = '1';
	}

?>

<h2>
	<span><strong>Bewerbung</strong></span>
</h2>

<div class="container-fluid bewerbungstabs">
	<!-- TABS -->
	<div class="row">
		<div class="col-md-12" style="padding-top: 15px;">
			<div class="row bg-white p-4 mb-5 rounded">
				<div class="col-md-8">
					<h3><strong><?php echo $ausbildungsgang; ?></strong></h3>

			        <!-- Bewerbungsicons --> 
					<ul class="nav nav-tabs bewerbungsicons mt-5 mb-3">
						<li class="<?php if ($formular == 2 || $formular == 3) : echo 'nextstep'; endif; ?> active">
							<div>
								<span class="icon gv-icon-388 mb-2" aria-hidden="true"></span>
								<p class="text-center"><strong>1. Schritt</strong><br>Allgemeine<br>Daten</p>
							</div>
						</li>
						<li class="middlestep <?php if ($formular == 2) : echo 'active'; endif; ?> <?php if ($formular == 3) : echo 'active laststep'; endif; ?>">
							<div>
								<span class="icon gv-icon-340 mb-2" aria-hidden="true"></span>
								<p class="text-center"><strong>2. Schritt</strong><br>Unterlagen<br>hochladen</p>
							</div>
						</li>
						<li <?php if ($formular == 3) : echo 'class="active"'; endif; ?>>
							<div>
								<span class="icon gv-icon-242 mb-2" aria-hidden="true"></span>
								<p class="text-center"><strong>3. Schritt</strong><br>Versand</p>
							</div>
						</li>
					</ul> <!-- Ende Bewerbungsicons -->
					
					<?php if ($formular == 1) { ?>
						<form action="" method="post">
							<div class="alert alert-info" role="alert">
								<strong>Achtung!</strong> Eine Online-Bewerbung ist erst ab <strong>16 Jahren</strong> möglich. Die Bewerbung kann in diesem Fall auf dem Postweg zugestellt werden.
							</div>
							<?php if ($ausb_id == '9') { ?>
								<div class="form-check form-check-inline col-md-12 mt-1">
									<div class="custom-control custom-checkbox radio my-1 mr-sm-2">
										<input type="radio" class="custom-control-input required" name="ausbildungsart" id="3jaehrig" value="3-jährige Ausbildung" required <?php if($ausbildungsart == '3-jährige Ausbildung') : echo 'checked'; endif; ?>>
										<label class="custom-control-label" for="3jaehrig">3-jährige Ausbildung</label>
									</div>
									<div class="custom-control custom-checkbox radio my-1 mr-sm-2">
										<input type="radio" class="custom-control-input required" name="ausbildungsart" id="berufsbegleitend" value="berufsbegleitende Ausbildung" required <?php if($ausbildungsart == 'berufsbegleitende Ausbildung') : echo 'checked'; endif; ?>>
										<label class="custom-control-label" for="berufsbegleitend">berufsbegleitende Ausbildung</label>
									</div>
								</div>
							<?php } ?>
							<!-- Anrede -->
							<div class="form-row">
								<strong class="pl-2 pb-1 w-100">Anrede*</strong>
								<div class="form-group col-md-4">
									<select name="anrede" id="anrede" class="form-control" required>
										<option value="">Anrede</option>
										<option <?php if ($anrede == 'Frau') : echo 'selected'; endif; ?> value="Frau">Frau</option>
										<option <?php if ($anrede == 'Herr') : echo 'selected'; endif; ?> value="Herr">Herr</option>
									</select>
								</div>
								<div class="form-group col-md-4">
									<input type="text" class="form-control" name="vorname" id="vorname" placeholder="Vorname" value="<?php echo $vorname; ?>" pattern=".*([a-zA-Z]){1,}.*([a-zA-Z]){1,}.*" required>
								</div>
								<div class="form-group col-md-4">
									<input type="text" class="form-control" name="nachname" id="nachname" placeholder="Name" value="<?php echo $nachname; ?>" pattern=".*([a-zA-Z]){1,}.*([a-zA-Z]){1,}.*" required>
								</div>
							</div>

							<!-- Adresse -->
							<div class="form-row">
								<strong class="pl-2 pb-1 w-100">Adresse*</strong>
								<div class="form-group col-md-12">
									<input type="text" class="form-control" name="strasse" id="strasse" placeholder="Straße und Hausnummer"  value="<?php echo $strasse; ?>" pattern=".*([a-zA-Z]){1,}.*([a-zA-Z]){1,}.*" required>
								</div>
								<div class="form-group col-md-12">
									<input type="text" class="form-control" name="adresszusatz" id="adresszusatz" placeholder="Adresszusatz (optional)"  value="<?php echo $adresszusatz; ?>">
								</div>
								<div class="form-group col-md-4">
									<input type="text" class="form-control" name="plz" id="plz" placeholder="PLZ" value="<?php echo $plz; ?>" pattern=".*([0-9]){1,}.*" minlength="3" required>
								</div>
								<div class="form-group col-md-8">
									<input type="text" class="form-control" name="ort" id="ort" placeholder="Ort" value="<?php echo $ort; ?>" pattern=".*([a-zA-Z]){1,}.*([a-zA-Z]){1,}.*" required>
								</div>
								<div class="form-group col-md-12">
									<select class="form-control" name="land" id="land" required>
										<option value="">Land</option>
										<option <?php if ($land == 'Afghanistan') : echo 'selected'; endif; ?> value="Afghanistan">Afghanistan (Afghanistan)</option>
										<option <?php if ($land == 'Ägypten') : echo 'selected'; endif; ?> value="Ägypten">Ägypten (Egypt)</option>
										<option <?php if ($land == 'Aland') : echo 'selected'; endif; ?> value="Aland">Aland (Åland Islands)</option>
										<option <?php if ($land == 'Albanien') : echo 'selected'; endif; ?> value="Albanien">Albanien (Albania)</option>
										<option <?php if ($land == 'Algerien') : echo 'selected'; endif; ?> value="Algerien">Algerien (Algeria)</option>
										<option <?php if ($land == 'Amerikanisch-Samoa') : echo 'selected'; endif; ?> value="Amerikanisch-Samoa">Amerikanisch-Samoa (American Samoa)</option>
										<option <?php if ($land == 'Amerikanische Jungferninseln') : echo 'selected'; endif; ?> value="Amerikanische Jungferninseln">Amerikanische Jungferninseln (Virgin Islands, U.s.)</option>
										<option <?php if ($land == 'Andorra') : echo 'selected'; endif; ?> value="Andorra">Andorra (Andorra)</option>
										<option <?php if ($land == 'Angola') : echo 'selected'; endif; ?> value="Angola">Angola (Angola)</option>
										<option <?php if ($land == 'Anguilla') : echo 'selected'; endif; ?> value="Anguilla">Anguilla (Anguilla)</option>
										<option <?php if ($land == 'Antarktis') : echo 'selected'; endif; ?> value="Antarktis">Antarktis (Antarctica)</option>
										<option <?php if ($land == 'Antigua und Barbuda') : echo 'selected'; endif; ?> value="Antigua und Barbuda">Antigua und Barbuda (Antigua And Barbuda)</option>
										<option <?php if ($land == 'Äquatorialguinea') : echo 'selected'; endif; ?> value="Äquatorialguinea">Äquatorialguinea (Equatorial Guinea)</option>
										<option <?php if ($land == 'Argentinien') : echo 'selected'; endif; ?> value="Argentinien">Argentinien (Argentina)</option>
										<option <?php if ($land == 'Armenien') : echo 'selected'; endif; ?> value="Armenien">Armenien (Armenia)</option>
										<option <?php if ($land == 'Aruba') : echo 'selected'; endif; ?> value="Aruba">Aruba (Aruba)</option>
										<option <?php if ($land == 'Ascension') : echo 'selected'; endif; ?> value="Ascension">Ascension (Ascension)</option>
										<option <?php if ($land == 'Aserbaidschan') : echo 'selected'; endif; ?> value="Aserbaidschan">Aserbaidschan (Azerbaijan)</option>
										<option <?php if ($land == 'Äthiopien') : echo 'selected'; endif; ?> value="Äthiopien">Äthiopien (Ethiopia)</option>
										<option <?php if ($land == 'Australien') : echo 'selected'; endif; ?> value="Australien">Australien (Australia)</option>
										<option <?php if ($land == 'Bahamas') : echo 'selected'; endif; ?> value="Bahamas">Bahamas (Bahamas)</option>
										<option <?php if ($land == 'Bahrain') : echo 'selected'; endif; ?> value="Bahrain">Bahrain (Bahrain)</option>
										<option <?php if ($land == 'Bangladesch') : echo 'selected'; endif; ?> value="Bangladesch">Bangladesch (Bangladesh)</option>
										<option <?php if ($land == 'Barbados') : echo 'selected'; endif; ?> value="Barbados">Barbados (Barbados)</option>
										<option <?php if ($land == 'Belgien') : echo 'selected'; endif; ?> value="Belgien">Belgien (Belgium)</option>
										<option <?php if ($land == 'Belize') : echo 'selected'; endif; ?> value="Belize">Belize (Belize)</option>
										<option <?php if ($land == 'Benin') : echo 'selected'; endif; ?> value="Benin">Benin (Benin)</option>
										<option <?php if ($land == 'Bermuda') : echo 'selected'; endif; ?> value="Bermuda">Bermuda (Bermuda)</option>
										<option <?php if ($land == 'Bhutan') : echo 'selected'; endif; ?> value="Bhutan">Bhutan (Bhutan)</option>
										<option <?php if ($land == 'Bolivien') : echo 'selected'; endif; ?> value="Bolivien">Bolivien (Bolivia)</option>
										<option <?php if ($land == 'Bosnien und Herzegowina') : echo 'selected'; endif; ?> value="Bosnien und Herzegowina">Bosnien und Herzegowina (Bosnia And Herzegovina)</option>
										<option <?php if ($land == 'Botswana') : echo 'selected'; endif; ?> value="Botswana">Botswana (Botswana)</option>
										<option <?php if ($land == 'Bouvetinsel') : echo 'selected'; endif; ?> value="Bouvetinsel">Bouvetinsel (Bouvet Island)</option>
										<option <?php if ($land == 'Brasilien') : echo 'selected'; endif; ?> value="Brasilien">Brasilien (Brazil)</option>
										<option <?php if ($land == 'Brunei') : echo 'selected'; endif; ?> value="Brunei">Brunei (Brunei Darussalam)</option>
										<option <?php if ($land == 'Bulgarien') : echo 'selected'; endif; ?> value="Bulgarien">Bulgarien (Bulgaria)</option>
										<option <?php if ($land == 'Burkina Faso') : echo 'selected'; endif; ?> value="Burkina Faso">Burkina Faso (Burkina Faso)</option>
										<option <?php if ($land == 'Burundi') : echo 'selected'; endif; ?> value="Burundi">Burundi (Burundi)</option>
										<option <?php if ($land == 'Chile') : echo 'selected'; endif; ?> value="Chile">Chile (Chile)</option>
										<option <?php if ($land == 'China') : echo 'selected'; endif; ?> value="China">China (China)</option>
										<option <?php if ($land == 'Cookinseln') : echo 'selected'; endif; ?> value="Cookinseln">Cookinseln (Cook Islands)</option>
										<option <?php if ($land == 'Costa Rica') : echo 'selected'; endif; ?> value="Costa Rica">Costa Rica (Costa Rica)</option>
										<option <?php if ($land == 'Cote d`Ivoire') : echo 'selected'; endif; ?> value="Cote d`Ivoire">Cote d'Ivoire (CÔte D'ivoire)</option>
										<option <?php if ($land == 'Dänemark') : echo 'selected'; endif; ?> value="Dänemark">Dänemark (Denmark)</option>
										<option <?php if ($land == 'Deutschland' || $land == '') : echo 'selected'; endif; ?> value="Deutschland">Deutschland (Germany)</option>
										<option <?php if ($land == 'Diego Garcia') : echo 'selected'; endif; ?> value="Diego Garcia">Diego Garcia (Diego Garcia)</option>
										<option <?php if ($land == 'Dominica') : echo 'selected'; endif; ?> value="Dominica">Dominica (Dominica)</option>
										<option <?php if ($land == 'Dominikanische Republik') : echo 'selected'; endif; ?> value="Dominikanische Republik">Dominikanische Republik (Dominican Republic)</option>
										<option <?php if ($land == 'Dschibuti') : echo 'selected'; endif; ?> value="Dschibuti">Dschibuti (Djibouti)</option>
										<option <?php if ($land == 'Ecuador') : echo 'selected'; endif; ?> value="Ecuador">Ecuador (Ecuador)</option>
										<option <?php if ($land == 'El Salvador') : echo 'selected'; endif; ?> value="El Salvador">El Salvador (El Salvador)</option>
										<option <?php if ($land == 'Eritrea') : echo 'selected'; endif; ?> value="Eritrea">Eritrea (Eritrea)</option>
										<option <?php if ($land == 'Estland') : echo 'selected'; endif; ?> value="Estland">Estland (Estonia)</option>
										<option <?php if ($land == 'Europäische Union') : echo 'selected'; endif; ?> value="Europäische Union">Europäische Union (Europäische Union)</option>
										<option <?php if ($land == 'Falklandinseln') : echo 'selected'; endif; ?> value="Falklandinseln">Falklandinseln (Falkland Islands (malvinas))</option>
										<option <?php if ($land == 'Färöer') : echo 'selected'; endif; ?> value="Färöer">Färöer (Faroe Islands)</option>
										<option <?php if ($land == 'Fidschi') : echo 'selected'; endif; ?> value="Fidschi">Fidschi (Fiji)</option>
										<option <?php if ($land == 'Finnland') : echo 'selected'; endif; ?> value="Finnland">Finnland (Finland)</option>
										<option <?php if ($land == 'Frankreich') : echo 'selected'; endif; ?> value="Frankreich">Frankreich (France)</option>
										<option <?php if ($land == 'Französisch-Guayana') : echo 'selected'; endif; ?> value="Französisch-Guayana">Französisch-Guayana (French Guiana)</option>
										<option <?php if ($land == 'Französisch-Polynesien') : echo 'selected'; endif; ?> value="Französisch-Polynesien">Französisch-Polynesien (French Polynesia)</option>
										<option <?php if ($land == 'Gabun') : echo 'selected'; endif; ?> value="Gabun">Gabun (Gabon)</option>
										<option <?php if ($land == 'Gambia') : echo 'selected'; endif; ?> value="Gambia">Gambia (Gambia)</option>
										<option <?php if ($land == 'Georgien') : echo 'selected'; endif; ?> value="Georgien">Georgien (Georgia)</option>
										<option <?php if ($land == 'Ghana') : echo 'selected'; endif; ?> value="Ghana">Ghana (Ghana)</option>
										<option <?php if ($land == 'Gibraltar') : echo 'selected'; endif; ?> value="Gibraltar">Gibraltar (Gibraltar)</option>
										<option <?php if ($land == 'Grenada') : echo 'selected'; endif; ?> value="Grenada">Grenada (Grenada)</option>
										<option <?php if ($land == 'Griechenland') : echo 'selected'; endif; ?> value="Griechenland">Griechenland (Greece)</option>
										<option <?php if ($land == 'Grönland') : echo 'selected'; endif; ?> value="Grönland">Grönland (Greenland)</option>
										<option <?php if ($land == 'Großbritannien') : echo 'selected'; endif; ?> value="Großbritannien">Großbritannien (Create Britain)</option>
										<option <?php if ($land == 'Guadeloupe') : echo 'selected'; endif; ?> value="Guadeloupe">Guadeloupe (Guadeloupe)</option>
										<option <?php if ($land == 'Guam') : echo 'selected'; endif; ?> value="Guam">Guam (Guam)</option>
										<option <?php if ($land == 'Guatemala') : echo 'selected'; endif; ?> value="Guatemala">Guatemala (Guatemala)</option>
										<option <?php if ($land == 'Guernsey') : echo 'selected'; endif; ?> value="Guernsey">Guernsey (Guernsey)</option>
										<option <?php if ($land == 'Guinea') : echo 'selected'; endif; ?> value="Guinea">Guinea (Guinea)</option>
										<option <?php if ($land == 'Guinea-Bissau') : echo 'selected'; endif; ?> value="Guinea-Bissau">Guinea-Bissau (Guinea-bissau)</option>
										<option <?php if ($land == 'Guyana') : echo 'selected'; endif; ?> value="Guyana">Guyana (Guyana)</option>
										<option <?php if ($land == 'Haiti') : echo 'selected'; endif; ?> value="Haiti">Haiti (Haiti)</option>
										<option <?php if ($land == 'Heard und McDonaldinseln') : echo 'selected'; endif; ?> value="Heard und McDonaldinseln">Heard und McDonaldinseln (Heard Island And Mcdonald Islands)</option>
										<option <?php if ($land == 'Honduras') : echo 'selected'; endif; ?> value="Honduras">Honduras (Honduras)</option>
										<option <?php if ($land == 'Hongkong') : echo 'selected'; endif; ?> value="Hongkong">Hongkong (Hong Kong)</option>
										<option <?php if ($land == 'Indien') : echo 'selected'; endif; ?> value="Indien">Indien (India)</option>
										<option <?php if ($land == 'Indonesien') : echo 'selected'; endif; ?> value="Indonesien">Indonesien (Indonesia)</option>
										<option <?php if ($land == 'Irak') : echo 'selected'; endif; ?> value="Irak">Irak (Iraq)</option>
										<option <?php if ($land == 'Iran') : echo 'selected'; endif; ?> value="Iran">Iran (Iran, Islamic Republic Of)</option>
										<option <?php if ($land == 'Irland') : echo 'selected'; endif; ?> value="Irland">Irland (Ireland)</option>
										<option <?php if ($land == 'Island') : echo 'selected'; endif; ?> value="Island">Island (Iceland)</option>
										<option <?php if ($land == 'Israel') : echo 'selected'; endif; ?> value="Israel">Israel (Israel)</option>
										<option <?php if ($land == 'Italien') : echo 'selected'; endif; ?> value="Italien">Italien (Italy)</option>
										<option <?php if ($land == 'Jamaika') : echo 'selected'; endif; ?> value="Jamaika">Jamaika (Jamaica)</option>
										<option <?php if ($land == 'Japan') : echo 'selected'; endif; ?> value="Japan">Japan (Japan)</option>
										<option <?php if ($land == 'Jemen') : echo 'selected'; endif; ?> value="Jemen">Jemen (Yemen)</option>
										<option <?php if ($land == 'Jersey') : echo 'selected'; endif; ?> value="Jersey">Jersey (Jersey)</option>
										<option <?php if ($land == 'Jordanien') : echo 'selected'; endif; ?> value="Jordanien">Jordanien (Jordan)</option>
										<option <?php if ($land == 'Kaimaninseln') : echo 'selected'; endif; ?> value="Kaimaninseln">Kaimaninseln (Cayman Islands)</option>
										<option <?php if ($land == 'Kambodscha') : echo 'selected'; endif; ?> value="Kambodscha">Kambodscha (Cambodia)</option>
										<option <?php if ($land == 'Kamerun') : echo 'selected'; endif; ?> value="Kamerun">Kamerun (Cameroon)</option>
										<option <?php if ($land == 'Kanada') : echo 'selected'; endif; ?> value="Kanada">Kanada (Canada)</option>
										<option <?php if ($land == 'Kanarische Inseln') : echo 'selected'; endif; ?> value="Kanarische Inseln">Kanarische Inseln (Kanarische Inseln)</option>
										<option <?php if ($land == 'Kap Verde') : echo 'selected'; endif; ?> value="Kap Verde">Kap Verde (Cape Verde)</option>
										<option <?php if ($land == 'Kasachstan') : echo 'selected'; endif; ?> value="Kasachstan">Kasachstan (Kazakhstan)</option>
										<option <?php if ($land == 'Katar') : echo 'selected'; endif; ?> value="Katar">Katar (Qatar)</option>
										<option <?php if ($land == 'Kenia') : echo 'selected'; endif; ?> value="Kenia">Kenia (Kenya)</option>
										<option <?php if ($land == 'Kirgisistan') : echo 'selected'; endif; ?> value="Kirgisistan">Kirgisistan (Kyrgyzstan)</option>
										<option <?php if ($land == 'Kiribati') : echo 'selected'; endif; ?> value="Kiribati">Kiribati (Kiribati)</option>
										<option <?php if ($land == 'Kokosinseln') : echo 'selected'; endif; ?> value="Kokosinseln">Kokosinseln (Cocos (keeling) Islands)</option>
										<option <?php if ($land == 'Kolumbien') : echo 'selected'; endif; ?> value="Kolumbien">Kolumbien (Colombia)</option>
										<option <?php if ($land == 'Komoren') : echo 'selected'; endif; ?> value="Komoren">Komoren (Comoros)</option>
										<option <?php if ($land == 'Kongo') : echo 'selected'; endif; ?> value="Kongo">Kongo (Congo)</option>
										<option <?php if ($land == 'Kroatien') : echo 'selected'; endif; ?> value="Kroatien">Kroatien (Croatia)</option>
										<option <?php if ($land == 'Kuba') : echo 'selected'; endif; ?> value="Kuba">Kuba (Cuba)</option>
										<option <?php if ($land == 'Kuwait') : echo 'selected'; endif; ?> value="Kuwait">Kuwait (Kuwait)</option>
										<option <?php if ($land == 'Laos') : echo 'selected'; endif; ?> value="Laos">Laos (Lao People's Democratic Republic)</option>
										<option <?php if ($land == 'Lesotho') : echo 'selected'; endif; ?> value="Lesotho">Lesotho (Lesotho)</option>
										<option <?php if ($land == 'Lettland') : echo 'selected'; endif; ?> value="Lettland">Lettland (Latvia)</option>
										<option <?php if ($land == 'Libanon') : echo 'selected'; endif; ?> value="Libanon">Libanon (Lebanon)</option>
										<option <?php if ($land == 'Liberia') : echo 'selected'; endif; ?> value="Liberia">Liberia (Liberia)</option>
										<option <?php if ($land == 'Libyen') : echo 'selected'; endif; ?> value="Libyen">Libyen (Libyan Arab Jamahiriya)</option>
										<option <?php if ($land == 'Liechtenstein') : echo 'selected'; endif; ?> value="Liechtenstein">Liechtenstein (Liechtenstein)</option>
										<option <?php if ($land == 'Litauen') : echo 'selected'; endif; ?> value="Litauen">Litauen (Lithuania)</option>
										<option <?php if ($land == 'Luxemburg') : echo 'selected'; endif; ?> value="Luxemburg">Luxemburg (Luxembourg)</option>
										<option <?php if ($land == 'Macao') : echo 'selected'; endif; ?> value="Macao">Macao (Macao)</option>
										<option <?php if ($land == 'Madagaskar') : echo 'selected'; endif; ?> value="Madagaskar">Madagaskar (Madagascar)</option>
										<option <?php if ($land == 'Malawi') : echo 'selected'; endif; ?> value="Malawi">Malawi (Malawi)</option>
										<option <?php if ($land == 'Malaysia') : echo 'selected'; endif; ?> value="Malaysia">Malaysia (Malaysia)</option>
										<option <?php if ($land == 'Malediven') : echo 'selected'; endif; ?> value="Malediven">Malediven (Maldives)</option>
										<option <?php if ($land == 'Mali') : echo 'selected'; endif; ?> value="Mali">Mali (Mali)</option>
										<option <?php if ($land == 'Malta') : echo 'selected'; endif; ?> value="Malta">Malta (Malta)</option>
										<option <?php if ($land == 'Marokko') : echo 'selected'; endif; ?> value="Marokko">Marokko (Morocco)</option>
										<option <?php if ($land == 'Marshallinseln') : echo 'selected'; endif; ?> value="Marshallinseln">Marshallinseln (Marshall Islands)</option>
										<option <?php if ($land == 'Martinique') : echo 'selected'; endif; ?> value="Martinique">Martinique (Martinique)</option>
										<option <?php if ($land == 'Mauretanien') : echo 'selected'; endif; ?> value="Mauretanien">Mauretanien (Mauritania)</option>
										<option <?php if ($land == 'Mauritius') : echo 'selected'; endif; ?> value="Mauritius">Mauritius (Mauritius)</option>
										<option <?php if ($land == 'Mayotte') : echo 'selected'; endif; ?> value="Mayotte">Mayotte (Mayotte)</option>
										<option <?php if ($land == 'Mazedonien') : echo 'selected'; endif; ?> value="Mazedonien">Mazedonien (Macedonia, The Former Yugoslav Republic Of)</option>
										<option <?php if ($land == 'Mexiko') : echo 'selected'; endif; ?> value="Mexiko">Mexiko (Mexico)</option>
										<option <?php if ($land == 'Mikronesien') : echo 'selected'; endif; ?> value="Mikronesien">Mikronesien (Micronesia)</option>
										<option <?php if ($land == 'Moldawien') : echo 'selected'; endif; ?> value="Moldawien">Moldawien (Moldova)</option>
										<option <?php if ($land == 'Monaco') : echo 'selected'; endif; ?> value="Monaco">Monaco (Monaco)</option>
										<option <?php if ($land == 'Mongolei') : echo 'selected'; endif; ?> value="Mongolei">Mongolei (Mongolia)</option>
										<option <?php if ($land == 'Montserrat') : echo 'selected'; endif; ?> value="Montserrat">Montserrat (Montserrat)</option>
										<option <?php if ($land == 'Mosambik') : echo 'selected'; endif; ?> value="Mosambik">Mosambik (Mozambique)</option>
										<option <?php if ($land == 'Myanmar') : echo 'selected'; endif; ?> value="Myanmar">Myanmar (Myanmar)</option>
										<option <?php if ($land == 'Namibia') : echo 'selected'; endif; ?> value="Namibia">Namibia (Namibia)</option>
										<option <?php if ($land == 'Nauru') : echo 'selected'; endif; ?> value="Nauru">Nauru (Nauru)</option>
										<option <?php if ($land == 'Nepal') : echo 'selected'; endif; ?> value="Nepal">Nepal (Nepal)</option>
										<option <?php if ($land == 'Neukaledonien') : echo 'selected'; endif; ?> value="Neukaledonien">Neukaledonien (New Caledonia)</option>
										<option <?php if ($land == 'Neuseeland') : echo 'selected'; endif; ?> value="Neuseeland">Neuseeland (New Zealand)</option>
										<option <?php if ($land == 'Neutrale Zone') : echo 'selected'; endif; ?> value="Neutrale Zone">Neutrale Zone (Neutrale Zone)</option>
										<option <?php if ($land == 'Nicaragua') : echo 'selected'; endif; ?> value="Nicaragua">Nicaragua (Nicaragua)</option>
										<option <?php if ($land == 'Niederlande') : echo 'selected'; endif; ?> value="Niederlande">Niederlande (Netherlands)</option>
										<option <?php if ($land == 'Niederländische Antillen') : echo 'selected'; endif; ?> value="Niederländische Antillen">Niederländische Antillen (Netherlands Antilles)</option>
										<option <?php if ($land == 'Niger') : echo 'selected'; endif; ?> value="Niger">Niger (Niger)</option>
										<option <?php if ($land == 'Nigeria') : echo 'selected'; endif; ?> value="Nigeria">Nigeria (Nigeria)</option>
										<option <?php if ($land == 'Niue') : echo 'selected'; endif; ?> value="Niue">Niue (Niue)</option>
										<option <?php if ($land == 'Nordkorea') : echo 'selected'; endif; ?> value="Nordkorea">Nordkorea (North Korea)</option>
										<option <?php if ($land == 'Nördliche Marianen') : echo 'selected'; endif; ?> value="Nördliche Marianen">Nördliche Marianen (Northern Mariana Islands)</option>
										<option <?php if ($land == 'Norfolkinsel') : echo 'selected'; endif; ?> value="Norfolkinsel">Norfolkinsel (Norfolk Island)</option>
										<option <?php if ($land == 'Norwegen') : echo 'selected'; endif; ?> value="Norwegen">Norwegen (Norway)</option>
										<option <?php if ($land == 'Oman') : echo 'selected'; endif; ?> value="Oman">Oman (Oman)</option>
										<option <?php if ($land == 'Österreich') : echo 'selected'; endif; ?> value="Österreich">Österreich (Austria)</option>
										<option <?php if ($land == 'Pakistan') : echo 'selected'; endif; ?> value="Pakistan">Pakistan (Pakistan)</option>
										<option <?php if ($land == 'Palästina') : echo 'selected'; endif; ?> value="Palästina">Palästina (Palestinian Territory)</option>
										<option <?php if ($land == 'Palau') : echo 'selected'; endif; ?> value="Palau">Palau (Palau)</option>
										<option <?php if ($land == 'Panama') : echo 'selected'; endif; ?> value="Panama">Panama (Panama)</option>
										<option <?php if ($land == 'Papua-Neuguinea') : echo 'selected'; endif; ?> value="Papua-Neuguinea">Papua-Neuguinea (Papua New Guinea)</option>
										<option <?php if ($land == 'Paraguay') : echo 'selected'; endif; ?> value="Paraguay">Paraguay (Paraguay)</option>
										<option <?php if ($land == 'Peru') : echo 'selected'; endif; ?> value="Peru">Peru (Peru)</option>
										<option <?php if ($land == 'Philippinen') : echo 'selected'; endif; ?> value="Philippinen">Philippinen (Philippines)</option>
										<option <?php if ($land == 'Pitcairninseln') : echo 'selected'; endif; ?> value="Pitcairninseln">Pitcairninseln (Pitcairn)</option>
										<option <?php if ($land == 'Polen') : echo 'selected'; endif; ?> value="Polen">Polen (Poland)</option>
										<option <?php if ($land == 'Portugal') : echo 'selected'; endif; ?> value="Portugal">Portugal (Portugal)</option>
										<option <?php if ($land == 'Puerto Rico') : echo 'selected'; endif; ?> value="Puerto Rico">Puerto Rico (Puerto Rico)</option>
										<option <?php if ($land == 'Réunion') : echo 'selected'; endif; ?> value="Réunion">Réunion (RÉunion)</option>
										<option <?php if ($land == 'Ruanda') : echo 'selected'; endif; ?> value="Ruanda">Ruanda (Rwanda)</option>
										<option <?php if ($land == 'Rumänien') : echo 'selected'; endif; ?> value="Rumänien">Rumänien (Romania)</option>
										<option <?php if ($land == 'Russische Föderation') : echo 'selected'; endif; ?> value="Russische Föderation">Russische Föderation (Russian Federation)</option>
										<option <?php if ($land == 'Salomonen') : echo 'selected'; endif; ?> value="Salomonen">Salomonen (Solomon Islands)</option>
										<option <?php if ($land == 'Sambia') : echo 'selected'; endif; ?> value="Sambia">Sambia (Zambia)</option>
										<option <?php if ($land == 'Samoa') : echo 'selected'; endif; ?> value="Samoa">Samoa (Samoa)</option>
										<option <?php if ($land == 'San Marino') : echo 'selected'; endif; ?> value="San Marino">San Marino (San Marino)</option>
										<option <?php if ($land == 'São Tomé und Príncipe') : echo 'selected'; endif; ?> value="São Tomé und Príncipe">São Tomé und Príncipe (Sao Tome And Principe)</option>
										<option <?php if ($land == 'Saudi-Arabien') : echo 'selected'; endif; ?> value="Saudi-Arabien">Saudi-Arabien (Saudi Arabia)</option>
										<option <?php if ($land == 'Schweden') : echo 'selected'; endif; ?> value="Schweden">Schweden (Sweden)</option>
										<option <?php if ($land == 'Schweiz') : echo 'selected'; endif; ?> value="Schweiz">Schweiz (Switzerland)</option>
										<option <?php if ($land == 'Senegal') : echo 'selected'; endif; ?> value="Senegal">Senegal (Senegal)</option>
										<option <?php if ($land == 'Serbien und Montenegro') : echo 'selected'; endif; ?> value="Serbien und Montenegro">Serbien und Montenegro (Serbien und Montenegro)</option>
										<option <?php if ($land == 'Seychellen') : echo 'selected'; endif; ?> value="Seychellen">Seychellen (Seychelles)</option>
										<option <?php if ($land == 'Sierra Leone') : echo 'selected'; endif; ?> value="Sierra Leone">Sierra Leone (Sierra Leone)</option>
										<option <?php if ($land == 'Simbabwe') : echo 'selected'; endif; ?> value="Simbabwe">Simbabwe (Zimbabwe)</option>
										<option <?php if ($land == 'Singapur') : echo 'selected'; endif; ?> value="Singapur">Singapur (Singapore)</option>
										<option <?php if ($land == 'Slowakei') : echo 'selected'; endif; ?> value="Slowakei">Slowakei (Slovakia)</option>
										<option <?php if ($land == 'Slowenien') : echo 'selected'; endif; ?> value="Slowenien">Slowenien (Slovenia)</option>
										<option <?php if ($land == 'Somalia') : echo 'selected'; endif; ?> value="Somalia">Somalia (Somalia)</option>
										<option <?php if ($land == 'Spanien') : echo 'selected'; endif; ?> value="Spanien">Spanien (Spain)</option>
										<option <?php if ($land == 'Sri Lanka') : echo 'selected'; endif; ?> value="Sri Lanka">Sri Lanka (Sri Lanka)</option>
										<option <?php if ($land == 'St. Helena') : echo 'selected'; endif; ?> value="St. Helena">St. Helena (Saint Helena)</option>
										<option <?php if ($land == 'St. Kitts und Nevis') : echo 'selected'; endif; ?> value="St. Kitts und Nevis">St. Kitts und Nevis (Saint Kitts And Nevis)</option>
										<option <?php if ($land == 'St. Lucia') : echo 'selected'; endif; ?> value="St. Lucia">St. Lucia (Saint Lucia)</option>
										<option <?php if ($land == 'St. Pierre und Miquelon') : echo 'selected'; endif; ?> value="St. Pierre und Miquelon">St. Pierre und Miquelon (Saint Pierre And Miquelon)</option>
										<option <?php if ($land == 'St. Vincent/Grenadinen (GB)') : echo 'selected'; endif; ?> value="St. Vincent/Grenadinen (GB)">St. Vincent/Grenadinen (GB) (Saint Vincent/Grenadines)</option>
										<option <?php if ($land == 'Südafrika, Republik') : echo 'selected'; endif; ?> value="Südafrika, Republik">Südafrika, Republik (South Africa)</option>
										<option <?php if ($land == 'Sudan') : echo 'selected'; endif; ?> value="Sudan">Sudan (Sudan)</option>
										<option <?php if ($land == 'Südkorea') : echo 'selected'; endif; ?> value="Südkorea">Südkorea (South Korea)</option>
										<option <?php if ($land == 'Suriname') : echo 'selected'; endif; ?> value="Suriname">Suriname (Suriname)</option>
										<option <?php if ($land == 'Svalbard und Jan Mayen') : echo 'selected'; endif; ?> value="Svalbard und Jan Mayen">Svalbard und Jan Mayen (Svalbard And Jan Mayen)</option>
										<option <?php if ($land == 'Swasiland') : echo 'selected'; endif; ?> value="Swasiland">Swasiland (Swaziland)</option>
										<option <?php if ($land == 'Syrien') : echo 'selected'; endif; ?> value="Syrien">Syrien (Syrian Arab Republic)</option>
										<option <?php if ($land == 'Tadschikistan') : echo 'selected'; endif; ?> value="Tadschikistan">Tadschikistan (Tajikistan)</option>
										<option <?php if ($land == 'Taiwan') : echo 'selected'; endif; ?> value="Taiwan">Taiwan (Taiwan, Province Of China)</option>
										<option <?php if ($land == 'Tansania') : echo 'selected'; endif; ?> value="Tansania">Tansania (Tanzania)</option>
										<option <?php if ($land == 'Thailand') : echo 'selected'; endif; ?> value="Thailand">Thailand (Thailand)</option>
										<option <?php if ($land == 'Timor-Leste') : echo 'selected'; endif; ?> value="Timor-Leste">Timor-Leste (Timor-leste)</option>
										<option <?php if ($land == 'Togo') : echo 'selected'; endif; ?> value="Togo">Togo (Togo)</option>
										<option <?php if ($land == 'Tokelau') : echo 'selected'; endif; ?> value="Tokelau">Tokelau (Tokelau)</option>
										<option <?php if ($land == 'Tonga') : echo 'selected'; endif; ?> value="Tonga">Tonga (Tonga)</option>
										<option <?php if ($land == 'Trinidad und Tobago') : echo 'selected'; endif; ?> value="Trinidad und Tobago">Trinidad und Tobago (Trinidad And Tobago)</option>
										<option <?php if ($land == 'Tristan da Cunha') : echo 'selected'; endif; ?> value="Tristan da Cunha">Tristan da Cunha (Tristan da Cunha)</option>
										<option <?php if ($land == 'Tschad') : echo 'selected'; endif; ?> value="Tschad">Tschad (Chad)</option>
										<option <?php if ($land == 'Tschechische Republik') : echo 'selected'; endif; ?> value="Tschechische Republik">Tschechische Republik (Czech Republic)</option>
										<option <?php if ($land == 'Tunesien') : echo 'selected'; endif; ?> value="Tunesien">Tunesien (Tunisia)</option>
										<option <?php if ($land == 'Türkei') : echo 'selected'; endif; ?> value="Türkei">Türkei (Turkey)</option>
										<option <?php if ($land == 'Turkmenistan') : echo 'selected'; endif; ?> value="Turkmenistan">Turkmenistan (Turkmenistan)</option>
										<option <?php if ($land == 'Turks- und Caicosinseln') : echo 'selected'; endif; ?> value="Turks- und Caicosinseln">Turks- und Caicosinseln (Turks And Caicos Islands)</option>
										<option <?php if ($land == 'Tuvalu') : echo 'selected'; endif; ?> value="Tuvalu">Tuvalu (Tuvalu)</option>
										<option <?php if ($land == 'Uganda') : echo 'selected'; endif; ?> value="Uganda">Uganda (Uganda)</option>
										<option <?php if ($land == 'Ukraine') : echo 'selected'; endif; ?> value="Ukraine">Ukraine (Ukraine)</option>
										<option <?php if ($land == 'Ungarn') : echo 'selected'; endif; ?> value="Ungarn">Ungarn (Hungary)</option>
										<option <?php if ($land == 'Uruguay') : echo 'selected'; endif; ?> value="Uruguay">Uruguay (Uruguay)</option>
										<option <?php if ($land == 'Usbekistan') : echo 'selected'; endif; ?> value="Usbekistan">Usbekistan (Uzbekistan)</option>
										<option <?php if ($land == 'Vanuatu') : echo 'selected'; endif; ?> value="Vanuatu">Vanuatu (Vanuatu)</option>
										<option <?php if ($land == 'Vatikanstadt') : echo 'selected'; endif; ?> value="Vatikanstadt">Vatikanstadt (Holy See (vatican City State))</option>
										<option <?php if ($land == 'Venezuela') : echo 'selected'; endif; ?> value="Venezuela">Venezuela (Venezuela)</option>
										<option <?php if ($land == 'Vereinigte Arabische Emirate') : echo 'selected'; endif; ?> value="Vereinigte Arabische Emirate">Vereinigte Arabische Emirate (United Arab Emirates)</option>
										<option <?php if ($land == 'Vereinigte Staaten von Amerika') : echo 'selected'; endif; ?> value="Vereinigte Staaten von Amerika">Vereinigte Staaten von Amerika (United States)</option>
										<option <?php if ($land == 'Vietnam') : echo 'selected'; endif; ?> value="Vietnam">Vietnam (Viet Nam)</option>
										<option <?php if ($land == 'Wallis und Futuna') : echo 'selected'; endif; ?> value="Wallis und Futuna">Wallis und Futuna (Wallis And Futuna)</option>
										<option <?php if ($land == 'Weihnachtsinsel') : echo 'selected'; endif; ?> value="Weihnachtsinsel">Weihnachtsinsel (Christmas Island)</option>
										<option <?php if ($land == 'Weißrussland') : echo 'selected'; endif; ?> value="Weißrussland">Weißrussland (Belarus)</option>
										<option <?php if ($land == 'Westsahara') : echo 'selected'; endif; ?> value="Westsahara">Westsahara (Western Sahara)</option>
										<option <?php if ($land == 'Zentralafrikanische Republik') : echo 'selected'; endif; ?> value="Zentralafrikanische Republik">Zentralafrikanische Republik (Central African Republic)</option>
										<option <?php if ($land == 'Zypern') : echo 'selected'; endif; ?> value="Zypern">Zypern (Cyprus)</option>
									</select>
								</div>
							</div>

							<!-- Kontakt -->
							<div class="form-row">
								<strong class="pl-2 pb-1 w-100">Kontakt*</strong>
								<div class="form-group col-md-12">
									<input type="tel" class="form-control" name="telefon" id="telefon" placeholder="Telefon" value="<?php echo $telefon; ?>" pattern=".*[0-9\-\+\s\(\)].*" title="Nur Zahlen und folgende Sonderzeichen dürfen verwendet werden: -, +, (, ), /, *, $" min-length="5" required>
								</div>
								<div class="form-group col-md-12">
									<input type="email" class="form-control" name="email" id="email" placeholder="E-Mail"  value="<?php echo $email; ?>"required>
								</div>
							</div>

							<!-- Geburtsdatum, Herkunft und Schulabschluss -->
							<div class="form-row">
								<strong class="pl-2 pb-1 w-100">Geburtsdatum*, Herkunft und Schulabschluss</strong>
								<?php echo $fehlerangabe; ?>
								<div class="form-group col-md-6">
									<input type="text" class="form-control" name="geburtsdatum" id="geburtsdatum" value="<?php echo $geburtsdatum; ?>" title="Bitte geben Sie Ihr Geburtsdatum im Format TT.MM.JJJJ an" autocomplete="bday" placeholder="Geburtsdatum (TT.MM.JJJJ)*" pattern="^(31|30|0[1-9]|[12][0-9]|[1-9])\.(0[1-9]|1[012]|[1-9])\.((18|19|20)\d{2}|\d{2})$" required>
								</div>
								<div class="form-group col-md-6">
									<input type="text" class="form-control" name="geburtsort" id="geburtsort" placeholder="Geburtsort (optional)" value="<?php echo $geburtsort; ?>" pattern=".*([a-zA-Z]){1,}.*([a-zA-Z]){1,}.*">
								</div>
								<div class="form-group col-md-6">
									<input type="text" class="form-control" name="geburtsname" id="geburtsname" placeholder="Geburtsname (nur wenn abweichend)" value="<?php echo $geburtsname; ?>" pattern=".*([a-zA-Z]){1,}.*([a-zA-Z]){1,}.*">
								</div>
								<div class="form-group col-md-6">
									<select class="form-control" name="herkunftsland" id="herkunftsland">
										<option value="">Herkunftsland (nur wenn abweichend)</option>
										<option <?php if ($herkunftsland == 'Afghanistan') : echo 'selected'; endif; ?> value="Afghanistan">Afghanistan (Afghanistan)</option>
										<option <?php if ($herkunftsland == 'Ägypten') : echo 'selected'; endif; ?> value="Ägypten">Ägypten (Egypt)</option>
										<option <?php if ($herkunftsland == 'Aland') : echo 'selected'; endif; ?> value="Aland">Aland (Åland Islands)</option>
										<option <?php if ($herkunftsland == 'Albanien') : echo 'selected'; endif; ?> value="Albanien">Albanien (Albania)</option>
										<option <?php if ($herkunftsland == 'Algerien') : echo 'selected'; endif; ?> value="Algerien">Algerien (Algeria)</option>
										<option <?php if ($herkunftsland == 'Amerikanisch-Samoa') : echo 'selected'; endif; ?> value="Amerikanisch-Samoa">Amerikanisch-Samoa (American Samoa)</option>
										<option <?php if ($herkunftsland == 'Amerikanische Jungferninseln') : echo 'selected'; endif; ?> value="Amerikanische Jungferninseln">Amerikanische Jungferninseln (Virgin Islands, U.s.)</option>
										<option <?php if ($herkunftsland == 'Andorra') : echo 'selected'; endif; ?> value="Andorra">Andorra (Andorra)</option>
										<option <?php if ($herkunftsland == 'Angola') : echo 'selected'; endif; ?> value="Angola">Angola (Angola)</option>
										<option <?php if ($herkunftsland == 'Anguilla') : echo 'selected'; endif; ?> value="Anguilla">Anguilla (Anguilla)</option>
										<option <?php if ($herkunftsland == 'Antarktis') : echo 'selected'; endif; ?> value="Antarktis">Antarktis (Antarctica)</option>
										<option <?php if ($herkunftsland == 'Antigua und Barbuda') : echo 'selected'; endif; ?> value="Antigua und Barbuda">Antigua und Barbuda (Antigua And Barbuda)</option>
										<option <?php if ($herkunftsland == 'Äquatorialguinea') : echo 'selected'; endif; ?> value="Äquatorialguinea">Äquatorialguinea (Equatorial Guinea)</option>
										<option <?php if ($herkunftsland == 'Argentinien') : echo 'selected'; endif; ?> value="Argentinien">Argentinien (Argentina)</option>
										<option <?php if ($herkunftsland == 'Armenien') : echo 'selected'; endif; ?> value="Armenien">Armenien (Armenia)</option>
										<option <?php if ($herkunftsland == 'Aruba') : echo 'selected'; endif; ?> value="Aruba">Aruba (Aruba)</option>
										<option <?php if ($herkunftsland == 'Ascension') : echo 'selected'; endif; ?> value="Ascension">Ascension (Ascension)</option>
										<option <?php if ($herkunftsland == 'Aserbaidschan') : echo 'selected'; endif; ?> value="Aserbaidschan">Aserbaidschan (Azerbaijan)</option>
										<option <?php if ($herkunftsland == 'Äthiopien') : echo 'selected'; endif; ?> value="Äthiopien">Äthiopien (Ethiopia)</option>
										<option <?php if ($herkunftsland == 'Australien') : echo 'selected'; endif; ?> value="Australien">Australien (Australia)</option>
										<option <?php if ($herkunftsland == 'Bahamas') : echo 'selected'; endif; ?> value="Bahamas">Bahamas (Bahamas)</option>
										<option <?php if ($herkunftsland == 'Bahrain') : echo 'selected'; endif; ?> value="Bahrain">Bahrain (Bahrain)</option>
										<option <?php if ($herkunftsland == 'Bangladesch') : echo 'selected'; endif; ?> value="Bangladesch">Bangladesch (Bangladesh)</option>
										<option <?php if ($herkunftsland == 'Barbados') : echo 'selected'; endif; ?> value="Barbados">Barbados (Barbados)</option>
										<option <?php if ($herkunftsland == 'Belgien') : echo 'selected'; endif; ?> value="Belgien">Belgien (Belgium)</option>
										<option <?php if ($herkunftsland == 'Belize') : echo 'selected'; endif; ?> value="Belize">Belize (Belize)</option>
										<option <?php if ($herkunftsland == 'Benin') : echo 'selected'; endif; ?> value="Benin">Benin (Benin)</option>
										<option <?php if ($herkunftsland == 'Bermuda') : echo 'selected'; endif; ?> value="Bermuda">Bermuda (Bermuda)</option>
										<option <?php if ($herkunftsland == 'Bhutan') : echo 'selected'; endif; ?> value="Bhutan">Bhutan (Bhutan)</option>
										<option <?php if ($herkunftsland == 'Bolivien') : echo 'selected'; endif; ?> value="Bolivien">Bolivien (Bolivia)</option>
										<option <?php if ($herkunftsland == 'Bosnien und Herzegowina') : echo 'selected'; endif; ?> value="Bosnien und Herzegowina">Bosnien und Herzegowina (Bosnia And Herzegovina)</option>
										<option <?php if ($herkunftsland == 'Botswana') : echo 'selected'; endif; ?> value="Botswana">Botswana (Botswana)</option>
										<option <?php if ($herkunftsland == 'Bouvetinsel') : echo 'selected'; endif; ?> value="Bouvetinsel">Bouvetinsel (Bouvet Island)</option>
										<option <?php if ($herkunftsland == 'Brasilien') : echo 'selected'; endif; ?> value="Brasilien">Brasilien (Brazil)</option>
										<option <?php if ($herkunftsland == 'Brunei') : echo 'selected'; endif; ?> value="Brunei">Brunei (Brunei Darussalam)</option>
										<option <?php if ($herkunftsland == 'Bulgarien') : echo 'selected'; endif; ?> value="Bulgarien">Bulgarien (Bulgaria)</option>
										<option <?php if ($herkunftsland == 'Burkina Faso') : echo 'selected'; endif; ?> value="Burkina Faso">Burkina Faso (Burkina Faso)</option>
										<option <?php if ($herkunftsland == 'Burundi') : echo 'selected'; endif; ?> value="Burundi">Burundi (Burundi)</option>
										<option <?php if ($herkunftsland == 'Chile') : echo 'selected'; endif; ?> value="Chile">Chile (Chile)</option>
										<option <?php if ($herkunftsland == 'China') : echo 'selected'; endif; ?> value="China">China (China)</option>
										<option <?php if ($herkunftsland == 'Cookinseln') : echo 'selected'; endif; ?> value="Cookinseln">Cookinseln (Cook Islands)</option>
										<option <?php if ($herkunftsland == 'Costa Rica') : echo 'selected'; endif; ?> value="Costa Rica">Costa Rica (Costa Rica)</option>
										<option <?php if ($herkunftsland == 'Cote d`Ivoire') : echo 'selected'; endif; ?> value="Cote d`Ivoire">Cote d'Ivoire (CÔte D'ivoire)</option>
										<option <?php if ($herkunftsland == 'Dänemark') : echo 'selected'; endif; ?> value="Dänemark">Dänemark (Denmark)</option>
										<option <?php if ($herkunftsland == 'Deutschland') : echo 'selected'; endif; ?> value="Deutschland">Deutschland (Germany)</option>
										<option <?php if ($herkunftsland == 'Diego Garcia') : echo 'selected'; endif; ?> value="Diego Garcia">Diego Garcia (Diego Garcia)</option>
										<option <?php if ($herkunftsland == 'Dominica') : echo 'selected'; endif; ?> value="Dominica">Dominica (Dominica)</option>
										<option <?php if ($herkunftsland == 'Dominikanische Republik') : echo 'selected'; endif; ?> value="Dominikanische Republik">Dominikanische Republik (Dominican Republic)</option>
										<option <?php if ($herkunftsland == 'Dschibuti') : echo 'selected'; endif; ?> value="Dschibuti">Dschibuti (Djibouti)</option>
										<option <?php if ($herkunftsland == 'Ecuador') : echo 'selected'; endif; ?> value="Ecuador">Ecuador (Ecuador)</option>
										<option <?php if ($herkunftsland == 'El Salvador') : echo 'selected'; endif; ?> value="El Salvador">El Salvador (El Salvador)</option>
										<option <?php if ($herkunftsland == 'Eritrea') : echo 'selected'; endif; ?> value="Eritrea">Eritrea (Eritrea)</option>
										<option <?php if ($herkunftsland == 'Estland') : echo 'selected'; endif; ?> value="Estland">Estland (Estonia)</option>
										<option <?php if ($herkunftsland == 'Europäische Union') : echo 'selected'; endif; ?> value="Europäische Union">Europäische Union (Europäische Union)</option>
										<option <?php if ($herkunftsland == 'Falklandinseln') : echo 'selected'; endif; ?> value="Falklandinseln">Falklandinseln (Falkland Islands (malvinas))</option>
										<option <?php if ($herkunftsland == 'Färöer') : echo 'selected'; endif; ?> value="Färöer">Färöer (Faroe Islands)</option>
										<option <?php if ($herkunftsland == 'Fidschi') : echo 'selected'; endif; ?> value="Fidschi">Fidschi (Fiji)</option>
										<option <?php if ($herkunftsland == 'Finnland') : echo 'selected'; endif; ?> value="Finnland">Finnland (Finland)</option>
										<option <?php if ($herkunftsland == 'Frankreich') : echo 'selected'; endif; ?> value="Frankreich">Frankreich (France)</option>
										<option <?php if ($herkunftsland == 'Französisch-Guayana') : echo 'selected'; endif; ?> value="Französisch-Guayana">Französisch-Guayana (French Guiana)</option>
										<option <?php if ($herkunftsland == 'Französisch-Polynesien') : echo 'selected'; endif; ?> value="Französisch-Polynesien">Französisch-Polynesien (French Polynesia)</option>
										<option <?php if ($herkunftsland == 'Gabun') : echo 'selected'; endif; ?> value="Gabun">Gabun (Gabon)</option>
										<option <?php if ($herkunftsland == 'Gambia') : echo 'selected'; endif; ?> value="Gambia">Gambia (Gambia)</option>
										<option <?php if ($herkunftsland == 'Georgien') : echo 'selected'; endif; ?> value="Georgien">Georgien (Georgia)</option>
										<option <?php if ($herkunftsland == 'Ghana') : echo 'selected'; endif; ?> value="Ghana">Ghana (Ghana)</option>
										<option <?php if ($herkunftsland == 'Gibraltar') : echo 'selected'; endif; ?> value="Gibraltar">Gibraltar (Gibraltar)</option>
										<option <?php if ($herkunftsland == 'Grenada') : echo 'selected'; endif; ?> value="Grenada">Grenada (Grenada)</option>
										<option <?php if ($herkunftsland == 'Griechenland') : echo 'selected'; endif; ?> value="Griechenland">Griechenland (Greece)</option>
										<option <?php if ($herkunftsland == 'Grönland') : echo 'selected'; endif; ?> value="Grönland">Grönland (Greenland)</option>
										<option <?php if ($herkunftsland == 'Großbritannien') : echo 'selected'; endif; ?> value="Großbritannien">Großbritannien (Create Britain)</option>
										<option <?php if ($herkunftsland == 'Guadeloupe') : echo 'selected'; endif; ?> value="Guadeloupe">Guadeloupe (Guadeloupe)</option>
										<option <?php if ($herkunftsland == 'Guam') : echo 'selected'; endif; ?> value="Guam">Guam (Guam)</option>
										<option <?php if ($herkunftsland == 'Guatemala') : echo 'selected'; endif; ?> value="Guatemala">Guatemala (Guatemala)</option>
										<option <?php if ($herkunftsland == 'Guernsey') : echo 'selected'; endif; ?> value="Guernsey">Guernsey (Guernsey)</option>
										<option <?php if ($herkunftsland == 'Guinea') : echo 'selected'; endif; ?> value="Guinea">Guinea (Guinea)</option>
										<option <?php if ($herkunftsland == 'Guinea-Bissau') : echo 'selected'; endif; ?> value="Guinea-Bissau">Guinea-Bissau (Guinea-bissau)</option>
										<option <?php if ($herkunftsland == 'Guyana') : echo 'selected'; endif; ?> value="Guyana">Guyana (Guyana)</option>
										<option <?php if ($herkunftsland == 'Haiti') : echo 'selected'; endif; ?> value="Haiti">Haiti (Haiti)</option>
										<option <?php if ($herkunftsland == 'Heard und McDonaldinseln') : echo 'selected'; endif; ?> value="Heard und McDonaldinseln">Heard und McDonaldinseln (Heard Island And Mcdonald Islands)</option>
										<option <?php if ($herkunftsland == 'Honduras') : echo 'selected'; endif; ?> value="Honduras">Honduras (Honduras)</option>
										<option <?php if ($herkunftsland == 'Hongkong') : echo 'selected'; endif; ?> value="Hongkong">Hongkong (Hong Kong)</option>
										<option <?php if ($herkunftsland == 'Indien') : echo 'selected'; endif; ?> value="Indien">Indien (India)</option>
										<option <?php if ($herkunftsland == 'Indonesien') : echo 'selected'; endif; ?> value="Indonesien">Indonesien (Indonesia)</option>
										<option <?php if ($herkunftsland == 'Irak') : echo 'selected'; endif; ?> value="Irak">Irak (Iraq)</option>
										<option <?php if ($herkunftsland == 'Iran') : echo 'selected'; endif; ?> value="Iran">Iran (Iran, Islamic Republic Of)</option>
										<option <?php if ($herkunftsland == 'Irland') : echo 'selected'; endif; ?> value="Irland">Irland (Ireland)</option>
										<option <?php if ($herkunftsland == 'Island') : echo 'selected'; endif; ?> value="Island">Island (Iceland)</option>
										<option <?php if ($herkunftsland == 'Israel') : echo 'selected'; endif; ?> value="Israel">Israel (Israel)</option>
										<option <?php if ($herkunftsland == 'Italien') : echo 'selected'; endif; ?> value="Italien">Italien (Italy)</option>
										<option <?php if ($herkunftsland == 'Jamaika') : echo 'selected'; endif; ?> value="Jamaika">Jamaika (Jamaica)</option>
										<option <?php if ($herkunftsland == 'Japan') : echo 'selected'; endif; ?> value="Japan">Japan (Japan)</option>
										<option <?php if ($herkunftsland == 'Jemen') : echo 'selected'; endif; ?> value="Jemen">Jemen (Yemen)</option>
										<option <?php if ($herkunftsland == 'Jersey') : echo 'selected'; endif; ?> value="Jersey">Jersey (Jersey)</option>
										<option <?php if ($herkunftsland == 'Jordanien') : echo 'selected'; endif; ?> value="Jordanien">Jordanien (Jordan)</option>
										<option <?php if ($herkunftsland == 'Kaimaninseln') : echo 'selected'; endif; ?> value="Kaimaninseln">Kaimaninseln (Cayman Islands)</option>
										<option <?php if ($herkunftsland == 'Kambodscha') : echo 'selected'; endif; ?> value="Kambodscha">Kambodscha (Cambodia)</option>
										<option <?php if ($herkunftsland == 'Kamerun') : echo 'selected'; endif; ?> value="Kamerun">Kamerun (Cameroon)</option>
										<option <?php if ($herkunftsland == 'Kanada') : echo 'selected'; endif; ?> value="Kanada">Kanada (Canada)</option>
										<option <?php if ($herkunftsland == 'Kanarische Inseln') : echo 'selected'; endif; ?> value="Kanarische Inseln">Kanarische Inseln (Kanarische Inseln)</option>
										<option <?php if ($herkunftsland == 'Kap Verde') : echo 'selected'; endif; ?> value="Kap Verde">Kap Verde (Cape Verde)</option>
										<option <?php if ($herkunftsland == 'Kasachstan') : echo 'selected'; endif; ?> value="Kasachstan">Kasachstan (Kazakhstan)</option>
										<option <?php if ($herkunftsland == 'Katar') : echo 'selected'; endif; ?> value="Katar">Katar (Qatar)</option>
										<option <?php if ($herkunftsland == 'Kenia') : echo 'selected'; endif; ?> value="Kenia">Kenia (Kenya)</option>
										<option <?php if ($herkunftsland == 'Kirgisistan') : echo 'selected'; endif; ?> value="Kirgisistan">Kirgisistan (Kyrgyzstan)</option>
										<option <?php if ($herkunftsland == 'Kiribati') : echo 'selected'; endif; ?> value="Kiribati">Kiribati (Kiribati)</option>
										<option <?php if ($herkunftsland == 'Kokosinseln') : echo 'selected'; endif; ?> value="Kokosinseln">Kokosinseln (Cocos (keeling) Islands)</option>
										<option <?php if ($herkunftsland == 'Kolumbien') : echo 'selected'; endif; ?> value="Kolumbien">Kolumbien (Colombia)</option>
										<option <?php if ($herkunftsland == 'Komoren') : echo 'selected'; endif; ?> value="Komoren">Komoren (Comoros)</option>
										<option <?php if ($herkunftsland == 'Kongo') : echo 'selected'; endif; ?> value="Kongo">Kongo (Congo)</option>
										<option <?php if ($herkunftsland == 'Kroatien') : echo 'selected'; endif; ?> value="Kroatien">Kroatien (Croatia)</option>
										<option <?php if ($herkunftsland == 'Kuba') : echo 'selected'; endif; ?> value="Kuba">Kuba (Cuba)</option>
										<option <?php if ($herkunftsland == 'Kuwait') : echo 'selected'; endif; ?> value="Kuwait">Kuwait (Kuwait)</option>
										<option <?php if ($herkunftsland == 'Laos') : echo 'selected'; endif; ?> value="Laos">Laos (Lao People's Democratic Republic)</option>
										<option <?php if ($herkunftsland == 'Lesotho') : echo 'selected'; endif; ?> value="Lesotho">Lesotho (Lesotho)</option>
										<option <?php if ($herkunftsland == 'Lettland') : echo 'selected'; endif; ?> value="Lettland">Lettland (Latvia)</option>
										<option <?php if ($herkunftsland == 'Libanon') : echo 'selected'; endif; ?> value="Libanon">Libanon (Lebanon)</option>
										<option <?php if ($herkunftsland == 'Liberia') : echo 'selected'; endif; ?> value="Liberia">Liberia (Liberia)</option>
										<option <?php if ($herkunftsland == 'Libyen') : echo 'selected'; endif; ?> value="Libyen">Libyen (Libyan Arab Jamahiriya)</option>
										<option <?php if ($herkunftsland == 'Liechtenstein') : echo 'selected'; endif; ?> value="Liechtenstein">Liechtenstein (Liechtenstein)</option>
										<option <?php if ($herkunftsland == 'Litauen') : echo 'selected'; endif; ?> value="Litauen">Litauen (Lithuania)</option>
										<option <?php if ($herkunftsland == 'Luxemburg') : echo 'selected'; endif; ?> value="Luxemburg">Luxemburg (Luxembourg)</option>
										<option <?php if ($herkunftsland == 'Macao') : echo 'selected'; endif; ?> value="Macao">Macao (Macao)</option>
										<option <?php if ($herkunftsland == 'Madagaskar') : echo 'selected'; endif; ?> value="Madagaskar">Madagaskar (Madagascar)</option>
										<option <?php if ($herkunftsland == 'Malawi') : echo 'selected'; endif; ?> value="Malawi">Malawi (Malawi)</option>
										<option <?php if ($herkunftsland == 'Malaysia') : echo 'selected'; endif; ?> value="Malaysia">Malaysia (Malaysia)</option>
										<option <?php if ($herkunftsland == 'Malediven') : echo 'selected'; endif; ?> value="Malediven">Malediven (Maldives)</option>
										<option <?php if ($herkunftsland == 'Mali') : echo 'selected'; endif; ?> value="Mali">Mali (Mali)</option>
										<option <?php if ($herkunftsland == 'Malta') : echo 'selected'; endif; ?> value="Malta">Malta (Malta)</option>
										<option <?php if ($herkunftsland == 'Marokko') : echo 'selected'; endif; ?> value="Marokko">Marokko (Morocco)</option>
										<option <?php if ($herkunftsland == 'Marshallinseln') : echo 'selected'; endif; ?> value="Marshallinseln">Marshallinseln (Marshall Islands)</option>
										<option <?php if ($herkunftsland == 'Martinique') : echo 'selected'; endif; ?> value="Martinique">Martinique (Martinique)</option>
										<option <?php if ($herkunftsland == 'Mauretanien') : echo 'selected'; endif; ?> value="Mauretanien">Mauretanien (Mauritania)</option>
										<option <?php if ($herkunftsland == 'Mauritius') : echo 'selected'; endif; ?> value="Mauritius">Mauritius (Mauritius)</option>
										<option <?php if ($herkunftsland == 'Mayotte') : echo 'selected'; endif; ?> value="Mayotte">Mayotte (Mayotte)</option>
										<option <?php if ($herkunftsland == 'Mazedonien') : echo 'selected'; endif; ?> value="Mazedonien">Mazedonien (Macedonia, The Former Yugoslav Republic Of)</option>
										<option <?php if ($herkunftsland == 'Mexiko') : echo 'selected'; endif; ?> value="Mexiko">Mexiko (Mexico)</option>
										<option <?php if ($herkunftsland == 'Mikronesien') : echo 'selected'; endif; ?> value="Mikronesien">Mikronesien (Micronesia)</option>
										<option <?php if ($herkunftsland == 'Moldawien') : echo 'selected'; endif; ?> value="Moldawien">Moldawien (Moldova)</option>
										<option <?php if ($herkunftsland == 'Monaco') : echo 'selected'; endif; ?> value="Monaco">Monaco (Monaco)</option>
										<option <?php if ($herkunftsland == 'Mongolei') : echo 'selected'; endif; ?> value="Mongolei">Mongolei (Mongolia)</option>
										<option <?php if ($herkunftsland == 'Montserrat') : echo 'selected'; endif; ?> value="Montserrat">Montserrat (Montserrat)</option>
										<option <?php if ($herkunftsland == 'Mosambik') : echo 'selected'; endif; ?> value="Mosambik">Mosambik (Mozambique)</option>
										<option <?php if ($herkunftsland == 'Myanmar') : echo 'selected'; endif; ?> value="Myanmar">Myanmar (Myanmar)</option>
										<option <?php if ($herkunftsland == 'Namibia') : echo 'selected'; endif; ?> value="Namibia">Namibia (Namibia)</option>
										<option <?php if ($herkunftsland == 'Nauru') : echo 'selected'; endif; ?> value="Nauru">Nauru (Nauru)</option>
										<option <?php if ($herkunftsland == 'Nepal') : echo 'selected'; endif; ?> value="Nepal">Nepal (Nepal)</option>
										<option <?php if ($herkunftsland == 'Neukaledonien') : echo 'selected'; endif; ?> value="Neukaledonien">Neukaledonien (New Caledonia)</option>
										<option <?php if ($herkunftsland == 'Neuseeland') : echo 'selected'; endif; ?> value="Neuseeland">Neuseeland (New Zealand)</option>
										<option <?php if ($herkunftsland == 'Neutrale Zone') : echo 'selected'; endif; ?> value="Neutrale Zone">Neutrale Zone (Neutrale Zone)</option>
										<option <?php if ($herkunftsland == 'Nicaragua') : echo 'selected'; endif; ?> value="Nicaragua">Nicaragua (Nicaragua)</option>
										<option <?php if ($herkunftsland == 'Niederlande') : echo 'selected'; endif; ?> value="Niederlande">Niederlande (Netherlands)</option>
										<option <?php if ($herkunftsland == 'Niederländische Antillen') : echo 'selected'; endif; ?> value="Niederländische Antillen">Niederländische Antillen (Netherlands Antilles)</option>
										<option <?php if ($herkunftsland == 'Niger') : echo 'selected'; endif; ?> value="Niger">Niger (Niger)</option>
										<option <?php if ($herkunftsland == 'Nigeria') : echo 'selected'; endif; ?> value="Nigeria">Nigeria (Nigeria)</option>
										<option <?php if ($herkunftsland == 'Niue') : echo 'selected'; endif; ?> value="Niue">Niue (Niue)</option>
										<option <?php if ($herkunftsland == 'Nordkorea') : echo 'selected'; endif; ?> value="Nordkorea">Nordkorea (North Korea)</option>
										<option <?php if ($herkunftsland == 'Nördliche Marianen') : echo 'selected'; endif; ?> value="Nördliche Marianen">Nördliche Marianen (Northern Mariana Islands)</option>
										<option <?php if ($herkunftsland == 'Norfolkinsel') : echo 'selected'; endif; ?> value="Norfolkinsel">Norfolkinsel (Norfolk Island)</option>
										<option <?php if ($herkunftsland == 'Norwegen') : echo 'selected'; endif; ?> value="Norwegen">Norwegen (Norway)</option>
										<option <?php if ($herkunftsland == 'Oman') : echo 'selected'; endif; ?> value="Oman">Oman (Oman)</option>
										<option <?php if ($herkunftsland == 'Österreich') : echo 'selected'; endif; ?> value="Österreich">Österreich (Austria)</option>
										<option <?php if ($herkunftsland == 'Pakistan') : echo 'selected'; endif; ?> value="Pakistan">Pakistan (Pakistan)</option>
										<option <?php if ($herkunftsland == 'Palästina') : echo 'selected'; endif; ?> value="Palästina">Palästina (Palestinian Territory)</option>
										<option <?php if ($herkunftsland == 'Palau') : echo 'selected'; endif; ?> value="Palau">Palau (Palau)</option>
										<option <?php if ($herkunftsland == 'Panama') : echo 'selected'; endif; ?> value="Panama">Panama (Panama)</option>
										<option <?php if ($herkunftsland == 'Papua-Neuguinea') : echo 'selected'; endif; ?> value="Papua-Neuguinea">Papua-Neuguinea (Papua New Guinea)</option>
										<option <?php if ($herkunftsland == 'Paraguay') : echo 'selected'; endif; ?> value="Paraguay">Paraguay (Paraguay)</option>
										<option <?php if ($herkunftsland == 'Peru') : echo 'selected'; endif; ?> value="Peru">Peru (Peru)</option>
										<option <?php if ($herkunftsland == 'Philippinen') : echo 'selected'; endif; ?> value="Philippinen">Philippinen (Philippines)</option>
										<option <?php if ($herkunftsland == 'Pitcairninseln') : echo 'selected'; endif; ?> value="Pitcairninseln">Pitcairninseln (Pitcairn)</option>
										<option <?php if ($herkunftsland == 'Polen') : echo 'selected'; endif; ?> value="Polen">Polen (Poland)</option>
										<option <?php if ($herkunftsland == 'Portugal') : echo 'selected'; endif; ?> value="Portugal">Portugal (Portugal)</option>
										<option <?php if ($herkunftsland == 'Puerto Rico') : echo 'selected'; endif; ?> value="Puerto Rico">Puerto Rico (Puerto Rico)</option>
										<option <?php if ($herkunftsland == 'Réunion') : echo 'selected'; endif; ?> value="Réunion">Réunion (RÉunion)</option>
										<option <?php if ($herkunftsland == 'Ruanda') : echo 'selected'; endif; ?> value="Ruanda">Ruanda (Rwanda)</option>
										<option <?php if ($herkunftsland == 'Rumänien') : echo 'selected'; endif; ?> value="Rumänien">Rumänien (Romania)</option>
										<option <?php if ($herkunftsland == 'Russische Föderation') : echo 'selected'; endif; ?> value="Russische Föderation">Russische Föderation (Russian Federation)</option>
										<option <?php if ($herkunftsland == 'Salomonen') : echo 'selected'; endif; ?> value="Salomonen">Salomonen (Solomon Islands)</option>
										<option <?php if ($herkunftsland == 'Sambia') : echo 'selected'; endif; ?> value="Sambia">Sambia (Zambia)</option>
										<option <?php if ($herkunftsland == 'Samoa') : echo 'selected'; endif; ?> value="Samoa">Samoa (Samoa)</option>
										<option <?php if ($herkunftsland == 'San Marino') : echo 'selected'; endif; ?> value="San Marino">San Marino (San Marino)</option>
										<option <?php if ($herkunftsland == 'São Tomé und Príncipe') : echo 'selected'; endif; ?> value="São Tomé und Príncipe">São Tomé und Príncipe (Sao Tome And Principe)</option>
										<option <?php if ($herkunftsland == 'Saudi-Arabien') : echo 'selected'; endif; ?> value="Saudi-Arabien">Saudi-Arabien (Saudi Arabia)</option>
										<option <?php if ($herkunftsland == 'Schweden') : echo 'selected'; endif; ?> value="Schweden">Schweden (Sweden)</option>
										<option <?php if ($herkunftsland == 'Schweiz') : echo 'selected'; endif; ?> value="Schweiz">Schweiz (Switzerland)</option>
										<option <?php if ($herkunftsland == 'Senegal') : echo 'selected'; endif; ?> value="Senegal">Senegal (Senegal)</option>
										<option <?php if ($herkunftsland == 'Serbien und Montenegro') : echo 'selected'; endif; ?> value="Serbien und Montenegro">Serbien und Montenegro (Serbien und Montenegro)</option>
										<option <?php if ($herkunftsland == 'Seychellen') : echo 'selected'; endif; ?> value="Seychellen">Seychellen (Seychelles)</option>
										<option <?php if ($herkunftsland == 'Sierra Leone') : echo 'selected'; endif; ?> value="Sierra Leone">Sierra Leone (Sierra Leone)</option>
										<option <?php if ($herkunftsland == 'Simbabwe') : echo 'selected'; endif; ?> value="Simbabwe">Simbabwe (Zimbabwe)</option>
										<option <?php if ($herkunftsland == 'Singapur') : echo 'selected'; endif; ?> value="Singapur">Singapur (Singapore)</option>
										<option <?php if ($herkunftsland == 'Slowakei') : echo 'selected'; endif; ?> value="Slowakei">Slowakei (Slovakia)</option>
										<option <?php if ($herkunftsland == 'Slowenien') : echo 'selected'; endif; ?> value="Slowenien">Slowenien (Slovenia)</option>
										<option <?php if ($herkunftsland == 'Somalia') : echo 'selected'; endif; ?> value="Somalia">Somalia (Somalia)</option>
										<option <?php if ($herkunftsland == 'Spanien') : echo 'selected'; endif; ?> value="Spanien">Spanien (Spain)</option>
										<option <?php if ($herkunftsland == 'Sri Lanka') : echo 'selected'; endif; ?> value="Sri Lanka">Sri Lanka (Sri Lanka)</option>
										<option <?php if ($herkunftsland == 'St. Helena') : echo 'selected'; endif; ?> value="St. Helena">St. Helena (Saint Helena)</option>
										<option <?php if ($herkunftsland == 'St. Kitts und Nevis') : echo 'selected'; endif; ?> value="St. Kitts und Nevis">St. Kitts und Nevis (Saint Kitts And Nevis)</option>
										<option <?php if ($herkunftsland == 'St. Lucia') : echo 'selected'; endif; ?> value="St. Lucia">St. Lucia (Saint Lucia)</option>
										<option <?php if ($herkunftsland == 'St. Pierre und Miquelon') : echo 'selected'; endif; ?> value="St. Pierre und Miquelon">St. Pierre und Miquelon (Saint Pierre And Miquelon)</option>
										<option <?php if ($herkunftsland == 'St. Vincent/Grenadinen (GB)') : echo 'selected'; endif; ?> value="St. Vincent/Grenadinen (GB)">St. Vincent/Grenadinen (GB) (Saint Vincent/Grenadines)</option>
										<option <?php if ($herkunftsland == 'Südafrika, Republik') : echo 'selected'; endif; ?> value="Südafrika, Republik">Südafrika, Republik (South Africa)</option>
										<option <?php if ($herkunftsland == 'Sudan') : echo 'selected'; endif; ?> value="Sudan">Sudan (Sudan)</option>
										<option <?php if ($herkunftsland == 'Südkorea') : echo 'selected'; endif; ?> value="Südkorea">Südkorea (South Korea)</option>
										<option <?php if ($herkunftsland == 'Suriname') : echo 'selected'; endif; ?> value="Suriname">Suriname (Suriname)</option>
										<option <?php if ($herkunftsland == 'Svalbard und Jan Mayen') : echo 'selected'; endif; ?> value="Svalbard und Jan Mayen">Svalbard und Jan Mayen (Svalbard And Jan Mayen)</option>
										<option <?php if ($herkunftsland == 'Swasiland') : echo 'selected'; endif; ?> value="Swasiland">Swasiland (Swaziland)</option>
										<option <?php if ($herkunftsland == 'Syrien') : echo 'selected'; endif; ?> value="Syrien">Syrien (Syrian Arab Republic)</option>
										<option <?php if ($herkunftsland == 'Tadschikistan') : echo 'selected'; endif; ?> value="Tadschikistan">Tadschikistan (Tajikistan)</option>
										<option <?php if ($herkunftsland == 'Taiwan') : echo 'selected'; endif; ?> value="Taiwan">Taiwan (Taiwan, Province Of China)</option>
										<option <?php if ($herkunftsland == 'Tansania') : echo 'selected'; endif; ?> value="Tansania">Tansania (Tanzania)</option>
										<option <?php if ($herkunftsland == 'Thailand') : echo 'selected'; endif; ?> value="Thailand">Thailand (Thailand)</option>
										<option <?php if ($herkunftsland == 'Timor-Leste') : echo 'selected'; endif; ?> value="Timor-Leste">Timor-Leste (Timor-leste)</option>
										<option <?php if ($herkunftsland == 'Togo') : echo 'selected'; endif; ?> value="Togo">Togo (Togo)</option>
										<option <?php if ($herkunftsland == 'Tokelau') : echo 'selected'; endif; ?> value="Tokelau">Tokelau (Tokelau)</option>
										<option <?php if ($herkunftsland == 'Tonga') : echo 'selected'; endif; ?> value="Tonga">Tonga (Tonga)</option>
										<option <?php if ($herkunftsland == 'Trinidad und Tobago') : echo 'selected'; endif; ?> value="Trinidad und Tobago">Trinidad und Tobago (Trinidad And Tobago)</option>
										<option <?php if ($herkunftsland == 'Tristan da Cunha') : echo 'selected'; endif; ?> value="Tristan da Cunha">Tristan da Cunha (Tristan da Cunha)</option>
										<option <?php if ($herkunftsland == 'Tschad') : echo 'selected'; endif; ?> value="Tschad">Tschad (Chad)</option>
										<option <?php if ($herkunftsland == 'Tschechische Republik') : echo 'selected'; endif; ?> value="Tschechische Republik">Tschechische Republik (Czech Republic)</option>
										<option <?php if ($herkunftsland == 'Tunesien') : echo 'selected'; endif; ?> value="Tunesien">Tunesien (Tunisia)</option>
										<option <?php if ($herkunftsland == 'Türkei') : echo 'selected'; endif; ?> value="Türkei">Türkei (Turkey)</option>
										<option <?php if ($herkunftsland == 'Turkmenistan') : echo 'selected'; endif; ?> value="Turkmenistan">Turkmenistan (Turkmenistan)</option>
										<option <?php if ($herkunftsland == 'Turks- und Caicosinseln') : echo 'selected'; endif; ?> value="Turks- und Caicosinseln">Turks- und Caicosinseln (Turks And Caicos Islands)</option>
										<option <?php if ($herkunftsland == 'Tuvalu') : echo 'selected'; endif; ?> value="Tuvalu">Tuvalu (Tuvalu)</option>
										<option <?php if ($herkunftsland == 'Uganda') : echo 'selected'; endif; ?> value="Uganda">Uganda (Uganda)</option>
										<option <?php if ($herkunftsland == 'Ukraine') : echo 'selected'; endif; ?> value="Ukraine">Ukraine (Ukraine)</option>
										<option <?php if ($herkunftsland == 'Ungarn') : echo 'selected'; endif; ?> value="Ungarn">Ungarn (Hungary)</option>
										<option <?php if ($herkunftsland == 'Uruguay') : echo 'selected'; endif; ?> value="Uruguay">Uruguay (Uruguay)</option>
										<option <?php if ($herkunftsland == 'Usbekistan') : echo 'selected'; endif; ?> value="Usbekistan">Usbekistan (Uzbekistan)</option>
										<option <?php if ($herkunftsland == 'Vanuatu') : echo 'selected'; endif; ?> value="Vanuatu">Vanuatu (Vanuatu)</option>
										<option <?php if ($herkunftsland == 'Vatikanstadt') : echo 'selected'; endif; ?> value="Vatikanstadt">Vatikanstadt (Holy See (vatican City State))</option>
										<option <?php if ($herkunftsland == 'Venezuela') : echo 'selected'; endif; ?> value="Venezuela">Venezuela (Venezuela)</option>
										<option <?php if ($herkunftsland == 'Vereinigte Arabische Emirate') : echo 'selected'; endif; ?> value="Vereinigte Arabische Emirate">Vereinigte Arabische Emirate (United Arab Emirates)</option>
										<option <?php if ($herkunftsland == 'Vereinigte Staaten von Amerika') : echo 'selected'; endif; ?> value="Vereinigte Staaten von Amerika">Vereinigte Staaten von Amerika (United States)</option>
										<option <?php if ($herkunftsland == 'Vietnam') : echo 'selected'; endif; ?> value="Vietnam">Vietnam (Viet Nam)</option>
										<option <?php if ($herkunftsland == 'Wallis und Futuna') : echo 'selected'; endif; ?> value="Wallis und Futuna">Wallis und Futuna (Wallis And Futuna)</option>
										<option <?php if ($herkunftsland == 'Weihnachtsinsel') : echo 'selected'; endif; ?> value="Weihnachtsinsel">Weihnachtsinsel (Christmas Island)</option>
										<option <?php if ($herkunftsland == 'Weißrussland') : echo 'selected'; endif; ?> value="Weißrussland">Weißrussland (Belarus)</option>
										<option <?php if ($herkunftsland == 'Westsahara') : echo 'selected'; endif; ?> value="Westsahara">Westsahara (Western Sahara)</option>
										<option <?php if ($herkunftsland == 'Zentralafrikanische Republik') : echo 'selected'; endif; ?> value="Zentralafrikanische Republik">Zentralafrikanische Republik (Central African Republic)</option>
										<option <?php if ($herkunftsland == 'Zypern') : echo 'selected'; endif; ?> value="Zypern">Zypern (Cyprus)</option>
									</select>
								</div>
								<div class="form-group col-md-6">
									<select name="schulabschluss" id="schulabschluss" class="form-control" onchange="sonstigeCheck(this);">
										<option value="">Schulabschluss (optional)</option>
										<option <?php if ($schulabschluss == 'Hauptschulabschluss') : echo 'selected'; endif; ?> value="Hauptschulabschluss">Hauptschulabschluss</option>
										<option <?php if ($schulabschluss == 'Realschulabschluss') : echo 'selected'; endif; ?> value="Realschulabschluss">Realschulabschluss</option>
										<option <?php if ($schulabschluss == 'Allgemeine Hochschulreife') : echo 'selected'; endif; ?> value="Allgemeine Hochschulreife">Allgemeine Hochschulreife</option>
										<option <?php if ($schulabschluss == 'Sonstiger Abschluss') : echo 'selected'; endif; ?> value="Sonstiger Abschluss">Sonstiger Abschluss</option>
									</select>
								</div>
								<div class="form-group col-md-6" id="sonstige" <?php if($schulabschluss == 'Sonstiger Abschluss') : ?>style="display: block;" <?php else : ?>style="display:none;"<?php endif; ?>>
									<input type="text" class="form-control" name="sonstiger_abschluss" id="sonstiger_abschluss" placeholder="Alternativer Schulabschluss" value="<?php echo $sonstiger_abschluss; ?>">
								</div>
							</div>

							<!-- Formularfelder aus Teil 2 übergeben -->
							<input type="hidden" name="aufmerksam" value="<?php echo $aufmerksam; ?>">
							<input type="hidden" name="kommentar" value="<?php echo $kommentar; ?>">
							<?php if($ausb_id == '1' || $ausb_id == '2' || $ausb_id == '3' || $ausb_id == '4' || $ausb_id == '8' || $ausb_id == '13' || $ausb_id == '14') : ?>
								<input type="hidden" name="wunscharbeitgeber" value="<?php echo $wunscharbeitgeber_str; ?>">
							<?php endif; ?>
							<input type="hidden" name="weitere_interessen" value="<?php echo $weitere_interessen; ?>">


							<!-- Weiter -->
							<div class="form-row mt-3 mb-2">
								<div class="form-group col-md-5 offset-md-7">
									<button type="submit" class="btn btn-info w-100" name="weiter" value="weiter">
										Weiter
									</button>
								</div>
							</div>
						</form>
					<?php } elseif ($formular == 2) { ?>
						<form action="" method="post" enctype="multipart/form-data">
							<div class="alert alert-info" role="alert">
								Bitte laden Sie die Unterlagen nur in den Formaten <strong>.pdf, .txt, .odt, .doc oder .docx</strong> hoch. Die Dateigröße darf <strong>2 MB</strong> nicht überschreiten. <span class="alert-mobile">Sollten Sie Probleme mit dem Dateiupload auf Ihrem Smartphone oder Tablet haben, versuchen Sie es über einen PC oder Laptop oder bewerben Sie sich gern über den Postweg bei uns.</span>
							</div>
							<?php echo $fehlerangabe; ?>
							<!-- Anschreiben -->
							<div class="form-row">
								<strong class="pl-2 pb-1 w-100">Anschreiben*</strong>
								<div class="form-group col-md-12">
									<div class="custom-file">
										<input type="file" class="custom-file-input required" name="anschreiben" id="anschreiben" required onchange="uploadAnschreiben()">
										<label class="custom-file-label" id="label_anschreiben" for="anschreiben">Pflichtfeld</label>
									</div>
									<i id="galerie_help" class="form-text text-muted mt-2">Laden Sie hier ein Anschreiben im entsprechenden Format hoch.</i>
								</div>
							</div>
							
							<div class="form-row">
								<strong class="pl-2 pb-1 w-100">Lebenslauf*</strong>
								<div class="form-group col-md-12">
									<div class="custom-file">
										<input type="file" class="custom-file-input required" name="lebenslauf" id="lebenslauf" required onchange="uploadLebenslauf()">
										<label class="custom-file-label" id="label_lebenslauf" for="lebenslauf">Pflichtfeld</label>
									</div>
									<i id="galerie_help" class="form-text text-muted mt-2">Laden Sie hier Ihren Lebenslauf im entsprechenden Format hoch.</i>
								</div>
							</div>
							
							<div class="form-row">
								<strong class="pl-2 pb-1 w-100">Schulzeugnis</strong>
								<div class="form-group col-md-12">
									<div class="custom-file">
										<input type="file" class="custom-file-input" name="schulzeugnis" id="schulzeugnis" onchange="uploadSchulzeugnis()">
										<label class="custom-file-label" id="label_schulzeugnis" for="schulzeugnis">optional</label>
									</div>
									<i id="galerie_help" class="form-text text-muted mt-2">Laden Sie hier eine Kopie Ihres Schulabschlusses oder Ihr letztes gültiges Zeugnis hoch.</i>
								</div>
							</div>
							
							<div class="form-row">
								<strong class="pl-2 pb-1 w-100">Praktikumsbeurteilung</strong>
								<div class="form-group col-md-12">
									<div class="custom-file">
										<input type="file" class="custom-file-input" name="praktikumsbeurteilung" id="praktikumsbeurteilung" onchange="uploadPraktikumsbeurteilung()">
										<label class="custom-file-label" id="label_praktikumsbeurteilung" for="praktikumsbeurteilung">optional</label>
									</div>
									<i id="galerie_help" class="form-text text-muted mt-2">Laden Sie hier (wenn vorhanden) Ihre Praktikumsbeurteilung hoch.</i>
								</div>
							</div>
							
							<div class="form-row">
								<strong class="pl-2 pb-1 w-100">Ärztliche Bescheinigung</strong>
								<div class="form-group col-md-12">
									<div class="custom-file">
										<input type="file" class="custom-file-input" name="arztbescheinigung" id="arztbescheinigung" onchange="uploadArztbescheinigung()">
										<label class="custom-file-label" id="label_arztbescheinigung" for="arztbescheinigung">optional</label>
									</div>
									<i id="galerie_help" class="form-text text-muted mt-2">Laden Sie hier eine ärztliche Bescheinigung Ihrer Berufstauglichkeit hoch.</i>
								</div>
							</div>

							<div class="form-row mt-2 accordion" id="plusOptional">
								<div id="plus" class="col-md-12" data-toggle="collapse" data-target="#collapseOptional" aria-expanded="false" aria-controls="collapseOptional">
									<div class="plus btn btn-info rounded white">
										<i class="fas fa-plus"></i>
									</div>
									<span class="lightblue align-self-center ml-2"><i>Laden Sie hier eine weitere optionale Datei hoch.</i></span>
								</div>
							
								<div id="collapseOptional" class="collapse w-100" aria-labelledby="plus" data-parent="#plusOptional">
									<div class="form-row m-0">
										<div class="form-group col-md-12 mt-3">
											<div class="custom-file">
												<input type="file" class="custom-file-input" name="optional" id="optional" onchange="uploadOptional()">
												<label class="custom-file-label" id="label_optional" for="optional">Weitere optionale Unterlage auswählen</label>
											</div>
										</div>
									</div>
								</div>
							</div>

							<?php if($ausb_id == '1' || $ausb_id == '2' || $ausb_id == '3' || $ausb_id == '4' || $ausb_id == '8' || $ausb_id == '13' || $ausb_id == '14') : ?>
								<div class="form-row mt-5">
									<div class="form-group col-md-12">
										<strong class="pb-4 w-100">Letzte Fragen*</strong>
										<p class="mb-3">Bitte wählen Sie einen Wunscharbeitgeber aus.</p>
										<select class="custom-select custom-script" size="7" name="wunscharbeitgeber[]" id="wunscharbeitgeber" required>
											<?php if($num_berlinbuch >= '1') : ?>
												<option value="" class="lightblue font-weight-bold mt-1 mb-1"><strong>Campus Berlin-Buch</strong></option><hr>
												<?php while ($row_berlinbuch = mysqli_fetch_object($sql_berlinbuch)) : ?>
													<option value="<?php echo $row_berlinbuch->ausb_arbeitg_name; ?>" <?php if($row_berlinbuch->ausb_arbeitg_name == $wunscharbeitgeber_str) { echo 'selected'; } ?>><?php echo $row_berlinbuch->ausb_arbeitg_name?></option>
												<?php endwhile; ?>
											<?php endif; ?>
											<?php if($num_eberswalde >= '1') : ?>
												<option value="" class="lightblue font-weight-bold mt-1 mb-1"><strong>Campus Eberswalde</strong></option><hr>
												<?php while ($row_eberswalde = mysqli_fetch_object($sql_eberswalde)) : ?>
													<option value="<?php echo $row_eberswalde->ausb_arbeitg_name; ?>" <?php if($row_eberswalde->ausb_arbeitg_name == $wunscharbeitgeber_str) { echo 'selected'; } ?>><?php echo $row_eberswalde->ausb_arbeitg_name?></option>
												<?php endwhile; ?>
											<?php endif; ?><?php if($num_badsaarow >= '1') : ?>
												<option value="" class="lightblue font-weight-bold mt-1 mb-1"><strong>Campus Bad Saarow</strong></option><hr>
												<?php while ($row_badsaarow = mysqli_fetch_object($sql_badsaarow)) : ?>
													<option value="<?php echo $row_badsaarow->ausb_arbeitg_name; ?>" <?php if($row_badsaarow->ausb_arbeitg_name == $wunscharbeitgeber_str) { echo 'selected'; } ?>><?php echo $row_badsaarow->ausb_arbeitg_name?></option>
												<?php endwhile; ?>
											<?php endif; ?>
										</select>
									</div>
								</div>
							<?php endif; ?>

							<div class="form-row mt-5">
								<div class="form-group col-md-12">
									<strong class="pb-4 w-100">Ich interessiere mich für weitere Berufe.*</strong>
									<p class="mb-3">Bitte wählen Sie einen oder mehrere Ausbildungsgänge aus.</p>
									<select class="custom-select custom-script" size="7" name="weitere_interessen[]" id="weitere_interessen" multiple required>
										<?php while ($row_alle_ausbildungen = mysqli_fetch_object($alle_ausbildungen)) : ?>
											<option value="<?php echo $row_alle_ausbildungen->ausb_name; ?>" <?php if(strpos($weitere_interessen, $row_alle_ausbildungen->ausb_name) !== false) { echo 'selected'; } ?>><?php echo $row_alle_ausbildungen->ausb_name?></option>
										<?php endwhile; ?>
										<option value="keine" <?php if(strpos($weitere_interessen, 'keine') !== false) { echo 'selected'; } ?>>Ich interessiere mich für keine weiteren Berufe.</option>
									</select>
								</div>
							</div>

							<!-- Aufmerksamkeit durch -->
							<div class="form-row mt-3">
								<div class="form-group col-md-12">
									<p>Wie wurden Sie auf uns aufmerksam?</p>
									<select name="aufmerksam" id="aufmerksam" class="form-control">
										<option value="">Bitte auswählen</option>
										<option <?php if ($aufmerksam == 'Internetsuchmaschine') : echo 'selected'; endif; ?> value="Internetsuchmaschine">Internetsuchmaschine (z.B. Google)</option>
										<option <?php if ($aufmerksam == 'Internetseite der AdG') : echo 'selected'; endif; ?> value="Internetseite der AdG">Internetseite der AdG</option>
										<option <?php if ($aufmerksam == 'Social Media') : echo 'selected'; endif; ?> value="Social Media">Social Media (Facebook, Instagram, ...)</option>
										<option <?php if ($aufmerksam == 'Berufsmesse Agentur für Arbeit') : echo 'selected'; endif; ?> value="Berufsmesse Agentur für Arbeit">Berufsmesse der Agentur für Arbeit</option>
										<option <?php if ($aufmerksam == 'Printmedien') : echo 'selected'; endif; ?> value="Printmedien">Printmedien (Zeitung, Zeitschriften etc.)</option>
										<option <?php if ($aufmerksam == 'Mitarbeiter der AdG') : echo 'selected'; endif; ?> value="Mitarbeiter der AdG">Mitarbeiter der AdG</option>
										<option <?php if ($aufmerksam == 'Bildungsteilnehmer der AdG') : echo 'selected'; endif; ?> value="Bildungsteilnehmer der AdG">Bildungsteilnehmer der AdG</option>
										<option <?php if ($aufmerksam == 'Sonstige') : echo 'selected'; endif; ?> value="Sonstige">Sonstige</option>
									</select>
								</div>

								<div class="form-group col-md-12 mt-3">
									<textarea class="form-control" id="kommentar" name="kommentar" rows="3" placeholder="Hier können Sie uns noch einen Kommentar hinterlassen."><?php echo $kommentar; ?></textarea>
								</div>

								<div class="form-group col-md-12 mt-1">
									<div class="custom-control custom-checkbox my-1 mr-sm-2">
										<input type="checkbox" class="custom-control-input required" id="datenschutz" required>
										<label class="custom-control-label" for="datenschutz">Ich akzeptiere die
											<a href="https://www.gesundheit-akademie.de/datenschutz" class="darkgrey" target="_blank" style="text-decoration:underline;">
												Datenschutzbestimmungen.
											</a>
							    		</label>
									</div>
								</div>
							</div>

							<div class="form-row mt-3">
								<div class="form-group col-md-12">
									<p>Das Abschicken der Bewerbung kann ein paar Momente dauern.</p>
								</div>
							</div>

							<!-- Formularfelder aus Teil 1 übergeben -->
							<?php if($ausb_id == '9') : ?>
								<input type="hidden" name="ausbildungsart" value="<?php echo $ausbildungsart; ?>">
							<?php endif; ?>
							<input type="hidden" name="anrede" value="<?php echo $anrede; ?>">
							<input type="hidden" name="vorname" value="<?php echo $vorname; ?>">
							<input type="hidden" name="nachname" value="<?php echo $nachname; ?>">
							<input type="hidden" name="strasse" value="<?php echo $strasse; ?>">
							<input type="hidden" name="adresszusatz" value="<?php echo $adresszusatz; ?>">
							<input type="hidden" name="plz" value="<?php echo $plz; ?>">
							<input type="hidden" name="ort" value="<?php echo $ort; ?>">
							<input type="hidden" name="land" value="<?php echo $land; ?>">
							<input type="hidden" name="email" value="<?php echo $email; ?>">
							<input type="hidden" name="telefon" value="<?php echo $telefon; ?>">
							<input type="hidden" name="geburtsdatum" value="<?php echo $geburtsdatum; ?>">
							<input type="hidden" name="geburtsort" value="<?php echo $geburtsort; ?>">
							<input type="hidden" name="geburtsname" value="<?php echo $geburtsname; ?>">
							<input type="hidden" name="herkunftsland" value="<?php echo $herkunftsland; ?>">
							<input type="hidden" name="schulabschluss" value="<?php echo $schulabschluss; ?>">
							<input type="hidden" name="sonstiger_abschluss" value="<?php echo $sonstiger_abschluss; ?>">


							<!-- Zurück/Weiter -->
							<div class="form-row mt-3 mb-2">
								<div class="form-group col-md-3">
									<button type="submit" class="btn btn-info-reverse w-100" name="zurueck" id="zurueck" onclick="changeRequired()">Zurück</button>
								</div>

								<div class="form-group col-md-5 offset-md-4">
									<button type="submit" class="btn btn-info w-100" name="absenden" id="absenden">
										<i class="fas fa-location-arrow pr-2"></i></span>Bewerbung abschicken!
									</button>
								</div>
							</div>
						</form>
					<?php } elseif ($formular == 3) { ?>
							<div class="row justify-content-center mt-5">
								<div class="svg-check">
									<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
									  <circle class="path circle" fill="none" stroke="#08accf" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"/>
									  <polyline class="path check" fill="none" stroke="#08accf" stroke-width="6" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "/>
									</svg>
								</div>
								<div class="col-md-12 mt-5 mb-5 text-center d-flex justify-content-center">
									<div class="col-md-10">
										<?php echo $fehlerangabe; ?>
									</div>
								</div>
							</div>
					<?php	}
					?>
				</div> <!-- Ende col-md-8 -->

				<!-- Sidebar -->
				<div class="col-md-4" id="sidebar">
					<div class="w-100 ergebnis-image mb-3" style="background-image: url('img/<?php if (!$row_ausbildungsgaenge->ausb_img_name == '') :
							echo $row_ausbildungsgaenge->ausb_img_name;
							endif; ?>')" alt="Ausbildung zum/r <?php echo $row_ausbildungsgaenge->ausb_name; ?>')"></div>
					<div class="bg-ultralightgrey w-100 bg-lightgrey p-3">
						<?php if (!$row_ausbildungsgaenge->ausb_campus == '') : ?>	
							<div class="row mb-3 mt-3">
								<div class="col-md-2 col-sm-3 col-2 text-right pr-0">
									<span class="icon gv-icon-75 lightblue"></span>
								</div>
								<div class="col-md-10 col-sm-9 col-10 text-left">
									Campus:<br><strong><?php echo $row_ausbildungsgaenge->ausb_campus; ?></strong><br>
								</div>
							</div>
						<?php endif; ?>
						<?php if (!$row_ausbildungsgaenge->ausb_beginn == '') : ?>	
							<div class="row mb-3 mt-3">
								<div class="col-md-2 col-sm-3 col-2 text-right pr-0">
									<span class="icon gv-icon-1126 lightblue"></span>
								</div>
								<div class="col-md-10 col-sm-9 col-10 text-left">
									Beginn:<strong><br><?php echo $row_ausbildungsgaenge->ausb_beginn; ?></strong><br>
								</div>
							</div>
						<?php endif; ?>
						<?php if (!$row_ausbildungsgaenge->ausb_dauer == '') : ?>	
							<div class="row mb-3 mt-3">
								<div class="col-md-2 col-sm-3 col-2 text-right pr-0">
									<span class="icon gv-icon-1102 lightblue"></span>
								</div>
								<div class="col-md-10 col-sm-9 col-10 text-left">
									Dauer:<strong><br><?php echo $row_ausbildungsgaenge->ausb_dauer; ?></strong><br>
								</div>
							</div>
						<?php endif; ?>
						<?php if (!$row_ausbildungsgaenge->ausb_schulgeld == '') : ?>	
							<div class="row mb-3 mt-3">
								<div class="col-md-2 col-sm-3 col-2 text-right pr-0">
									<span class="icon gv-icon-961 lightblue"></span>
								</div>
								<div class="col-md-10 col-sm-9 col-10 text-left">
									Schulgeld:<strong><br><?php echo $row_ausbildungsgaenge->ausb_schulgeld; ?></strong><br>
								</div>
							</div>
						<?php endif; ?>
						<?php if (!$row_ausbildungsgaenge->ausb_verguetung == '') : ?>	
							<div class="row mb-3 mt-3">
								<div class="col-md-2 col-sm-3 col-2 text-right pr-0">
									<span class="icon gv-icon-972 lightblue"></span>
								</div>
								<div class="col-md-10 col-sm-9 col-10 text-left">
									Ausbildungsvergütung:<strong><br><?php echo $row_ausbildungsgaenge->ausb_verguetung; ?></strong>
								</div>
							</div>
						<?php endif; ?>
					</div>
				</div> <!-- Ende Sidebar -->


			</div> <!-- Ende row -->
		</div><!--  Ende col-md-12 -->
	</div> <!-- Ende row -->
</div> <!-- Ende container-fluid -->

<?php
	// Footer
	include 'footer.php';
?>
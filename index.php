<?php
	// Connection und Functions anfordern
	require_once('connection.inc.php');
	require_once('function.inc.php');

	// Header einfügen
	include 'header.php';
?>

<h2>
	<span><strong>Jetzt für deine Ausbildung bewerben</strong></span>
</h2>

<div class="container-fluid" id="content">
	<!-- TABS -->
	<div class="row">
		<div class="col-md-12" style="padding-top: 15px;">
			<form id="searchform">
				<div class="form-row">

					<!-- Ausbildungsstart -->
					<div class="form-group col-md-4">
						<div class="form-control">
							<button type="button" class="w-100 dropdown-select" data-toggle="dropdown">
								<span>Ausbildungsbeginn</span><i class="fas fa-sort-down"></i>
							</button>
							<ul class="dropdown-menu w-75">
								<li>
									<div class="custom-control custom-checkbox w-100 radio">
										<input type="radio" class="custom-control-input" name="ausb_beginn" id="april" value="" onchange="typeIn()">
										<label class="m-0 custom-control-label" for="april">&nbsp;1. April</label>
									</div>
								</li>
								<li>
									<div class="custom-control custom-checkbox w-100 radio">
										<input type="radio" class="custom-control-input" name="ausb_beginn" id="oktober" value="" onchange="typeIn()">
										<label class="m-0 custom-control-label" for="oktober">&nbsp;1. Oktober</label>
									</div>
								</li>
								<li>
									<div class="custom-control custom-checkbox w-100 radio">
										<input type="radio" class="custom-control-input" name="ausb_beginn" id="alle" value="" onchange="typeIn()">
										<label class="m-0 custom-control-label" for="alle">&nbsp;Alle Zeiten</label>
									</div>
								</li>
							</ul>
						</div>
					</div>

					<!-- Standort -->
					<div class="form-group col-md-4">
						<div class="form-control">
							<button type="button" class="w-100 dropdown-select" data-toggle="dropdown">
								<span>Standort</span><i class="fas fa-sort-down"></i>
							</button>
							<ul class="dropdown-menu w-75">
								<li>
									<div class="custom-control custom-checkbox w-100">
										<input type="checkbox" class="custom-control-input" id="berlinbuch" name="berlinbuch" value="" onchange="typeIn()">
										<label class="m-0 custom-control-label" for="berlinbuch">&nbsp;Berlin-Buch</label>
									</div>
								</li>
								<li>
									<div class="custom-control custom-checkbox w-100">
										<input type="checkbox" class="custom-control-input" id="eberswalde" name="eberswalde" value="" onchange="typeIn()">
										<label class="m-0 custom-control-label" for="eberswalde">&nbsp;Eberswalde</label>
									</div>
								</li>
								<li>
									<div class="custom-control custom-checkbox w-100">
										<input type="checkbox" class="custom-control-input" id="badsaarow" name="badsaarow" value="" onchange="typeIn()">
										<label class="m-0 custom-control-label" for="badsaarow">&nbsp;Bad Saarow</label>
									</div>
								</li>
							</ul>
						</div>
					</div>

					<!-- Filter zurücksetzen -->
					<div class="form-group col-md-4">
						<button class="btn btn-info w-100" name="on-off-switch" id="on-off-switch">
						<i class="fas fa-undo mr-1"></i></span> Filter zurücksetzen
						</button>
					</div>

				</div> <!-- Ende searchform -->
			</form>

			<div class="alert alert-secondary fade show searchtag" id="tag_april" role="alert" style="display:none;">
				<strong>1. April</strong>
			</div>
			<div class="alert alert-secondary fade show searchtag" id="tag_oktober" role="alert" style="display:none;">
				<strong>1. Oktober</strong>
			</div>
			<div class="alert alert-secondary fade show searchtag" id="tag_alle" role="alert" style="display:none;">
				<strong>Alle Zeiten</strong>
			</div>
			<div class="alert alert-secondary fade show searchtag" id="tag_berlinbuch" role="alert" style="display:none;">
				<strong>Berlin-Buch</strong>
			</div>
			<div class="alert alert-secondary fade show searchtag" id="tag_eberswalde" role="alert" style="display:none;">
				<strong>Eberswalde</strong>
			</div>
			<div class="alert alert-secondary fade show searchtag" id="tag_badsaarow" role="alert" style="display:none;">
				<strong>Bad Saarow</strong>
			</div>

			<hr class="line-bold">

			<!-- Suchergebnisse -->
			<div class="ergebnisse" id="ergebnis">

			</div> <!-- Ende Suchergebnisse -->

		</div> <!-- Ende Ausbildungs-TAB -->
		
	</div> <!-- Ende row/TABS -->
</div> <!-- Ende container-fluid -->

<?php
	// Footer
	include 'footer.php';
?>
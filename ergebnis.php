<?php $ausb_id = $row_ausbildungsgaenge->ausb_id; ?>

<div id="ergebnis_ausbildung<?php echo $ausb_id; ?>" class="accordion">
	<div id="ergebnis<?php echo $ausb_id; ?>" data-toggle="collapse" data-target="#collapse<?php echo $ausb_id; ?>" aria-expanded="false" aria-controls="collapse<?php echo $ausb_id; ?>">
		<div class="dropdown-select row">
			<div class="ergebnis-title col-md-8 col-sm-8 col-xs-12">
				<i class="fas fa-angle-down lightgrey mr-3 ml-2"></i>
				<?php echo $row_ausbildungsgaenge->ausb_name; ?>
			</div>
			<div class="ergebnis-right col-md-4 col-sm-4 col-xs-12">
				<a class="btn btn-info" href="<?php if(!$row_ausbildungsgaenge->ausb_url == '') { echo $row_ausbildungsgaenge->ausb_url; } else { ?>bewerben.php?ausb_id=<?php echo $row_ausbildungsgaenge->ausb_id; } ?>" <?php if(!$row_ausbildungsgaenge->ausb_url == '') { echo 'target="_parent"'; } ?>name="on-off-switch" id="on-off-switch">
					<i class="fas fa-location-arrow mr-1"></i></span> Jetzt bewerben
				</a>
			</div>
		</div>
	</div>

	<div id="collapse<?php echo $ausb_id; ?>" class="collapse" aria-labelledby="ergebnis<?php echo $ausb_id; ?>" data-parent="#ergebnis_ausbildung<?php echo $ausb_id; ?>">
		<div class="card-body mt-2">
			<div class="col-md-5 ergebnis-image mb-3" <?php if (!$row_ausbildungsgaenge->ausb_img_name == '') : ?>style="background-image: url('img/<?php echo $row_ausbildungsgaenge->ausb_img_name; endif; ?>')" alt="Ausbildung zum/r <?php echo $row_ausbildungsgaenge->ausb_name; ?>')"></div>
			<div class="col-md-7 mb-2">
				<?php if (!$row_ausbildungsgaenge->ausb_beschreibung == '') : ?>
					<div class="mb-3"><?php echo $row_ausbildungsgaenge->ausb_beschreibung; ?></div>
				<?php endif; ?>
				<?php if (!$row_ausbildungsgaenge->ausb_campus == '') : ?>
					<span class="icon gv-icon-75 lightblue mr-2"></span>
					<strong>Campus:</strong> <?php echo $row_ausbildungsgaenge->ausb_campus; ?><br>
				<?php endif; ?>
				<?php if (!$row_ausbildungsgaenge->ausb_beginn == '') : ?>
					<span class="icon gv-icon-1126 lightblue mr-2"></span>
					<strong>Beginn:</strong> <?php echo $row_ausbildungsgaenge->ausb_beginn; ?><br>
				<?php endif; ?>
				<?php if (!$row_ausbildungsgaenge->ausb_dauer == '') : ?>	
					<span class="icon gv-icon-1102 lightblue mr-2"></span>
					<strong>Dauer:</strong> <?php echo $row_ausbildungsgaenge->ausb_dauer; ?><br>
				<?php endif; ?>
				<?php if (!$row_ausbildungsgaenge->ausb_schulgeld == '') : ?>
					<span class="icon gv-icon-961 lightblue mr-2"></span>
					<strong>Schulgeld:</strong> <?php echo $row_ausbildungsgaenge->ausb_schulgeld; ?><br>
				<?php endif; ?>
				<?php if (!$row_ausbildungsgaenge->ausb_verguetung == '') : ?>
					<span class="icon gv-icon-972 lightblue mr-2"></span>
					<strong>Ausbildungsvergütung:</strong> <?php echo $row_ausbildungsgaenge->ausb_verguetung; ?><br>
				<?php endif; ?>
				<?php if (!$row_ausbildungsgaenge->ausb_url == '') : ?>
					<span class="icon gv-icon-38 lightblue mr-2"></span>
					<strong>Bewerbung:</strong> Die Bewerbung erfolgt bei einer kooperierenden Einrichtung der Akademie der Gesundheit. Dort wird der Ausbildungsvertrag abgeschlossen.
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>

<hr class="m-0">
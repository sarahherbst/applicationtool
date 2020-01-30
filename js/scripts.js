///////////////////////// Dropdown-Select /////////////////////////////
var options = [];

$('.dropdown-menu a').on('click', function(event) {

   var $target = $( event.currentTarget ),
       val = $target.attr( 'data-value' ),
       $inp = $target.find( 'input' ),
       idx;

   if ( ( idx = options.indexOf( val ) ) > -1 ) {
      options.splice( idx, 1 );
      setTimeout( function() { $inp.prop( 'checked', false ) }, 0);
   } else {
      options.push( val );
      setTimeout( function() { $inp.prop( 'checked', true ) }, 0);
   }

   $( event.target ).blur();
      
   console.log( options );
   return false;
});

//////////////////////// Live-Search ///////////////////////////
//Getting value from 'ajax.php'.
function fill(Value) {
   //Assigning value to searchfields in 'index.php' file.
   $('#berlinbuch').val(Value);
   $('#eberswalde').val(Value);
   $('#badsaarow').val(Value);
   $('#april').val(Value);
   $('#oktober').val(Value);
};

//On pressing a key on inputfields in 'index.php' file. This function will be called
function typeIn() {
  //Assigning search box value to javascript variable
  var berlinbuch = document.getElementById('berlinbuch');
  if (typeof(berlinbuch) != 'undefined' && berlinbuch != null) {
    var berlinbuch = document.getElementById('berlinbuch').value;
  }

  var eberswalde = document.getElementById('eberswalde');
  if (typeof(eberswalde) != 'undefined' && eberswalde != null) {
    var eberswalde = document.getElementById('eberswalde').value;
  }

  var badsaarow = document.getElementById('badsaarow');
  if (typeof(badsaarow) != 'undefined' && badsaarow != null) {
    var badsaarow = document.getElementById('badsaarow').value;
  }

  var april = document.getElementById('april');
  if (typeof(april) != 'undefined' && april != null) {
    var april = document.getElementById('april').value;
  }

  var oktober = document.getElementById('oktober');
  if (typeof(oktober) != 'undefined' && oktober != null) {
    var oktober = document.getElementById('oktober').value;
  }

  var alle = document.getElementById('alle');
  if (typeof(alle) != 'undefined' && alle != null) {
    var alle = document.getElementById('alle').value;
  }
  
  if (document.getElementById('berlinbuch').checked) {
    var berlinbuch = 'Berlin-Buch';
    document.getElementById('tag_berlinbuch').style.display = 'inline-block';
  } else {
    var berlinbuch = 'empty';
    document.getElementById('tag_berlinbuch').style.display = 'none';
  }

  if (document.getElementById('eberswalde').checked) {
    var eberswalde = 'Eberswalde';
    document.getElementById('tag_eberswalde').style.display = 'inline-block';
  } else {
    var eberswalde = 'empty';
    document.getElementById('tag_eberswalde').style.display = 'none';
  }

  if (document.getElementById('badsaarow').checked) {
    var badsaarow = 'Bad Saarow';
    document.getElementById('tag_badsaarow').style.display = 'inline-block';
  } else {
    var badsaarow = 'empty';
    document.getElementById('tag_badsaarow').style.display = 'none';
  }

  if (document.getElementById('april').checked) {
    var april = 'April';
    document.getElementById('tag_april').style.display = 'inline-block';
  } else {
    var april = 'empty';
    document.getElementById('tag_april').style.display = 'none';
  }

  if (document.getElementById('oktober').checked) {
    var oktober = 'Oktober';
    document.getElementById('tag_oktober').style.display = 'inline-block';
  } else {
    var oktober = 'empty';
    document.getElementById('tag_oktober').style.display = 'none';
  }

  if (document.getElementById('alle').checked) {
    var alle = 'Alle';
    document.getElementById('tag_alle').style.display = 'inline-block';
  } else {
    var alle = 'empty';
    document.getElementById('tag_alle').style.display = 'none';
  }

  //AJAX is called
    $.ajax({
      //AJAX type is 'Post'
      type: 'POST',
      //Data will be sent to 'ajax.php'
      url: 'ajax-livesearch.php',
      //Data, that will be sent to 'ajax.php'
      data: {
        //Assigning value of 'plz' and 'bundesland' into 'search' variable
        searchberlinbuch: berlinbuch,
        searcheberswalde: eberswalde,
        searchbadsaarow: badsaarow,
        searchapril: april,
        searchoktober: oktober,
      },
      //If result found, this funtion will be called
      success: function(html) {
        //Assigning result to 'ergebnis' div in 'index.php' file
        $('#ergebnis').html(html).show();
      }
    });
  }
if ( $('#searchform').length ) {
  window.onload = typeIn();
}

//////////////////////// Filter zurücksetzen //////////////////////////
if ($('#on-off-switch')[0]) {
  document.getElementById('on-off-switch').onclick = function filterOff() {
    $('#searchform')[0].reset();
  }
}

///////////////////// Datei-Upload: Dateiname zeigen //////////////////
function uploadAnschreiben () {
  var anschreiben = document.getElementById('anschreiben').value.split("\\").pop();
  document.getElementById("label_anschreiben").innerHTML = anschreiben;
}

function uploadLebenslauf () {
  var lebenslauf = document.getElementById('lebenslauf').value.split("\\").pop();
  document.getElementById("label_lebenslauf").innerHTML = lebenslauf;
}

function uploadOptional () {
  var optional = document.getElementById('optional').value.split("\\").pop();
  document.getElementById("label_optional").innerHTML = optional;
}

function uploadSchulzeugnis () {
  var schulzeugnis = document.getElementById('schulzeugnis').value.split("\\").pop();
  document.getElementById("label_schulzeugnis").innerHTML = schulzeugnis;
}

function uploadPraktikumsbeurteilung () {
  var praktikumsbeurteilung = document.getElementById('praktikumsbeurteilung').value.split("\\").pop();
  document.getElementById("label_praktikumsbeurteilung").innerHTML = praktikumsbeurteilung;
}

function uploadArztbescheinigung () {
  var arztbescheinigung = document.getElementById('arztbescheinigung').value.split("\\").pop();
  document.getElementById("label_arztbescheinigung").innerHTML = arztbescheinigung;
}

/////////////////////// Require für Zurück-Button deaktivieren ///////////////////
function changeRequired() {
  $('input').removeAttr('required');
  $('select').removeAttr('required');
}

function sonstigeCheck(that) {
  if (that.value == 'Sonstiger Abschluss') {
      document.getElementById('sonstige').style.display = 'block';
    } else {
      document.getElementById('sonstige').style.display = 'none';
    }
  }

////////////////////// nach oben Scrollen ///////////////////////////////////
window.onload = function scrollToTop() {
  if ('parentIFrame' in window) { 
    console.log('TEST');
    window.parentIFrame.scrollTo(0,0);
    return false;
    
  }
}

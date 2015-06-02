"use strict";
// DEV
function chargePage(page, id) {
	
	if (id == null)
		id = '#contenu';
	
	
	if (page == null) {
		var h = window.location.hash;
		if ((h != null) && (h.length > 1))
			page = h.substr(1);
	}
	
	if (page == null)
		page = "accueil.html";
	
	console.log("Chargement de la page", page);
	$(id).load(page);
	setTimeout(function() {
		window.location.hash = "#" + page;
	}, 100);
	
}


// UTILS
function valideNir(nir) {
	
}

function hide(id) {
	$('#'+id).addClass('hide');
}
function show(id) {
	$('#'+id).removeClass('hide');
}

function initialisation() {
	//chargePage();
	
	
}

function brancheAutocomplete(liste, id, idCode, codeDepartement) {
	
	var input = '#'+id;
	var codeInput = '#'+idCode;
	
	var com = isCodeCOM(codeDepartement);
	
	$(input).autocomplete({source: function(request, response) {
		var t = removeDiacritics(request.term).toUpperCase();
		var re = new RegExp($.ui.autocomplete.escapeRegex(t), 'i');
		var r = $.grep(liste, function(e, i) {
			if (com) {
				return e.value.indexOf(codeDepartement) != -1 && e.label.indexOf(t) != -1;
			} else if (codeDepartement) {
				return e.dpt == codeDepartement && e.label.indexOf(t) != -1;
			}
			return e.label.indexOf(t) != -1;//re.test(e.label);
		});
		response(r);
	},
	open: function (event, ui) {
		var termTemplate = "<strong>%s</strong>";
		var ac = $(this).data('uiAutocomplete');
		var term = removeDiacritics(ac.term).toUpperCase();
		var styledTerm = termTemplate.replace('%s', term);
		
		ac.menu.element.find('a').each(function() {
	        var me = $(this);
	        me.html( me.text().replace(term, styledTerm) );  
	    });
	},
	change: function (event, ui) {
        if(ui.item != null){
           return;
        }
        var label = removeDiacritics($(input).val()).toUpperCase();
        for (var i=0; i < liste.length; i++) {
        	var l = liste[i].label;
        	if (label == l) {
        		$(input).val(label);
        		$(codeInput).val(liste[i].value);
        		return;
        	}
        }
        $(input).val(''); $(codeInput).val('');
    },
    select: function (event, ui) {
    	if (codeDepartement == '99') return;
    	event.preventDefault();
    	$(input).val(ui.item.label);
    	if (idCode != null) {
    		$(codeInput).val(ui.item.value);
    	}
    },
    focus: function (event, ui) {
    	event.preventDefault();
    	$(input).val(ui.item.label);
    	$(codeInput).val(ui.item.value);
    }
	});
}

function createOption(label, value) {
	var o = new Option(label, value);
	$(o).html(label);
	return o;
}

function isCodeCOM(code) {
	if (!code) return false;
	if (code.length < 2 || code.length > 3)
		return false;
	for (var i=0; i<com.length; i++)
		if (com[i].value == code)
			return true;
	return false;
}

function isCodeDpt(code) {
	if (!code) return false;
	if (code.length < 2 || code.length > 3)
		return false;
	return !isCodeCOM(code);
}

function resetSelect(id, label, value) {
	$('#'+id).find('option').remove().end().append('<option></option>').val('');
}

/* 
 * initialise le formulaire géo (pays / département / commune) 
 * avec saisie assistée.
 */
function initialiseFormulaireGeo(options) {
	/*var pays = options.pays;
	var departements = options.departements;
	var communes = options.communes;
	var communes_com = options.communes_com;*/
	
	var idInputPays = options.idInputPays;
	var idInputCodePays = options.idInputCodePays;
	var idSelectDepartements = options.idSelectDepartements;
	var idSelectCommune = options.idSelectCommune;
	var idInputCommune = options.idInputCommune;
	var idInputCodeCommune = options.idInputCodeCommune;
	
	function alimenteDepartements(id) {
		var dpts_tries = departements.sort(function(a,b) {
			if (a.value < b.value) return -1;
			if (a.value > b.value) return 1;
			return 0;
		});
		var n = dpts_tries.length;
		for (i=0; i<n; i++) {
			var d = dpts_tries[i];
			$('#'+id).append(createOption(d.value + ' - ' + d.label.toUpperCase(), d.value));
		}
	}
	
	function rafraichitDepartements(codePays, id) {
		if (codePays != 'XXXXX') {		
			$('#'+id).val('99');
		} else {
			$('#'+id).val('');
		}
	}
	
	function alimenteCommunes(codePays, codeDepartement, id) {
		if (codePays == 'XXXXX') {
			resetSelect(id);
			
			if (isCodeDpt(codeDepartement)) { // département
				brancheAutocomplete(communes, idInputCommune, idInputCodeCommune, codeDepartement);
				var n = communes.length;
				for (i=0; i<n; i++) {
					var c = communes[i];
					if (c.dpt == codeDepartement) {
						$('#'+id).append(createOption(c.label.toUpperCase(), c.value));
					}
				}
			} else if (isCodeCOM(codeDepartement)) { // COM
				brancheAutocomplete(communes_com, idInputCommune, idInputCodeCommune, codeDepartement);
				var n = communes_com.length;
				for (i=0; i<n; i++) {
					var c = communes_com[i];
					if (c.value.indexOf(codeDepartement) == 0) {
						$('#'+id).append(createOption(c.label.toUpperCase(), c.value));
					}
				}
			}
		}
	}
	
	brancheAutocomplete(pays, idInputPays, idInputCodePays);
	
	alimenteDepartements(idSelectDepartements);
	
	$('#'+idInputPays).change(function(event) {
		rafraichitDepartements($('#'+idInputCodePays).val(), idSelectDepartements)
	});
	
	$('#'+idSelectDepartements).change(function(event) {
		var codePays = $('#'+idInputCodePays).val();
		var codeDepartement = $('#'+idSelectDepartements).val();
		console.log(codePays, codeDepartement);
		alimenteCommunes(codePays, codeDepartement, idSelectCommune);
	});
}

$(initialisation);







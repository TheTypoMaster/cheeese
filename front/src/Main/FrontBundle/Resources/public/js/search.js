//DATEPICKER
//http://bootstrap-datepicker.readthedocs.org/en/latest/
$.fn.datepicker.dates['fr'] = {
    days: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
    daysShort: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
    daysMin: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"],
    months: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
    monthsShort: ["Janv", "Févr", "Mar", "Avr", "Mai", "Juin", "Juil", "Août", "Sep", "Oct", "Nov", "Déc"],
    today: "Aujourd\'hui",
    clear: "Fermer"
};

$('#form_front_search_day').datepicker({
	format: "dd/mm/yyyy",
	autoclose: true,
	language: 'fr', // A recuperer avec la locale
	startDate: '+1d',
	endDate: '+1y'
});
//AUTOCOMPLETE

var input     = $("#form_front_search_town_text");
var codeInput = $("#form_front_search_town_code");
$(input).autocomplete({
	        source: 
	        	
	            function (req, add) {
	                var t = removeDiacritics(req.term);
	              $.ajax({
	            	    url:base + '/api/country/' + pays + '/town.json?contient=' + t,
	                    type:"get",
	                    dataType: 'json',
	                    async: true,
	                    cache: true,
	                    success: function (data) {
	                        var suggestions = [];  
	                        //process response  
	                        $.each(data, function(i, val){  
	                            suggestions.push({"value": val.id, "label": val.name});  
	                        });
	                        
	                        //pass array to callback  
	                        add(suggestions); 
	                    }
	                });
	        },	        	
	        open: function (event, ui) { // mise en gras du terme recherché
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
	            $(input).val(''); $(codeInput).val('');
	        },
	        select: function (event, ui) {
	            event.preventDefault();
	            $(input).val(ui.item.label);
	            $(codeInput).val(ui.item.value);
	        },
	        focus: function (event, ui) {
	            event.preventDefault();
	            $(input).val(ui.item.label);
	            $(codeInput).val(ui.item.value);
	        }
	    });

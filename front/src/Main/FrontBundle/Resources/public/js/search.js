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
	startDate: 'today',
	endDate: '+6m'
});
//AUTOCOMPLETE
// Sélection code INSEE
var department  = $("#form_front_search_department");
var town        = $("#form_front_search_town_text");
var codeInput = $("#form_front_search_town_code");

onchange(department, town);

$(department).change(function(e) {
onchange(department, town);
});

/**
* 
* @param pays
* @param div
*/
function onchange(dept, town){
$(form_front_search_department).change(function(e) {
	$(town).val('');
});
var department = $(dept).val();
var input = town;
$.ajax({
    url: base + '/api/department/'+ department + '/' + 1 + '/towns',
    type:"get",
    dataType: 'json',
    async: true,
    cache: true,
    success: function (data) {
        var suggestions = [];  
        $.each(data, function(i, val){  
            suggestions.push({"value": val.id, "label": val.name});  
        });
        $(input).autocomplete({
            source: suggestions,                          
            open: function (event, ui) {
                var termTemplate = "<strong>%s</strong>";
                var ac = $(this).data('ui-autocomplete');
                var term = ac.term;                      
                var termCaps = term.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                    return letter.toUpperCase();
                });                       
                var styledTerm = termTemplate.replace('%s', term);
                var styledTermCaps = termTemplate.replace('%s', termCaps);
                ac.menu.element.find('a').each(function() {
                    var me = $(this)
                    mapObj = {};
                    mapObj[term] = styledTerm;
                    mapObj[termCaps] = styledTermCaps;
                    var re = new RegExp(Object.keys(mapObj).join("|"),"gi");
                    str = me.text().replace(re, function(matched){
                      return mapObj[matched];
                    });
                    me.html( str) ;
                });
            },
            change: function (event, ui) {
                if(ui.item != null){
                   return;
                }
                $(input).val('');
                $(codeInput).val('');
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
    }
});

}
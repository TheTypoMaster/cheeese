//DATEPICKER
//http://bootstrap-datepicker.readthedocs.org/en/latest/

towns = JSON.parse(towns.replace(/&quot;/g,'"'));
datestopick = JSON.parse(dates.replace(/&quot;/g,'"'));
$.fn.datepicker.dates['fr-FR'] = {
    days: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
    daysShort: ["Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam"],
    daysMin: ["Di", "Lu", "Ma", "Me", "Je", "Ve", "Sa"],
    months: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"],
    monthsShort: ["Janv", "Févr", "Mar", "Avr", "Mai", "Juin", "Juil", "Août", "Sep", "Oct", "Nov", "Déc"],
    today: "Aujourd\'hui",
    clear: "Fermer"
};
var town      = $("#form_front_devis_book_town_text");
var codeInput = $("#form_front_devis_book_town_code");
var picker	  = $('#form_front_devis_book_day');

onchange(town);
$(picker).datepicker({
                format: "dd-mm-yyyy",
                autoclose: true,
                language: 'fr-FR', // A recuperer avec la locale
                startDate: 'today',
                endDate: '+6m',
                beforeShowDay: function(date){
                    var formattedDate = $.fn.datepicker.DPGlobal.formatDate(date, 'dd-mm-yyyy', 'fr-FR');
                    if ($.inArray(formattedDate.toString(), datestopick) == -1){
                        return {
                            enabled : false
                        };
                    }
                    return;
                }
            });
/**
* 
* @param pays
* @param div
*/
function onchange(town){
var input = town;
    var suggestions = []; 
    $.each(towns, function(i, val){  
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

var duration  = $("#form_front_devis_book_duration");
displayPrices(duration, prices);

$(duration).change(function(e) {
displayPrices(duration, prices);
});
function displayPrices(duration, prices){
var value = $(duration).val();
var hiddenDiv = document.getElementById("price");
    hiddenDiv.style.display = (value == "") ? "none":"block";
    if (value != "") {
        prices = JSON.parse(prices.replace(/&quot;/g,'"'));
        prices.forEach(function(obj) { 
            if (value == obj.id) {
                document.getElementById('devis_price').innerHTML = obj.price.toFixed(2) +' €';
                var percent = (obj.price * rate / 100).toFixed(2);
                document.getElementById('devis_tax').innerHTML = percent + ' €';
                var total = parseFloat(percent) + parseFloat(obj.price); 
                document.getElementById('devis_total').innerHTML = total.toFixed(2) + ' €';
            }
         });
        
    }
}

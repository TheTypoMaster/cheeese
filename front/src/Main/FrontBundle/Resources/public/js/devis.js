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
var substringMatcher = function(strs) {
              return function findMatches(q, cb) {
                var matches, substringRegex;
                matches = [];
                substrRegex = new RegExp(q, 'i');
                $.each(strs, function(i, str) {
                  if (substrRegex.test(str)) {
                    matches.push(str);
                  }
                });
                cb(matches);
              };
            };
onchange(town);
$(picker).datepicker({             
                format: "dd-mm-yyyy",
                language: 'fr-FR', // A recuperer avec la locale
                startDate: 'today',
                endDate: '+6m',
                autoclose: true,
                datesDisabled: getDisabled(),
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
    objects = [];
    map = {};
    $.each(suggestions, function(i, object) {
        map[object.label] = object;
        objects.push(object.label);
    });
    $(input).typeahead({
                  hint: true,
                  highlight: true,
                  minLength: 1
                },{
                    source: substringMatcher(objects),
                });
                $(input).on('typeahead:selected', function (e, datum) {   
                    $(codeInput).val(map[datum].value);
                  });
                $(input).on('typeahead:change', function (event, ui) {  
                    if(map[ui] != undefined){
                           return;
                        }
                    $(input).val('');
                    $(codeInput).val('');
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

function addMonths(date, months) {
  date.setMonth(date.getMonth() + months);
  return date;
}

function getDisabled() {
var start = new Date(Date.now());
var end = addMonths(new Date(), 6);
var disabled = [];
for (var d = start; d <= end; d.setDate(d.getDate() + 1)) {
    var day = new Date(d);
    var dateString = ('0' + day.getDate()).slice(-2) + '-'+ ('0' + (day.getMonth()+1)).slice(-2) + '-' +  day.getFullYear()
    if ($.inArray(dateString, datestopick) == -1){
        disabled.push(dateString);                 
    }
}
return disabled;
}

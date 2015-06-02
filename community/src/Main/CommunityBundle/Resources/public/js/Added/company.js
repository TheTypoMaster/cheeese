
    // Sélection code INSEE
    var town    = $("#form_company_town");
    var country = $("#form_company_country");
    onchange(country, town);
    
    $(town).change(function(e) {
    	onchange(country, town);
    });
    
    /**
     * 
     * @param pays
     * @param div
     */
    function onchange(pays, div){
        $(pays).change(function(e) {
        	$(div).val('');
        });
        var input = div;
        $(input).autocomplete({

        source: 
            function (req, add) {
               // var t = removeDiacritics(req.term).toUpperCase();
               	 var t = removeDiacritics(req.term);
               	 var division = $(pays).val();
                
              $.ajax({
                    url:base + '/api/country/' + division + '/town.json?contient=' + t,
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
            var termTemplate = "<strong><span class='form-control'>%s</span></strong>";
            var ac = $(this).data('ui-autocomplete');
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
            $(input).val('');
        },
        select: function (event, ui) {
            event.preventDefault();
            $(input).val(ui.item.label);
        },
        focus: function (event, ui) {
            event.preventDefault();
            $(input).val(ui.item.label);
        }
    });
        
    }

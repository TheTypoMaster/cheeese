
    // Sélection code INSEE
    var country     = $("#form_company_country");
    var department  = $("#form_company_department");
    var town        = $("#form_company_town");
    onchange(country, department, town);
    
    $(department).change(function(e) {
    	onchange(country, department, town);
    });

    /**
     * 
     * @param pays
     * @param div
     */
    function onchange(country, dept, town){
        $(form_company_department).change(function(e) {
        	$(town).val('');
        });
        var department = $(dept).val();
        var country = $(country).val();
        var input = town;
        $.ajax({
            url: base + '/api/department/'+ department + '/' + country + '/towns',
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
                $(input).autocomplete({
                    source: suggestions,                          
                    open: function (event, ui) {
                        var termTemplate = "%s";
                        var ac = $(this).data('ui-autocomplete');
                        var term = ac.term;
                      
                        var termCaps = term.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                            return letter.toUpperCase();
                        });
                       
                        var styledTerm = termTemplate.replace('%s', term);
                        var styledTermCaps = termTemplate.replace('%s', termCaps);
                        console.log(ac.menu.element);
                        ac.menu.element.find('a').each(function() {
                            var me = $(this);
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
        });
        
    }


        // Sélection code INSEE
        //var country     = $("#form_company_country");
        var department         = $("#form_company_department");
        var town               = $("#form_company_town");
        var town_id            = $("#form_company_town_id");
        onchange(/*country,*/ department, town, town_id);
        $(department).change(function(e) {
        	onchange(department, town, town_id);
        });
        var department_studio         = $("#form_company_department_studio");
        var town_studio               = $("#form_company_town_studio");
        var town_studio_id            = $("#form_company_town_studio_id");
        onchange(department_studio, town_studio, town_studio_id);
        $(department_studio).change(function(e) {
            onchange(department_studio, town_studio, town_studio_id);
        });
        
        /**
         * 
         * @param pays
         * @param div
         */
        function onchange(dept, town, id){
            $(dept).change(function(e) {
            	$(id).val('');
                $(town).val('');
                $(town).typeahead("destroy");
            });
            var department = $(dept).val();
            var country = 1;//$(country).val();
            var input = town;  
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
            $.ajax({
                url: base + '/api/department/'+ department + '/' + country + '/towns',
                type:"get",
                dataType: 'json',
                async: true,
                cache: true,
                success: function (data) {
                    var suggestions = [];  
                    $.each(data, function(i, val){  
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
                        $(id).val(map[datum].value);
                      });
                    $(input).on('typeahead:change', function (event, ui) {  
                        if(map[ui] != undefined){
                               return;
                            }
                        $(input).val('');
                        $(id).val('');
                      });
                }
            });
         
        }

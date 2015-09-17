/*
 * Author: Abdullah A Almsaeed
 * Date: 4 Jan 2014
 * Description:
 *      This is a demo file used only for the main dashboard (index.html)
 **/

$(function() {
    "use strict";
    //Make the dashboard widgets sortable Using jquery UI
    var dataTo = [
    {label: "A vérifier", value: 0},
    {label: "Vérifiée", value: 0},
    {label: "Erronée", value: 0},
    {label: "suspendue", value: 0},
    ];
    var json = JSON.parse(groupByStatus.replace(/&quot;/g,'"'));
    json.forEach(function(entry) {
    	if (entry['label'] == 'TO_VERIFY') {
    		dataTo[0]['value'] = entry['value'];
    	}
    	if(entry['label'] == 'VERIFICATION_OK') {
    		dataTo[1]['value'] = entry['value'];
    	}
    	if(entry['label'] == 'VERIFICATION_KO') {
    		dataTo[2]['value'] = entry['value'];
    	}
    	if(entry['label'] == 'SUSPENDED') {
    		dataTo[3]['value'] = entry['value'];
    	}
	});
    /*
    groupByPrest = JSON.parse(groupByPrest.replace(/&quot;/g,'"'));
    groupByPrest.forEach(function(entry) {
        switch(entry['y']) {
            case 'CREATED':
                entry['y'] = 'En cours';
                break;
            case 'PRE_CONFIRMED':
                entry['y'] = 'Pré-confirmée';
                break;
            case 'REFUSED':
                entry['y'] = 'Refusée';
                break;
            case 'CANCELED':
                entry['y'] = 'Abandonnée';
                break;
            case 'CONFIRMED':
                entry['y'] = 'Confirmée';
                break;
            case 'PASSED':
                entry['y'] = 'Traitée';
                break;
            case 'DELIVERED':
                entry['y'] = 'Delivrée';
                break;
            case 'CLOSED':
                entry['y'] = 'Clotûrée';
                break;
            case 'CANCELED_PHOTOGRAPHER':
                entry['y'] = 'Annulée photographe';
                break;
            case 'CANCELED_CLIENT':
                entry['y'] = 'Annulée client';
                break;
            case 'LITIGE_CLIENT':
                entry['y'] = 'Litige client';
                break;
            case 'LITIGE_PHOTOGRAPHER':
                entry['y'] = 'Litige photographe';
                break;
            } 
    });
*/
    $(".connectedSortable").sortable({
        placeholder: "sort-highlight",
        connectWith: ".connectedSortable",
        handle: ".box-header, .nav-tabs",
        forcePlaceholderSize: true,
        zIndex: 999999
    }).disableSelection();
    $(".connectedSortable .box-header, .connectedSortable .nav-tabs-custom").css("cursor", "move");
    //jQuery UI sortable for the todo list
    $(".todo-list").sortable({
        placeholder: "sort-highlight",
        handle: ".handle",
        forcePlaceholderSize: true,
        zIndex: 999999
    }).disableSelection();
    //Donut Chart
    var donut = new Morris.Donut({
        element: 'sales-chart',
        resize: true,
        colors: ["#FFA500",  "#00a65a", "#f56954","#DCDCDC"],
        data: dataTo,
        hideHover: 'auto'
    });
});

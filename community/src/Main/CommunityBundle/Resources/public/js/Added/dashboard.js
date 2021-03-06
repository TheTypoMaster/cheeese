/*
 * Author: Abdullah A Almsaeed
 * Date: 4 Jan 2014
 * Description:
 *      This is a demo file used only for the main dashboard (index.html)
 **/

$(function() {
    "use strict";
    //Make the dashboard widgets sortable Using jquery UI
    groupByDevis = JSON.parse(groupByDevis.replace(/&quot;/g,'"'));
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
        colors: ["#3c8dbc", "#f56954", "#00a65a", "#f0e36b", "#ffa500", "#DCDCDC"],
        data: groupByDevis,
        hideHover: 'auto'
    });
    //Bar chart
    var bar = new Morris.Bar({
        element: 'bar-chart',
        resize: true,
        data: groupByPrest,
        barColors: ['#00a65a', '#f56954'],
        xkey: 'y',
	    xLabelAngle: 30,
        ykeys: ['a'],
        hideHover: 'auto',
        yLabelFormat: function(y){return y != Math.round(y)?'':y;},
        linewidth: 0,
        labels: ['Total']
    });

});

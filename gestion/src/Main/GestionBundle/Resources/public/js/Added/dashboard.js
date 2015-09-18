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
    {label: "Shooting studio", value: 0},
    {label: "Shooting Extérieur", value: 0},
    {label: "Anniversaire", value: 0},
    {label: "Cérémonies religieuses", value: 0},
    {label: "Mariage/ Fiançailles", value: 0},
    {label: "Naissance / Grossesse", value: 0},
    {label: "EVJF/EVJH", value: 0},
    {label: "Soirée / Vernissage", value: 0},
    ];
    groupByDevis = JSON.parse(groupByDevis.replace(/&quot;/g,'"'));
    groupByDevis.forEach(function(entry) {
        if (entry['label'] == 'Shooting studio') {
            dataTo[0]['value'] = entry['value'];
        }
        if(entry['label'] == 'Shooting Extérieur') {
            dataTo[1]['value'] = entry['value'];
        }
        if(entry['label'] == 'Anniversaire') {
            dataTo[2]['value'] = entry['value'];
        }
        if(entry['label'] == 'Cérémonies religieuses') {
            dataTo[3]['value'] = entry['value'];
        }
        if(entry['label'] == 'Mariage/ Fiançailles') {
            dataTo[4]['value'] = entry['value'];
        }
        if(entry['label'] == 'Naissance / Grossesse') {
            dataTo[5]['value'] = entry['value'];
        }
        if(entry['label'] == 'EVJF/EVJH') {
            dataTo[6]['value'] = entry['value'];
        }
        if(entry['label'] == 'Soirée / Vernissage') {
            dataTo[7]['value'] = entry['value'];
        }
    });
    groupByPrest = JSON.parse(groupByPrest.replace(/&quot;/g,'"'));
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
        data: dataTo,
        hideHover: 'auto'
    });
    //Bar chart
    var bar = new Morris.Bar({
        element: 'bar-chart',
        resize: true,
        data: groupByPrest,
        barColors: ['#00a65a', '#f56954'],
        xkey: 'y',
	xLabelAngle: 60,
        ykeys: ['a'],
        hideHover: 'auto'
    });

});

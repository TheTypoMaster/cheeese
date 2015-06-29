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
    console.log(groupByPrest);
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
        ykeys: ['a'],
        hideHover: 'auto'
    });

});
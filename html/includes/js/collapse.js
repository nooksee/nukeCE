$(document).ready(function () {
    $('tr.parent')
    .css("cursor","pointer")
    .attr("title","Click to expand/collapse")
    .click(function(){
        $(this).siblings('.child-'+this.id).toggle();
    });
    $('tr[@class^=child-]').hide().children('td');
});
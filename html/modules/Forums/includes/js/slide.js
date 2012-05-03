$(document).ready(function () {
    $('.menuCATS').hide();
    $('.category').collapser({
        target: 'next',
        effect: 'slide',
        changeText: 0,
        expandHtml: 'Expand',
	collapseHtml: 'Collapse',
	expandClass: 'exp',
        collapseClass: 'coll'},
    function(){
        $('.menuCATS').slideUp();
    });
});
var myLayout; // a var is required because this page utilizes: myLayout.allowOverflow() method
$(document).ready(function () {
        myLayout = $('body').layout({
                west__size: 215,
                west__spacing_closed: 10,
                west__togglerLength_closed: 100,
                west__togglerAlign_closed: "center",
                west__togglerContent_closed: "",
                west__togglerTip_closed: "Open & Pin Menu",
                west__sliderTip: "Slide Open Menu",
                west__slideTrigger_open: "mouseover"
        });
});
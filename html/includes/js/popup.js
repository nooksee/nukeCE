var windowSizeArray = [ 
                        "width=200,height=200",                  // 0
                        "width=350,height=150,scrollbars=yes",   // 1
                        "width=300,height=400,scrollbars=yes",   // 2
                        "width=300,height=300,scrollbars=yes",   // 3
                        "width=400,height=200,scrollbars=yes",   // 4
                        "width=1024,height=768,scrollbars=yes",  // 5
                        "width=500,height=300",                  // 6
                        "width=700,height=450",                  // 7
                        "width=600,height=400,scrollbars=yes",   // 8
                        "width=280,height=200,scrollbars=yes",   // 9
                        "width=550,height=450,scrollbars=yes"    // 10
                      ];
$(document).ready(function () {
    $('.newWindow').click(function (event){
        var url = $(this).attr("href");
        var windowName = "popUp";//$(this).attr("name");
        var windowSize = windowSizeArray[  $(this).attr("rel")  ];
        window.open(url, windowName, windowSize);
        event.preventDefault();
    });
});
/* 
 * Javascript file for deposit page
 */

function checkstatus() {
    if (typeof(depositStatus) !== 'undefined'
       && depositStatus.toLowerCase() === 'demo'
    ) {
        $.ajax({
            type: "GET",
            url: "php/AjaxRequest.php",
            dataType: 'json',
            data: 'check=depositstatus',
            success: function (data) {
                if(data.status === true) {
                    location.reload();
                }
            }
        });
    }
}

// Check every minute for deposit status
setInterval(function() {
    checkstatus();
}, (1000*60*1));
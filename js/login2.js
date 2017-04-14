/**
 * Created by Kent on 2017-04-09.
 */

$(document).ready(function() {
    document.forms[0].email.select();

    $("#email").blur(function(){
        var email = $("#email").val();
        if(email != 0){
            if(isValidEmailAddress(email)){
                $("#validEmail").css({
                    "background-image": "url('validYes.png')"
                });
            } else {
                $("#validEmail").css({
                    "background-image": "url('validNo.png')"
                });
            }
        } else {
            $("#validEmail").css({
                "background-image": "none"
            });
        }
    });

    // give immediate email validation feedback
    $("#email").keyup(function(){
        var email = $("#email").val();
        if(email != 0){
            if(isValidEmailAddress(email)){
                $("#validEmail").css({
                    "background-image": "url('validYes.png')"
                });
            } else {
                $("#validEmail").css({
                    "background-image": "url('validNo.png')"
                });
            }
        } else {
            $("#validEmail").css({
                "background-image": "none"
            });
        }
    });

    function isValidEmailAddress(emailAddress) {
        //var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);


        var pattern = new RegExp(/^([a-zA-Z0-9_.-])+@(([a-zA-Z0-9-])+.)+([a-zA-Z0-9]{2,4})+$/i);





        return pattern.test(emailAddress);
    }
})


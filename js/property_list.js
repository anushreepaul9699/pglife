window.addEventListener("load" , function()
{
    var is_interested_images = document.getElementsByClassName("is-interested-image") ;
    Array.from(is_interested_images).forEach(element =>
    {
        element.addEventListener("click" , function(event)
        {
            var XHR = new XMLHttpRequest() ;
            var property_id = event.target.getAttribute("property_id") ;

            //On Success
            XHR.addEventListener("load" , toggle_interested_success) ;

            //On Error
            XHR.addEventListener("error" , on_error) ;

            //Set up Request 
            XHR.open("GET" , "api/toggle_interested.php?property_id=" + property_id) ;

            //Sending Request --->
            XHR.send() ;

            //accessing loading part --->
            document.getElementById("loading").style.display = 'block' ;

            //default action should be taken as it normally would be --->
            event.preventDefault();

        });

    });

});

var toggle_interested_success = function(event)
{
    document.getElementById("loading").style.display = 'none' ;

    var response = JSON.parse(event.target.responseText) ;

    if(response.success)
    {
        //accessing the property_id --->
        var property_id = response.property_id ;
        
        var is_interested_image = document.querySelectorAll(".property-id-" + property_id + " .is-interested-image")[0];
        var interested_user_count = document.querySelectorAll(".property-id-" + property_id + " .interested-user-count")[0];

        if(response.is_interested)
        {
            is_interested_image.classList.add("fas");
            is_interested_image.classList.remove("far");
            interested_user_count.innerHTML = parseFloat(interested_user_count.innerHTML) + 1;
        }

        else
        {
            is_interested_image.classList.add("far");
            is_interested_image.classList.remove("fas");
            interested_user_count.innerHTML = parseFloat(interested_user_count.innerHTML) - 1;
        }

    }

    else if (!response.success && !response.is_logged_in) {
        window.$("#login-modal").modal("show");
    }

};


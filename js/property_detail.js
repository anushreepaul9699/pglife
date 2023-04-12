window.addEventListener("load" , function()
{
    //fetch the property_id and its value from the url --->(get parameter from url) : search = (property_id = 2) : k-v
    const search = window.location.search ;

    //convert search into object using URLSearchParams --->
    //URLSearchParams API provide a way to get the data in the URL query parameters.(to get the value of particular parameters from the url using some method)
    const params =  new URLSearchParams(search) ;

    //to get the property id --->
    const property_id = params.get('property_id') ;

    var is_interested_images = document.getElementsByClassName("is-interested-image")[0] ; //is_interested_image = heart icon ,0 because we want to fetch the first element of the array
    is_interested_images.addEventListener("click" , function(event)
    {
        var XHR  = new XMLHttpRequest() ;

        //On success -->
        XHR.addEventListener("load" , toggle_interested_success) ;

        //On error --->
        XHR.addEventListener("error" , on_error) ;

        //Set up Request -->
        XHR.open("GET" , "api/toggle_interested.php?property_id ="+property_id) ;

        //initiate the request (send the http request to the server)
        XHR.send() ;

        document.getElementById("loading").style.display = 'block' ;

        event.preventDefault() ;

    });

});

    var toggle_interested_success = function (event)
    {
        document.getElementById("loading").style.display = 'none' ;

        var response = JSON.parse(event.target.responseText) ;

        //check if user is logged in or not ---> if not log in (and the heart is clicked already )the user will logged in and the heart will be filled 
        if(response.success)
        {
            //access the first element of the array (is_interested_image : heart icon) & interested_user_count
            var is_interested_image = document.getElementsByClassName("is-interested-image")[0] ;

            var interested_user_count = document.getElementsByClassName("interested-user-count")[0] ;

            if(response.is_interested) // if is_interested is true : user is interested
            {
                is_interested_image.classList.add("fas"); //fas = "fill the heart"
                is_interested_image.classList.remove("far"); //far = "remove the empty heart"

                interested_user_count.innerHTML = parseFloat(interested_user_count.innerHTML) + 1; // increase the interested count



            }

            //if user want to undo the interested part 
            else 
            {
                 
                 is_interested_image.classList.add ("far") ;
                 is_interested_image.classList.remove("fas") ;

                 interested_user_count.innerHTML = parseFloat(interested_user_count.innerHTML) - 1 ;

            }


        }


        //else show login modal as a response
        else if (!response.success && !response.is_logged_in)
        {
            window.$("#login-modal").modal(show) ;

        }

    };









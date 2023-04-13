
//when the window is loaded successfully --->
window.addEventListener("load" , function()
{
    //access the signup element using signup-form id --->
    var signup_form = document.getElementById("signup-form") ;

    //when the form is submitted call the function --->
    signup_form.addEventListener("submit" , function(event)
    {

        //initiate a new object of XMLHttpRequest --->
        var XHR  = new XMLHttpRequest() ;

        //fetch the form data (K-V) from the signup-form --->
        var form_data = new FormData(signup_form) ;

        // On Success (when the signup form is loaded successfully call signup success function---- >
        XHR.addEventListener ("load" , signup_success) ;

        //On Error (when the signup form is not loaded successfully )---->
        XHR.addEventListener ("error" , on_error) ;

        //Set up Request (to initiate a new HTTP request where webserver search for signup_submit.php file ) --->
        XHR.open("POST" , "api/signup_submit.php") ;

        //Sending form data with http request (form-data)--->
        XHR.send(form_data) ;

        document.getElementById("loading").style.display = 'block' ;

        //default actions that belongs to the event will not occur : will prevent in submitting the form while clicking on the submit button
        event.preventDefault() ;

    });//end of signup form part ----------||

    
    //start of login part-----------------||

    //accessing login elements using login-form id --->
    var login_form = document.getElementById("login-form");

    //on submiting the login form --->
    login_form.addEventListener("submit", function (event) 
    
    {
        //initiate a new object of XMLHttpRequest --->
        var XHR = new XMLHttpRequest();

        //accessing form data ---->

        //FormData objects are used to capture HTML form and submit it using fetch or 
        //another network method. We can either create new FormData(form) 
        //from an HTML form, or create an object without a form at all, and then append fields with methods: formData.

        var form_data = new FormData(login_form);

        //On Success (when the login form is loaded successfully ) --->
        XHR.addEventListener("load", login_success);

        //On Error (when the login form is not loaded successfully)--->
        XHR.addEventListener("error", on_error);


        //Set up Request (that search form login_submit.php file in the web server)---->
        XHR.open("POST", "api/login_submit.php");

        //Sending form data with http request --->
        XHR.send(form_data);

        //making the loading div class display property as 'block'
        document.getElementById("loading").style.display = 'block' ;

        //default action to be taken as normally it would be --->
        event.preventDefault();

    });

});// end of login part -----------------------------------------------------------------------------------||

//signup success function ----------------------------------------------------------------------------------||
var signup_success = function (event) 
{

    document.getElementById("loading").style.display = 'none' ;

    //JSON parsing is the process of converting a JSON object in text format to
    // a Javascript object that can be used inside a program.

    var response = JSON.parse(event.target.responseText);

    //if the response is successfully recieved --->
    if(response.success)
    {
        //print the message in alert box
        alert(response.message);

        //redirect it to home page --->
        window.location.href = "index.php";
    }

    //else print the errror message --->
    else
    {
       alert(response.message) ;
    }

};//end of signup success function ---------------------------------------------||


//start of login_success function --->
var login_success = function(event) 
{
    //make the display of loading div class as none ---->
    document.getElementById("loading").style.display = 'none' ;

    //JSON parsing is the process of converting a JSON object in text format to
    // a Javascript object that can be used inside a program.(parsing responseText recieved from the server)
    var response = JSON.parse(event.target.responseText) ;

    //if response is recieved successfully ---->
    if(response.success)
    {
        //reload to same page
        location.reload();
    }
		else 
    {
        //else print the error message --->
        alert(response.message);
    }

};//end of login_Success function ---------------------------------------------------------------------------------------||

//error function ---->
var on_error = function (event) 
{
    document.getElementById("loading").style.display = 'none';

    //printing error message ------------>
    alert('Oops! Something went wrong.');
};
//Als de loginpagina ingeladen is
$(document).ready(function()
{
    $("#login").validationEngine({promptPosition : "centerRight"});
    $("#register").validationEngine({promptPosition : "bottomLeft"});
    
    //Deze functies dienen voor de dynamische registerformulieren op te roepen
    $("#registreerbutton").click(function(e) 
    {
        
        e.preventDefault();
        $("#slidercontent").delay(50).animate({marginLeft:"-700px"},500,"swing");

    });
    $("#chckalgemenevoorwaarden").click(function(e) 
    {
        $("#slidercontent").delay(50).animate({marginLeft:"-1400px"},500,"swing");
        $("#chckalgemenevoorwaarden").attr("disabled",true);
        

    });
    
    var register = urlParam('register');
    
    if(register == 1)
    {
        $("#slidercontent").css({marginLeft:"-1400px"});
        $("#registreerform").slideDown(250);
        //alert("ola");
    }
});

//Functie om parameters uit de url te halen
function urlParam(name)
{
    var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
    return results[1] || 0;
}
$(document).ready(function(){
    
    //VARIABELEN
    var letters = new Array();
    
    //PAGINA ONKOSTEN TOEGEVOEGD DOOR TEAM2
    $(".hidden").hide();
    $("select[name='46']").change(function(){
        $(".hidden").show();
        $(".hidden select").each(function(){
            $(this).hide();
        });
        
	switch($(this).val())
        {
            case "Advocaat":
               $(".hidden select[name='advocaten']").show();
               break;
            case "Vrederechter":
               $(".hidden select[name='vrederechters']").show();
               break;
           case "School":
               $(".hidden select[name='scholen']").show();
               break;
        }
    });
    
    //datumpicker toevoegen aan alle inputvelden met datepicker klasse
    $("input.datepicker").datepicker({
	changeYear: true, 
	changeMonth: true, 
	altFormat: "dd-mm-yy" , 
	dateFormat: "dd-mm-yy"
    });
    
    //PAGINA 'KOSTEN OVERZICHT': FILTER LIJST MET DATUMPICKERS
    //GET INITIAL TOTAL (FROM PHP GENERATED VAR) 
    var totalcostall = $("#costTotal strong").text();
    var totalAmountKmMoney = $("#costTotalAmount strong").text();
    var totalAmountMoney = $("#costTotalMoney strong").text();

    var totalcostvar;
    var totalcostKmvar;
    var totalcostMoneyvar;
    var start;
    var end;
    
    //SHOW ALL COSTS
    $("input#costall").click(function(){
        $("#costTableBody tr.costRow").each(function(){
            $(this).show();
            $("#costTotal strong").text(totalcostall);
            $("#costTotalAmount strong").text(totalAmountKmMoney);
            $("#costTotalMoney strong").text(totalAmountMoney);
        });

    });
    
    //SHOW COSTS BASED ON DATES
    $("input#costfilter").click(function(){
        start = $("input#coststart").datepicker("getDate");
        end = $("input#costend").datepicker("getDate");
        
        if(( start !=null) && ( end !=null) && (start <= end))
        {
            totalcostvar = parseFloat(totalcostall);
            totalcostKmvar = parseFloat(totalAmountKmMoney);
            totalcostMoneyvar = parseFloat(totalAmountMoney);
            $("#costTableBody tr.costRow").each(function(){
                $(this).show();
                var val = $(this).children("td.costDate").text();
                var cost = $.datepicker.parseDate("dd-mm-yy", val);
                
                //REMOVE ROWS & VALUES IF NOT BETWEEN DATES
                if((cost < start) || (cost > end))
                    {
                        var totalMoneyCost = 0;
                        var currentKmCompensation = 0;
                        var totalKmCost = 0;
                        if($(this).children('td.costValueMoney').length){

                            var money = $(this).children("td.costValueMoney").text();
                            if((money == "") || (money == null) || (money == undefined))
                            {
                                money = 0;
                            }
                            totalMoneyCost = parseFloat(money);
                        }
                        else if($(this).children('td.costValueAmount').length){
                            var km = $(this).children("td.costValueAmount").text();
                            if((km == "") || (km == null) || (km == undefined))
                            {
                                km = currentKmCompensation = totalKmCost = 0;
                            }else{
                                currentKmCompensation = $(this).children("td.costValueAmount").next('td').text();
                                totalKmCost = parseFloat(km) * parseFloat(currentKmCompensation);
                            }
                        }
                        var totalCost = totalMoneyCost + totalKmCost;
                        $(this).hide();
                        totalcostKmvar      -= parseFloat(totalKmCost);
                        totalcostMoneyvar   -= parseFloat(totalMoneyCost);
                        totalcostvar        -= parseFloat(totalCost);
                    }
            });
            $("#costTotalAmount strong").text(totalcostKmvar.toFixed(2));
            $("#costTotalMoney strong").text(totalcostMoneyvar.toFixed(2));
            $("#costTotal strong").text(totalcostvar.toFixed(2));
        }
        else
        {
            alert("Gelieve twee datums in te geven waarbij de einddatum na de startdatum valt");
        }
    });  //werken met addClasss en removeClass op hide/show rows kan ook
    
    
    //validatie starten op alle formulieren met validate klasse
    $("form.validate").validationEngine();
    
    //confirmbox tonen 
    $(".confirm").easyconfirm({
	locale: {
	    title: 'Bent u zeker?', 
	    button: ['Annuleer','Ja']
	}
    });
    
    //placeholders in IE
    
    if($.browser.msie && $.browser.version < 10)
    {		
	$('[placeholder]').focus(function() {
	    var input = $(this);
	    if (input.val() == input.attr('placeholder')) {
		input.val('');
		input.removeClass('placeholder');
	    }
	}).blur(function() {
	    var input = $(this);
	    if (input.val() == '' || input.val() == input.attr('placeholder')) {
		input.addClass('placeholder');
		input.val(input.attr('placeholder'));
	    }
	}).blur();
    
	$('[placeholder]').parents('form').submit(function() {
	    $(this).find('[placeholder]').each(function() {
		var input = $(this);
		if (input.val() == input.attr('placeholder')) {
		    input.val('');
		}
	    })
	});
    }
    
    //PAGINASPECIFIEK
    
    //block toevoegen
    
    if ($('body').hasClass("body_block_add")){
    
	$("input[name='startdate']").change(function(){
	    $("input[name='enddate']").datepicker( "option", "minDate", $("input[name='startdate']").val() );

	});

	$("input[name='enddate']").change(function(){
	    $("input[name='startdate']").datepicker( "option", "maxDate", $("input[name='enddate']").val() );

	});
	
	//als er een keuzeoptie is moet het formulier om nieuwe informatie in te geven verborgen worden
	
	if($("input[name='level']").val() != "none")
	{
	    $("fieldset[name='new']").hide();
	    $("fieldset[name='titel']").hide();
	}
	else
	{
	    $("select[name='existingblock']").val("new");
	    $("fieldset[name='action']").hide();
	    $("fieldset[name='titel']").hide()
	}
	
	
	$("select[name='existingblock']").change(function(){
	   
	    if($(this).val() != "new")
	    {
		$("fieldset[name='new']").hide(500);
		$("fieldset[name='titel']").hide(500);
	    }
	    else
	    {
		$("fieldset[name='new']").show();
		$("fieldset[name='titel']").show();
	    }
	    
	});
    
    }
    
    
    
    //block bewerken
    
    if ($('body').hasClass("body_block_edit")){
	
	$("input[name='startdate']").change(function(){
	    $("input[name='enddate']").datepicker( "option", "minDate", $("input[name='startdate']").val() );

	});
    
	$("input[name='enddate']").change(function(){
	    $("input[name='startdate']").datepicker( "option", "maxDate", $("input[name='enddate']").val() );

	});
	
	if($("input[name='level']").val() == "none")
	{
	    $("fieldset[name='titel']").hide();
	}
    }
    
    //pupil overzicht
    
    if ($('body').hasClass("body_pupil_overview")){

	$("#pupiloverviewacties .hide").hide();
    
	$("#pupiloverviewacties .show").click(function(e){
	
	    e.preventDefault();
	    $(".pupilrow.inactive").show();
	    $("#pupiloverviewacties .show").hide();
	    $("#pupiloverviewacties .hide").show();
	});
    
	$("#pupiloverviewacties .hide").click(function(e){
	    e.preventDefault();
	    $(".pupilrow.inactive").hide();
	    $("#pupiloverviewacties .show").show();
	    $("#pupiloverviewacties .hide").hide();
	});
	
	disableWrongLetters();
	
	$("#searchbar li:not(:contains('Alle'))").click(function(event) {
			
	    // letter
	    var startletter = $(this).html().toLowerCase();
	    
	    if(letters.indexOf(startletter) != -1)
	    {			
		// alle uit lijst verwijderen
		$("#pupilscontainer .pupilrow").hide();
		
		
		// juiste tonen
		$("#pupilscontainer .pupilrow .name a").filter(function(index) {
				
		    var regex = new RegExp("^" + startletter + "","i")
		    return $(this).html().match(regex);
				
		}).closest(".pupilrow").show();
	    }
		
	});
	
	$("#searchbar li:contains('Alle')").click(function(event) {	
	    // alle uit lijst tonen
	    $("#pupilscontainer .pupilrow").show();
	});
	
	$("#searchbar input").keyup(function(event) {
            
	    // searchtekst
	    var searchtext = $(this).val();
		
	    // alle uit lijst verwijderen
	    $("#pupilscontainer .pupilrow").hide();
		
		
	    // juiste tonen
	    $("#pupilscontainer .pupilrow .name a").filter(function(index) {
				
		var regex = new RegExp(searchtext,"i")
		return $(this).html().match(regex);
				
	    }).closest(".pupilrow").show();
			
	});
    }

    if ($('body').hasClass("body_pupil_transfer")){
	
	$("select[name='type']").change(function(){
	    
	    if ($(this).val() == "standard")
	    {
		$("form #periode").hide();
		
	    }
	    else
	    {
		$("form #periode").show();
	    }

	});
	
    }
    
    //FUNCTIES
    
    function disableWrongLetters()
    {
	$("#pupilscontainer .pupilrow .name a").each(function(index, element)
	{
	    letters.push($(this).text().toLowerCase().substr(0,1));
	});
		
	$("#searchbar li:not(:contains('Alle'))").each(function(index, element) {
	    var text = $(this).text().toLowerCase();
	    if(letters.indexOf(text) == -1)
	    {
		$(this).addClass("disabled");
	    }
	});	
    }
});
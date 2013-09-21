// preload images
var imgTransition= new Array();
for(var i=0;i<8;i++) {
	imgTransition[i]= new Image();
	imgTransition[i].src = i+"anim.gif";
}

var imgWon =  new Image();
imgWon.src = "bhuannaich.gif";
	


$(document).ready(function() {


	/* ******************** init ************************************** */

	//hideButtons();
	
	var	guesses = 0;
 	var	max = 7;
 	var	guessed = "&nbsp;";
 	var word="";
 	var toGuess="";
 	var gamestarted=false;

	var winmessage = "";
	
	hideButtons();

	function startGame() {
		gamestarted=true;
 		guesses = 0;
 		guessed = "&nbsp;";
 		resetButtons();
 		displayHangmanTransition();
 		displayToGuess();
 		displayGuessed();
 		
 		if(document.getElementById("beag").checked)
		{
			hideButton(document.getElementById("-"));
			hideButton(document.getElementById("asgair"));
			guess(toGuess.charAt(Math.floor((Math.random()*(toGuess.length-1))+0)));
		}
 		
 		document.getElementById("messages").innerHTML="<span style='font-size:80%'><span style='color:green; font-weight:bold;'>Geama air tòiseachadh.</span> Feuch am faigh tu a-mach dè am facal a th' ann.</span>";
	}

	function displayHangmanTransition() {
 		document.getElementById("status").src=imgTransition[guesses].src;
	}	
	
	function resetButtons() {

		var buttons = new Array();
		
		buttons[0]=document.getElementById("a");
		buttons[1]=document.getElementById("b");
		buttons[2]=document.getElementById("c");
		buttons[3]=document.getElementById("d");
		buttons[4]=document.getElementById("e");
		buttons[5]=document.getElementById("f");
		buttons[6]=document.getElementById("g");
		buttons[7]=document.getElementById("h");
		buttons[8]=document.getElementById("i");
		buttons[9]=document.getElementById("l");
		buttons[10]=document.getElementById("m");
		buttons[11]=document.getElementById("n");
		buttons[12]=document.getElementById("o");
		buttons[13]=document.getElementById("p");
		buttons[14]=document.getElementById("r");
		buttons[15]=document.getElementById("s");
		buttons[16]=document.getElementById("t");
		buttons[17]=document.getElementById("u");
		buttons[18]=document.getElementById("à");
		buttons[19]=document.getElementById("è");
		buttons[20]=document.getElementById("ì");
		buttons[21]=document.getElementById("ò");
		buttons[22]=document.getElementById("ù");
		buttons[23]=document.getElementById("-");
		buttons[24]=document.getElementById("asgair");
		
		for(var i=0;i<=24;i++)
		{
			enableButton(buttons[i]);
			
		}
	}
	
	function enableButton(button)
	{
		button.style.borderStyle="outset";
		button.style.borderWidth="4px";
		button.style.borderColor="#003BD6";
		button.style.fontSize="100%";
		button.style.width="2em";
		button.style.height="2em";
		button.style.color="black";
		button.style.backgroundColor="#FCFF9C";
		button.style.fontWeight="normal";
		button.disabled = false;
	}

	function disableButton(button)
	{
		button.style.borderStyle="groove";
		button.style.borderColor="Silver";
		button.disabled = true;
	}


	function hideButtons()
	{
		var buttons = new Array();
		
		buttons[0]=document.getElementById("a");
		buttons[1]=document.getElementById("b");
		buttons[2]=document.getElementById("c");
		buttons[3]=document.getElementById("d");
		buttons[4]=document.getElementById("e");
		buttons[5]=document.getElementById("f");
		buttons[6]=document.getElementById("g");
		buttons[7]=document.getElementById("h");
		buttons[8]=document.getElementById("i");
		buttons[9]=document.getElementById("l");
		buttons[10]=document.getElementById("m");
		buttons[11]=document.getElementById("n");
		buttons[12]=document.getElementById("o");
		buttons[13]=document.getElementById("p");
		buttons[14]=document.getElementById("r");
		buttons[15]=document.getElementById("s");
		buttons[16]=document.getElementById("t");
		buttons[17]=document.getElementById("u");
		buttons[18]=document.getElementById("à");
		buttons[19]=document.getElementById("è");
		buttons[20]=document.getElementById("ì");
		buttons[21]=document.getElementById("ò");
		buttons[22]=document.getElementById("ù");
		buttons[23]=document.getElementById("-");
		buttons[24]=document.getElementById("asgair");
			
		for(var i=0;i<=24;i++)
		{
			hideButton(buttons[i]);
		}
	}
	
	function hideButton(button)
	{
		button.style.borderStyle="groove";
		button.style.borderWidth="4px";
		button.style.borderColor="white";
		button.style.fontSize="100%";
		button.style.width="2em";
		button.style.height="2em";
		button.style.color="lightgrey";
		button.style.backgroundColor="white";
		button.disabled = true;
	}



	function displayToGuess() {
 		var pattern="";
 		for(i=0;i<toGuess.length;++i) {
  			if(guessed.indexOf(toGuess.charAt(i)) != -1)
   				pattern += (toGuess.charAt(i)+" ");
  			else pattern += "_ ";
 		}
 		document.game.toGuess.value=pattern;
	}
	

	/* ********** guesses ****************************************** */

	function displayGuessed() {
 		document.getElementById("guessed").innerHTML=guessed;
	}

	function badGuess(s) {
 		if(toGuess.indexOf(s) == -1) return true;
 		return false;
	}

	
	function winner() {
 		for(i=0;i<toGuess.length;++i) {
  			if(guessed.indexOf(toGuess.charAt(i)) == -1) return false;
  		}
  		if (toGuess.length <2) return false;
 		return true;
 	}
 	
 	
 	/* ***************** main control flow ************************* */


	function guess(s){
 		if(gamestarted)
		{
			//alert(s);
			if(guessed.indexOf(s) == -1)
 			{
 				guessed = guessed + s + ' ';
 			}
 			var messagetemp ="</br>"+document.getElementById("messages").innerHTML;
 			
 			if(s=="'") 
 			{
 				var buttonElement =document.getElementById("asgair");
 			}
 			else
 			{
 				var buttonElement =document.getElementById(s.toLowerCase());
 			}
 			
 			if(badGuess(s))
 			{
 				++guesses;
 				
 				disableButton(buttonElement);
 				
 				buttonElement.style.backgroundColor="#F7E3E3";
 				buttonElement.style.color="grey";

 				document.getElementById("status").style.backgroundColor="red";
 				document.getElementById("messages").innerHTML="<span style='font-size:80%'><span style='color:red; font-weight:bold;'>Iochd!</span> Feuch nach bàsaich thu!</span>"+messagetemp;
 				displayHangmanTransition();
 			}
 			else
 			{
 				disableButton(buttonElement);

 				buttonElement.style.backgroundColor="#E3F7EB";
 				buttonElement.style.fontWeight="bold";

 				document.getElementById("status").style.backgroundColor="green";
 				document.getElementById("messages").innerHTML="<span style='font-size:80%'><span style='color:green; font-weight:bold;'>Glè mhath!</span> Tha an litir seo san fhacal!</span>"+messagetemp;
 			}

 			displayToGuess();
 			displayGuessed();
 		}

 		if(guesses >= max){
 			gamestarted=false;
 			document.getElementById("messages").innerHTML="<span style='color: red; font-weight:bold;'>Bhàsaich thu. 'S e '"+word+"' a bha a dhìth.</span><br />"+ winmessage;
 		}
 		if(winner()) {
 			gamestarted=false;
 			document.getElementById("status").src=imgWon.src;
 			document.getElementById("messages").innerHTML="<span style='color: red; font-weight:bold;'>Bhuannaich thu!</span><br />"+ winmessage;

 		}
	}	


	/* *************** listeners ******************************* */
   
   	// Get word from database
   	$("#restart").click(function(e){
    	
    	document.getElementById("status").style.backgroundColor="sienna";
     	document.getElementById("status").src="geamaur.gif";
     	document.getElementById("messages").innerHTML="<span style='font-size:80%'>A' tòiseachadh air geama ùr...</span>";
     
     	// send request
     	if(document.getElementById("àiteachan").checked)
     	{
     		$.post("getword.php?mode=placenames", {list: $(this).html()}, function(xml) {
     	
       			word=$(xml).find('gaidhlig').text();
       			var aaa=$(xml).find('aaa').text();
       			var imt=$(xml).find('imt').text();
	       		
 				toGuess = word.toUpperCase();
 			
 				winmessage = "<span style='font-size:80%'><br /><b>Àite:</b> "+ word;

 				if(imt==1)
 				{
 					winmessage+="<br />&nbsp;<br /><a href='http://www.smo.uhi.ac.uk/gaidhlig/aite/lorg.php?seorsa=gaidhlig&tus_saor=on&facal="+ encodeURI(word.replace(/'/g, "*"))+"*&eis_saor=on&tairg=Lorg'>"+word+" sna h-Ainmean-àite le buidheachas do dh' Iain Mac an Tailleir</a>";
 				}
 				if(aaa!=0)
 				{
 					winmessage+="<br />&nbsp;<br /><a href='http://www.gaelicplacenames.org/databasedetails.php?id="+ aaa+"&lan=ga'>"+word+" air làrach Ainmean-àite na h-Alba</a>";
 				}
 					
 				winmessage+="</span>";
				startGame();      
       		
    	   	});
       	} // àiteachan
       	else if($("#iomlan").checked)
       	{
       		$.post("getword.php?mode=words", {list: $(this).html()}, function(xml) {
     	
       			word=$(xml).find('facal').text();
       			
 				toGuess = word.toUpperCase();
       			 			
 				winmessage = "<span style='font-size:80%'><br /><b>Facal:</b> "+ word+ "<br />&nbsp;<br /><a href='http://www.faclair.com/?txtSearch="+ encodeURI(word)+"'>Lorg '"+word+"' san Fhaclair Bheag</a></span>";
 				
 				
				startGame();      
       		
    	   	});
       	} // iomlan
      	else
       	{
       		$.post("getword.php?mode=wordssmall", {list: $(this).html()}, function(xml) {
     	
       			word=$(xml).find('faclanbeag').text();
       			
 				toGuess = word.toUpperCase();
 			
 				winmessage = "<span style='font-size:80%'><br /><b>Facal:</b> "+ word+ "<br />&nbsp;<br /><a href='http://www.faclair.com/?txtSearch="+ encodeURI(word)+"'>Lorg '"+word+"' san Fhaclair Bheag</a></span>";
 				
 				
				startGame();      
       		
    	   	});
       	} // liosta beag
       	
	});       	
    
    // react on guesses   	
   	$("#a").click(function(e){ guess('A'); });
   	$("#à").click(function(e){ guess('À'); });
   	$("#e").click(function(e){ guess('E'); });
   	$("#è").click(function(e){ guess('È'); });
   	$("#i").click(function(e){ guess('I'); });
   	$("#ì").click(function(e){ guess('Ì'); });
   	$("#o").click(function(e){ guess('O'); });
   	$("#ò").click(function(e){ guess('Ò'); });
   	$("#u").click(function(e){ guess('U'); });
   	$("#ù").click(function(e){ guess('Ù'); });
   	$("#b").click(function(e){ guess('B'); });
   	$("#c").click(function(e){ guess('C'); });
   	$("#d").click(function(e){ guess('D'); });
   	$("#f").click(function(e){ guess('F'); });
   	$("#g").click(function(e){ guess('G'); });
   	$("#h").click(function(e){ guess('H'); });
   	$("#l").click(function(e){ guess('L'); });
   	$("#m").click(function(e){ guess('M'); });
   	$("#n").click(function(e){ guess('N'); });
   	$("#p").click(function(e){ guess('P'); });
   	$("#r").click(function(e){ guess('R'); });
   	$("#s").click(function(e){ guess('S'); });
   	$("#t").click(function(e){ guess('T'); });
   	$("#-").click(function(e){ guess('-'); });
   	$("#asgair").click(function(e){ guess("'"); });

});
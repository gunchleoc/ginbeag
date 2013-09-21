// preload images
var imgStates= new Array();
for(var i=0;i<8;i++) {
	imgStates[i]= new Image();
	imgStates[i].src = i+".gif";
}

var imgWon =  new Image();
imgWon.src = "bhuannaich.gif";
	


$(document).ready(function() {


	/* ******************** init ************************************** */

	var	guesses = 0;
 	var	max = 7;
 	var	guessed = " ";
 	var word="";
 	var toGuess="";
 	var gamestarted=false;

	var winmessage = "";


	function startGame() {
		gamestarted=true;
 		guesses = 0;
 		guessed = " ";
 		resetButtons();
 		displayHangman();
 		displayToGuess();
 		displayGuessed();
 		document.getElementById("messages").innerHTML="<span style='font-size:80%'>Geama air tòiseachadh. Feuch am faigh tu a-mach dè am facal a th' ann.</span>";
	}

	
	function displayHangman() {
 		document.getElementById("status").src=imgStates[guesses].src;
	}
	
	function resetButtons() {
 		document.getElementById("a").style.borderColor="lightgrey";
 		document.getElementById("b").style.borderColor="lightgrey";
 		document.getElementById("c").style.borderColor="lightgrey";
 		document.getElementById("d").style.borderColor="lightgrey";
 		document.getElementById("e").style.borderColor="lightgrey";
 		document.getElementById("f").style.borderColor="lightgrey";
 		document.getElementById("g").style.borderColor="lightgrey";
 		document.getElementById("h").style.borderColor="lightgrey";
 		document.getElementById("i").style.borderColor="lightgrey";
 		document.getElementById("l").style.borderColor="lightgrey";
 		document.getElementById("m").style.borderColor="lightgrey";
 		document.getElementById("n").style.borderColor="lightgrey";
 		document.getElementById("o").style.borderColor="lightgrey";
 		document.getElementById("p").style.borderColor="lightgrey";
 		document.getElementById("r").style.borderColor="lightgrey";
 		document.getElementById("s").style.borderColor="lightgrey";
 		document.getElementById("t").style.borderColor="lightgrey";
 		document.getElementById("u").style.borderColor="lightgrey";
 		document.getElementById("à").style.borderColor="lightgrey";
 		document.getElementById("è").style.borderColor="lightgrey";
 		document.getElementById("ì").style.borderColor="lightgrey";
 		document.getElementById("ò").style.borderColor="lightgrey";
 		document.getElementById("ù").style.borderColor="lightgrey";
 		document.getElementById("-").style.borderColor="lightgrey";
 		document.getElementById("asgair").style.borderColor="lightgrey";
 		
 		document.getElementById("a").style.color="black";
 		document.getElementById("b").style.color="black";
 		document.getElementById("c").style.color="black";
 		document.getElementById("d").style.color="black";
 		document.getElementById("e").style.color="black";
 		document.getElementById("f").style.color="black";
 		document.getElementById("g").style.color="black";
 		document.getElementById("h").style.color="black";
 		document.getElementById("i").style.color="black";
 		document.getElementById("l").style.color="black";
 		document.getElementById("m").style.color="black";
 		document.getElementById("n").style.color="black";
 		document.getElementById("o").style.color="black";
 		document.getElementById("p").style.color="black";
 		document.getElementById("r").style.color="black";
 		document.getElementById("s").style.color="black";
 		document.getElementById("t").style.color="black";
 		document.getElementById("u").style.color="black";
 		document.getElementById("à").style.color="black";
 		document.getElementById("è").style.color="black";
 		document.getElementById("ì").style.color="black";
 		document.getElementById("ò").style.color="black";
 		document.getElementById("ù").style.color="black";
 		document.getElementById("asgair").style.color="black";
 		
 		document.getElementById("a").style.fontWeight="normal";
 		document.getElementById("b").style.fontWeight="normal";
 		document.getElementById("c").style.fontWeight="normal";
 		document.getElementById("d").style.fontWeight="normal";
 		document.getElementById("e").style.fontWeight="normal";
 		document.getElementById("f").style.fontWeight="normal";
 		document.getElementById("g").style.fontWeight="normal";
 		document.getElementById("h").style.fontWeight="normal";
 		document.getElementById("i").style.fontWeight="normal";
 		document.getElementById("l").style.fontWeight="normal";
 		document.getElementById("m").style.fontWeight="normal";
 		document.getElementById("n").style.fontWeight="normal";
 		document.getElementById("o").style.fontWeight="normal";
 		document.getElementById("p").style.fontWeight="normal";
 		document.getElementById("r").style.fontWeight="normal";
 		document.getElementById("s").style.fontWeight="normal";
 		document.getElementById("t").style.fontWeight="normal";
 		document.getElementById("u").style.fontWeight="normal";
 		document.getElementById("à").style.fontWeight="normal";
 		document.getElementById("è").style.fontWeight="normal";
 		document.getElementById("ì").style.fontWeight="normal";
 		document.getElementById("ò").style.fontWeight="normal";
 		document.getElementById("ù").style.fontWeight="normal";
 		document.getElementById("asgair").style.fontWeight="normal";
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
 				guessed = guessed + " " + s;
 			}

 			if(badGuess(s))
 			{
 				++guesses;
 			
 				if(s=="'") 
 				{
 					document.getElementById("asgair").style.borderColor="red";
 					document.getElementById("asgair").style.color="grey";
 				}
 				else
 				{
 					document.getElementById(s.toLowerCase()).style.borderColor="red";
 					document.getElementById(s.toLowerCase()).style.color="grey";
 				}
 				document.getElementById("status").style.backgroundColor="red";
 				document.getElementById("messages").innerHTML="<span style='font-size:80%'><span style='color:red; font-weight:bold;'>Iochd!</span> Feuch nach bàsaich thu!</span>";
 		
 			}
 			else
 			{
				if(s=="'") 
 				{
 					document.getElementById("asgair").style.borderColor="green";
 					document.getElementById("asgair").style.fontWeight="bold";
 				}
 				else
 				{
 					document.getElementById(s.toLowerCase()).style.borderColor="green";
 					document.getElementById(s.toLowerCase()).style.fontWeight="bold";
 				}
 				document.getElementById("status").style.backgroundColor="green";
 				document.getElementById("messages").innerHTML="<span style='font-size:80%'><span style='color:green; font-weight:bold;'>Glè mhath!</span> Tha an litir seo san fhacal!</span>";
 			}

 			displayHangman();
 			displayToGuess();
 			displayGuessed();
 		}

 		if(guesses >= max){
 			gamestarted=false;
 			document.getElementById("messages").innerHTML="<span style='color: red; font-weight:bold;'>Bhàsaich thu. 'S e '"+word+"' a bha a dhìth.</span>"+ winmessage;
 		}
 		if(winner()) {
 			gamestarted=false;
 			document.getElementById("status").src=imgWon.src;
 			document.getElementById("messages").innerHTML="<span style='color: red; font-weight:bold;'>Bhuannaich thu!</span><br />&nbsp;"+ winmessage;

 		}
	}	


	/* *************** listeners ******************************* */
   
   	// Get word form database
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
       	}
       	else
       	{
       		$.post("getword.php?mode=words", {list: $(this).html()}, function(xml) {
     	
       			word=$(xml).find('facal').text();
       			
 				toGuess = word.toUpperCase();
 			
 				winmessage = "<span style='font-size:80%'><br /><b>Facal:</b> "+ word+ "<br />&nbsp;<br /><a href='http://www.faclair.com/?txtSearch="+ encodeURI(word)+"'>Lorg '"+word+"' san Fhaclair Bheag</a></span>";
 				
 				
				startGame();      
       		
    	   	});
       	}
       	
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
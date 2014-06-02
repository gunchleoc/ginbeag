$(document).ready(function() {

	// http://www.brain4.de/programmierecke/js/arrayShuffle.php
	/* helper function for randomising array
	*/
	function arrayShuffle(){
 		var tmp, rand;
 		for(var i =0; i < this.length; i++){
			rand = Math.floor(Math.random() * this.length);
			tmp = this[i];
			this[i] = this[rand];
			this[rand] =tmp;
		}
	}
	Array.prototype.shuffle =arrayShuffle;

	// init game vars
//alert(square.join (" | "));

	var images = new Array();
	var emptyimage ="";
	var gamerunning = false;

	var explanation="<p>Cuir na leacan san òrdugh mar bu chòir.</p><p>Brùth air leac ri taobh an fhir fhalaimh gus an leac a ghluasad.</p>"
	var loading= "<p>A' luchdadh geama ùr ...</p>";
	var won= "<p class='highlight' style='font-size:110%;'>Rinn thu a' chùis air!</p><p>Bruth air dealbh gu h-ìosal gus geama ùr a thaghadh.</p>";

	// init tilesets
	var tilesets = new Array();
	tilesets[0] = new Array("Loch Lìobhainn","liobhann", ".jpg");
	tilesets[1] = new Array("Ròs","ros", ".jpg");
	tilesets[2] = new Array("Abaid Phàislig","paislig", ".jpg");
	tilesets[3] = new Array("Gleann Comhainn","comhann", ".jpg");
	tilesets[4] = new Array("An Cuiltheann","cuiltheann", ".jpg");
	tilesets[5] = new Array("Agairg nan cuileagan","agairg", ".jpg");
	tilesets[6] = new Array("Caora mì-mhodhail","caora", ".jpg");
	tilesets[7] = new Array("Fiadh","fiadh", ".jpg");
	tilesets[8] = new Array("Flùr","flur", ".jpg");


	var gameslinks = "";

	for(var i = 0; i<tilesets.length;i++) {
		gameslinks += '<img id="game'+i+'" src="'+tilesets[i][1]+'/thumb.jpg" title="'+tilesets[i][0]+'"  alt="'+tilesets[i][0]+'" class="thumb" />';
	}

	$("#games").html(gameslinks);

	for(var i = 0; i<tilesets.length;i++) {

		$("#game"+i).click(function() {
			var index=eval($(this).attr("id").substring(4));
    		startGame(index);
		});
	}



	/* init arrays with images, imgextension as variable? todo
	*/
	function startGame(index) {


		$("#messages").html(loading);
		$("#gametitle").html(tilesets[index][0]);

		var imgpath = tilesets[index][1]+"/";
		var imgextension =".jpg";

		$("#master").attr("src",imgpath+"master"+imgextension);

		emptyimage = imgpath+"empty"+imgextension;

		// use this array for check if won
		images = new Array(imgpath+"0_0"+imgextension, imgpath+"0_1"+imgextension, imgpath+"0_2"+imgextension, imgpath+"0_3"+imgextension,
			imgpath+"1_0"+imgextension, imgpath+"1_1"+imgextension, imgpath+"1_2"+imgextension, imgpath+"1_3"+imgextension,
			imgpath+"2_0"+imgextension, imgpath+"2_1"+imgextension, imgpath+"2_2"+imgextension, imgpath+"2_3"+imgextension,
			imgpath+"3_0"+imgextension, imgpath+"3_1"+imgextension, imgpath+"3_2"+imgextension, imgpath+"3_3"+imgextension);

		var square=new Array();
		square[0]=new Array();
		square[1]=new Array();
		square[2]=new Array();
		square[3]=new Array();
		square[0][0]=images[0];
		square[0][1]=images[1];
		square[0][2]=images[2];
		square[0][3]=images[3];
		square[1][0]=images[4];
		square[1][1]=images[5];
		square[1][2]=images[6];
		square[1][3]=images[7];
		square[2][0]=images[8];
		square[2][1]=images[9];
		square[2][2]=images[10];
		square[2][3]=images[11];
		square[3][0]=images[12];
		square[3][1]=images[13];
		square[3][2]=images[14];
		square[3][3]=images[15];

		// create random empty tile

		var emptyposx = Math.floor(Math.random()*4);
		var emptyposy = Math.floor(Math.random()*4);

		square[emptyposx][emptyposy] = emptyimage;


		// randomise array

		for(var i=0;i<200;i++) {
			var direction = Math.floor(Math.random()*4);
			var temp = "";
			// move up
			if(direction==0 && emptyposx > 0) {
				temp=square[emptyposx-1][emptyposy];
				square[emptyposx-1][emptyposy]=emptyimage;
				square[emptyposx][emptyposy]=temp;
				emptyposx--;
			}
			// move left
			else if(direction==1 && emptyposy > 0) {
				temp=square[emptyposx][emptyposy-1];
				square[emptyposx][emptyposy-1]=emptyimage;
				square[emptyposx][emptyposy]=temp;
				emptyposy--;
			}
			// move down
			else if(direction==2 && emptyposx <3) {
				temp=square[emptyposx+1][emptyposy];
				square[emptyposx+1][emptyposy]=emptyimage;
				square[emptyposx][emptyposy]=temp;
				emptyposx++;
			}
			// move right
			else if (direction==3 && emptyposy <3){
				temp=square[emptyposx][emptyposy+1];
				square[emptyposx][emptyposy+1]=emptyimage;
				square[emptyposx][emptyposy]=temp;
				emptyposy++;
			}
			// try again
			else {
				i--;
			}
		}


		$("#0_0").attr("src",square[0][0]);
		$("#0_1").attr("src",square[0][1]);
		$("#0_2").attr("src",square[0][2]);
		$("#0_3").attr("src",square[0][3]);
		$("#1_0").attr("src",square[1][0]);
		$("#1_1").attr("src",square[1][1]);
		$("#1_2").attr("src",square[1][2]);
		$("#1_3").attr("src",square[1][3]);
		$("#2_0").attr("src",square[2][0]);
		$("#2_1").attr("src",square[2][1]);
		$("#2_2").attr("src",square[2][2]);
		$("#2_3").attr("src",square[2][3]);
		$("#3_0").attr("src",square[3][0]);
		$("#3_1").attr("src",square[3][1]);
		$("#3_2").attr("src",square[3][2]);
		$("#3_3").attr("src",square[3][3]);

		gamerunning = true;
		$("#messages").html(explanation);
	}


	/* moves empty from a to b and image from b to a
	* then check if game has been won
	*/
	function swap (a, b) {
		if(gamerunning) {
			// swap tiles
			$(a).attr("src",$(b).attr("src"));
    		$(b).attr("src",emptyimage);
      		$(a).fadeOut('fast', function() {
        		// Animation complete
      		$(a).fadeIn('slow', function() {
        		// Animation complete
      		});
      		});


    		// check if won
	   		if($("#0_0").attr("src") == images[0] || $("#0_0").attr("src") == emptyimage) {
   			if($("#0_1").attr("src") == images[1] || $("#0_1").attr("src") == emptyimage) {
   			if($("#0_2").attr("src") == images[2] || $("#0_2").attr("src") == emptyimage) {
   			if($("#0_3").attr("src") == images[3] || $("#0_3").attr("src") == emptyimage) {
   			if($("#1_0").attr("src") == images[4] || $("#1_0").attr("src") == emptyimage) {
   			if($("#1_1").attr("src") == images[5] || $("#1_1").attr("src") == emptyimage) {
   			if($("#1_2").attr("src") == images[6] || $("#1_2").attr("src") == emptyimage) {
   			if($("#1_3").attr("src") == images[7] || $("#1_3").attr("src") == emptyimage) {
   			if($("#2_0").attr("src") == images[8] || $("#2_0").attr("src") == emptyimage) {
   			if($("#2_1").attr("src") == images[9] || $("#2_1").attr("src") == emptyimage) {
   			if($("#2_2").attr("src") == images[10] || $("#2_2").attr("src") == emptyimage) {
   			if($("#2_3").attr("src") == images[11] || $("#2_3").attr("src") == emptyimage) {
   			if($("#3_0").attr("src") == images[12] || $("#3_0").attr("src") == emptyimage) {
   			if($("#3_1").attr("src") == images[13] || $("#3_1").attr("src") == emptyimage) {
   			if($("#3_2").attr("src") == images[14] || $("#3_2").attr("src") == emptyimage) {
   			if($("#3_3").attr("src") == images[15] || $("#3_3").attr("src") == emptyimage) {
    			gamerunning = false;

    			$("#0_0").attr("src",images[0]);
				$("#0_1").attr("src",images[1]);
				$("#0_2").attr("src",images[2]);
				$("#0_3").attr("src",images[3]);
				$("#1_0").attr("src",images[4]);
				$("#1_1").attr("src",images[5]);
				$("#1_2").attr("src",images[6]);
				$("#1_3").attr("src",images[7]);
				$("#2_0").attr("src",images[8]);
				$("#2_1").attr("src",images[9]);
				$("#2_2").attr("src",images[10]);
				$("#2_3").attr("src",images[11]);
				$("#3_0").attr("src",images[12]);
				$("#3_1").attr("src",images[13]);
				$("#3_2").attr("src",images[14]);
				$("#3_3").attr("src",images[15]);
				$("#messages").html(won);
    		}}}}}}}}}}}}}}}}
    	}
	}


	/* shift tiles
	*/

	// row 0
	$("#0_0").click(function() {
       	if($("#0_1").attr("src") == emptyimage) {
    		swap("#0_1","#0_0");
    	}
    	else if($("#1_0").attr("src") == emptyimage) {
    		swap("#1_0","#0_0");
    	}
	});

	$("#0_1").click(function() {
    	if($("#0_0").attr("src") == emptyimage) {
    		swap("#0_0","#0_1");
    	}
    	else if($("#0_2").attr("src") == emptyimage) {
    		swap("#0_2","#0_1");
    	}
    	else if($("#1_1").attr("src") == emptyimage) {
    		swap("#1_1","#0_1");
    	}
	});

	$("#0_2").click(function() {
    	if($("#0_1").attr("src") == emptyimage) {
    		swap("#0_1","#0_2");
    	}
    	else if($("#0_3").attr("src") == emptyimage) {
    		swap("#0_3","#0_2");
    	}
    	else if($("#1_2").attr("src") == emptyimage) {
    		swap("#1_2","#0_2");
    	}
	});

	$("#0_3").click(function() {
       	if($("#0_2").attr("src") == emptyimage) {
    		swap("#0_2","#0_3");
    	}
    	else if($("#1_3").attr("src") == emptyimage) {
    		swap("#1_3","#0_3");
    	}
	});

	// row 1
	$("#1_0").click(function() {
       	if($("#1_1").attr("src") == emptyimage) {
    		swap("#1_1","#1_0");
    	}
    	else if($("#2_0").attr("src") == emptyimage) {
    		swap("#2_0","#1_0");
    	}
    	else if($("#0_0").attr("src") == emptyimage) {
    		swap("#0_0","#1_0");
    	}
	});

	$("#1_1").click(function() {
    	if($("#1_0").attr("src") == emptyimage) {
    		swap("#1_0","#1_1");
    	}
    	else if($("#1_2").attr("src") == emptyimage) {
    		swap("#1_2","#1_1");
    	}
    	else if($("#2_1").attr("src") == emptyimage) {
    		swap("#2_1","#1_1");
    	}
    	else if($("#0_1").attr("src") == emptyimage) {
    		swap("#0_1","#1_1");
    	}
	});

	$("#1_2").click(function() {
    	if($("#1_1").attr("src") == emptyimage) {
    		swap("#1_1","#1_2");
    	}
    	else if($("#1_3").attr("src") == emptyimage) {
    		swap("#1_3","#1_2");
    	}
    	else if($("#2_2").attr("src") == emptyimage) {
    		swap("#2_2","#1_2");
    	}
    	else if($("#0_2").attr("src") == emptyimage) {
    		swap("#0_2","#1_2");
    	}
	});

	$("#1_3").click(function() {
       	if($("#1_2").attr("src") == emptyimage) {
    		swap("#1_2","#1_3");
    	}
    	else if($("#2_3").attr("src") == emptyimage) {
    		swap("#2_3","#1_3");
    	}
    	else if($("#0_3").attr("src") == emptyimage) {
    		swap("#0_3","#1_3");
    	}
	});

	// row 2
	$("#2_0").click(function() {
       	if($("#2_1").attr("src") == emptyimage) {
    		swap("#2_1","#2_0");
    	}
    	else if($("#3_0").attr("src") == emptyimage) {
    		swap("#3_0","#2_0");
    	}
    	else if($("#1_0").attr("src") == emptyimage) {
    		swap("#1_0","#2_0");
    	}
	});

	$("#2_1").click(function() {
    	if($("#2_0").attr("src") == emptyimage) {
    		swap("#2_0","#2_1");
    	}
    	else if($("#2_2").attr("src") == emptyimage) {
    		swap("#2_2","#2_1");
    	}
    	else if($("#3_1").attr("src") == emptyimage) {
    		swap("#3_1","#2_1");
    	}
    	else if($("#1_1").attr("src") == emptyimage) {
    		swap("#1_1","#2_1");
    	}
	});

	$("#2_2").click(function() {
    	if($("#2_1").attr("src") == emptyimage) {
    		swap("#2_1","#2_2");
    	}
    	else if($("#2_3").attr("src") == emptyimage) {
    		swap("#2_3","#2_2");
    	}
    	else if($("#3_2").attr("src") == emptyimage) {
    		swap("#3_2","#2_2");
    	}
    	else if($("#1_2").attr("src") == emptyimage) {
    		swap("#1_2","#2_2");
    	}
	});

	$("#2_3").click(function() {
       	if($("#2_2").attr("src") == emptyimage) {
    		swap("#2_2","#2_3");
    	}
    	else if($("#3_3").attr("src") == emptyimage) {
    		swap("#3_3","#2_3");
    	}
    	else if($("#1_3").attr("src") == emptyimage) {
    		swap("#1_3","#2_3");
    	}
	});

	// row 3
	$("#3_0").click(function() {
       	if($("#3_1").attr("src") == emptyimage) {
    		swap("#3_1","#3_0");
    	}
    	else if($("#2_0").attr("src") == emptyimage) {
    		swap("#2_0","#3_0");
    	}
	});

	$("#3_1").click(function() {
    	if($("#3_0").attr("src") == emptyimage) {
    		swap("#3_0","#3_1");
    	}
    	else if($("#3_2").attr("src") == emptyimage) {
    		swap("#3_2","#3_1");
    	}
    	else if($("#2_1").attr("src") == emptyimage) {
    		swap("#2_1","#3_1");
    	}
	});

	$("#3_2").click(function() {
    	if($("#3_1").attr("src") == emptyimage) {
    		swap("#3_1","#3_2");
    	}
    	else if($("#3_3").attr("src") == emptyimage) {
    		swap("#3_3","#3_2");
    	}
    	else if($("#2_2").attr("src") == emptyimage) {
    		swap("#2_2","#3_2");
    	}
	});

	$("#3_3").click(function() {
       	if($("#3_2").attr("src") == emptyimage) {
    		swap("#3_2","#3_3");
    	}
    	else if($("#2_3").attr("src") == emptyimage) {
    		swap("#2_3","#3_3");
    	}
	});


	// game start
	startGame(Math.floor(Math.random()*(tilesets.length)));


});

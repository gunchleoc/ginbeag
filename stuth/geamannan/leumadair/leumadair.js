// preload images
var imgSquareUp= new Array();
var imgSquareDown= new Array();
for(var i=0;i<5;i++) {
	imgSquareUp[i]=new Array();
	imgSquareDown[i]=new Array();
	for(var j=0;j<5;j++) {
		imgSquareUp[i][j]= new Image();
   		imgSquareUp[i][j].src = "up"+i+"_"+j+".png";
   		imgSquareDown[i][j]= new Image();
   		imgSquareDown[i][j].src = "down"+i+"_"+j+".png";
	}	
}
	

$(document).ready(function() {

	// init game vars
	var gamerunning = false;
	
	var explanation="<p class='gen'><p class='gen'>Thoir air a h-uile leumadair leum!</p><p class='gen'>Bruth air leumadair. Sguiridh esan is an fheadhainn ri gach taobh dhe na tha iad a' dèanamh.</p>"
	var loading= "<p class='gen'>A' luchdadh geama ùr ...</p>";
	var won= "<p class='highlight'>Rinn thu a' chùis air!</p><p class='gen'>Bruth air dealbh gu h-ìosal gus geama ùr a thòiseachadh.</p>";
	
	$("#messages").html(loading);
	
	// size of image when won
	var winStyle="width:300px; height:300px;";
	var imgWon = new Image();


	var gamesize=4;
	
	var square=new Array();
	
	for(var i=0;i<gamesize;i++) {
		square[i]=new Array();
	}
	

	var gameslinks = "";
	
	var levels=5;

	for(var i = 1; i<=levels;i++) {
		gameslinks += '<img id="game'+i+'" src="level'+i+'.gif" title="Ìre '+i+'"  alt=" Ìre '+i+'" class="thumb" />';
	}
	
	$("#games").html(gameslinks);
	
	for(var i = 1; i<=levels;i++) {
	
		$("#game"+i).click(function() {
			var index=eval($(this).attr("id").substring(4));
    		startGame(index);
		});
	}
	

	
	/* init new gane
	*/
	function startGame(level) {
		
		// set levels
		if(level==3 || level==4) {
			gamesize=5;
			winStyle="width:375px; height:375px;";
		}
		else if(level ==5) {
			gamesize=3;
			winStyle="width:225px; height:225px;";
		}
		else {
			gamesize=4;
			winStyle="width:300px; height:300px;";
		}
		
		var iterations=level;
		if(level==1) iterations=3;
		else if(level==2) iterations=30;
		else if(level==3) iterations=6;
		else if(level==4) iterations=50;
		else if(level==5) iterations=20;
		
		imgWon.src = "won"+level+".png"

		// set messages
		$("#messages").html(loading);
		$("#gametitle").html("Ìre: "+level);

		// init game size
		square=new Array();

		for(var i=0;i<gamesize;i++) {

			square[i]=new Array();

			for(var j=0;j<gamesize;j++) {
				square[i][j]=true;
			}
		}
		
				
		// randomise array
		var prevx=-1;
		var prevy=-1;
		var prevx2=-1;
		var prevy2=-1;
		var prevx3=-1;
		var prevy3=-1;
		
		for(var i=0;i<iterations;i++) {
			var posx = Math.floor(Math.random()*gamesize);
			var posy = Math.floor(Math.random()*gamesize);
			
			//prevent selecting same tile twice in a row
			while((prevx == posx && prevy == posy) || (prevx2 == posx && prevy2 == posy) || (prevx3 == posx && prevy3 == posy)) {
				posx = Math.floor(Math.random()*gamesize);
				posy = Math.floor(Math.random()*gamesize);
			}
			prevx3 = prevx2;
			prevy3 = prevy2;
			prevx2 = prevx;
			prevy2 = prevy;
			prevx = posx;
			prevy = posy;
		
			swap (posx, posy);
		}
		
		// start gane
		initimages();
		setimages();
		addListeners();
		
		gamerunning = true;
		$("#messages").html(explanation);
	}
	
	/* set images from square state
	*/
	function initimages() {
		var gamesquare="";
			for(var i=0;i<gamesize;i++) {
				gamesquare+="<tr>";
				for(var j=0;j<gamesize;j++) {
					gamesquare+='<td class="frame"><img id="'+i+'_'+j+'" class="frame" src="" /></td>';
				}
				gamesquare+="</tr>";
			}
		$("#square").html(gamesquare);
	}	
	
	/* set images from square state
	*/
	function setimages() {
		for(var i=0;i<gamesize;i++) {
			for(var j=0;j<gamesize;j++) {
		
				if(square[i][j]) {
					$("#"+i+"_"+j).attr("src",imgSquareUp[i][j].src);
					$("#"+i+"_"+j).attr("title","a' leum");
					$("#"+i+"_"+j).attr("alt","a' leum");
				}
				else {
					$("#"+i+"_"+j).attr("src",imgSquareDown[i][j].src);
					$("#"+i+"_"+j).attr("title","a' snàmh");
					$("#"+i+"_"+j).attr("alt","a' snàmh");
				}
			}
		}
	}
	
	
	/* moves empty from a to b and image from b to a
	*/
	function swap (posx, posy) {

		square[posx][posy] = !square[posx][posy];
		
		// move up
		if(posx > 0) {
			square[posx-1][posy]=!square[posx-1][posy];
		}
		// move left
		if(posy > 0) {
			square[posx][posy-1]=!square[posx][posy-1];
		}
		// move down
		if(posx <(gamesize-1)) {
			square[posx+1][posy]=!square[posx+1][posy];
		}
		// move right
		if (posy <(gamesize-1)){
			square[posx][posy+1]=!square[posx][posy+1];;
		}

	}
	
	function addListeners()
	{
	
	
	/* shift tiles
	*/
	
		for(var i=0;i<gamesize;i++) {
			for(var j=0;j<gamesize;j++) {
		
				$("#"+i+"_"+j).click(function() {
					if(gamerunning) {
						//swap tile states

						var posx=eval($(this).attr("id").substring(0,1));
						var posy=eval($(this).attr("id").substring(2));
						swap(posx,posy);
						setimages();

   						// check if won
   						var gamewon=true;
   						for(var i=0;i<gamesize && gamewon;i++) {
							for(var j=0;j<gamesize && gamewon;j++) {
								if(square[i][j]==false) {
									gamewon=false;
								}
							}
						}
						if(gamewon) {
							gamerunning = false;    			
							$("#messages").html(won);
						
							var gamesquare="";
							gamesquare+="<tr>";
							gamesquare+='<td class="frame"><img id="imgwon" src="" style="'+winStyle+'" /></td>';
							gamesquare+="</tr>";
							$("#square").html(gamesquare);
							$("#imgwon").attr("src",imgWon.src);
							
						}
					}
				});

			}
		}
		
	}
	

	// game start
	startGame(1);
});

//  ******************** actions start here

$(document).ready(function() {

	var words = new Array();
	var selectedcells = new Array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
	var row1=0;
	var row2=0;
	var row3=0;
	var row4=0;
	var row5=0;
	var col1 = 0;
	var col2 = 0;
	var col3 = 0;
	var col4 = 0;
	var col5 = 0;
	var diag1 = 0;
	var diag2 = 0;

	getwords();
	
	
	// reset and start a new game
	$("#startgame").click(function(e){
		words = new Array();
		selectedcells = new Array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
		row1=0;
		row2=0;
		row3=0;
		row4=0;
		row5=0;
		col1 = 0;
		col2 = 0;
		col3 = 0;
		col4 = 0;
		col5 = 0;
		diag1 = 0;
		diag2 = 0;
		
		
		getwords();
		for (var i = 0; i<25; i++)
		{
			$("#word"+i+"cell").addClass("word");
			$("#word"+i+"cell").removeClass("solved");
			$("#word"+i+"cell").removeClass("bingo");
			$("#word"+i).removeClass("bingo");
		}
	});
	
	
	// print view
	$("#printgame").click(function(e){
		var text = '<div align="center"><h1>Bullshit Bingo</h1>';
		text += '<table class="grid">';
		text += '					<tr>';
		text += '						<td class="print">';
		text += '							<div>'+words[0]+'</div>';
		text += '						</td>';
		text += '						<td class="print">';
		text += '							<div>'+words[1]+'</div>';
		text += '						</td>';
		text += '						<td class="print">';
		text += '							<div>'+words[2]+'</div>';
		text += '						</td>';
		text += '						<td class="print">';
		text += '							<div>'+words[3]+'</div>';
		text += '						</td>';
		text += '						<td class="print">';
		text += '							<div>'+words[4]+'</div>';
		text += '						</td>';
		text += '					</tr>';
		text += '					<tr>';
		text += '						<td class="print">';
		text += '							<div>'+words[5]+'</div>';
		text += '						</td>';
		text += '						<td class="print">';
		text += '							<div>'+words[6]+'</div>';
		text += '						</td>';
		text += '						<td class="print">';
		text += '							<div>'+words[7]+'</div>';
		text += '						</td>';
		text += '						<td class="print">';
		text += '							<div>'+words[8]+'</div>';
		text += '						</td>';
		text += '						<td class="print">';
		text += '							<div>'+words[9]+'</div>';
		text += '						</td>';
		text += '					</tr>';
		text += '					<tr>';
		text += '						<td class="print">';
		text += '							<div>'+words[10]+'</div>';
		text += '						</td>';
		text += '						<td class="print">';
		text += '							<div>'+words[11]+'</div>';
		text += '						</td>';
		text += '						<td class="print">';
		text += '							<div>'+words[12]+'</div>';
		text += '						</td>';
		text += '						<td class="print">';
		text += '							<div>'+words[13]+'</div>';
		text += '						</td>';
		text += '						<td class="print">';
		text += '							<div>'+words[14]+'</div>';
		text += '						</td>';
		text += '					</tr>';
		text += '					<tr>';
		text += '						<td class="print">';
		text += '							<div>'+words[15]+'</div>';
		text += '						</td>';
		text += '						<td class="print">';
		text += '							<div>'+words[16]+'</div>';
		text += '						</td>';
		text += '						<td class="print">';
		text += '							<div>'+words[17]+'</div>';
		text += '						</td>';
		text += '						<td class="print">';
		text += '							<div>'+words[18]+'</div>';
		text += '						</td>';
		text += '						<td class="print">';
		text += '							<div>'+words[19]+'</div>';
		text += '						</td>';
		text += '					</tr>';
		text += '					<tr>';
		text += '						<td class="print">';
		text += '							<div>'+words[20]+'</div>';
		text += '						</td>';
		text += '						<td class="print">';
		text += '							<div>'+words[21]+'</div>';
		text += '						</td>';
		text += '						<td class="print">';
		text += '							<div>'+words[22]+'</div>';
		text += '						</td>';
		text += '						<td class="print">';
		text += '							<div>'+words[23]+'</div>';
		text += '						</td>';
		text += '						<td class="print">';
		text += '							<div>'+words[24]+'</div>';
		text += '						</td>';
		text += '					</tr>';
		text += '</table>';
		text += '</div>';

		writeConsole(text);
		function writeConsole(content) {
 			top.consoleRef=window.open('','myconsole',
  				'width=650,height=720'
   				+',menubar=1'
   				+',toolbar=1'
   				+',status=1'
   				+',scrollbars=1'
   				+',resizable=1')
 			top.consoleRef.document.writeln(
  				'<html><head><title>Bullshit Bingo</title><link rel="stylesheet" href="bs.css" type="text/css"></head>'
   				+'<body bgcolor=white onLoad="self.focus()">'
   				+content
   				+'</body></html>'
 				)
 			top.consoleRef.document.close()
		}

	});
	

	// event handlers
	for(var i = 0; i<25; i++)
	{
		// mouse over cell
		$("#word"+i+"cell").mouseover(function(e){
			var index= this.id.substring(4,this.id.indexOf("cell"));
			if(!selectedcells[index]) 
			$("#word"+index).addClass("mouseover");
		});
		// mouse out cell
		$("#word"+i+"cell").mouseout(function(e){
			var name= this.id.substring(0,this.id.indexOf("cell"));
			$("#"+name).removeClass("mouseover");
		});
		// select cell
		$("#word"+i+"cell").click(function(e){
			this.className = "solved";
			var index= this.id.substring(4,this.id.indexOf("cell"));
			handleselection(index);
		});
	}
	

	/*
	 * 0  1  2  3  4
	 * 5  6  7  8  9
	 * 10 11 12 13 14
	 * 15 16 17 18 19
	 * 20 21 22 23 24
	*/	
	function handleselection(index)
	{
		//alert(index);
		// do nothing if already selected
		if(!selectedcells[index])
		{
			var bullshitcounter = 0;
			selectedcells[index]=1;
			if(index >=0 && index <=4)
			{
				row1++;
				if(row1 == 5)
				{
					$("#word0cell").addClass("bingo");
					$("#word1cell").addClass("bingo");
					$("#word2cell").addClass("bingo");
					$("#word3cell").addClass("bingo");
					$("#word4cell").addClass("bingo");
					$("#word0").addClass("bingo");
					$("#word1").addClass("bingo");
					$("#word2").addClass("bingo");
					$("#word3").addClass("bingo");
					$("#word4").addClass("bingo");

					bullshitcounter++;
				}
			}
			else if(index >=5 && index <=9)
			{
				row2++;
				if(row2 == 5)
				{
					$("#word5cell").addClass("bingo");
					$("#word6cell").addClass("bingo");
					$("#word7cell").addClass("bingo");
					$("#word8cell").addClass("bingo");
					$("#word9cell").addClass("bingo");
					$("#word5").addClass("bingo");
					$("#word6").addClass("bingo");
					$("#word7").addClass("bingo");
					$("#word8").addClass("bingo");
					$("#word9").addClass("bingo");
					bullshitcounter++;
				}
			}
			else if(index >=10 && index <=14)
			{
				row3++;
				if(row3 == 5)
				{
					$("#word10cell").addClass("bingo");
					$("#word11cell").addClass("bingo");
					$("#word12cell").addClass("bingo");
					$("#word13cell").addClass("bingo");
					$("#word14cell").addClass("bingo");
					$("#word10").addClass("bingo");
					$("#word11").addClass("bingo");
					$("#word12").addClass("bingo");
					$("#word13").addClass("bingo");
					$("#word14").addClass("bingo");
					bullshitcounter++;
				}				
			}
			else if(index >=15 && index <=19)
			{
				row4++;
				if(row4 == 5)
				{
					$("#word15cell").addClass("bingo");
					$("#word16cell").addClass("bingo");
					$("#word17cell").addClass("bingo");
					$("#word18cell").addClass("bingo");
					$("#word19cell").addClass("bingo");
					$("#word15").addClass("bingo");
					$("#word16").addClass("bingo");
					$("#word17").addClass("bingo");
					$("#word18").addClass("bingo");
					$("#word19").addClass("bingo");
					bullshitcounter++;
				}				
			}
			else if(index >=20 && index <=24)
			{
				row5++;
				if(row5 == 5)
				{
					$("#word20cell").addClass("bingo");
					$("#word21cell").addClass("bingo");
					$("#word22cell").addClass("bingo");
					$("#word23cell").addClass("bingo");
					$("#word24cell").addClass("bingo");
					$("#word20").addClass("bingo");
					$("#word21").addClass("bingo");
					$("#word22").addClass("bingo");
					$("#word23").addClass("bingo");
					$("#word24").addClass("bingo");
					bullshitcounter++;
				}				
			}
			
			if(index==0 || index==5 || index==10 || index==15 || index==20)
			{
				col1++;
				if(col1 == 5)
				{
					$("#word0cell").addClass("bingo");
					$("#word5cell").addClass("bingo");
					$("#word10cell").addClass("bingo");
					$("#word15cell").addClass("bingo");
					$("#word20cell").addClass("bingo");
					$("#word0").addClass("bingo");
					$("#word5").addClass("bingo");
					$("#word10").addClass("bingo");
					$("#word15").addClass("bingo");
					$("#word20").addClass("bingo");
					bullshitcounter++;
				}
			}
			else if(index==1 || index==6 || index==11 || index==16 || index==21)
			{
				col2++;
				if(col2 == 5)
				{
					$("#word1cell").addClass("bingo");
					$("#word6cell").addClass("bingo");
					$("#word11cell").addClass("bingo");
					$("#word16cell").addClass("bingo");
					$("#word21cell").addClass("bingo");
					$("#word1").addClass("bingo");
					$("#word6").addClass("bingo");
					$("#word11").addClass("bingo");
					$("#word16").addClass("bingo");
					$("#word21").addClass("bingo");					
					bullshitcounter++;
				}

			}
			else if(index==2 || index==7 || index==12 || index==17 || index==22)
			{
				col3++;
				if(col3 == 5)
				{
					$("#word2cell").addClass("bingo");
					$("#word7cell").addClass("bingo");
					$("#word12cell").addClass("bingo");
					$("#word17cell").addClass("bingo");
					$("#word22cell").addClass("bingo");
					$("#word2").addClass("bingo");
					$("#word7").addClass("bingo");
					$("#word12").addClass("bingo");
					$("#word17").addClass("bingo");
					$("#word22").addClass("bingo");					
					bullshitcounter++;
				}
				
			}
	
			else if(index==3 || index==8 || index==13 || index==18 || index==23)
			{
				col4++;
				if(col4 == 5)
				{
					$("#word3cell").addClass("bingo");
					$("#word8cell").addClass("bingo");
					$("#word13cell").addClass("bingo");
					$("#word18cell").addClass("bingo");
					$("#word23cell").addClass("bingo");
					$("#word3").addClass("bingo");
					$("#word8").addClass("bingo");
					$("#word13").addClass("bingo");
					$("#word18").addClass("bingo");
					$("#word23").addClass("bingo");					
					bullshitcounter++;
				}
								
			}
			else if(index==4 || index==9 || index==14 || index==19 || index==24)
			{
				col5++;
				if(col5 == 5)
				{
					$("#word4cell").addClass("bingo");
					$("#word9cell").addClass("bingo");
					$("#word14cell").addClass("bingo");
					$("#word19cell").addClass("bingo");
					$("#word24cell").addClass("bingo");
					$("#word4").addClass("bingo");
					$("#word9").addClass("bingo");
					$("#word14").addClass("bingo");
					$("#word19").addClass("bingo");
					$("#word24").addClass("bingo");					
					bullshitcounter++;
				}
								
			}
			
			if(index==0 || index==6 || index==12 || index==18 || index==24)
			{
				diag1++;
				if(diag1 == 5)
				{
					$("#word0cell").addClass("bingo");
					$("#word6cell").addClass("bingo");
					$("#word12cell").addClass("bingo");
					$("#word18cell").addClass("bingo");
					$("#word24cell").addClass("bingo");
					$("#word0").addClass("bingo");
					$("#word6").addClass("bingo");
					$("#word12").addClass("bingo");
					$("#word18").addClass("bingo");
					$("#word24").addClass("bingo");					
					bullshitcounter++;
				}
								
			}
			if(index==20 || index==16 || index==12 || index==8 || index==4)
			{
				diag2++;
				if(diag2 == 5)
				{
					$("#word20cell").addClass("bingo");
					$("#word16cell").addClass("bingo");
					$("#word12cell").addClass("bingo");
					$("#word8cell").addClass("bingo");
					$("#word4cell").addClass("bingo");
					$("#word20").addClass("bingo");
					$("#word16").addClass("bingo");
					$("#word12").addClass("bingo");
					$("#word8").addClass("bingo");
					$("#word4").addClass("bingo");					
					bullshitcounter++;
				}
								
			}
			
			if(row1==5 && row2==5 && row3==5 && row4==5 && row5==5 && col1==5 && col2==5 && col3==5 && col4==5 && col5==5 && diag1== 5 && diag2==5)
			{
				alert("Seo am bullshit as miosa a chuala mi riamh!");
			}
			
			else if(bullshitcounter == 1)
			{
				alert("Abair bullshit!");
			}
			else if(bullshitcounter == 2)
			{
				alert("Abair fìor bhullshit!");
			}
			else if(bullshitcounter == 3)
			{
				alert("Seo am bullshit as miosa a chuala mi riamh!");
			}
		}
		
	}
	
	
		
	
	/**
 	 *  gets words from server
 	 */
	function getwords()
	{
		$.post("bs.php", {list: $(this).html()}, function(xml) {
     	
			$(xml).find('facal').each(function(){
				words.push($(this).text());
			});
			
		
			for(var i=0; i<25; i++)
			{
				document.getElementById("word"+i).innerHTML=words[i];
			}

			document.getElementById("iomlan").innerHTML=$(xml).find('iomlan').text();
		});
		
	}
});
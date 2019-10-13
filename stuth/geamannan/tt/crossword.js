// when solving turn CH into ĊĠḂḊḞṖṠṪ
// map words will be done by server
//alert("ĊC");
//alert("ĠG");
//alert("ḂB");
//alert("ḊD");
//alert("ḞF");
//alert("ṖP");
//alert("ṠS");
//alert("ṪT");
//ṀM

// to be generated - word with punctum delens, word without punctum delens, hint, x coordinate, y coordinate, bool issolved
var vertical=new Array();

var horizontal = new Array();

var dimension = 0;

var unsolved = 0;
var wronganswers=0;




// getter
function getWord(wordArray) 
{
    return wordArray[0]; }

function getSolution(wordArray) 
{
    return wordArray[1]; }

function getClue(wordArray) 
{
    return wordArray[2]; }

function getXPos(wordArray) 
{
    return wordArray[3]; }

function getYPos(wordArray) 
{
    return wordArray[4]; }

function isSolved(wordArray) 
{
    return wordArray[5]; }


/// mark word as solved
function setSolved(wordid, isHorizontal) 
{
    if(isHorizontal) {
        horizontal[wordid][5] = true;
        document.getElementById("cluehorizontal"+wordid).className = "solvedclue";
    }
    else
    {
        vertical[wordid][5] = true;
        document.getElementById("cluevertical"+wordid).className = "solvedclue";
    }
}


// do the solve game cheat
// display all letters
function solveGame()
{
    for (var i=0; i<horizontal.length;i++)
    {
        setSolved(i, true);

        var xpos = getXPos(horizontal[i]);
        var ypos = getYPos(horizontal[i]);
        var word = getWord(horizontal[i]);

        for (var j=0; j<word.length;j++)
        {
            document.getElementById("letter"+xpos+"_"+(ypos+j)).innerHTML=word.charAt(j);
        }
    }
    for (var i=0; i<vertical.length;i++)
    {
        setSolved(i, false);

        var xpos = getXPos(vertical[i]);
        var ypos = getYPos(vertical[i]);
        var word = getWord(vertical[i]);

        for (var j=0; j<word.length;j++)
        {
            document.getElementById("letter"+(xpos+j)+"_"+ypos).innerHTML=word.charAt(j);
        }
    }
}


// fill the back of the squares with a colour using CSS
// display the word if it has been solved
function paintVertical(vword, vstyle)
{
    var xpos = getXPos(vword);
    var ypos = getYPos(vword);
    var word = getWord(vword);

    for (var i=0; i<word.length;i++)
    {
        document.getElementById("element"+(xpos+i)+"_"+ypos).className = vstyle;

        if(isSolved(vword)) {
            document.getElementById("letter"+(xpos+i)+"_"+ypos).innerHTML=word.charAt(i);
        }
    }
}


// fill the back of the squares with a colour using CSS
// display the word if it has been solved
function paintHorizontal(hword, hstyle)
{
    var xpos = getXPos(hword);
    var ypos = getYPos(hword);
    var word = getWord(hword);

    for (var i=0; i<word.length;i++)
    {
        document.getElementById("element"+xpos+"_"+(ypos+i)).className = hstyle;
        if(isSolved(hword)) {
            document.getElementById("letter"+xpos+"_"+(ypos+i)).innerHTML=word.charAt(i);
        }
    }
}


// alert with stats when game is won
function showWinMessage()
{
    var noofwords= horizontal.length + vertical.length;
    var winmessage = "Meal do naidheachd!\nRinn thu a' chùis air a' gheama.\n\nFhuair thu a-mach mu "+noofwords;

    if (noofwords ==1 || noofwords == 2) { winmessage +=" fhacal";
    } else if (noofwords == 0 || noofwords ==20 || noofwords >= 40) { winmessage +=" facal";
    } else { winmessage +=" faclan";
    }

    winmessage+=" agus chuir thu a-steach "+wronganswers;

    if (wronganswers ==1 || wronganswers == 2) { winmessage +=" fhreagairt cheàrr.";
    } else if (wronganswers == 0 || wronganswers ==20 || wronganswers >= 40) { winmessage +=" freagairt ceàrr.";
    } else { winmessage +=" freagairtean ceàrr.";
    }
    alert(winmessage);
}

// prompt for answer
function promptForAnswer(wordid, isHorizontal)
{
    var word ="";

    if(isHorizontal) {
        word = prompt("Cuir a-steach facal airson còmhnard "+(1+wordid)+"\n"+getClue(horizontal[wordid])+":", "");
        var solution = getSolution(horizontal[wordid]);
        if(word !=null) {
            if (word.toUpperCase() == solution.toUpperCase()) {
                setSolved(wordid, true);
                paintHorizontal(horizontal[wordid], "solved");
                unsolved--;

                if(unsolved==0) {
                    showWinMessage();
                }
                else
                {
                    alert("Glè mhath!");
                }
            }
            else
            {
                wronganswers++;
                alert("Duilich, ach chan e '"+word+"' am facal a tha a dhìth.");
            }
        }
    }
    else
    {
        word = prompt("Cuir a-steach facal airson dìreach "+(1+wordid)+"\n"+getClue(vertical[wordid])+":", "");
        var solution = getSolution(vertical[wordid]);
        if(word !=null) {
            if(word.toUpperCase() == solution.toUpperCase()) {
                setSolved(wordid, false);
                paintVertical(vertical[wordid], "solved");
                unsolved--;

                if(unsolved==0) {
                    showWinMessage();
                }
                else
                {
                    alert("Glè mhath!");
                }
            }
            else
            {
                wronganswers++;
                alert("Duilich, ach chan e '"+word+"' am facal a tha a dhìth.");
            }
        }
    }
}




//  ******************** actions start here

$(document).ready(
    function () {



        /**
         *  gets words from server
         */
        function getwords()
        {

            vertical=new Array();
            vertical[0]=new Array("FACAL", "FACAL","Pàirt de rosgrann",1,2,false);
            vertical[1]=new Array("CEANN","CEANN","Tha falt air an rud seo agus sùilean ann",0,5,false);
            vertical[2]=new Array("CÀISE","CÀISE","Tha seo blasta air aran",3,0,false);
            vertical[3]=new Array("EANĊAINN","EANCHAINN","Rud anns do cheann leis an smaoinich thu",0,8,false);
            vertical[4]=new Array("UĊD","UCHD","Tha anail nam ...",6,5,false);
            vertical[5]=new Array("MAR","MAR","Coltach ri",6,3,false);
            vertical[6]=new Array("ÒL","ÒL","A' gabhail deoch",7,1,false);

            horizontal = new Array();
            horizontal[0]=new Array("CARBADAN","CARBADAN","Rudan air an siubhlas tu",2,1,false);
            horizontal[1]=new Array("ÀLAINN","ÀLAINN","Brèagha",4,0,false);
            horizontal[2]=new Array("EÒLAIĊEAN","EÒLAICHEAN","Daoine ghlice",7,0,false);
            horizontal[3]=new Array("ACA","ACA","Aig feadhainn",0,4,false);

            unsolved=horizontal.length + vertical.length;
        }


        /**
         *  paint grid, fill in words and define interactions
         */
        function startGame()
        {
            // initialise empty grid
            var grid=new Array();
            for (var i=0; i<dimension;i++)
            {
                grid[i]=new Array();

                for (var j=0; j<dimension;j++)
                {
                    grid[i][j]="";
                }
            }


            // display initial grid table
            var gridtable='<table class="grid" cellspacing="0">';
            for (var i=0; i<dimension;i++)
            {
                gridtable +="<tr>";
                for (var j=0; j<dimension;j++)
                {
                    gridtable +='<td id="element'+i+'_'+j+'" class="puzzlebackground"><table><tr><td id="horizontal'+i+'_'+j+'" class="number" align="right">&nbsp;</td></tr><td id="letter'+i+'_'+j+'" class="element" align="center">&nbsp;';
                    gridtable +='</td></tr><tr><td id="vertical'+i+'_'+j+'" class="number" align="left">&nbsp;</td></tr></table></td>';
                }
                gridtable +="</tr>";
            }
            gridtable +="</table>";
            document.getElementById("grid").innerHTML=gridtable;

            // paint word background
            for (var i=0; i<horizontal.length;i++)
            {
                paintHorizontal(horizontal[i], "new");
            }

            for (var i=0; i<vertical.length;i++)
            {
                paintVertical(vertical[i], "new");
            }

            // display numbers
            for (var i=0; i<horizontal.length;i++)
            {
                document.getElementById("horizontal"+getXPos(horizontal[i])+"_"+getYPos(horizontal[i])).innerHTML=i+1;
            }
            for (var i=0; i<vertical.length;i++)
            {
                document.getElementById("vertical"+getXPos(vertical[i])+"_"+getYPos(vertical[i])).innerHTML=i+1;
            }

            // show clues
            var horizontalhtml='<ol>';
            for (var i=0; i<horizontal.length;i++)
            {
                horizontalhtml+='<li id="cluehorizontal'+i+'" class="clue">'+getClue(horizontal[i])+'<br />&nbsp;</li>';
            }
            horizontalhtml+="</ol>";
            document.getElementById("horizontal").innerHTML=horizontalhtml;

            var verticalhtml='<ol>';
            for (var i=0; i<vertical.length;i++)
            {
                verticalhtml+='<li id="cluevertical'+i+'" class="clue">'+getClue(vertical[i])+'<br />&nbsp;</li>';
            }
            verticalhtml+="</ol>";
            document.getElementById("vertical").innerHTML=verticalhtml;

            // Clue interaction - horizontal
            for (var i=0; i<dimension;i++)
            {
                // enter anwer on mouse click
                $('#cluehorizontal'+i).click(
                    function () {
                        var wordid=eval($(this).attr("id").substring(14));

                        if(!isSolved(horizontal[wordid])) {
                            promptForAnswer(wordid, true);
                        }
                    }
                );

                // colour in on mouseover
                $('#cluehorizontal'+i).mouseover(
                    function () {
                        var wordid=eval($(this).attr("id").substring(14));

                        if(!isSolved(horizontal[wordid])) {
                            paintHorizontal(horizontal[wordid], "markedletter");
                            document.getElementById($(this).attr("id")).className = "markedclue";
                        }
                    }
                );

                // reset colour on mouseout
                $('#cluehorizontal'+i).mouseout(
                    function () {
                        var wordid=eval($(this).attr("id").substring(14));

                        if(!isSolved(horizontal[wordid])) {
                            paintHorizontal(horizontal[wordid], "new");
                            document.getElementById($(this).attr("id")).className = "clue";
                        }
                    }
                );
            }

            // Clue interaction - vertical
            for (var i=0; i<dimension;i++)
            {
                // enter anwer on mouse click
                $('#cluevertical'+i).click(
                    function () {
                        var wordid=eval($(this).attr("id").substring(12));
                        if(!isSolved(vertical[wordid])) {
                            promptForAnswer(wordid, false);
                        }
                    }
                );


                // colour in on mouseover
                $('#cluevertical'+i).mouseover(
                    function () {
                        var wordid=eval($(this).attr("id").substring(12));

                        if(!isSolved(vertical[wordid])) {
                            paintVertical(vertical[wordid], "markedletter");
                            document.getElementById($(this).attr("id")).className = "markedclue";
                        }
                    }
                );

                // reset colour on mouseout
                $('#cluevertical'+i).mouseout(
                    function () {
                        var wordid=eval($(this).attr("id").substring(12));

                        if(!isSolved(vertical[wordid])) {
                            paintVertical(vertical[wordid], "new");
                            document.getElementById($(this).attr("id")).className = "clue";
                        }
                    }
                );
            }


            // enter answer if click on grid start element - horizontal
            for (var i=0; i<horizontal.length;i++)
            {
                var xpos = getXPos(horizontal[i]);
                var ypos = getYPos(horizontal[i]);

                // prompt for anwer on click
                $('#horizontal'+xpos+"_"+ypos).click(
                    function () {
                        var wordid = eval($(this).text());
                        if(!isSolved(horizontal[wordid-1])) {
                            promptForAnswer(wordid-1, true);
                        }
                    }
                );

                // colour in on mouseover
                $('#horizontal'+xpos+"_"+ypos).mouseover(
                    function () {
                        var wordid = eval($(this).text())-1;
                        if(!isSolved(horizontal[wordid])) {
                            document.getElementById('cluehorizontal'+wordid).className = "markedclue";
                            paintHorizontal(horizontal[wordid], "markedletter");
                        }
                    }
                );

                // reset colour on mouseout
                $('#element'+xpos+"_"+ypos).mouseout(
                    function () {
                        var wordid = document.getElementById("horizontal"+$(this).attr("id").substring(7)).innerHTML;

                        if(!isSolved(horizontal[wordid-1])) {
                            document.getElementById('cluehorizontal'+(wordid-1)).className = "clue";
                            paintHorizontal(horizontal[wordid-1], "new");
                        }
                    }
                );
            }


            // enter answer if click on grid start element - vertical
            for (var i=0; i<vertical.length;i++)
            {
                var xpos = getXPos(vertical[i]);
                var ypos = getYPos(vertical[i]);

                // prompt for anwer on click
                $('#vertical'+xpos+"_"+ypos).click(
                    function () {
                        var wordid = eval($(this).text());
                        if(!isSolved(vertical[wordid-1])) {
                            promptForAnswer(wordid-1, false);
                        }
                    }
                );

                // colour in on mouseover
                $('#vertical'+xpos+"_"+ypos).mouseover(
                    function () {
                        var wordid = eval($(this).text())-1;
                        if(!isSolved(vertical[wordid])) {
                            document.getElementById('cluevertical'+wordid).className = "markedclue";
                            paintVertical(vertical[wordid], "markedletter");
                        }
                    }
                );


                // reset colour on mouseout
                $('#element'+xpos+"_"+ypos).mouseout(
                    function () {
                        var wordid = document.getElementById("vertical"+$(this).attr("id").substring(7)).innerHTML;
                        if(!isSolved(vertical[wordid-1])) {
                            document.getElementById('cluevertical'+(wordid-1)).className = "clue";
                            paintVertical(vertical[wordid-1], "new");
                        }
                    }
                );
            }
        }


        // start new game
        $('#startgame').click(
            function () {

                document.getElementById("grid").innerHTML="<p>Starting game...</p>";
                // todo: parameter
                dimension=9;
                wronganswers=0;

                getwords();
                startGame();
            }
        );



        // solvegame cheat
        $('#solvegame').click(
            function () {
                solveGame();
            }
        );


        /**
         *  display mockup game on page reload
         *  "fàilte dhan tòimhseachan tarsainn - am feuch thusa e"
         */
        function welcomePuzzle()
        {
            // init and define words
            dimension=11;
            wronganswers=0;

            vertical=new Array();
            horizontal = new Array();

            vertical[0]=new Array("FÀILTE","FÀILTE","Nuair a chòrdas e do dhaoine gu bheil thu air tighinn, cuiridh iad seo ort",1,3,false);
            horizontal[0]=new Array("ḊAN","DHAN","'dha' agus an t-alt",7,0,false);
            horizontal[1]=new Array("TÒIṀSEAĊAN", "TÒIMHSEACHAN","'S e ... tarsainn a tha seo",3,1,false);
            vertical[1]=new Array("TARSAINN","TARSAINN","Taobh eile",3,1,false);
            vertical[2]=new Array("AM","AM","Facal gus ceist a chur ro na litrichean 'bpfm'",3,7,false);
            horizontal[2]=new Array("FEUĊ","FEUCH","Briog air seo agus cuir 'feuch' a-steach",1,3,false);
            vertical[3]=new Array("ṪUSA","THUSA","Cha mhì!",0,9,false);
            horizontal[3]=new Array("E","E","An còigeamh litir san aibidil",6,3,false);

            unsolved=horizontal.length + vertical.length;

            // start game
            startGame();

            // paint backgrounds
            paintVertical(vertical[0], "new");
            paintHorizontal(horizontal[0], "new");
            paintHorizontal(horizontal[1], "new");
            paintVertical(vertical[1], "new");
            paintVertical(vertical[2], "new");
            paintHorizontal(horizontal[2], "new");
            paintVertical(vertical[3], "new");
            paintHorizontal(horizontal[3], "new");

            // display me words with time delay, one after the other
            setSolved(0, false);
            paintVerticalWithTimeout(vertical[0], "solved",500);
            unsolved--;

            setSolved(0, true);
            paintHorizontalWithTimeout(horizontal[0], "solved",1000);
            unsolved--;

            setSolved(1, true);
            paintHorizontalWithTimeout(horizontal[1], "solved",1500);
            unsolved--;

            setSolved(1, false);
            paintVerticalWithTimeout(vertical[1], "solved",2000);
            unsolved--;

            /*
            setSolved(2, false);
            paintVerticalWithTimeout(vertical[2], "solved", 5000);
            unsolved--;

            setSolved(2, true);
            paintHorizontalWithTimeout(horizontal[2], "solved",6000);
            unsolved--;

            setSolved(3, false);
            paintVerticalWithTimeout(vertical[3], "solved",7000);
            unsolved--;

            setSolved(3, true);
            paintHorizontalWithTimeout(horizontal[3], "solved", 8000);
            unsolved--;
            */


            /**
             *  display pre-solved word
             *  with time delay for the mockup
             */
            function paintVerticalWithTimeout(vword, vstyle, ms)
            {
                var xpos = getXPos(vword);
                var ypos = getYPos(vword);
                var word = getWord(vword);

                for (var i=0; i<word.length;i++)
                {
                    document.getElementById("element"+(xpos+i)+"_"+ypos).className = vstyle;

                    if(isSolved(vword)) {
                        setTimeout(
                            (function (k,l,m,n,o) {
                                return function () {
                                     paintwithtimeout(k,l,m,n,o);
                                }; })(i,xpos,ypos,word,false) ,ms
                        );
                    }
                }
            }


            /**
             *  display pre-solved word
             *  with time delay for the mockup
             */
            function paintHorizontalWithTimeout(hword, hstyle, ms)
            {
                var xpos = getXPos(hword);
                var ypos = getYPos(hword);
                var word = getWord(hword);

                for (var i=0; i<word.length;i++)
                {
                    document.getElementById("element"+xpos+"_"+(ypos+i)).className = hstyle;
                    if(isSolved(hword)) {
                        setTimeout(
                            (function (k,l,m,n,o) {
                                return function () {
                                     paintwithtimeout(k,l,m,n,o);
                                }; })(i,xpos,ypos,word,true) ,ms
                        );
                    }
                }
            }

            /**
             *  helper function - display pre-solved word
             *  with time delay for the mockup
             */
            function paintwithtimeout(paintletterindex,xpos,ypos,word,ishorizontal)
            {
                if(ishorizontal) {
                    document.getElementById("letter"+xpos+"_"+(ypos+paintletterindex)).innerHTML=word.charAt(paintletterindex);
                }
                else
                {
                    document.getElementById("letter"+(xpos+paintletterindex)+"_"+ypos).innerHTML=word.charAt(paintletterindex);
                }
            }

        }

        // on page reload
        welcomePuzzle();

    }
);

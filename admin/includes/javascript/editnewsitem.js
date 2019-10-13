$(document).ready(
    function () {

        // save title
        $("#{JSID}savetitlebutton").click(
            function () {
    
                var elements = new Array();
                elements[0] = $("#{JSID}savetitlebutton");
                elements[1] = $("#{JSID}savetitlereset");
                elements[2] = $("#{JSID}title");
                disableElements(elements);
        
                showprogressbox("Saving Title for Newsitem ID: "+$("#{JSID}newsitem").val()+" ...");
        
                postRequest(
                    projectroot+"admin/includes/ajax/news/savetitle.php",
                    {
                        newsitem: $("#{JSID}newsitem").val(),
                        page: $("#{JSID}page").val(),
                        title: uni2ent($("#{JSID}title").val())
                    },
                    function (xml) {
                        postRequest(
                            projectroot+"admin/includes/ajax/news/updatetitle.php",
                            {
                                newsitem: $("#{JSID}newsitem").val()
                            },
                            function (html) {
                                $("#{JSID}newsitemheader").html(html);
                                $("#{JSID}newsitemtitleheader").html(html);
                                $("#{JSID}headernewsitemtitle").html(html);
                                enableElements(elements);
                            },
                            elements
                        ); // post                     
    
                        showmessageXML(xml);
                    },
                    elements
                ); // post                     
        
            }
        ); // save section title
    
        // publish/unpublish
        $("#{JSID}publishbutton").click(
            function () {
                var elements = new Array();
                elements[0] = $("#{JSID}publishbutton");
                disableElements(elements);
    
                if($("#{JSID}publishbutton").val()=="Hide Newsitem") {
                    showprogressbox("Hiding Newsitem ID: "+$("#{JSID}newsitem").val()+" ...");
            
                    postRequest(
                        projectroot+"admin/includes/ajax/news/unpublish.php",
                        {
                            newsitem: $("#{JSID}newsitem").val(),
                            page: $("#{JSID}page").val()
                        },
                        function (xml) {
                            showmessageXML(xml);
                            enableElements(elements);
                            $("#{JSID}publishbutton").val('Publish Newsitem');
                            $("#{JSID}ispublished").val('false');
                        },
                        elements
                    ); // post                     
    
                } // if
                else
                {
                    showprogressbox("Publishing Newsitem ID: "+$("#{JSID}newsitem").val()+" ...");
            
            
                    postRequest(
                        projectroot+"admin/includes/ajax/news/publish.php",
                        {
                            newsitem: $("#{JSID}newsitem").val(),
                            page: $("#{JSID}page").val()
                        },
                        function (xml) {
                            showmessageXML(xml);
                            enableElements(elements);
                            $("#{JSID}publishbutton").val('Hide Newsitem');
                            $("#{JSID}ispublished").val('true');
                        },
                        elements
                    ); // post
                } //else
            
            }
        ); // publish/unpublish
    
    
    
        // save permissions
        $("#{JSID}savepermissionsbutton").click(
            function () {
    
                var elements = new Array();
                elements[0] = $("#{JSID}savepermissionsbutton");
                elements[1] = $("#{JSID}savepermissionsreset");
                elements[2] = $("#{JSID}copyright");
                elements[3] = $("#{JSID}imagecopyright");
                elements[4] = $("input[name={JSID}permission]");
                disableElements(elements);
    
                showprogressbox("Saving Copyright Info for Newsitem ID: "+$("#{JSID}newsitem").val()+" ...");
        
                postRequest(
                    projectroot+"admin/includes/ajax/news/savepermissions.php",
                    {
                        newsitem: $("#{JSID}newsitem").val(),
                        page: $("#{JSID}page").val(),
                        copyright: uni2ent($("#{JSID}copyright").val()),
                        imagecopyright: uni2ent($("#{JSID}imagecopyright").val()),
                        permission: $('input[name={JSID}permission]:checked').val()
                    },
                    function (xml) {
                        enableElements(elements);
                        showmessageXML(xml);
                    },
                    elements
                ); // post             
        
            }
        ); // save permissions
    
        // save source
        $("#{JSID}savesourcebutton").click(
            function () {
    
                var elements = new Array();
                elements[0] = $("#{JSID}savesourcebutton");
                elements[1] = $("#{JSID}savesourcereset");
                elements[2] = $("#{JSID}contributor");
                elements[3] = $("#{JSID}location");
                elements[4] = $("#{JSID}source");
                elements[5] = $("#{JSID}sourcelink");
                disableElements(elements);        
    
                showprogressbox("Saving Source Info for Newsitem ID: "+$("#{JSID}newsitem").val()+" ...");
        
                postRequest(
                    projectroot+"admin/includes/ajax/news/savesource.php",
                    {
                        newsitem: $("#{JSID}newsitem").val(),
                        page: $("#{JSID}page").val(),
                        source: uni2ent($("#{JSID}source").val()),
                        sourcelink: $("#{JSID}sourcelink").val(),
                        location: uni2ent($("#{JSID}location").val()),
                        contributor: uni2ent($("#{JSID}contributor").val())
                    },
                    function (xml) {
                        enableElements(elements);
                        showmessageXML(xml);
                    },
                    elements
                ); // post             
    
            }
        ); // save source
    
    
        // save date
        $("#{JSID}savedatebutton").click(
            function () {
        
                if($("#{JSID}year").val().length!=4) {
                    alert("Please enter a 4-digit year!");
                }
                else if(!$.isNumeric($("#{JSID}year").val())) {
                    alert("The year must be a number!");
                }
                else
                {
                    var elements = new Array();
                    elements[0] = $("#{JSID}savedatebutton");
                    elements[1] = $("#{JSID}savedatereset");
                    elements[2] = $("#{JSID}day");
                    elements[3] = $("#{JSID}month");
                    elements[4] = $("#{JSID}year");
                    elements[5] = $("#{JSID}hours");
                    elements[6] = $("#{JSID}minutes");
                    elements[7] = $("#{JSID}seconds");
                    disableElements(elements);
                            
                    showprogressbox("Saving Date for Newsitem ID: "+$("#{JSID}newsitem").val()+" ...");
            
                    postRequest(
                        projectroot+"admin/includes/ajax/news/savedate.php",
                        {
                            newsitem: $("#{JSID}newsitem").val(),
                            page: $("#{JSID}page").val(),
                            day: $("#{JSID}day").val(),
                            month: $("#{JSID}month").val(),
                            year: $("#{JSID}year").val(),
                            hours: $("#{JSID}hours").val(),
                            minutes: $("#{JSID}minutes").val(),
                            seconds: $("#{JSID}seconds").val()
                        },
                        function (xml) {
        
                            postRequest(
                                projectroot+"admin/includes/ajax/news/updatedate.php",
                                {
                                    newsitem: $("#{JSID}newsitem").val()
                                },
                                function (html) {
                                    enableElements(elements);
                                    $("#{JSID}dateheader").html(html);
                                },
                                elements
                            ); // post     
            
                            showmessageXML(xml);
                        },
                        elements
                    ); // post     
                } // year check
            }
        ); // save date        
    
        // add categories
        $("#{JSID}addcatbutton").click(
            function () {
    
                var elements = new Array();
                elements[0] = $("#{JSID}addcatbutton");
                elements[1] = $("#{JSID}removecatbutton");
                elements[2] = $("#{JSID}selectedcat");
                disableElements(elements);
    
                showprogressbox("Saving Categories for Newsitem ID: "+$("#{JSID}newsitem").val()+" ...");
    
                postRequest(
                    projectroot+"admin/includes/ajax/news/addcategories.php",
                    {
                        newsitem: $("#{JSID}newsitem").val(),
                        page: $("#{JSID}page").val(),
                        selectedcat: $("#{JSID}selectedcat").val()
                    },
                    function (xml) {
                        postRequest(
                            projectroot+"admin/includes/ajax/news/updatecategories.php",
                            {
                                newsitem: $("#{JSID}newsitem").val()
                            },
                            function (html) {
                                $("#{JSID}categorylist").html(html);
                                enableElements(elements);
                            },
                            elements
                        ); // post
                        showmessageXML(xml);
                    },
                    elements
                ); // post 
               
            }
        ); // add categories
    
    
        // remove categories
        $("#{JSID}removecatbutton").click(
            function () {
    
                var elements = new Array();
                elements[0] = $("#{JSID}addcatbutton");
                elements[1] = $("#{JSID}removecatbutton");
                elements[2] = $("#{JSID}selectedcat");
                disableElements(elements);
        
                showprogressbox("Saving Categories for Newsitem ID: "+$("#{JSID}newsitem").val()+" ...");
    
                postRequest(
                    projectroot+"admin/includes/ajax/news/removecategories.php",
                    {
                        newsitem: $("#{JSID}newsitem").val(),
                        page: $("#{JSID}page").val(),
                        selectedcat: $("#{JSID}selectedcat").val()
                    },
                    function (xml) {
            
                        postRequest(
                            projectroot+"admin/includes/ajax/news/updatecategories.php",
                            {
                                newsitem: $("#{JSID}newsitem").val()
                            },
                            function (html) {
                                $("#{JSID}categorylist").html(html);
                                enableElements(elements);
                            },
                            elements
                        ); // post                        
                        showmessageXML(xml);
                    },
                    elements
                ); // post 
            }
        ); // remove categories

    }
); // document
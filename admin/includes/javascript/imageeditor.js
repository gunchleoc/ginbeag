/**
 * returns text
 */
function savestatusfailedmessage()
{
    return "Failed to save!";
}


$(document).ready(
    function () {

        // only activate buttons if there is a change
        var filenameisedited=false;
        var alignisedited=false;
        var sizeisedited=false;

        /**
         * call when something changes in the text
         */
        function setfilenameisedited()
        {
            filenameisedited=true;
            $("#{JSID}submitfilename").val("Save Changes");
            $("#{JSID}submitfilename").css("font-style","normal");
            enableElements([$("#{JSID}submitfilename"),$("#{JSID}resetfilename")]);
        }

        /**
         * call when changes are reset or saved
         */
        function setfilenameisnotedited()
        {
            filenameisedited=false;
            $("#{JSID}submitfilename").val("To change image, type in the box above");
            $("#{JSID}submitfilename").css("font-style","italic");
            disableElements([$("#{JSID}submitfilename"),$("#{JSID}resetfilename")]);
        }

        /**
         * call when something changes in the text
         */
        function setalignisedited()
        {
            alignisedited=true;
            $("#{JSID}submitalignment").val("Save Changes");
            $("#{JSID}submitalignment").css("font-style","normal");
            enableElements([$("#{JSID}submitalignment"),$("#{JSID}resetalignment")]);
        }

        /**
         * call when changes are reset or saved
         */
        function setalignisnotedited()
        {
            alignisedited=false;
            $("#{JSID}submitalignment").val("To change image alignment, select a button above");
            $("#{JSID}submitalignment").css("font-style","italic");
            disableElements([$("#{JSID}submitalignment"),$("#{JSID}resetalignment")]);
        }


        /**
         * call when something changes in the text
         */
        function setsizeisedited()
        {
            sizeisedited=true;
            $("#{JSID}submitsize").val("Save Changes");
            $("#{JSID}submitsize").css("font-style","normal");
            enableElements([$("#{JSID}submitsize"),$("#{JSID}resetsize")]);
        }

        /**
         * call when changes are reset or saved
         */
        function setsizeisnotedited()
        {
            sizeisedited=false;
            $("#{JSID}submitsize").val("To change image size options, select a button above");
            $("#{JSID}submitsize").css("font-style","italic");
            disableElements([$("#{JSID}submitsize"),$("#{JSID}resetsize")]);
        }

        addlistenersFilename();
        addlistenersAlign();
        addlistenersSize();


        /**
         * listeners for filename pane
         */
        function addlistenersFilename()
        {
            setfilenameisnotedited();
            var elements = new Array();
            elements[0] = $("#{JSID}submitfilename");
            elements[1] = $("#{JSID}resetfilename");

            // watch edit state to activate save button
            $("#{JSID}imagefilename").on(
                "keypress", function () {
                    setfilenameisedited();
                }
            );
            $("#{JSID}imagefilename").bind('paste', function() {
                setfilenameisedited();
            });

            /* save image filename */
            $("#{JSID}submitfilename").click(
                function () {

                    disableElements(elements);

                    //alert("Submit filename clicked!");

                    showprogressbox("Saving Image File: "+$("#{JSID}imagefilename").val()+" ... ");

                    postRequest(
                        projectroot+"admin/includes/ajax/imageeditor/saveimagefilename.php",
                        {
                            imagefilename: $("#{JSID}imagefilename").val(),
                            page: $("#{JSID}page").val(),
                            item: $("#{JSID}item").val(),
                            elementtype: $("#{JSID}elementtype").val()
                        },
                        function (xml) {
                            var element=$(xml).find('message');
                            var error = element.attr("error");
                            if(error !="1") {
                                   //alert("Image saved!");
                                   setfilenameisnotedited();

                                postRequest(
                                    projectroot+"admin/includes/ajax/imageeditor/updateimage.php",
                                    {
                                        page: $("#{JSID}page").val(),
                                        item: $("#{JSID}item").val(),
                                        elementtype: $("#{JSID}elementtype").val()
                                    },
                                    function (html) {
                                        $("#{JSID}editorimagepane").html(html);
                                    },
                                    elements
                                ); // post updateimage.php

                                if($("#{JSID}imagefilename").val().length<1) {
                                    $("#{JSID}editoralignmentpane").html("");
                                    $("#{JSID}editorsizepane").html("");
                                }
                                else
                                {
                                    postRequest(
                                        projectroot+"admin/includes/ajax/imageeditor/showimagealignment.php",
                                        {
                                            page: $("#{JSID}page").val(),
                                            item: $("#{JSID}item").val(),
                                            elementtype: $("#{JSID}elementtype").val()
                                        },
                                        function (html) {
                                            $("#{JSID}editoralignmentpane").html(html);
                                            addlistenersAlign();
                                        },
                                        elements
                                    ); // post showimagealignment.php except for linklist links
                                    if ($("#{JSID}elementtype").val() !== "link") {
                                        postRequest(
                                            projectroot+"admin/includes/ajax/imageeditor/showimagesize.php",
                                            {
                                                page: $("#{JSID}page").val(),
                                                item: $("#{JSID}item").val(),
                                                elementtype: $("#{JSID}elementtype").val()
                                            },
                                            function (html) {
                                                $("#{JSID}editorsizepane").html(html);
                                                addlistenersSize();
                                            },
                                            elements
                                        ); // post showimagesize.php
                                    }
                                }

                            } // no error
                            showmessageXML(xml);
                            enableElements(elements);
                        },
                        elements
                    ); // post saveimagefilename.php

                }
            ); // submitfilename



            /* reset edit status */
            $("#{JSID}resetfilename").click(
                function () {
                    setfilenameisnotedited();
                }
            );    // resetbutton

        } // addlistenersfilename




        /**
         * listeners for alignment pane
         */
        function addlistenersAlign()
        {
            setalignisnotedited();

            var elements = new Array();
            elements[0] = $("#{JSID}submitalignment");
            elements[1] = $("#{JSID}resetalignment");

            // watch edit state to activate save button
            $("#{JSID}imagealignleft").on(
                "click", function () {
                    setalignisedited();
                }
            );

            $("#{JSID}imagealignright").on(
                "click", function () {
                    setalignisedited();
                }
            );

            $("#{JSID}imagealigncenter").on(
                "click", function () {
                    setalignisedited();
                }
            );

            /* save image filename */
            $("#{JSID}submitalignment").click(
                function () {

                    disableElements(elements);

                    showprogressbox("Saving Image Alignment: "+$('input[name={JSID}imagealign]:checked').val()+" ... ");

                    postRequest(
                        projectroot+"admin/includes/ajax/imageeditor/saveimagealignment.php",
                        {
                            imagealign: $('input[name={JSID}imagealign]:checked').val(),
                            page: $("#{JSID}page").val(),
                            item: $("#{JSID}item").val(),
                            elementtype: $("#{JSID}elementtype").val()
                        },
                        function (xml) {
                            var element=$(xml).find('message');
                            var error = element.attr("error");
                            if(error !="1") {
                                setalignisnotedited();
                            } // no error
                            showmessageXML(xml);
                            enableElements(elements);
                        },
                        elements
                    ); // post saveimagealignment.php

                }
            ); // submitfilename

            /* reset edit status */
            $("#{JSID}resetalignment").click(
                function () {
                    setalignisnotedited();
                }
            );    // resetbutton

        } // addlistenersalign




        /**
         * listeners for size pane
         */
        function addlistenersSize()
        {
            setsizeisnotedited();

            var elements = new Array();
            elements[0] = $("#{JSID}submitzize");
            elements[1] = $("#{JSID}resetsize");

            // watch edit state to activate save button
            $("#{JSID}autoshrinkon").on(
                "click", function () {
                    setsizeisedited();
                }
            );

            $("#{JSID}autoshrinkoff").on(
                "click", function () {
                    setsizeisedited();
                }
            );

            $("#{JSID}usethumbnailon").on(
                "click", function () {
                    setsizeisedited();
                }
            );

            $("#{JSID}usethumbnailoff").on(
                "click", function () {
                    setsizeisedited();
                }
            );

            /* save image size */
            $("#{JSID}submitsize").click(
                function () {

                    disableElements(elements);

                    showprogressbox("Saving Image Size: Shrink "+$('input[name={JSID}autoshrink]:checked').val()+" Thumbnail "+$('input[name={JSID}usethumbnail]:checked').val()+" ... ");

                    postRequest(
                        projectroot+"admin/includes/ajax/imageeditor/saveimagesize.php",
                        {
                            autoshrink: $('input[name={JSID}autoshrink]:checked').val(),
                            usethumbnail: $('input[name={JSID}usethumbnail]:checked').val(),
                            page: $("#{JSID}page").val(),
                            item: $("#{JSID}item").val(),
                            elementtype: $("#{JSID}elementtype").val()
                        },
                        function (xml) {
                            var element=$(xml).find('message');
                            var error = element.attr("error");
                            if(error !="1") {
                                setalignisnotedited();
                            } // no error
                            showmessageXML(xml);
                            enableElements(elements);
                        },
                        elements
                    ); // post saveimagesize.php

                }
            ); // submitsize

            /* reset edit status */
            $("#{JSID}resetsize").click(
                function () {
                    setsizeisnotedited();
                }
            );    // resetbutton

        } // addlistenerssize

    }
); // document

(function () {
    'use strict';

    function ajaxcall(){
        $("#ajax-infile").on("click", function(){
            $.ajax({
                url: Routing.generate(  //use of functions from friendsofsymfony/jsrouting-bundle
                    'cpasimusante_simuresource_updatesimuresourceinpage2',
                    {'userid': $("#userid option:selected").val()}
                ),
                type: 'GET',
                success: function (data) {
                    $('#ajax-infile-div').html("content from ajax-infile click : salt=" + data.salt + " and locale= "+data.locale);
                },
                error: function( jqXHR, textStatus, errorThrown){
                    $('#ajax-infile-div').html("error. textStatus="+ textStatus + " errorThrown="+ errorThrown);
                }
            });
        });
    }

    $(document).ready(function () {
        ajaxcall();
    });
})();
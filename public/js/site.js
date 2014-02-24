/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function() {

    renderDir('');
    
    $("#browsedir").on("click", "a", function() { 
        console.log(encodeURIComponent($(this).data('path')));
        renderDir(encodeURIComponent($(this).data('path')));
    });

    
    function renderDir(path) {
        $.ajax({
            url: "/index/getdir/path/" + path,
        }).done(function(data) {
            $("#browsedir").html(data);
        });
    }
    
    $('#browsedir').on("click","i[data-song]", function(){
        $.ajax({
            url: "/index/playlist/a/add/song/" + encodeURIComponent($(this).data('song'))
        });
    });


    function startTimer(ele, starttime, totaltime) {
        ele.progressTimer({
            timeLimit: totaltime,
            startTime: starttime,
            onFinish: function() {
                handlePlaylist();
            }
        });
    }
    var playlisttimer = false;
    function handlePlaylist() {
        $.getJSON("/index/playlist/a/playtime", function(json) {
            $("#playlist div[data-songid]").html("");
            console.log(json.status);
            if (json.status === false) {
                setTimeout(function() {
                    handlePlaylist();
                }, 5000);
            } else {
                clearTimeout(playlisttimer);

                var ele = $("#playlist div[data-songid='" + json.songid + "']");
                ele.html('<i class="glyphicon glyphicon-headphones"></i>');
                $("#playlist .playtime").html('');
                startTimer(ele.parent().find('.playtime'), json.elapsed, json.full);
            }
        });
    }

    handlePlaylist();
    $('#playlist a').click(function() {
        $.ajax({
            async: false,
            url: '/index/playlist/a/play/id/' + $(this).find('div[data-songid]').data('songpos'),
            success: handlePlaylist()
        });
    });
});

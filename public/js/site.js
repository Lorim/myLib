/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function() {
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
        $.getJSON("/index/playtime", function(json) {
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
                url: '/index/play/id/'+$(this).find('div[data-songid]').data('songpos'),
                success: handlePlaylist()
            });
        });
    });

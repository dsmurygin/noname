var timeFormat = function(seconds){
    var h = Math.floor(seconds/3600)<10 ? "0"+Math.floor(seconds/3600) : Math.floor(seconds/3600);
    var m =Math.floor((seconds-h*3600)/60);
    if (m < 10) m = "0" +m;
    var s = Math.floor(seconds-(h*3600+m*60))<10 ? "0"+Math.floor(seconds-(h*3600+m*60)) : Math.floor(seconds-(h*3600+m*60));
    return h +":"+m+":"+s;
};
function get_cookie ( cookie_name ){
    var results = document.cookie.match ( '(^|;) ?' + cookie_name + '=([^;]*)(;|$)' );
    if ( results )
        return ( unescape ( results[2] ) );
    else
        return null;
}
var $time=0;
var interval;
var idBook;
bookURL = '';
var metadata=true;
$(document).ready(function(){
    var audio = $('#audioPlayer');
    audio.on('loadedmetadata',function(){
            audio[0].currentTime=$time;
            $.ajax({
                method:"POST",
                url:"/player/changeLastBook.php",
                data:{book:idBook},
                success:function(data){
                    $('#Time').text(" / "+timeFormat(audio[0].duration));
                    if (data !='' ){
                        audio[0].currentTime = data;
                        $('#currentTime').text(timeFormat(data));
                    }
                    else{
                        if (get_cookie('idBook')==idBook){
                            audio[0].currentTime= get_cookie('time');
                        }
                        else{
                            audio[0].currentTime = 0;
                        }
                        $('#currentTime').text(timeFormat(audio[0].currentTime));
                    }
                    bookURL = audio[0].src.split('/')[5];
                    bookURL = bookURL.split('.')[0];
                }
            });
    });
    $("#saveTime").on("click",function(){
        $.ajax({
            method:"POST",
            url:"/player/addBookSave.php",
            data:{book:idBook},
            success: function(data){
                alert(data);
            }
        });
    });
    $("#playPause").on("click",function(){
        var audio=$('#audioPlayer');
        if (audio[0].paused){
            interval = setInterval(saveTime, 30000);
            audio[0].play();
            $('#playPause').find('.icon-play').addClass('icon-pause').removeClass('icon-play');
        } else {
            clearInterval(interval);
            audio[0].pause();
            $('#playPause').find('.icon-pause').removeClass('icon-pause').addClass('icon-play');
        }
    });
    audio.on('timeupdate',function(){
        var currentPos = audio[0].currentTime;
        var maxDuration = audio[0].duration;
        var perc = 100 * currentPos / maxDuration;
        $('#progressBar').css('width',perc+'%');
        $("#currentTime").text(timeFormat(currentPos));
    });
    var timeDrag = false;
    $('#timeBar').on('mousedown', function(e) {
        timeDrag = true;
        updatebar(e.pageX);
    });
    $(document).on('mouseup', function(e) {
        if(timeDrag) {
            timeDrag = false;
            updatebar(e.pageX);
        }
    });
    $(document).on('mousemove', function(e) {
        if (timeDrag) {
            updatebar(e.pageX);
        }
    });
    var updatebar = function(x) {
        var progress = $('#timeBar');
        var maxduration = audio[0].duration;
        var position = x - progress.offset().left;
        var percentage = 100 * position / (progress.width()-4);
        if(percentage > 100) {
            percentage = 100;
        }
        if(percentage < 0) {
            percentage = 0;
        }
        $('#progressBar').css('width',percentage+'%');
        audio[0].currentTime = maxduration * percentage / 100;
    };
});
function playBook(id,url,info,image){
    var audio = $('#audioPlayer');
    var $src ="http://audioknigionline.ru/books/audio/"+url+".mp3";
    if (audio[0].src!=$src){
        var imgSrc;
        if (image) imgSrc="http://audioknigionline.ru/books/images/"+image+".jpg";
        else imgSrc="http://audioknigionline.ru/books/images/"+url+".jpg";
        audio[0].src=$src;
        idBook=id;
        $("#playerDownload").attr({"href" : $src, "download" : url+".mp3"});
        $("#playerInfo").text(info).attr({"href": "/book/" + url, "onclick": "showContent('book','"+url+"','');return false"});
        $("#bookIcon").attr({src:imgSrc});
    }
    if (audio[0].paused){
        interval = setInterval(saveTime, 30000);
        audio[0].play();
        $('#playPause').find('.icon-play').addClass('icon-pause').removeClass('icon-play');
    } else {
        clearInterval(interval);
        audio[0].pause();
        $('#playPause').find('.icon-pause').removeClass('icon-pause').addClass('icon-play');
    }
}

function saveTime(){
    var audio = $('#audioPlayer');
    $.ajax({
        method:"POST",
        url:"/player/saveTime.php",
        data:{book:idBook,time:audio[0].currentTime},
        success: function(data){
            if (data !=true){
                var date = new Date;
                date.setDate(date.getDate() + 90);
                document.cookie = "idBook="+idBook+"; path=/; expires=" + date.toUTCString();
                document.cookie = "bookURL="+bookURL+"; path=/; expires=" + date.toUTCString();
                document.cookie = "time="+audio[0].currentTime+"; path=/; expires=" + date.toUTCString();
            }
        }
    })
}
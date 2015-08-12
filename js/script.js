$changeHistory = true,
scrollPage = 2,
scrollLoad = false,
inProgress = false,
typeLoad = '',
dataLoad ='',
eof = true;

function showContent(typeContent,dataContent,page,bookId){
    inProgress = true;
    var query = Array; query[typeContent] = dataContent; query['page'] = page;
    if (bookId){
        post = $('.post_box[id='+bookId+']');
        $(post).text('').append('<img id="loading" src="/images/loading.gif?3">');
        query['onPageLoad'] = true;
        $changeHistory = false;

    }else{
        if (scrollLoad) scrollPage += 1; else{scrollPage = 2; $(window).scrollTop(0)}
        if (!page) $('#content_column').text('').append('<img id="loading" src="/images/loading.gif?3"><div class="cleaner" id="cleaner"></div>');
            else $('#content_column').append('<img id="loading-page" src="/images/loading.gif?3"><div class="cleaner" id="cleaner"></div>');
        typeLoad = typeContent;
        dataLoad = dataContent;
    }
    $.ajax({type:'POST', url:'/showContent.php', data: query}).done(function(data) {
        data = JSON.parse(data);
        if ($changeHistory){
            if (typeContent!="")history.pushState(null, data['title'], '/' + typeContent + '/' + dataContent);
            else history.pushState(null, data['title'], '/');
            document.title = data['title'];
            $("meta[name='description']").attr('content', data['description']);
            $("meta[name='keywords']").attr('content', data['keywords']);
        }
        $('#loading, #cleaner, #loading-page').remove();
        inProgress = false;    scrollLoad = false;    $changeHistory = true;
        if (bookId){
            post.append(data['content']).addClass('midle-post');
        } else{
            eof=data['eof'];
            $('#content_column').append(data['content']);
        }
        delete query[typeContent]; delete query['page']; delete query['onPageLoad']
    });
}

function search(){
    $changeHistory = false;
    showContent('search',$('#searchInput').val(),'')
}

function ShowRegForm(){
    alert('На данный момент сайт работает в тестовом режиме, ориентировочное время реализации всего функционала сайта конец лета, начало осени');
}

$(window).on('popstate',function(){
    $split = document.URL.split('/');
    $changeHistory = false;
    showContent($split[3],$split[4],'');
});

function vote(bookId,value){
    $.ajax({method:"POST", url:"/vote.php", data:{bookId:bookId,value:value}}).done(function(data){
        alert(data);
    })
}

$(document).ready(function(){
    typeLoad=document.URL.split('/')[3];
    dataLoad=document.URL.split('/')[4];
    if((typeLoad == 'author' && !dataLoad) || (typeLoad =='voice' && !dataLoad) || (typeLoad=='publisher' && !dataLoad)) eof = true;

    $(window).scroll(function(){
        if($(window).scrollTop() + $(window).height() >= $(document).height() - 1000 && !inProgress && typeLoad!='book' && !eof) {
            scrollLoad = true;
            $changeHistory = false;
            showContent(typeLoad,dataLoad,scrollPage);
        }
    });
    $("#password").keypress(function(e){
            if(e.keyCode==13){
            $userLogin=document.getElementById("login").value;
            $userPassword=document.getElementById("password").value;
            login($userLogin,$userPassword);
        }
    });
    $("#searchInput").keypress(function(e){
        if(e.keyCode==13){
            search();
        }
    });
});
function clearText(field)
{
    if (field.defaultValue == field.value) field.value = '';
    else if (field.value == '') field.value = field.defaultValue;

}
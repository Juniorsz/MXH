$('.comment-value').keypress(function(e) {
    if (e.which == 13) {
      $('.insert-comment').click();
    }
  });
function closeSearch(){
    $('.result').hide();
}
function showNoti(){
    $('.menu-noti-child').toggle();
    $.ajax({
        url : '/MXH/Controller/controller.php',
        method: 'POST',
        data:
        {
           action:'clearNoti'
        },
        success:function(data){
            $('.uk-badge').html(0);
        }
    })
}
$('.main-container').click(function(){
    $('.menu-noti-child').hide();
    $('.result').hide();
});
  function displayData(){
    $.ajax({
      url : '/MXH/Controller/controller.php',
      method: 'POST',
      data:
      {
         action:'displayData'
      },
      success:function(data){
          $('.main-newsfeed').html(data);
      }
    })
 }
 function displayDataId(id){
    $.ajax({
      url : '/MXH/Controller/controller.php',
      method: 'POST',
      data:
      {
         action:'displayDataId',
         postId:id
      },
      success:function(data){
          $('.main-newsfeed').html(data);
      }
    })
 }
 function post(){
        var content = $('.status-posts').val();
        if(!content.trim() || content == ''){
            return false;
        }
        else{
            $.ajax({
                url : '/MXH/Controller/controller.php',
                method: 'POST',
                data:
                {
                    action:'post',
                    content:content,
                },
                beforeSend:function(){
                    $('.up-posts').html("Loading <i class='fas fa-spinner fa-spin'></i>");
                },
                success:function(data){
                    $('.up-posts').html("Loading <i class='fas fa-spinner fa-spin'></i>");
                    $('.status-posts').val('');
                    uploadPhoto();
                    setTimeout(function(){
                        displayData();
                        $('.up-posts').html("ĐĂNG <i class='fas fa-paper-plane'></i>");
                    },2000);
                }
            });
        }
 }
$('.icon-up').click(function(){
    $('.upload-photo').click();
})
function uploadPhoto(){
    var file_data = $('#file').prop('files')[0];
    var form_data = new FormData();
    form_data.append('file', file_data);
       $.ajax({
            url: '../Controller/upload.php',
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            success: function (res) {
            }
        });
}
function react(element){
    $(element).toggleClass('bounceIn');
    var id = $(element).val();
    $.ajax({
        url:'../Controller/controller.php',
        method:'POST',
        data:{
            id:id,
            action:'react'
        },
        success:function(data){
            displayData();
        }
    })
}
function reactId(element){
    $(element).toggleClass('bounceIn');
    var id = $(element).val();
    $.ajax({
        url:'../Controller/controller.php',
        method:'POST',
        data:{
            id:id,
            action:'react'
        },
        success:function(data){
            displayDataId(id);
        }
    })
}
function noti(){
    $.ajax({
        url:'../Controller/controller.php',
        method:'POST',
        data:{
            action:'noti'
        },
        success:function(data){
            $('.notifis').html(data);
        }
    })
}
setInterval(function(){
    noti();
},60000);
function deletePost(element){
   var id = $(element).val();
   if(confirm('Xóa?') == true){
       alert('Chức năng này đang bảo trì');
   }
}
function upComment(element){
    var id = $(element).val();
    var x = '.' + id;
    var content = $(x).val();
    var y = '.cmt-' + id;
    $.ajax({
        method:'POST',
        url:'/MXH/Controller/controller.php',
        data:{
            action:'comment',
            content:content,
            id:id
        },
        success:function(data){
            displayData();
            setTimeout(function(){
                $(y).show();
            },300);
        }
    })
 }
 function upCommentPost(element){
    var id = $(element).val();
    var x = '.' + id;
    var content = $(x).val();
    var y = '.cmt-' + id;
    $.ajax({
        method:'POST',
        url:'/MXH/Controller/controller.php',
        data:{
            action:'comment',
            content:content,
            id:id
        },
        success:function(data){
            displayDataId(id);
            setTimeout(function(){
                $(y).show();
            },300);
        }
    })
 }
 function showComment(element){
    var id = $(element).val();
    var x = '.cmt-' + id;
    var z = '.insertcmt-' + id;
    $(x).toggle();
    $(element).toggleClass('bounceIn');
    $(z).toggle();
 }
function moreComment(element){
    var id = $(element).val();
    $('.full-cmt-btn').click();
    $.ajax({
        url:'/MXH/Controller/controller.php',
        method:'POST',
        data:{
            action:'fullComment',
            id:id
        },
        success:function(data){
            $('.show-commented').html(data);
            $('.sk-cube-grid').hide();
        }
    })
}
function postAction(element)
{
    alert('1');
    id = $(element).val();
}
function follow(element){
    var id_followed = $(element).val();
    $.ajax({
      url:'../Controller/controller.php',
      method:'POST',
      data:{
         id_followed:id_followed,
         action:'follow'
      },
      success:function(data){
         updateFollow();
         noti();
      }
    })
}
function unfollow(element){
    var id_followed = $(element).val();
    $.ajax({
      url:'../Controller/controller.php',
      method:'POST',
      data:{
         id_followed:id_followed,
         action:'unfollow'
      },
      success:function(data){
         updateFollow();
         noti();
      }
    })
}
function updateFollow(){
    $.ajax({
        url:'../Controller/controller.php',
        method:'POST',
        data:{
           action:'updateFollow'
        },
        success:function(data){
           $('.suggest-follow').html(data);
        }
      })
}
function moreFollow(){
    $.ajax({
        url:'../Controller/controller.php',
        method:'POST',
        data:{
           action:'moreFollow'
        },
        success:function(data){
           $('.suggest-follow').html(data);
        }
      })
}
  $('.logout-btn').click(function(){
      $('.logout-btn').click();
  })
  function readURL(input) {

    if (input.files && input.files[0]) {
      var reader = new FileReader();
  
      reader.onload = function(e) {
        $('#blah').attr('src', e.target.result);
      }
  
      reader.readAsDataURL(input.files[0]);
    }
  }
  function load_data(query){
  $.ajax({
   url:"../Controller/controller.php",
   method:"POST",
   cache:false,
   data:{query:query,action:'search'},
   success:function(data)
   {
    $('.result').show();
    $('.result-list').html(data);
   }
  });
 }
 function load_hashtag(query){
    $.ajax({
     url:"../Controller/controller.php",
     method:"POST",
     cache:false,
     data:{query:query,action:'hashtag'},
     beforeSend:function(){
        $('.search-btn').html('<i class="fas fa-spinner fa-spin"></i>');
     },
     success:function(data)
     {
        $('.search-btn').html('<i class="fas fa-search"></i>');
        $('.result').show();
        $('.result-list').html(data);
     }
    });
   }
 $('.search-ipt').keyup(function(){
  var search = $('.search-ipt').val();
  if(!search.trim() || search == ''){
      $('.result').hide();
      return false;
  }
  else if(search.charAt(0) == '#'){
     load_hashtag(search);
  }
  else{
      load_data(search);
  }
 });
 $('.noti-follow').click(function(){
     setTimeout(function(){
        $('.menu-noti-child').show();
     },500);
 })
 $('.icon').click(function(){
     $('.emojiPickerIcon').click();
 })
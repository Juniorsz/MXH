<div class="uk-width-1-3@m">
           <div class="uk-card uk-card-default uk-card-body uk-clone uk-fixed">
             <h5>Có thể bạn biết?</h5>
             <div class="suggest-follow">
                <?php $data->followTable(); ?>
             </div>
             <button class="view-more-suggest" onclick="moreFollow()" style="border:none;background:transparent;color:#888da8;float:right;">Xem thêm <i class="fas fa-redo-alt"></i></button>
           </div>
           <div class="uk-card uk-card-default uk-card-body uk-clone uk-fixed" uk-sticky="offset: 10; bottom: #top">
             <h5 style="margin-bottom:5px;">Trending</h5>
             <div class="top-trending">
               <?php $data->trending(); ?>
             </div>
           </div>
</div>
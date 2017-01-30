<div id="wall-fullscreen" class="wall-photo-container reveal" data-reveal data-close-on-click="true">
    <img src="">
    <div class="row background-light-gray row-padding">
        <div class="small-12 medium-9 columns" id="wall-fullscreen-reported"></div>
        <div class="small-12 medium-3 columns text-right" >
            <span class="pointer small badge primary" id="wall-photo-like" @click="like()">
                <i class="fa fa-thumbs-up"></i>@{{ likes }}
            </span>
            <span class="wall-photo-view-map pointer small">View on map</span>
        </div>
    </div>
    <div class="row row-padding">
        <div class="small-12 columns">
            <p id="wall-fullscreen-caption"></p>
        </div>
    </div>
    <div class="row">
        <div class="small-12 columns">
            <button class="button secondary small" data-close>Close</button>
        </div>
    </div>
    <div id="wall-fullscreen-id" class="hide"></div>
</div>
<div id="wall-content">
    <photo-wall></photo-wall>
</div>
@include('page.footer')
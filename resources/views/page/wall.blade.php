<div id="wall-fullscreen" class="wall-photo-container reveal" data-reveal data-close-on-click="true">
    <img src="">
    <div class="row background-light-gray row-padding">
        <div class="small-12 medium-9 columns" id="wall-fullscreen-reported"></div>
        <div class="small-12 medium-3 columns text-right">
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
</div>
<div id="wall-content">
    <div class="row">
        <div id="wall-photos" class="small-12 large-9 large-centered columns">
            <template v-for="photo in photos">
                <photo :id="photo.id"
                       :img_location="photo.img_location"
                       :thumbnail="photo.thumbnail"
                       :lat="photo.lat"
                       :lng="photo.lng"
                       :caption="photo.caption"
                       :reported_by="photo.reported_by"
                       :created_at="photo.created_at">
                </photo>
            </template>
        </div>
    </div>
    <div class="row">
        <div class="small-uncentered large-centered columns">
            <button class="button float-center" id="wall-load-more" v-if="hasMore">Load more tent site photos</button>
        </div>
    </div>
</div>
@include('page.footer')
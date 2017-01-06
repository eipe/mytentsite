<div id="wall-fullscreen" class="wall-photo-container reveal" data-reveal data-close-on-click="true">
    <div class="wall-photo-controllers">
        <i class="wall-photo-view-map wall-photo-enlarged fa fa-map-marker"
           title="View photo on map"></i>
        <i class="wall-photo-close fa fa-times" data-close title="Close"></i>
    </div>
    <img src="">
    <p id="wall-fullscreen-caption"></p>
    <p id="wall-fullscreen-reported"></p>
</div>
<div id="wall-photos">
    <template v-for="photo in photos">
        <photo :id="photo.id"
               :img_location="photo.img_location"
               :thumbnail="photo.thumbnail"
               :latitude="photo.latitude"
               :longitude="photo.longitude"
               :caption="photo.caption"
               :reported_by="photo.reported_by"
               :created_at="photo.created_at">
        </photo>
    </template>
</div>
<div class="row is-hidden">
    <button class="button float-center" id="wall-load-more">Load more tent site photos</button>
</div>
@include('page.footer')
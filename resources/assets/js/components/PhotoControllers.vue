<template>
    <div class="photo-controllers">
        <i class="photo-view-map fa fa-map-marker" title="View photo on map" @click="viewOnMap"></i>
    </div>
</template>
<script>

    import Map from '../pages/Map.vue';

    export default {
        data() {
            return {
            }
        },
        components: {
            Map
        },
        methods: {
            viewOnMap(event) {
                var $photoContainer = $(event.target).closest(".photo-container"),
                    photoLatitude = $photoContainer.data("photo-latitude"),
                    photoLongitude = $photoContainer.data("photo-longitude");
                event.stopPropagation();
                this.$router.push({ path: 'map', params: {latitude : photoLatitude, longitude: photoLongitude}});
            }
        }
    }
</script>

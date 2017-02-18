<template>
    <div>
        <div class="row">
            <div class="photo-wall small-12 large-9 large-centered columns">
                <template v-for="photo in photos">
                    <photo :id="photo.id"
                    :img_location="photo.img_location"
                    :thumbnail="photo.thumbnail"
                    :lat="photo.lat"
                    :lng="photo.lng"
                    :caption="photo.caption"
                    :reported_by="photo.reported_by"
                    :created_at="photo.created_at"
                    :likes="photo.likes">
                    </photo>
                </template>
            </div>
        </div>
        <div class="row">
            <div class="small-uncentered large-centered columns">
                <button class="button float-center" v-if="hasMore" @click="loadMore">Load more tent site photos</button>
                <button class="button disabled float-center" v-else>All tent site photos are loaded</button>
            </div>
        </div>
    </div>
</template>
<script>
    import Photo from './Photo.vue';

    export default{
        data() {
            return {
                showControllers: {
                    type: Boolean,
                    default: true
                },
                photos: this.$store.state.tentSites.data,
                hasMore: this.$store.state.tentSites.hasMore
            }
        },
        created() {
            this.$store.commit('loadMoreTentSites');
        },

        methods: {
            loadMore() {
                this.$store.commit('loadMoreTentSites');
            }
        },
        components:{
            Photo, Map
        }
    }
</script>

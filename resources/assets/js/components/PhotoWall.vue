<template>
    <div>
        <div class="columns is-multiline is-tablet has-tablet-no-padding">
            <template v-for="photo in photos">
                <photo class="column is-one-third" :id="photo.id"
                :img_location="photo.img_location"
                :thumbnail="photo.thumbnail"
                :lat="photo.lat"
                :lng="photo.lng"
                :caption="photo.caption"
                :reported_by="photo.reported_by"
                :created_at="photo.created_at"
                :bookmarks="photo.bookmarks">
                </photo>
            </template>
        </div>
        <div class="container has-text-centered">
            <button class="button is-info" v-if="hasMore" @click="loadMore"
                    v-bind:class="{ 'is-loading' : isLoadingMore }">Load more tent site photos</button>
            <button class="button" disabled v-else>All tent site photos are loaded</button>
        </div>
    </div>
</template>
<script>
    import Photo from "./Photo.vue";
    import Locate from "../pages/Locate.vue";

    export default {
        name: "PhotoWall",
        data() {
            return {
                showControllers: {
                    type: Boolean,
                    default: true
                },
                isLoadingMore: false
            }
        },
        computed: {
            hasMore() {
                return this.$store.state.tentSites.hasMore;
            },
            photos() {
                return this.$store.state.tentSites.data;
            }
        },
        created() {
            if(typeof this.photos === typeof undefined || this.photos.length === 0) {
                this.$store.commit("loadMoreTentSites");
            }
        },
        methods: {
            loadMore() {
                this.isLoadingMore = true;
                this.$store.commit("loadMoreTentSites");
            }
        },
        components:{
            Photo, Locate
        },
        watch: {
            photos() {
                this.isLoadingMore = false;
            }
        }
    }
</script>

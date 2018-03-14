<template>
    <div>
        <section class="hero">
            <div class="hero-body">
                <div class="container">
                    <h2 class="title">Your bookmarks - {{ tentSiteIds.length}} in total</h2>
                    <button class="button" @click.prevent="loadTentSites"
                            v-if="!isLoaded" v-bind:class="{ 'is-loading disabled' : isLoading }">Try again</button>
                    <div class="columns is-multiline is-mobile">
                        <div class="column is-2" v-for="tentSite in tentSites">
                            <img :src="tentSite.thumbnail" class="is-clickable" @click="openGallery(tentSite)" />
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <photo-gallery :tent-sites="tentSites" ref="gallery"></photo-gallery>
    </div>
</template>
<script>

    import Photo from "../../components/Photo.vue"
    import PhotoGallery from "../../components/PhotoGallery.vue"

    export default {
        name: "User-bookmarks",
        data() {
            return {
                tentSiteIds: [],
                isLoaded: false,
                isLoading: false,
            }
        },
        computed: {
            tentSites() {
                let me = this;
                return me.tentSiteIds.map(function (id) {
                    if(me.$store.state.tentSites.hasOwnProperty(id)) {
                        return me.$store.state.tentSites[id];
                    }
                });
            }
        },
        methods: {
            openGallery(tentSite) {
                this.$refs.gallery.openGallery(tentSite);
            },
            addTentSite(tentSite) {
                this.tentSites.push(tentSite);
            },
            loadBookmarks() {
                let me = this;
                me.isLoading = true;
                Vue.axios.get("bookmarks").then(function success(success) {
                    if(typeof success.data !== typeof undefined) {
                        success.data.data.forEach(function (tentSite) {
                            // Todo: Fix API and provide correct path to images
                            tentSite["thumbnail_location"] = '/storage/photos/tentsite_thumbnails/' + tentSite.img_location;
                            tentSite["img_location"] = '/storage/photos/tentsites/' + tentSite.img_location;
                            tentSite["bookmarks"] = [];
                            me.$store.dispatch("addTentSite", tentSite);
                            me.tentSiteIds.push(tentSite["id"]);
                        });
                        me.isLoaded = true;
                        me.isLoading = false;
                    }
                }).catch(function error(error) {
                    me.isLoading = false;
                    me.isLoaded = false;
                    me.$store.dispatch("displayError", "Could not load your bookmarks. <br>Please try again later");
                });
            }
        },
        created() {
           this.loadBookmarks();
        },
        components: {
            PhotoGallery,
            Photo
        }
    }
</script>

<template>
    <div>
        <div v-if="tentSites">
            <section class="hero">
                <div class="hero-body">
                    <div class="container">
                        <h2 class="title">Your bookmarks</h2>
                        <button class="button" @click.prevent="loadTentSites"
                                v-if="!isLoaded" v-bind:class="{ 'is-loading disabled' : isLoading }">Try again</button>
                        <div class="columns is-multiline is-mobile">
                            <template v-for="tentSite in bookmarkedTentSites">
                                <photo class="column is-2" :id="tentSite.id"
                                       :img_location="tentSite.img_location"
                                       :thumbnail="tentSite.thumbnail"
                                       :lat="tentSite.lat"
                                       :lng="tentSite.lng"
                                       :caption="tentSite.caption"
                                       :reported_by="tentSite.reported_by"
                                       :created_at="tentSite.created_at"
                                       :bookmarks="tentSite.bookmarks"
                                       :approved="tentSite.approved"
                                       :showDetails="false"
                                       :showControllers="false">
                                </photo>
                            </template>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</template>
<script>

    import Photo from "../../components/Photo.vue";

    export default {
        name: "User-bookmarks",
        data() {
            return {
                tentSites: [],
                isLoaded: false,
                isLoading: false,
            }
        },
        methods: {
            addTentSite(tentSite) {
                this.tentSites.push(tentSite);
            },
            loadTentSites() {
                let me = this;
                me.tentSites = [];
                me.isLoading = true;
                Vue.axios.get("bookmarks").then(function success(success) {
                    if(typeof success.data !== typeof undefined) {
                        success.data.data.forEach(function (photo) {
                            photo.thumbnail = '/storage/photos/tentsite_thumbnails/' + photo.img_location;
                            me.addTentSite(photo);
                        });
                        me.isLoaded = true;
                        me.isLoading = false;
                    }
                }).catch(function error(error) {
                    me.isLoading = false;
                    me.isLoaded = false;
                    me.tentSites = [];
                    me.$store.dispatch("displayError", "Could not load your bookmarks. <br>Please try again later");
                });
            }
        },
        computed: {
            bookmarkedTentSites() {
                return this.tentSites;
            }
        },
        created() {
           this.loadTentSites();
        },
        components: {
            Photo
        }
    }
</script>

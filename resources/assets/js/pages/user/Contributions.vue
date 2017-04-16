<template>
    <div>
        <div v-if="tentSites">
            <section class="hero">
                <div class="hero-body">
                    <div class="container">
                        <h2 class="title">Your contributions ({{ tentSites.length }})</h2>
                        <button class="button" @click.prevent="loadTentSites"
                                v-if="!isLoaded" v-bind:class="{ 'is-loading disabled' : isLoading }">Try again</button>
                        <div class="columns is-multiline is-mobile">
                            <template v-for="tentSite in tentSites">
                                <photo class="column is-2" :id="tentSite.id"
                                       :img_location="tentSite.img_location"
                                       :thumbnail="tentSite.thumbnail"
                                       :lat="tentSite.lat"
                                       :lng="tentSite.lng"
                                       :caption="tentSite.caption"
                                       :reported_by="tentSite.reported_by"
                                       :created_at="tentSite.created_at"
                                       :likes="tentSite.likes" :approved="tentSite.approved" :showDetails="true">
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
        name: "User-contributions",
        data() {
            return {
                tentSites: [],
                isLoaded: false,
                isLoading: false
            }
        },
        methods: {
            loadTentSites() {
                let me = this;
                me.tentSites = [];
                me.isLoading = true;
                axios.get("usersites").then(function success(success) {
                    if(typeof success.data !== typeof undefined) {
                        success.data.data.forEach(function (photo) {
                            me.tentSites.push({
                                id: photo["id"],
                                reported_by: photo["reported_by"],
                                lat: photo["latitude"],
                                lng: photo["longitude"],
                                likes: photo["likes"],
                                img_location: "storage/photos/tentsites/" + photo["img_location"],
                                thumbnail: "storage/photos/tentsite_thumbnails/" + photo["thumbnail_location"],
                                caption: photo["caption"],
                                created_at: photo["created_at"],
                                updated_at: photo["updated_at"],
                                approved: photo["approved"],
                                comments: []
                            });
                        });
                        me.isLoaded = true;
                        me.isLoading = false;
                    }
                }).catch(function error(error) {
                    me.isLoading = false;
                    me.isLoaded = false;
                    me.tentSites = [];
                    me.$store.dispatch("displayError", "Could not load your contributions. <br>Please try again later");
                });
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

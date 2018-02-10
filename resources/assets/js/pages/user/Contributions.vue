<template>
    <div>
        <div v-if="tentSites">
            <section class="hero">
                <div class="hero-body">
                    <div class="container">
                        <h2 class="title">Your contributions <small>- displaying {{ tentSitesCount }}</small></h2>
                        <div class="content">
                            <span class="tag is-light is-clickable tooltip is-tooltip-top"
                                  data-tooltip="Click to toggle filter"
                                  @click="toggleFilter('approved')"
                                  v-bind:class="{ 'is-success' : filter.approved }">
                                {{ count.approved }} Approved
                            </span>
                            <span class="tag is-light is-clickable tooltip is-tooltip-top"
                                  data-tooltip="Click to toggle filter"
                                  @click="toggleFilter('denied')"
                                  v-bind:class="{ 'is-warning' : filter.denied }">
                                {{ count.denied }} Not approved
                            </span>
                            <span class="tag is-light is-clickable tooltip is-tooltip-top"
                                  data-tooltip="Click to toggle filter"
                                  @click="toggleFilter('waitingApproval')"
                                  v-bind:class="{'is-info' : filter.waitingApproval }">
                                {{ count.waitingApproval }} Waiting for approval
                            </span>
                        </div>
                        <button class="button" @click.prevent="loadTentSites"
                                v-if="!isLoaded" v-bind:class="{ 'is-loading disabled' : isLoading }">Try again</button>
                        <div class="columns is-multiline is-mobile">
                            <template v-for="tentSite in filteredTentSites">
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
                                       :showDetails="true"
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
        name: "User-contributions",
        data() {
            return {
                tentSites: [],
                isLoaded: false,
                isLoading: false,
                count: {
                    approved: 0,
                    denied: 0,
                    waitingApproval: 0
                },
                filter: {
                    approved: true,
                    denied: true,
                    waitingApproval: true
                }
            }
        },
        computed: {
            activeFilters() {
                return {
                    "approved": this.filter.approved,
                    "denied": this.filter.denied,
                    "waitingApproval": this.filter.waitingApproval
                };
            },
            filteredTentSites() {
                let me = this,
                    filtered = [];
                me.tentSites.forEach(function(tentSite) {
                    if(me.activeFilters[tentSite.state]) {
                        filtered.push(tentSite);
                    }
                });
                return filtered;
            },
            tentSitesCount() {
                return this.filteredTentSites.length + " of " + this.tentSites.length;
            }
        },
        methods: {
            toggleFilter(key) {
                this.filter[key] = (this.filter[key] ? false : true);
            },
            addTentSite(tentSite) {
                this.tentSites.push(tentSite);
                if(tentSite.approved > 0) {
                    this.count.approved++;
                } else if(tentSite.approved < 0) {
                    this.count.denied++;
                } else {
                    this.count.waitingApproval++;
                }
            },
            loadTentSites() {
                let me = this;
                me.tentSites = [];
                me.isLoading = true;
                Vue.axios.get("usersites").then(function success(success) {
                    if(typeof success.data !== typeof undefined) {
                        success.data.data.forEach(function (photo) {

                            let approved = parseInt(photo["approved"]),
                                state = 'waitingApproval';

                            if(approved > 0) {
                                state = 'approved';
                            } else if(approved < 0) {
                                state = 'denied';
                            }

                            me.addTentSite({
                                id: photo["id"],
                                reported_by: photo["reported_by"],
                                lat: photo["latitude"],
                                lng: photo["longitude"],
                                bookmarks: photo["likes"],
                                img_location: photo["img_location"],
                                thumbnail: photo["thumbnail_location"],
                                caption: photo["caption"],
                                created_at: photo["created_at"],
                                updated_at: photo["updated_at"],
                                approved: approved,
                                state: state,
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

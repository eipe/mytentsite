<template>
    <div>
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
                        <div class="column is-2" v-for="tentSite in filteredTentSites">
                            <img :src="tentSite.thumbnail" class="is-clickable" @click="openGallery(tentSite)" />
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <photo-gallery :tent-sites="filteredTentSites" ref="gallery"></photo-gallery>
    </div>
</template>
<script>

    import Photo from "../../components/Photo.vue"
    import PhotoGallery from "../../components/PhotoGallery.vue"

    export default {
        name: "User-contributions",
        data() {
            return {
                tentSiteIds: [],
                tentSiteStates: {},
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
            tentSites() {
                let me = this;
                return me.tentSiteIds.map(function (id) {
                    if(me.$store.state.tentSites.hasOwnProperty(id)) {
                        return me.$store.state.tentSites[id];
                    }
                });
            },
            filteredTentSites() {
                let me = this,
                    filtered = [];

                me.tentSites.forEach(function(tentSite) {
                    if(me.activeFilters[me.tentSiteStates[tentSite.id]]) {
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
            openGallery(tentSite) {
                this.$refs.gallery.openGallery(tentSite);
            },
            toggleFilter(key) {
                this.filter[key] = (this.filter[key] ? false : true);
            },
            loadTentSites() {
                let me = this;
                me.isLoading = true;
                Vue.axios.get("usersites").then(function success(success) {
                    if(typeof success.data !== typeof undefined) {
                        success.data.data.forEach(function (tentSite) {
                            let approved = parseInt(tentSite["approved"]);

                            if(approved > 0) {
                                me.tentSiteStates[tentSite["id"]] = 'approved';
                                me.count.approved++;
                            } else if(approved < 0) {
                                me.tentSiteStates[tentSite["id"]] = 'denied';
                                me.count.denied++;
                            } else {
                                me.tentSiteStates[tentSite["id"]] = 'waitingApproval';
                                me.count.waitingApproval++;
                            }

                            me.$store.dispatch("addTentSite", tentSite);
                            me.tentSiteIds.push(tentSite.id);
                        });
                        me.isLoaded = true;
                        me.isLoading = false;
                    }
                }).catch(function error(error) {
                    me.isLoading = false;
                    me.isLoaded = false;
                    me.$store.dispatch("displayError", "Could not load your contributions. <br>Please try again later");
                });
            }
        },
        created() {
           this.loadTentSites();
        },
        components: {
            PhotoGallery,
            Photo
        }
    }
</script>

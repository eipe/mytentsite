<template>
    <div>
        <section class="section">
            <div class="container">
                <div class="content">
                    <h2 class="title">
                        {{ $t('tentSite.contributionCount', [this.filteredTentSites.length, this.tentSites.length])}}
                    </h2>
                    <span class="tag is-light is-clickable tooltip is-tooltip-top"
                          :data-tooltip="$t('action.clickToToggle')"
                          @click="toggleFilter('approved')"
                          v-bind:class="{ 'is-success' : filter.approved }">
                        {{ count.approved }} {{ $t('tentSite.state.approved')}}
                    </span>
                    <span class="tag is-light is-clickable tooltip is-tooltip-top"
                          :data-tooltip="$t('action.clickToToggle')"
                          @click="toggleFilter('denied')"
                          v-bind:class="{ 'is-warning' : filter.denied }">
                        {{ count.denied }} {{ $t('tentSite.state.notApproved')}}
                    </span>
                    <span class="tag is-light is-clickable tooltip is-tooltip-top"
                          :data-tooltip="$t('action.clickToToggle')"
                          @click="toggleFilter('waitingApproval')"
                          v-bind:class="{'is-info' : filter.waitingApproval }">
                        {{ count.waitingApproval }} {{ $t('tentSite.state.waitingApproval')}}
                    </span>
                    <span class="tag is-light is-clickable tooltip is-tooltip-top"
                          :data-tooltip="$t('action.clickToToggle')"
                          @click="toggleFilter('deleted')"
                          v-bind:class="{'is-danger' : filter.deleted }">
                        {{ count.deleted }} {{ $t('tentSite.state.deleted')}}
                    </span>
                </div>
                <div class="content">
                    <button class="button" @click.prevent="loadTentSites"
                        v-if="!isLoaded" v-bind:class="{ 'is-loading disabled' : isLoading }">
                        {{ $t('action.tryAgain')}}
                    </button>
                    <div class="columns is-multiline is-mobile">
                        <div class="column is-one-quarter" v-for="tentSite in filteredTentSites">
                            <div class="is-relative">
                                <span class="tag is-absolute is-top is-right"
                                      v-bind:class="stateClass(tentSite)"></span>
                                <img :src="tentSite.thumbnail"
                                     class="is-clickable"
                                     @click="openGallery(tentSite)"
                                     @error="handleImageError" />
                            </div>
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
                    waitingApproval: 0,
                    deleted: 0
                },
                filter: {
                    approved: true,
                    denied: true,
                    waitingApproval: true,
                    deleted: false
                }
            }
        },
        computed: {
            activeFilters() {
                return {
                    "approved": this.filter.approved,
                    "denied": this.filter.denied,
                    "waitingApproval": this.filter.waitingApproval,
                    "deleted": this.filter.deleted
                };
            },
            tentSites() {
                let me = this;
                return me.tentSiteIds.map((id) => {
                    if(me.$store.state.tentSites.hasOwnProperty(id)) {
                        return me.$store.state.tentSites[id];
                    }
                });
            },
            filteredTentSites() {
                let me = this,
                    filtered = [];

                me.tentSites.forEach((tentSite) => {
                    if(me.activeFilters[me.tentSiteStates[tentSite.id]]) {
                        filtered.push(tentSite);
                    }
                });
                return filtered;
            }
        },
        methods: {
            handleImageError(event) {
                event.target.src = '/images/500.png';
            },
            stateClass(tentSite) {
                let state = this.tentSiteStates[tentSite.id];
                if(state === 'deleted') {
                    return 'is-danger';
                } else if(state === 'approved') {
                    return 'is-success';
                } else if(state === 'denied') {
                    return 'is-warning';
                } else if(state === 'waitingApproval') {
                    return 'is-info';
                }
                return '';
            },
            openGallery(tentSite) {
                this.$refs.gallery.openGallery(tentSite);
            },
            toggleFilter(key) {
                this.filter[key] = (this.filter[key] ? false : true);
            },
            loadTentSites() {
                let me = this;
                me.isLoading = true;
                Vue.axios.get("usersites").then((success) => {
                    if(typeof success.data !== typeof undefined) {
                        success.data.data.forEach((tentSite) => {
                            let approved = parseInt(tentSite["approved"]);

                            if(tentSite["deleted_at"] !== null) {
                                me.tentSiteStates[tentSite["id"]] = 'deleted';
                                me.count.deleted++;
                            }
                            else if(approved > 0) {
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
                }).catch(() => {
                    me.isLoading = false;
                    me.isLoaded = false;
                    me.$store.dispatch(
                        "displayError",
                        me.$t(
                            'error.couldNotLoad',
                            [me.$tc('tentSite.contribution', 2).toLowerCase()]
                        )
                    );
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

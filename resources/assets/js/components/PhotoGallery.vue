<template>
    <div :data-tent-sites="tentSites" v-if="isActive">
        <div class="modal" v-bind:class="{ 'is-active' : isActive }">
            <div class="modal-background" @click="destroy"></div>
            <transition enter-active-class="animated zoomIn">
                <div v-show="isActive">
                    <transition enter-active-class="animated slideInDown">
                        <div class="modal-content is-fullheight is-marginless" v-if="activePage === 'photo'">
                            <div class="is-paddingless is-marginless">
                                <div class="card" id="photo-container">
                                    <div class="card-image">
                                        <figure class="image is-4by3">
                                            <img :src="activeTentSite.img_location" />
                                            <div class="modal-navigate modal-navigate-prev is-clickable"
                                                 @click.prevent="navigatePrev"></div>
                                            <div class="modal-navigate modal-navigate-next is-clickable"
                                                 @click.prevent="navigateNext"></div>
                                        </figure>
                                    </div>
                                    <div class="card-content">
                                        <div class="tags" v-if="tags">
                                            <div class="tag" v-for="tag in tags">
                                                {{ tag }}
                                            </div>
                                        </div>
                                        <div class="media">
                                            <div class="media-left">
                                                <p class="title is-4">{{ activeTentSite.reported_by_name }}</p>
                                                <p class="subtitle is-6">{{ activeTentSite.created_at }}</p>
                                            </div>
                                            <div class="media-right" v-if="shouldDisplayUserActions">
                                                <div class="buttons has-addons">
                                                    <a class="button is-white tooltip is-tooltip-top is-tooltip-multiline"
                                                       :data-tooltip="bookmarkTooltip"
                                                       @click="toggleBookmark">
                                                            <i class="fa"
                                                               :title="bookmarkTitle"
                                                               v-bind:class="bookmarkIcon"></i>&nbsp;{{ bookmarks.length }}
                                                    </a>
                                                    <a @click="toggleComment"
                                                              class="button is-white tooltip is-tooltip-top"
                                                              :data-tooltip="commentsTooltip">
                                                        <i class="fa fa-comments-o"></i>&nbsp;{{ comments.length }}
                                                    </a>
                                                    <a @click="viewOnMap" data-tooltip="View tent site on map"
                                                              class="button is-white tooltip is-tooltip-top">
                                                        <i class="fa fa-map-o"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="content">{{ activeTentSite.caption }}</div>
                                        <div class="content is-small" v-if="activeTentSite.taken_date">
                                            Photo was taken {{ activeTentSite.taken_date }}
                                        </div>
                                        <div v-if="isUserCreator && shouldDisplayUserActions">
                                            <a v-if="isDeleted" class="button is-success tooltip is-tooltip-top"
                                                  v-bind:class="{ 'is-loading' : isDeleting }"
                                                  data-tooltip="Click to restore this contribution"
                                                  @click="restoreTentSite(activeTentSite)">
                                                Restore
                                            </a>
                                            <a class="button is-danger tooltip is-tooltip-top"
                                                  v-bind:class="{ 'is-loading' : isDeleting }"
                                                  data-tooltip="Click to delete this contribution"
                                                  @click="deleteTentSite(activeTentSite)"
                                                  v-else>
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </transition>
                    <transition enter-active-class="animated slideInUp">
                        <div class="modal-card is-paddingless is-marginless" v-if="activePage === 'comments'">
                            <header class="modal-card-head">
                                <p class="modal-card-title">Comments ({{ comments.length }})</p>
                                <span class="button is-link is-pulled-right"
                                      @click.prevent="toggleActivePage()">Back</span>
                            </header>
                            <section class="modal-card-body">
                                <photo-comments :comments="comments" id="photo-comments" />
                            </section>
                            <footer class="modal-card-foot">
                                <photo-comment-form :tent-site="activeTentSite" :focus="focus"
                                                    v-if="isUserActionsAvailable" />
                            </footer>
                        </div>
                    </transition>
                    <button class="modal-close" @click="destroy"></button>
                </div>
            </transition>
        </div>
    </div>
</template>
<script>

    import PhotoComments from './PhotoComments.vue'
    import PhotoCommentForm from './PhotoCommentForm.vue'

    export default {
        name: "PhotoGallery",
        data() {
            return {
                isActive: false,
                isDeleting: false,
                focus: false,
                activePage: "photo",
                activeTentSite: null,
                hasUserBookmarked: false,
                order: {},
            }
        },
        props: {
            tentSites: {
                type: Array,
                required: true
            },
            userActions: {
                type: Boolean,
                default: true
            }
        },
        computed: {
            shouldDisplayUserActions() {
                return (this.userActions);
            },
            isDeleted() {
                if(!this.activeTentSite) {
                    return false;
                }
                return this.activeTentSite.deleted !== null;
            },
            isUserCreator() {
                if(!this.activeTentSite) {
                    return false;
                }
                return (this.$auth.user().id === this.activeTentSite.reported_by)
            },
            tags() {
                return this.activeTentSite.tags;
            },
            comments() {
                return this.activeTentSite.comments;
            },
            commentsTooltip() {
                if(this.isUserActionsAvailable) {
                    return "View comments and/or add your own";
                }
                return "View comments others have added. Login to add comments";
            },
            bookmarks() {
                if(this.activeTentSite.bookmarks) {
                    return this.activeTentSite.bookmarks;
                }
                return [];
            },
            bookmarkTooltip() {
                if(this.isUserActionsAvailable) {
                    return "Click to add/remove tent site bookmark. Review your bookmarks in user profile";
                }
                return "Login to bookmark your favorite tent sites";
            },
            bookmarkTitle() {
                if(this.isUserActionsAvailable) {
                    if(this.hasUserBookmarked) {
                        return 'Click to remove bookmark';
                    } else {
                        return 'Click to add bookmark';
                    }
                }
                return 'Number of users who has bookmarked this tent site';
            },
            bookmarkIcon() {
                let icon = '';
                if(this.hasUserBookmarked) {
                    icon = "fa-bookmark";
                } else {
                    icon = "fa-bookmark-o";
                }

                if(this.isUserActionsAvailable) {
                    icon += ' is-clickable';
                }
                return icon;
            },
            isUserActionsAvailable() {
                return this.$auth.check();
            }
        },
        created() {
            let me = this;
            window.addEventListener("keyup", function(event) {
                if(me.isActive) {
                    if(event.keyCode === 27) {
                        me.destroy();
                    } else if(event.keyCode === 37) {
                        me.navigatePrev();
                    } else if(event.keyCode === 39) {
                        me.navigateNext();
                    }
                }
            });
        },
        methods: {
            openGallery(tentSite) {
                this.activeTentSite = tentSite;
                this.isActive = true;
            },
            deleteTentSite(tentSite) {
                let me = this;
                me.isDeleting = true;
                Vue.axios.delete("delete/" + tentSite.id).then(success => {
                    me.isDeleting = false;
                    tentSite.deleted = success.data.data.deleted_at.date;
                    me.$store.dispatch("removeTentSite", tentSite);
                }, error => {
                    me.isDeleting = false;
                    me.$store.dispatch(
                        "displayError",
                        "Could not delete tent site, please try again. <br><br>If you are stuck with error messages, " +
                        "please contact us so we can help you.<br>See information page for contact information");
                });
            },
            restoreTentSite(tentSite) {
                let me = this;
                me.isDeleting = true;
                Vue.axios.post("restore/" + tentSite.id).then(success => {
                    me.isDeleting = false;
                    tentSite.deleted = null;
                    me.$store.dispatch("addTentSite", tentSite);
                }, error => {
                    me.isDeleting = false;
                    me.$store.dispatch(
                        "displayError",
                        "Could not restore tent site, please try again. <br><br>If you are stuck with error messages, " +
                        "please contact us so we can help you.<br>See information page for contact information");
                });
            },
            navigateNext() {
                if(this.activePage !== "photo") {
                    return;
                }
                let indexOfCurrent = this.tentSites.indexOf(this.activeTentSite);
                if(indexOfCurrent === this.tentSites.length-1) {
                    this.activeTentSite = this.tentSites[0];
                } else {
                    this.activeTentSite = this.tentSites[++indexOfCurrent];
                }
            },
            navigatePrev() {
                if(this.activePage !== "photo") {
                    return;
                }
                let indexOfCurrent = this.tentSites.indexOf(this.activeTentSite);
                if(indexOfCurrent === 0) {
                    this.activeTentSite = this.tentSites[this.tentSites.length-1];
                } else {
                    this.activeTentSite = this.tentSites[--indexOfCurrent];
                }
            },
            destroy() {
                this.focus = false;
                this.activePage = "photo";
                this.activeTentSite = null;
                this.isActive = false;
            },
            toggleBookmark() {
                if(this.isUserActionsAvailable && this.activeTentSite) {
                    let me = this;
                    if(this.hasUserBookmarked) {
                        this.$store.dispatch("removeBookmark", this.activeTentSite).then(function() {
                            let indexOfUser = me.activeTentSite.bookmarks.indexOf(me.$auth.user().id);
                            if(indexOfUser > -1) {
                                me.bookmarks.splice(indexOfUser, 1);
                            }
                            me.hasUserBookmarked = false;
                        });
                    } else {
                        this.$store.dispatch("addBookmark", this.activeTentSite).then(function() {
                            me.bookmarks.push(me.$auth.user().id);
                            me.hasUserBookmarked = true;
                        });
                    }
                }
            },
            toggleActivePage() {
                this.activePage = (this.activePage === "photo" ? "comments" : "photo");
            },
            viewOnMap() {
                if(this.activeTentSite) {
                    this.$store.dispatch("viewPhotoOnMap", this.activeTentSite);
                }
            },
            toggleComment() {
                this.toggleActivePage();
                this.focus = true;
            }
        },
        watch: {
            activeTentSite(tentSite) {
                if(!tentSite) {
                    return;
                }

                if(!this.$auth.user().id) {
                    this.hasUserBookmarked = false;
                } else {
                    this.hasUserBookmarked = (tentSite.bookmarks.indexOf(this.$auth.user().id) > -1);
                }

                if(tentSite.comments.length === 0) {
                    this.$store.dispatch("loadCommentsForTentSite", tentSite);
                }
            }
        },
        components: { PhotoComments, PhotoCommentForm }
    }
</script>

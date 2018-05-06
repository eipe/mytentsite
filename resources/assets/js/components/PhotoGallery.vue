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
                                                    <a @click="viewOnMap" :data-tooltip="$tc('tentSite.locateTentSite', 1)"
                                                              class="button is-white tooltip is-tooltip-top">
                                                        <i class="fa fa-map-o"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="content">{{ activeTentSite.caption }}</div>
                                        <div class="content is-small" v-if="activeTentSite.taken_date">
                                            {{ $t('photoTakenDate', [activeTentSite.taken_date])}}
                                        </div>
                                        <div v-if="isUserCreator && shouldDisplayUserActions">
                                            <a v-if="isDeleted" class="button is-success tooltip is-tooltip-top"
                                                  v-bind:class="{ 'is-loading' : isDeleting }"
                                                  :data-tooltip="$t('action.clickToRestore')"
                                                  @click="restoreTentSite(activeTentSite)">
                                                {{ $t('action.restore')}}
                                            </a>
                                            <a class="button is-danger tooltip is-tooltip-top"
                                                  v-bind:class="{ 'is-loading' : isDeleting }"
                                                  :data-tooltip="$t('action.clickToDelete')"
                                                  @click="deleteTentSite(activeTentSite)"
                                                  v-else>
                                                {{ $t('action.delete')}}
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
                                <p class="modal-card-title">{{ $tc('misc.comment', 2) }} ({{ comments.length }})</p>
                                <span class="button is-link is-pulled-right"
                                      @click.prevent="toggleActivePage()">
                                    {{ $t('action.back')}}
                                </span>
                            </header>
                            <section class="modal-card-body">
                                <photo-comments :comments="comments" id="photo-comments" />
                            </section>
                            <footer class="modal-card-foot">
                                <photo-comment-form
                                        :tent-site="activeTentSite"
                                        :focus="focus"
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
        i18n: {
            messages: {
                en: {
                    photoTakenDate: 'Photo taken {0}',
                    viewComments: 'View comments and/or add your own',
                    viewCommentsLoggedIn: 'View comments others have added. Login to add comments',
                    clickToAddBookmark: 'Click to add/remove tent site bookmark. Review your bookmarks in user profile',
                    loginToBookmark: 'Login to bookmark your favorite tent sites',
                    numberOfBookmarks: 'Number of users who has bookmarked this tent site',
                },
                no: {
                    photoTakenDate: 'Bilde ble tatt {0}',
                    viewComments: 'Se kommenter og/eller legg til dine egne',
                    viewCommentsLoggedIn: 'Se kommentarer brukere har lagt til. Logg inn for å legge til egne kommentarer',
                    clickToAddBookmark: 'Klikk for å legge til som bokmerke. De blir så tilgjenglig i din brukerprofil',
                    loginToBookmark: 'Logg inn for å bokmerke dine favoritt telt plasser',
                    numberOfBookmarks: 'Antall brukere som har bokmerket denne teltplassen',
                }
            }
        },
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
                    return this.$t('viewCommentsLoggedIn');
                }
                return this.$t('viewComments');
            },
            bookmarks() {
                if(this.activeTentSite.bookmarks) {
                    return this.activeTentSite.bookmarks;
                }
                return [];
            },
            bookmarkTooltip() {
                if(this.isUserActionsAvailable) {
                    return this.$t('clickToAddBookmark');
                }
                return this.$t('loginToBookmark');
            },
            bookmarkTitle() {
                if(this.isUserActionsAvailable) {
                    if(this.hasUserBookmarked) {
                        return this.$t('clickToRemove', [this.$tc('tentSite.bookmark', 1)]);
                    } else {
                        return this.$t('clickToAdd', [this.$tc('tentSite.bookmark, 1')]);
                    }
                }
                return this.$t('numberOfBookmarks');
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
                }, () => {
                    me.isDeleting = false;
                    me.$store.dispatch(
                        'displayError',
                        me.$t('error.couldNotDelete', [me.$tc('tentSite.tentSite', 1)])
                    );
                });
            },
            restoreTentSite(tentSite) {
                let me = this;
                me.isDeleting = true;
                Vue.axios.post("restore/" + tentSite.id).then(() => {
                    me.isDeleting = false;
                    tentSite.deleted = null;
                    me.$store.dispatch("addTentSite", tentSite);
                }, () => {
                    me.isDeleting = false;
                    me.$store.dispatch(
                        'displayError',
                        me.$t('error.couldNotRestore', [me.$tc('tentSite.tentSite', 1)])
                    );
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

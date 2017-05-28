/**
 * Created by Eivind Røe <eivindroe@gmail.com> on 06.08.2016.
 */
import PhotoGallery from './components/PhotoGallery.vue';
import Error from './components/Error.vue';
import routes from './routes.js'
import VueProgressiveImage from 'vue-progressive-image'
import Vuex from 'vuex'
import axios from 'axios'
import VueAxios from 'vue-axios'
import VueAuth from '@websanova/vue-auth'
import VueScrollTo from 'vue-scroll-to'
import VueAnalytics from 'vue-analytics'

Vue.router = new VueRouter({
    routes: routes.routes,
    linkActiveClass: "is-active",
    path: "/"
});

Vue.use(VueAxios, axios);
Vue.use(VueAuth, {
    auth: require('@websanova/vue-auth/drivers/auth/bearer.js'),
    http: require('@websanova/vue-auth/drivers/http/axios.1.x.js'),
    router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
    rolesVar: "roles",
    facebookOauth2Data: {
        clientId: "",
        redirect: function() {
            return this.options.getUrl() + "/auth/facebook"
        }
    },
    fetchData: {
        url: "/user",
        enabled: true
    },
    refreshData: {
        url: "/refresh",
        enabled: true,
        interval: 0
    },
});
Vue.use(VueProgressiveImage, {delay: 200});

Vue.use(VueAnalytics, {
    id: document.getElementById("analytics").innerHTML.toString(),
    router: Vue.router
});

// Set default base url used for all requests in application
Vue.axios.defaults.baseURL = "/api/";

function getPhotoIndex(state, photoId) {
    return state.tentSites.data.findIndex(function(photo) {
        if(photo.id === photoId) {
            return true;
        }
    });
}

function getPhotoByIndex(state, index) {
    if(typeof state.tentSites.data[index] !== typeof undefined) {
        return state.tentSites.data[index];
    }
    return null;
}

function getPhotoById(state, photoId) {
    let index = getPhotoIndex(state, photoId);
    if(index !== null) {
        return getPhotoByIndex(state, index);
    }
    return null;
}

// Define store
const store = new Vuex.Store({
    state: {
        tentSites: {
            apiUrl: "/tentsites",
            hasMore: true,
            firstPhotoId: null,
            lastPhotoId: null,
            data: []
        },
        gallery: {
            activePhoto: {},
            isActive: false
        },
        beta: false,
        error: null
    },
    mutations: {
        setActivePhoto(state, photo) {
            state.gallery.activePhoto = getPhotoById(state, photo.id);
            state.gallery.isActive = true;
        },
        galleryNavigateNext(state) {
            let currentIndex = getPhotoIndex(state, state.gallery.activePhoto.id),
                nextIndex = currentIndex+1;
            let photo = getPhotoByIndex(state, nextIndex);
            if(photo) {
                state.gallery.activePhoto = photo;
            }
        },
        galleryNavigatePrev(state) {
            let currentIndex = getPhotoIndex(state, state.gallery.activePhoto.id),
                nextIndex = currentIndex-1;
            let photo = getPhotoByIndex(state, nextIndex);
            if(photo) {
                state.gallery.activePhoto = photo;
            }
        },
        destroyGallery(state) {
            state.gallery.activePhoto = {};
            state.gallery.isActive = false;
        },
        loadMoreTentSites(state) {
            if(state.tentSites.hasMore) {
                Vue.axios.get(state.tentSites.apiUrl).then(function(response) {
                    let responseData = response.data.data;
                    if(responseData.next_page_url) {
                        state.tentSites.apiUrl = responseData.next_page_url;
                    } else {
                        state.tentSites.hasMore = false;
                    }

                    if(typeof responseData.data !== typeof undefined && responseData.data.length > 0) {
                        let lastNewPhotoId = null;
                        responseData.data.forEach(function (photo) {
                            if(!state.tentSites.firstPhotoId) {
                                state.tentSites.firstPhotoId = photo["id"];
                            }
                            state.tentSites.data.push({
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
                                approved: photo["approved"],
                                taken_date: photo["taken_date"]
                            });
                            lastNewPhotoId = photo["id"];
                        });
                        state.tentSites.lastPhotoId = lastNewPhotoId;
                    }
                }).catch(function(error) {
                    console.log(error);
                });
            }
        },
        addBookmark(state, id) {
            let photo = getPhotoById(state, id);
            if(photo && !photo.hasUserBookmarked) {
                photo.bookmarks += 1;
                photo.hasUserBookmarked = true;
                Vue.axios.post("/like/" + id);
            }
        },
        removeBookmark(state, id) {
            let photo = getPhotoById(state, id);
            if(photo && photo.hasUserBookmarked) {
                photo.bookmarks -= 1;
                photo.hasUserBookmarked = false;
            }
            Vue.axios.post("/unlike/" + id);
        },
        addCommentOnPhoto(state, data) {
            let photo = getPhotoById(state, data.id);
            if(photo) {
                photo.comments.push(data.comment);
            }
        },
        addCommentsOnPhoto(state, data) {
            let photo = getPhotoById(state, data.id);
            if(photo) {
                photo.comments = data.comments;
            }
        },
        setError(state, error) {
            state.error = error;
        }
    },
    actions: {
        openPhoto(state, photo) {
            state.commit("setActivePhoto", photo);
        },
        galleryNavigateNext(state) {
            state.commit("galleryNavigateNext");
        },
        galleryNavigatePrev(state) {
            state.commit("galleryNavigatePrev");
        },
        viewPhotoOnMap(state, photo) {
            state.commit("destroyGallery");
            Vue.router.push({
                path: "/locate",
                query: {
                    latitude: photo.lat,
                    longitude: photo.lng
                }
            });
        },
        destroyGallery(state) {
            state.commit("destroyGallery");
        },
        addBookmark(state, id) {
            state.commit("addBookmark", id);
        },
        removeBookmark(state, id) {
            state.commit("removeBookmark", id);
        },
        addCommentOnPhoto(state, data) {
            state.commit("addCommentOnPhoto", data);
        },
        addCommentsOnPhoto(state, data) {
            state.commit("addCommentsOnPhoto", data);
        },
        displayError(state, error) {
            state.commit("setError", error);
        }
    }
});

Vue.use(VueScrollTo);

new Vue({
    el: "#app",
    store,
    router: Vue.router,
    components: {
        PhotoGallery, Error
    },
    created() {
        let environment = document.getElementById("environment").innerHTML.toString();

        if(environment !== "production") {
            store.state.beta = true;
        }
    }
});
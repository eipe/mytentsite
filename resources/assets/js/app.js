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

// Set default base url used for all requests in application
Vue.axios.defaults.baseURL = "/api/";

// Define store
const store = new Vuex.Store({
    state: {
        tentSites: {
            apiUrl: "/tentsites",
            hasMore: true,
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
            state.gallery.activePhoto = photo;
            state.gallery.isActive = true;
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
                        responseData.data.forEach(function (photo) {
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
                                comments: []
                            });
                        });
                    }
                }).catch(function(error) {
                    console.log(error);
                });
            }
        },
        addBookmark(state, id) {
            let index = state.tentSites.data.findIndex(function(photo) {
                if(photo.id === id) {
                    return true;
                }
            });

            if(typeof state.tentSites.data[index] !== typeof undefined &&
                !state.tentSites.data[index].hasUserBookmarked) {
                state.tentSites.data[index].bookmarks += 1;
                state.tentSites.data[index].hasUserBookmarked = true;
                Vue.axios.post("/like/" + id + "/");
            }
        },
        removeBookmark(state, id) {
            let index = state.tentSites.data.findIndex(function(photo) {
                if(photo.id === id) {
                    return true;
                }
            });

            if(typeof state.tentSites.data[index] !== typeof undefined &&
                state.tentSites.data[index].hasUserBookmarked) {
                state.tentSites.data[index].bookmarks -= 1;
                state.tentSites.data[index].hasUserBookmarked = false;
            }
            Vue.axios.post("/unlike/" + id + "/");
        },
        addCommentOnPhoto(state, id, comment) {
            let index = state.tentSites.data.findIndex(function(photo) {
                if(photo.id === id) {
                    return true;
                }
            });

            if(typeof state.tentSites.data[index] !== typeof undefined &&
                state.tentSites.data[index].hasUserBookmarked) {
                state.tentSites.data[index].comments.push(comment);
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
        viewPhotoOnMap(state, photo) {
            state.commit("destroyGallery");
            Vue.router.push({
                path: "/map",
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
        addCommentOnPhoto(state, photoId, comment) {
            state.commit("addCommentOnPhoto", photoId, comment);
        },
        displayError(state, error) {
            state.commit("setError", error);
        }
    }
});

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
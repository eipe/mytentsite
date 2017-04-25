/**
 * Created by Eivind Røe <eivindroe@gmail.com> on 06.08.2016.
 */
import VueProgressiveImage from 'vue-progressive-image'
import Vuex from 'vuex'
import PhotoGallery from './components/PhotoGallery.vue';
import Error from './components/Error.vue';
import routes from './routes.js'

Vue.use(VueProgressiveImage, {delay: 200});

const router = new VueRouter({
    routes: routes.routes,
    linkActiveClass: "is-active"
});

axios.defaults.baseURL = "/api/";
axios.defaults.params = {};

const store = new Vuex.Store({
    state: {
        tentSites: {
            apiUrl: "/tentsites",
            hasMore: true,
            data: []
        },
        user: {
            name: "",
            id: null
        },
        gallery: {
            activePhoto: {},
            isActive: false
        },
        beta: false,
        apiToken: null,
        blockedRoute: null,
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
                axios.get(state.tentSites.apiUrl).then(function(response) {
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
                axios.post("/like/" + id + "/");
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
            axios.post("/unlike/" + id + "/");
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
        setUser(state, user) {
            state.user = user;
        },
        setToken(state, token) {
            state.apiToken = token;
        },
        setError(state, error) {
            state.error = error;
        },
        clearToken(state) {
            state.apiToken = null;
        }
    },
    actions: {
        openPhoto(state, photo) {
            state.commit("setActivePhoto", photo);
        },
        viewPhotoOnMap(state, photo) {
            state.commit("destroyGallery");
            router.push({
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
        storeToken(state, token) {
            localStorage.setItem("api_token", token);
            state.commit("setToken", token);
            axios.defaults.params.token = token;
            axios.defaults.headers.common["Authorization"] = token;
        },
        loginWithToken(state, token) {
            state.dispatch("storeToken", token);
            if(state.state.blockedRoute) {
                router.push(state.state.blockedRoute.path);
                state.state.blockedRoute = null;
            } else {
                router.push("/user");
            }
        },
        displayError(state, error) {
            state.commit("setError", error);
        },
        logout(state) {
            localStorage.removeItem("api_token");
            state.commit("clearToken");
            axios.defaults.params.token = null;
            axios.defaults.headers.common["Authorization"] = null;
            router.push("/info");
        }
    }
});

let cachedToken = localStorage.getItem("api_token");
if(cachedToken) {
    store.dispatch("storeToken", cachedToken);
}

axios.interceptors.response.use(function (response) {
    if(typeof response.headers.authorization !== typeof undefined) {
        store.dispatch("storeToken", response.headers.authorization);
    }
    return response;
}, function (error) {
    return Promise.reject(error);
});

router.beforeEach((to, from, next) => {
    if(to.meta.auth && !store.state.apiToken) {
        store.state.blockedRoute = to;
        next("/login");
    } else {
        next();
    }
});

new Vue({
    el: "#app",
    store,
    router,
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
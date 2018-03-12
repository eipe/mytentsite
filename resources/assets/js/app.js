/**
 * Created by Eivind Røe <eivindroe@gmail.com> on 06.08.2016.
 */
import Error from './components/Error.vue';
import routes from './routes.js'
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

Vue.use(VueAnalytics, {
    id: document.getElementById("analytics").innerHTML.toString(),
    router: Vue.router
});

// Set default base url used for all requests in application
Vue.axios.defaults.baseURL = "/api/";

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
        beta: false,
        error: null,
    },
    mutations: {
        addBookmark(state, tentSite) {
            let me = this;
            Vue.axios.post("/like/" + tentSite.id).then(function () {
                tentSite.bookmarks.push(me.$auth.user().id);
            });
        },
        removeBookmark(state, tentSite) {
            let me = this;
            Vue.axios.post("/unlike/" + tentSite.id).then(function() {
                let indexOfBookmark = tentSite.bookmarks.indexOf(me.$auth.user().id);
                if(indexOfBookmark) {
                    tentSite.bookmarks.splice(indexOfBookmark, 1);
                }
            });
        },
        addCommentOnPhoto(state, data) {
            let tentSiteId = data.id,
                tentSite = state.$store.tentSites.data[tentSiteId];
            if(tentSite) {
                tentSite.comments.push(data.comment);
            }
        },
        addCommentsOnPhoto(state, data) {
            let tentSiteId = data.id,
                tentSite = state.$store.tentSites.data[tentSiteId];
            if(tentSite) {
                tentSite.comments = data.comments;
            }
        },
        setError(state, error) {
            state.error = error;
        },
        loadMoreTentSites(state) {
            return new Promise((resolve, reject) => {
                if(!state.tentSites.hasMore) {
                    reject("Has no more tent sites");
                }

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
                    resolve();
                }).catch(function(error) {
                    reject(error);
                });
            });
        }
    },
    actions: {
        loadMoreTentSites(state) {
            state.commit("loadMoreTentSites");
        },
        viewPhotoOnMap(state, photo) {
            Vue.router.push({
                path: "/locate",
                query: {
                    latitude: photo.lat,
                    longitude: photo.lng
                }
            });
        },
        addBookmark(state, tentSite) {
            state.commit("addBookmark", tentSite);
        },
        removeBookmark(state, tentSite) {
            state.commit("removeBookmark", tentSite);
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
        Error
    },
    created() {
        let environment = document.getElementById("environment").innerHTML.toString();

        if(environment !== "production") {
            store.state.beta = true;
        }
    }
});
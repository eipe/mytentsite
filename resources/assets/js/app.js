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
        tentSites: {},
        beta: false,
        error: null,
    },
    mutations: {
        addTentSite(state, tentSite) {
            if(state.tentSites[tentSite["id"]]) {
                return;
            }

            state.tentSites[tentSite["id"]] = {
                id: tentSite["id"],
                reported_by: tentSite["reported_by"],
                lat: tentSite["latitude"],
                lng: tentSite["longitude"],
                bookmarks: tentSite["likes"],
                img_location: tentSite["img_location"],
                thumbnail: tentSite["thumbnail_location"],
                caption: tentSite["caption"],
                created_at: tentSite["created_at"],
                updated_at: tentSite["updated_at"],
                approved: tentSite["approved"],
                taken_date: tentSite["taken_date"]
            };
        },
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
            let tentSite = state.tentSites[data.id];
            if(tentSite) {
                if(typeof tentSite.comments == "undefined") {
                    tentSite.comments = [];
                }
                tentSite.comments.push(data.comment);
            }
        },
        setError(state, error) {
            state.error = error;
        }
    },
    actions: {
        addTentSite(state, tentSite) {
            state.commit("addTentSite", tentSite);
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
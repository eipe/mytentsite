/**
 * Created by Eivind Røe <eivindroe@gmail.com> on 06.08.2016.
 */
import Error from './components/Error.vue';
import Header from './elements/Header.vue';
import routes from './routes.js'
import Vuex from 'vuex'
import axios from 'axios'
import VueAxios from 'vue-axios'
import VueAuth from '@websanova/vue-auth'
import VueAnalytics from 'vue-analytics'
import Vuei18n from 'vue-i18n'
import language from './lang/language'

Vue.router = new VueRouter({
    routes: routes.routes,
    linkActiveClass: "is-active",
    path: "/"
});

const i18n = new Vuei18n(language);

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
        interval: 25
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

            if(typeof tentSite.comments === "undefined") {
                tentSite.comments = [];
            }

            state.tentSites[tentSite["id"]] = {
                id: tentSite["id"],
                reported_by: tentSite["reported_by"],
                reported_by_name: tentSite["reported_by_name"],
                lat: tentSite["latitude"],
                lng: tentSite["longitude"],
                comments: tentSite["comments"],
                bookmarks: tentSite["likes"],
                img_location: tentSite["img_location"],
                thumbnail: tentSite["thumbnail_location"],
                caption: tentSite["caption"],
                created_at: tentSite["created_at"],
                updated_at: tentSite["updated_at"],
                approved: tentSite["approved"],
                taken_date: tentSite["taken_date"],
                deleted: tentSite["deleted_at"],
                tags: tentSite["tags"]
            };
        },
        removeTentSite(state, tentSite) {
            if(state.tentSites[tentSite.id]) {
                return;
            }
            delete state.tentSites[tentSite.id];
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
            let tentSite = data.tentSite;
            if(tentSite) {
                if(typeof tentSite.comments === "undefined") {
                    tentSite.comments = [];
                }
                tentSite.comments.push(data.comment);
            }
        },
        removeCommentFromPhoto(state, data) {
            let tentSite = state.tentSites[data["tentSiteId"]],
                indexOfComment = tentSite.comments.indexOf(data["comment"]);
            if (indexOfComment) {
                tentSite.comments.splice(indexOfComment, 1);
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
        removeTentSite(state, tentSite) {
            state.commit("removeTentSite", tentSite);
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
        removeCommentFromPhoto(state, data) {
            state.commit("removeCommentFromPhoto", data);
        },
        loadCommentsForTentSite(state, tentSite) {
            return new Promise((resolve, reject)  => {
                Vue.axios.get("/comments/" + tentSite.id).then(response => {
                    if(typeof response.data !== typeof undefined) {
                        for (let commentKey in response.data.data) {
                            if(!response.data.data.hasOwnProperty(commentKey)) {
                                continue;
                            }
                            state.commit("addCommentOnPhoto", {
                                    tentSite: tentSite,
                                    comment: response.data.data[commentKey]
                                }
                            );
                        }
                    }
                    resolve();
                }, error => {
                    reject(error);
                });
            });
        },
        displayError(state, error) {
            state.commit("setError", error);
        },
        changeLanguage(state, lng) {
            i18n.locale = lng;
        }
    }
});

new Vue({
    el: "#app",
    store,
    router: Vue.router,
    i18n: i18n,
    components: {
        Error, "header-element" : Header
    },
    created() {
        let environment = document.getElementById("environment").innerHTML.toString();

        if(environment !== "production") {
            store.state.beta = true;
        }

        this.$auth.ready(() => {
            let userLanguage = this.$auth.user().language;
            if(userLanguage) {
                store.dispatch('changeLanguage', userLanguage);
            }
        });
    }

});
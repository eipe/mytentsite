/**
 * Created by Eivind Røe <eivindroe@gmail.com> on 06.08.2016.
 */
import VueProgressiveImage from 'vue-progressive-image'
import Vuex from 'vuex'


Vue.use(VueProgressiveImage, {delay: 200});

import Share from './pages/Share.vue';
import Info from './pages/Info.vue';
import Map from './pages/Map.vue';
import Wall from './pages/Wall.vue';
import User from './pages/User.vue';
import PhotoGallery from './components/PhotoGallery.vue';

const routes = [{
    path: '/',
    redirect: '/info'
}, {
    path: '/share',
    name: 'Share',
    component: Share,
    meta: {
        auth: true
    },
}, {
    path: '/map',
    name: 'Map',
    component: Map
}, {
    path: '/wall',
    name: 'Wall',
    component: Wall
}, {
    path: '/info',
    name: 'Info',
    component: Info,
    data: { staging: true }
}, {
    path: '/user',
    name: 'User',
    component: User,
    meta: {
        auth: true
    }
}, {
    path: '/login',
    name: 'Login',
    component: require('./pages/Login.vue')
}, {
    path: '/register',
    name: 'Register',
    component: require('./pages/Register.vue')
}, {
    path: '/password/reset',
    name: 'Reset password',
    component: require('./pages/PasswordReset.vue')
},{
    path: '/admin',
    name: 'Admin',
    component: require('./pages/admin/Admin.vue'),
    meta: {
        auth: true
    },
    children: [{
        path: '/admin',
        redirect: '/admin/dashboard'
    }, {
        path: '/admin/dashboard',
        name: 'Dashboard',
        component: require('./pages/admin/Dashboard.vue'),
        meta: {
            auth: true
        }
    }, {
        path: '/admin/tentsites',
        name: 'Tent sites',
        component: require('./pages/admin/TentSites.vue'),
        meta: {
            auth: true
        }
    }, {
        path: '/admin/users',
        name: 'Users',
        component: require('./pages/admin/Users.vue'),
        meta: {
            auth: true
        }
    }, {
        path: '/admin/statistics',
        name: 'Statistics',
        component: require('./pages/admin/Statistics.vue'),
        meta: {
            auth: true
        }
    }]
}];

const router = new VueRouter({
    routes: routes,
    linkActiveClass: 'is-active'
});

let $apiToken = document.getElementById('api_token'),
    apiToken = null;

if($apiToken) {
    apiToken = $apiToken.innerHTML.toString();
}

const store = new Vuex.Store({
    state: {
        tentSites: {
            apiUrl: '/api/tentsites',
            hasMore: true,
            data: []
        },
        user: {
            name: '',
            id: null,
            apiToken: apiToken
        },
        gallery: {
            activePhoto: {},
            isActive: false
        }
    },
    getters: {
        getUserTentSites: state => {
            if(state.user.id) {
                // Todo: Secure that all user photos are loaded
                return state.tentSites.data.filter(photo => {
                    if(photo.reported_by === state.user.name) {
                        return true;
                    }
                    return false;
                });
            }
            return [];
        }
    },
    mutations: {
        setActivePhoto(state, id) {
            let index = state.tentSites.data.findIndex(function(photo) {
                if(photo.id === id) {
                    return true;
                }
            });

            if(typeof state.tentSites.data[index] !== typeof undefined) {
                state.gallery.activePhoto = state.tentSites.data[index];
                state.gallery.isActive = true;
            }
        },
        destroyGallery(state) {
            state.gallery.activePhoto = {};
            state.gallery.isActive = false;
        },
        loadMoreTentSites(state) {
            if(state.tentSites.hasMore) {
                $.ajax({
                    url: state.tentSites.apiUrl,
                    success: function (response) {
                        if(parseInt(response.code) === 200) {
                            if(response.data.next_page_url) {
                                state.tentSites.apiUrl = response.data.next_page_url;
                            } else {
                                state.tentSites.hasMore = false;
                            }

                            if(typeof response.data.data !== typeof undefined && response.data.data.length > 0) {
                                $.each(response.data.data, function (key, photo) {
                                    state.tentSites.data.push({
                                        id: photo["id"],
                                        reported_by: photo["reported_by"],
                                        lat: photo["latitude"],
                                        lng: photo["longitude"],
                                        likes: photo["likes"],
                                        img_location: photo["img_location"],
                                        thumbnail: photo["thumbnail_location"],
                                        caption: photo["caption"],
                                        created_at: photo["created_at"],
                                        updated_at: photo["updated_at"],
                                        approved: photo["approved"]
                                    });
                                });
                            }
                        }
                    }, error: function (error) {
                        console.log(error);
                    }
                });
            }
        },
        likePhoto(state, id) {
            if(!state.user.apiToken) {
                return false;
            }

            let index = state.tentSites.data.findIndex(function(photo) {
                if(photo.id === id) {
                    return true;
                }
            });

            if(typeof state.tentSites.data[index] !== typeof undefined && !state.tentSites.data[index].hasLiked) {
                state.tentSites.data[index].likes += 1;
                state.tentSites.data[index].hasLiked = true;

                $.ajax({
                    url: "/api/like/" + id + "/?api_token=" + state.user.apiToken,
                    method: "POST"
                });
            }
        },
        unlikePhoto(state, id) {
            if(!state.user.apiToken) {
                return false;
            }

            let index = state.tentSites.data.findIndex(function(photo) {
                if(photo.id === id) {
                    return true;
                }
            });

            if(typeof state.tentSites.data[index] !== typeof undefined && state.tentSites.data[index].hasLiked) {
                state.tentSites.data[index].likes -= 1;
                state.tentSites.data[index].hasLiked = false;
            }

            $.ajax({
                url: "/api/unlike/" + id + "/?api_token=" + state.user.apiToken,
                method: "POST"
            });
        },
        setUser(state, user) {
            state.user = user;
        }
    },
    actions: {
        openPhoto(state, id) {
            state.commit('setActivePhoto', id);
        },
        viewPhotoOnMap(state, id) {
            state.commit('destroyGallery');
            router.push({ path: 'map' });
            // Todo: move to photo coordinates on map
        },
        destroyGallery(state) {
            state.commit('destroyGallery');
        },
        likePhoto(state, id) {
            state.commit('likePhoto', id);
        },
        unlikePhoto(state, id) {
            state.commit('unlikePhoto', id);
        }
    }
});

router.beforeEach((to, from, next) => {
    if(to.meta.auth && !apiToken) {
        next('/login');
    } else {
        next();
    }
});

new Vue({
    el: '#app',
    store,
    router,
    components: {
        PhotoGallery
    }
});
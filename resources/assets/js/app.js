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
}, {
    path: '/user',
    name: 'User',
    component: User,
    meta: {
        auth: true
    },
    children: [{
        path: '/user',
        redirect: '/user/profile'
    }, {
        path: '/user/profile',
        component: require('./pages/user/Profile.vue'),
        meta: {
            auth: true
        }
    }, {
        path: '/user/contributions',
        component: require('./pages/user/Contributions.vue'),
        meta: {
            auth: true
        }
    }]
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

axios.defaults.baseURL = '/api/';
axios.defaults.params = {};

const store = new Vuex.Store({
    state: {
        tentSites: {
            apiUrl: '/tentsites',
            hasMore: true,
            data: []
        },
        user: {
            name: '',
            id: null
        },
        gallery: {
            activePhoto: {},
            isActive: false
        },
        beta: false,
        apiToken: null,
        blockedRoute: null,
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

                if(state.tentSites.data[index].comments.length === 0) {
                    axios.get('/comments/' + id).then(function(response) {
                        state.tentSites.data[index].comments = response.data.data;
                    }).catch(function(error) {
                    });
                }
            }
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
                                likes: photo["likes"],
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
        likePhoto(state, id) {
            let index = state.tentSites.data.findIndex(function(photo) {
                if(photo.id === id) {
                    return true;
                }
            });

            if(typeof state.tentSites.data[index] !== typeof undefined && !state.tentSites.data[index].hasLiked) {
                state.tentSites.data[index].likes += 1;
                state.tentSites.data[index].hasLiked = true;
                axios.post('/like/' + id + '/');
            }
        },
        unlikePhoto(state, id) {
            let index = state.tentSites.data.findIndex(function(photo) {
                if(photo.id === id) {
                    return true;
                }
            });

            if(typeof state.tentSites.data[index] !== typeof undefined && state.tentSites.data[index].hasLiked) {
                state.tentSites.data[index].likes -= 1;
                state.tentSites.data[index].hasLiked = false;
            }
            axios.post('/unlike/' + id + '/');
        },
        addCommentOnPhoto(state, id, comment) {
            let index = state.tentSites.data.findIndex(function(photo) {
                if(photo.id === id) {
                    return true;
                }
            });

            if(typeof state.tentSites.data[index] !== typeof undefined && state.tentSites.data[index].hasLiked) {
                state.tentSites.data[index].comments.push(comment);
            }
        },
        setUser(state, user) {
            state.user = user;
        },
        setToken(state, token) {
            state.apiToken = token;
        },
        clearToken(state) {
            state.apiToken = null;
        }
    },
    actions: {
        openPhoto(state, id) {
            state.commit('setActivePhoto', id);
        },
        viewPhotoOnMap(state, id) {
            state.commit('destroyGallery');
            let routerVariables = { path: '/map', query: {}};
            let index = state.state.tentSites.data.findIndex(function(photo) {
                if(photo.id === id) {
                    return true;
                }
            });

            if(typeof state.state.tentSites.data[index] !== typeof undefined) {
                let photo = state.state.tentSites.data[index];
                routerVariables.query.latitude = photo.lat;
                routerVariables.query.longitude = photo.lng;
            }
            router.push(routerVariables);
        },
        destroyGallery(state) {
            state.commit('destroyGallery');
        },
        likePhoto(state, id) {
            state.commit('likePhoto', id);
        },
        unlikePhoto(state, id) {
            state.commit('unlikePhoto', id);
        },
        addCommentOnPhoto(state, photoId, comment) {
            state.commit('addCommentOnPhoto', photoId, comment);
        },
        storeToken(state, token) {
            localStorage.setItem('api_token', token);
            state.commit('setToken', token);
            axios.defaults.params.token = token;
            axios.defaults.headers.common['Authorization'] = token;
        },
        loginWithToken(state, token) {
            state.dispatch('storeToken', token);
            if(state.state.blockedRoute) {
                router.push(state.state.blockedRoute.path);
                state.state.blockedRoute = null;
            } else {
                router.push('/user');
            }
        },
        logout(state) {
            localStorage.removeItem('api_token');
            state.commit('clearToken');
            axios.defaults.params.apiToken = null;
            axios.defaults.headers.common['Authorization'] = null;
            router.push('/info');
        }
    }
});

let cachedToken = localStorage.getItem('api_token');
if(cachedToken) {
    store.dispatch('storeToken', cachedToken);
} else {
    let $apiToken = document.getElementById('api_token');

    if($apiToken) {
        store.dispatch('storeToken', $apiToken.innerHTML.toString());
    }
}

axios.defaults.headers.common['Authorization'] = store.state.apiToken;

router.beforeEach((to, from, next) => {
    if(to.meta.auth && !store.state.apiToken) {
        store.state.blockedRoute = to;
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
    },
    created() {
        let environment = document.getElementById('environment').innerHTML.toString();

        if(environment !== 'production') {
            store.state.beta = true;
        }
    }
});
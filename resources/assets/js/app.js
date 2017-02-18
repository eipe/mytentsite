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
import Login from './pages/Login.vue';
import PhotoGallery from './components/PhotoGallery.vue';

const routes = [{
    path: '/',
    redirect: '/info'
}, {
    path: '/share',
    name: 'Share',
    component: Share
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
    component: User
}, {
    path: '/login',
    name: 'Login',
    component: Login
}];

const router = new VueRouter({
    routes: routes,
    linkActiveClass: 'is-active'
});

const store = new Vuex.Store({
    state: {
        tentSites: {
            apiUrl: '/api/tentsites',
            hasMore: true,
            data: []
        },
        user: {
            name: '',
            tentSites: {}
        },
        gallery: {
            activePhoto: {},
            isActive: false
        }
    },
    mutations: {
        setActivePhoto(state, id) {
            let index = state.tentSites.data.findIndex(function(photo) {
                if(photo.id === id) {
                    return true;
                }
            });

            if(index) {
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
        }
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
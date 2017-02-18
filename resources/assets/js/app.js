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

var Sites = require('./tentsites.js'),
    Provider = new Sites();

const store = new Vuex.Store({
    state: {
        tentSites: {
            hasMore: true,
            data: {}
        },
        user: {
            name: '',
            tentSites: {}
        },
        gallery: {
            activeIndex: 0,
            activeId: 0,
            activePhoto: {},
            isActive: false
        }
    },
    mutations: {
        setActivePhoto(state, id) {
            if(typeof state.tentSites.data[id] === typeof undefined) {
                state.gallery.activeId = 0;
                state.gallery.activePhoto = {};
                state.gallery.isActive = false;
            }

            state.gallery.activeId = id;
            state.gallery.activePhoto = state.tentSites.data[id];
            state.gallery.isActive = true;
        },
        destroyGallery(state) {
            state.gallery.activeId = 0;
            state.gallery.activePhoto = {};
            state.gallery.isActive = false;
        },
        loadMoreTentSites(state) {
            let mxdResponse = Provider.getTentSites();

            if(mxdResponse) {
                $.each(mxdResponse, function(key, value) {
                    state.tentSites.data[value.id] = value;
                });
            }
            else {
                state.tentSites.hasMore = false;
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
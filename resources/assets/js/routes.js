export default {
    routes: [{
        path: '/',
        redirect: '/info'
    }, {
        path: '/share',
        name: 'Share',
        component: require('./pages/Share.vue').default,
        meta: {
            auth: true
        },
    }, {
        path: '/locate',
        name: 'Locate',
        component: require('./pages/Locate.vue').default
    }, {
        path: '/explore',
        name: 'Explore',
        component: require('./pages/Explore.vue').default
    }, {
        path: '/info',
        name: 'Info',
        component: require('./pages/Info.vue').default,
    }, {
        path: '/user',
        name: 'User',
        component: require('./pages/User.vue').default,
        meta: {
            auth: true
        },
        children: [{
            path: '/user',
            redirect: '/user/profile'
        }, {
            path: '/user/profile',
            component: require('./pages/user/Profile.vue').default,
            meta: {
                auth: true
            }
        }, {
            path: '/user/bookmarks',
            component: require('./pages/user/Bookmarks.vue').default,
            meta: {
                auth: true
            }
        }, {
            path: '/user/contributions',
            component: require('./pages/user/Contributions.vue').default,
            meta: {
                auth: true
            }
        }]
    }, {
        path: '/login',
        name: 'Login',
        component: require('./pages/Login.vue').default
    }, {
        path: '/register',
        name: 'Register',
        component: require('./pages/Register.vue').default
    }, {
        path: '/password/reset',
        name: 'Reset password',
        component: require('./pages/PasswordReset.vue').default
    }, {
        path: '/password/reset-form',
        name: 'Reset password form',
        component: require('./pages/PasswordResetForm.vue').default
    }, {
        path: '/admin',
        name: 'Admin',
        component: require('./pages/admin/Admin.vue').default,
        meta: {
            auth: true
        },
        children: [{
            path: '/admin',
            redirect: '/admin/dashboard'
        }, {
            path: '/admin/dashboard',
            name: 'Dashboard',
            component: require('./pages/admin/Dashboard.vue').default,
            meta: {
                auth: true
            }
        }, {
            path: '/admin/tentsites',
            name: 'Tent sites',
            component: require('./pages/admin/TentSites.vue').default,
            meta: {
                auth: true
            }
        }, {
            path: '/admin/users',
            name: 'Users',
            component: require('./pages/admin/Users.vue').default,
            meta: {
                auth: true
            }
        }, {
            path: '/admin/statistics',
            name: 'Statistics',
            component: require('./pages/admin/Statistics.vue').default,
            meta: {
                auth: true
            }
        }]
    }]
}
export default {
    routes: [{
        path: '/',
        redirect: '/info'
    }, {
        path: '/share',
        name: 'Share',
        component: require('./pages/Share.vue'),
        meta: {
            auth: true
        },
    }, {
        path: '/locate',
        name: 'Locate',
        component: require('./pages/Locate.vue')
    }, {
        path: '/wall',
        name: 'Wall',
        component: require('./pages/Wall.vue')
    }, {
        path: '/info',
        name: 'Info',
        component: require('./pages/Info.vue'),
    }, {
        path: '/user',
        name: 'User',
        component: require('./pages/User.vue'),
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
            path: '/user/bookmarks',
            component: require('./pages/user/Bookmarks.vue'),
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
    }, {
        path: '/password/reset-form',
        name: 'Reset password form',
        component: require('./pages/PasswordResetForm.vue')
    }, {
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
    }]
}
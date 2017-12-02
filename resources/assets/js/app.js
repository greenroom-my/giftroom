import Vue from 'vue';
import VueRouter from 'vue-router';
import VueUp from 'vueup'

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = new Vue;
window.VueEvent = new Vue({});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.use(VueRouter);
Vue.use(VueUp);
Vue.component('giftroom-navbar', require('./components/Navbar.vue'));

const router = new VueRouter({
    routes: [
        {
            path: '/',
            component: require('./views/HomeView.vue')
        },
        {
            path: '/register',
            component: require('./views/RegisterView.vue')
        },
        {
            path: '/first-time',
            component: require('./views/FirstTimeView.vue')
        },
        {
            path: '/room-home',
            component: require('./views/RoomHomeView.vue')
        }
    ]
});

const app = new Vue({
    router: router,
    data: {
        navbar: {
            logo: true,
            menu: true
        },
        event: window.VueEvent
    },
    created() {
        let navbar = this.navbar;
        window.VueEvent.$on('pageChange', function(data) {
            navbar.logo = data.logo;
            navbar.menu = data.menu;
        });
    }
}).$mount('#app');

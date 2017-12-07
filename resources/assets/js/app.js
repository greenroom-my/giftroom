import Vue from 'vue';
import VueRouter from 'vue-router';
import VueUp from 'vueup'
import Slideout from 'vue-slideout'

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = new Vue;
window.VueBus = new Vue({
    data: {
        user: null,
        room: null
    }
});

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
            path: '/room-create',
            component: require('./views/RoomCreateView.vue')
        },
        {
            path: '/room-home',
            component: require('./views/RoomHomeView.vue')
        },
        {
            path: '/room-invite',
            component: require('./views/RoomInviteView.vue')
        },
        {
            path: '/wish-list',
            component: require('./views/WishListView.vue')
        },
        {
            path: '/match',
            component: require('./views/MatchView.vue')
        }
    ]
});

const app = new Vue({
    router: router,
    components: {
        Slideout
    },
    data: {
        navbar: {
            logo: true,
            menu: true
        },
        event: window.VueBus,
    },
    computed: {
      roomName() {
          if(this.event.room)
              return this.event.room.name;
          else
              return "";
      }
    },
    created() {
        let navbar = this.navbar;
        window.VueBus.$on('pageChange', function(data) {
            navbar.logo = data.logo;
            navbar.menu = data.menu;
        });
    },
    methods: {
        closeMenu(route) {
            this.$children[0].slideout.close();
            router.push(route);
        }
    }
}).$mount('#app');

if ( 'serviceWorker' in navigator ) {
  navigator.serviceWorker
           .register('/service-worker.js');
}

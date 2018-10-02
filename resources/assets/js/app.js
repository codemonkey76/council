
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import InstantSearch from 'vue-instantsearch';
window.Vue = require('vue');
Vue.use(InstantSearch);
let authorizations = require('./authorizations');

window.Vue.prototype.authorize = function (...params) {
    if (! window.App.signedIn) return false;

    if (typeof params[0] === 'string') {
        return authorizations[params[0]](params[1]);
    }

    return params[0](window.App.user);
};

Vue.prototype.signedIn = window.App.signedIn;

window.events = new Vue();

window.flash = function(message, level='success') {
    window.events.$emit('flash', {message, level});
};

// Set up global Vue components
Vue.component('flash', require('./components/Flash.vue'));
Vue.component('thread-view', require('./pages/Thread.vue'));
Vue.component('paginator', require('./components/Paginator'));
Vue.component('user-notifications', require('./components/UserNotifications'));
Vue.component('avatar-form', require('./components/AvatarForm'));
Vue.component('wysiwyg', require('./components/Wysiwyg'));

const app = new Vue({
    el: '#app'
});

/*jshint esversion: 6 */

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import VueRouter from 'vue-router';
import VueSocketio from 'vue-socket.io';

Vue.use(VueRouter);


//Vue.use(VueSocketio, 'http://192.168.10.10:8080');
Vue.use(VueSocketio, 'http://192.168.10.10:8080');

const user = Vue.component('user', require('./components/user.vue'));
const login = Vue.component('login', require('./components/login.vue'));
const logout = Vue.component('logout', require('./components/logout.vue'));
const register = Vue.component('register', require('./components/register.vue'));
const userAccount = Vue.component('userAccount', require('./components/userAccount.vue'));
const forgot = Vue.component('forgot', require('./components/forgot.vue'));
const verifyUser = Vue.component('verifyUser', require('./components/verifyUser.vue'));


const sueca = Vue.component('sueca', require('./components/sueca.vue'));

const adminStatistics = Vue.component('statistics', require('./components/statistics.vue'));
const adminLogin = Vue.component('adminLogin', require('./components/adminLogin.vue'));
const adminMasterPage = Vue.component('adminMasterPage', require('./components/admin/adminMasterPage.vue'));
const adminUserDetails = Vue.component('adminUserDetails', require('./components/adminUserDetails.vue'));
const adminPassword = Vue.component('resetPWAdmin', require('./components/resetPWAdmin.vue'));
const playerStatistics = Vue.component('playerStatistics', require('./components/playerStatistics.vue'));
const decksManagement = Vue.component('decksManagement', require('./components/decksManagement.vue'));

//gestao user
const accountSettings = Vue.component('accountSettings', require('./components/user/accountSettings.vue'));
const changeUserInfo = Vue.component('changeUserInfo', require('./components/user/changeUserInfo.vue'));
const changeUserPassword = Vue.component('changeUserPassword', require('./components/user/changeUserPassword.vue'));
const deleteUserAccount = Vue.component('deleteUserAccount', require('./components/user/deleteUserAccount.vue'));
const changeAvatar = Vue.component('changeAvatar', require('./components/user/changeAvatar.vue'));

//gestao admin
const adminSettings = Vue.component('adminSettings', require('./components/admin/adminSettings.vue'));
const changeAdminEmail = Vue.component('changeAdminEmail', require('./components/admin/changeAdminEmail.vue'));
const changeAdminPassword = Vue.component('changeAdminPassword', require('./components/admin/changeAdminPassword.vue'));
const platformEmailSettings = Vue.component('platformEmailSettings', require('./components/admin/platformEmailSettings.vue'));

const routes = [
    {path: '/', redirect: '/login'},
    {path: '/users', component: user},
    {path: '/login', component: login},
    {path: '/logout', component: logout},
    {path: '/register', component: register},
    {path: '/verifyUser/:token', component: verifyUser, props: true},
    {path: '/adminLogin', component: adminLogin},
    {path: '/adminMasterPage', component: adminMasterPage},
    {path: '/adminUserDetails/:id', component: adminUserDetails},
    {path: '/adminPassword', component: adminPassword},
    {path: '/playerStatistics', component: playerStatistics},
    {path: '/adminStatistics', component: adminStatistics},
    {path: '/userAccount', component: userAccount},
    {path: '/forgot', component: forgot},
    {path: '/decksManagement', component: decksManagement},
    {path: '/sueca', component: sueca},
    {path: '/adminSettings', component: adminSettings},
    {path: '/accountSettings', component: accountSettings}

];

const router = new VueRouter({
  routes:routes
});


const app = new Vue({
  router,
  data:{
      player1: undefined,
      player2: undefined,
      player3: undefined,
      player4: undefined


  }
}).$mount('#app');

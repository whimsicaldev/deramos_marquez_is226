/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';
import 'bootstrap/scss/bootstrap.scss';

import * as Vue from 'vue';
import * as VueRouter from "vue-router";
import App from './components/App';

const routes = [{ path: "/", component: App }];
const router = VueRouter.createRouter({
  routes,
  history: VueRouter.createWebHistory(),
});

const app = Vue.createApp(App);
app.use(router);
app.mount("#root");

//Contains all of the routes for the application
import Vue from 'vue'
import VueRouter from 'vue-router'

import Login from './components/Login.vue';
import Register from './components/Register.vue';

import PostList from './pages/Post/PostList.vue';
import CreatePost from './pages/Post/CreatePost.vue';
import EditPost from './pages/Post/EditPost.vue';

Vue.use(VueRouter)

export default new VueRouter({
    routes: [
        {  path: '/login', name: 'Login', component: Login},
        {  path: '/register', name: 'Register', component: Register},
        {  path: '/posts', name: 'PostList', component: PostList},
        {  path: '/posts/create', name: 'CreatePost', component: CreatePost},
        {  path: '/posts/:id/edit', name: 'EditPost', component: EditPost}
    ]
});
<template>
    <div>
        <div class="card mb-2" v-for="post in posts" v-bind:key="post.id">
            <div class="card-body">
                <h4 class="card-title">{{ post.post_title | uppercase }}</h4>
                <p class="card-text">{{ post.post_content }}</p>
                <div v-if="auth == true">
                    <button type="button" @click="deletePost(post.id)" class="btn btn-danger" style="float:right;">Delete</button>
                    <router-link :to=" { name: 'EditPost', params: { id: post.id }}">
                        <button type="button" class="btn btn-warning" style="float:right;margin-right: 15px;">Update</button>
                    </router-link>
                </div>
            </div>
        </div>
    </div>
</template>
<script>   
    import { EventBus } from '../../app.js'

    export default {
        name: "PostList",
        data() {
            return {
                posts: [],
                post: {
                    id: '',
                    post_title: '',
                    post_content: ''
                },
                auth: false
            };
        },
        filters: {
            uppercase: function(value, onlyFirstCharacter) {
                if (!value) {
                    return '';
                } 
                value = value.toString();
                if (onlyFirstCharacter) {
                    return value.charAt(0).toUpperCase() + value.slice(1);
                } else {
                    return value.toUpperCase();
                }
            }
        },
        created() {
            this.getPosts();
        },
        methods: {
            getPosts() {
                fetch('/api/v1/posts')
                    .then(response => response.json())
                    .then(response => {
                        var data = response.message.posts;
                        this.posts = data;
                    })
                    .catch(err => console.log(err));
            },
            deletePost(id) {
                let token = localStorage.getItem('user-token');
                const config = {
                    headers: { Authorization: `Bearer ${token}` }
                };
                console.log(token);
                axios.delete('/api/v1/posts/' + id,config)
                .then((response) => {
                    var data = response.data.message.posts;
                    var status = data.status || 'error';
                    if(status.toLowerCase() == 'success'){
                        this.title = data.title;
                        this.content = data.content;
                        this.getPosts();
                    }
                }).catch((err) => {
                    console.log(err)
                })
            },
        },
        mounted() {
            let token = localStorage.getItem('user-token');
            if(token){
                this.auth = true;
            }else{
                this.auth = false;
            }
        }
    };
</script>
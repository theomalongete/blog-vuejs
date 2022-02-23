<template>
    <div>   
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Blog</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarColor01">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Home
                                <span class="visually-hidden">(current)</span>
                            </a>
                        </li>
                    </ul>
                    <div class="d-flex">
                        <div v-if="auth == false">
                            <router-link :to="{ name: 'Login'}">
                                <button type="button" class="btn btn-primary my-2 my-sm-0">Login</button>
                            </router-link>
                    
                            <router-link :to="{ name: 'Register'}">
                                <button type="button" class="btn btn-info my-2 my-sm-0">Register</button>
                            </router-link>
                        </div>
                        <div v-if="auth==true">
                            <router-link :to="{ name: 'CreatePost'}">
                                <button type="button" class="btn btn-success my-2 my-sm-0">Create New</button>
                            </router-link>
                        
                            <button type="button" @click="logout()" class="btn btn-danger my-2 my-sm-0">Logout</button>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        <router-view></router-view>
    </div>
</template>
<script>
    export default {
        name: "App",
        component: {},
        data() {
            return {
                auth: false,
            }
        },
        methods:{
            logout() {
                localStorage.removeItem('user-token')
                this.$router.push('/login')
            }
        },
        mounted() {
            let token = localStorage.getItem('user-token');
            if(token){
                this.auth = true;
            }else{
                this.auth = false;
            }
        }
    }
</script>
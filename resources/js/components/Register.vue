<template>
  <div class="container">
    <div class="row">
        <div class="col-md-6 mt-5 mx-auto">
            <form @submit.prevent="register">
                <fieldset>
                    <legend>Register</legend>
                    <br/>
                    <div class="form-group">
                        <label for="user_first_name">Name</label>
                        <input type="text" v-model="name" class="form-control" name="name" placeholder="Name">
                    </div>
                    <br/>
                    <div class="form-group">
                        <label for="user_surname">Surname</label>
                        <input type="text" v-model="surname" class="form-control" name="surname" placeholder="Surname">
                    </div>
                    <br/>
                    <div class="form-group">
                        <label for="email">E-mail Address:</label>
                        <input type="email" v-model="email" class="form-control" name="email" placeholder="E-mail Address">
                    </div>
                    <br/>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" v-model="password" class="form-control" name="password" placeholder="Password">
                    </div>
                    <br/>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success">Register</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
  </div>
</template>

<script>
    export default {
        name : "Register",
        data(){
            return {
                name: '',
                surname: '',
                email: '',
                password: '',
            }
        },
        methods:{
            register() {
                axios.post('/api/auths/register',{
                    name: this.name,
                    surname: this.surname,
                    email: this.email,
                    password: this.password,
                }).then((response) => {
                    this.$router.push('/login')
                }).catch((err) => {
                    console.log(err)
                })
            }
        },
        mounted() {
            localStorage.removeItem('user-token');
        }
    }
</script>
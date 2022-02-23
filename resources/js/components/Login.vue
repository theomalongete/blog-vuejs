
<template>
  <div class="container">
    <div class="row">
        <div class="col-md-6 mt-5 mx-auto">
            <form @submit.prevent="login">
                <fieldset>
                    <legend>Please sign in</legend>
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
                        <button type="submit" class="btn btn-success">Sign In</button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
  </div>
</template>

<script>
    import {EventBus} from '../app.js'

    export default {
        name: "Login",
        data(){
            return {
                email: '',
                password: '',
            }
        },
        methods: {
            login() {
                axios.post('/api/auths/login',{
                    email: this.email,
                    password: this.password,
                }).then((response) => {
                    console.log(response)
                    localStorage.setItem('user-token', response.data.message.token)
                    this.email = ''
                    this.password = ''
                    this.$router.push('/posts')
                }).catch((err) => {
                    console.log(err)
                })

                this.emitMethod()
            },
            emitMethod() {
                EventBus.$emit('logged-in','loggedin')
            }
        }
  }
</script>
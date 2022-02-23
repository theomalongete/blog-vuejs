<template>
  <div class="container">
      <div class="row">
        <form @submit.prevent="addPost">
          <fieldset>
            <legend>Add Post</legend>
            <br/>
            <div class="form-group">
              <label for="title">Title:</label>
              <input type="text" class="form-control" v-model="title" placeholder="Title">
            </div>
            <br/>
            <div class="form-group">
              <label for="content">Content:</label>
              <textarea class="form-control" v-model="content" placeholder="Content"></textarea>
            </div>
            <br/>
            <div class="form-group">
              <button type="submit" class="btn btn-success">Save</button>
            </div>
          </fieldset>
        </form>
      </div>
  </div>
</template>

<script>
  export default {
    name: "CreatePost",
     data(){
      return{
        title: '',
        content: '',
      }
    },
    methods: {
      addPost() {
        let token = localStorage.getItem('user-token');
        const config = {
            headers: { Authorization: `Bearer ${token}` }
        };
        const body = {
          title: this.title,
          content: this.content
        };
        axios.post('/api/v1/posts/create',body,config).then((response) => {
          var data = response.data.message.posts;
          var status = data.status || 'error';
          if(status.toLowerCase() == 'success'){
            this.title = '';
            this.content = '';
            this.$router.push('/posts');
          }
        }).catch((err) => {
          console.log(err)
        });
      }
    }
  }
</script>
<template>
  <div class="container">
      <div class="row">
        <form @submit.prevent="editPost">
          <fieldset>
            <legend>Edit Post</legend>
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
              <button type="submit" class="btn btn-warning">Update</button>
            </div>
          </fieldset>
        </form>
      </div>
  </div>
</template>

<script>
  export default {
    name: "EditPost",
    data(){
      return{
        title: '',
        content: '',
      }
    },
    created() {
      this.getPost();
    },
    methods: {
        getPost(){
          let token = localStorage.getItem('user-token');
          const config = {
              headers: { Authorization: `Bearer ${token}` }
          };
          let id = this.$route.params.id;
          axios.get('/api/v1/posts/' + id,null,config)
          .then((response) => {
            var data = response.data.message.posts;
            var status = data.status || 'error';
            if(status.toLowerCase() == 'success'){
              this.title = data.title;
              this.content = data.content;
            }
          }).catch((err) => {
            console.log(err)
          })
        },
        editPost(){
          let token = localStorage.getItem('user-token');
          const config = {
              headers: { Authorization: `Bearer ${token}` }
          };
          const body = {
            title: this.title,
            content: this.content
          };
          let id = this.$route.params.id;
          axios.put('/api/v1/posts/' + id,body,config).then((response) => {
            var data = response.data.message.posts;
            var status = data.status || 'error';
            if(status.toLowerCase() == 'success'){
              this.title = data.title;
              this.content = data.content;
              this.$router.push('/posts');
            }
          }).catch((err) => {
            console.log(err)
          });
        }
    }
  }
</script>
<template>
  <v-app>
    <v-container grid-list-xs,sm,md,lg,xl>
      <v-layout row wrap justify-center>
        <v-flex xs12 sm12 md10>
          <div class="header">
            <v-layout row wrap>
              <v-flex class="text-xs-left py-2">
                <h6 class="headline"><span class="grey--text">Trash</span>Post.io</h6>
              </v-flex>
              <v-flex class="text-xs-right">
                <v-btn dark class="primary" @click="showDialogAdd" style="margin-right: 0px;"><v-icon>add</v-icon> Add new post</v-btn>
              </v-flex>
            </v-layout>


          </div>

          <v-card v-for="post in posts" :key="post.id" class="my-4 px-3">
            <v-card-title primary-title>
              <h2>{{ post.title }}</h2>
            </v-card-title>
            <v-card-text>
              <p>{{ post.content }}</p>
            </v-card-text>
            <v-card-actions>
              <v-layout row wrap justify-space-around>

                <v-btn color="warning" @click="showDialogEdit(post.id)" block flat>Edit <v-icon right>create</v-icon></v-btn>
                <v-btn dark color="error" @click="showDialogDelete(post.id)" block flat>Delete <v-icon right>delete</v-icon></v-btn>

              </v-layout>
            </v-card-actions>
          </v-card>
          <div class="text-xs-center">
            <v-pagination
              :length="meta.last_page"
              v-model="meta.current_page"
              @input="changePage"
              color="primary" >
            </v-pagination>
          </div>

          <v-dialog v-model="dialogAdd" max-width="600px" persistent>
            <v-card>
              <v-card-title primary-title>
                <h5 class="headline">New Post</h5>
              </v-card-title>
              <v-form v-model="addValid" id="formAdd">
                <v-card-text>
                  <v-text-field
                    name="title"
                    v-model="title"
                    :rules="titleRules"
                    label="Title"
                    required
                  ></v-text-field>
                  <v-textarea
                    label="Content"
                    v-model="content"
                    :rules="contentRules"
                    name="content"
                    outline
                    required
                  ></v-textarea>
                </v-card-text>
                <v-card-actions>
                  <v-layout row wrap>
                    <v-flex class="text-xs-right">
                      <v-btn class="grey--text" @click="dialogAdd = false" flat>Cancel</v-btn>
                      <v-btn color="primary" @click="addPost" :disabled="!addValid" flat>Add</v-btn>
                    </v-flex>
                  </v-layout>
                </v-card-actions>
              </v-form>
            </v-card>
          </v-dialog>

          <v-dialog v-model="dialogEdit" max-width="600px" persistent>
            <v-card>
              <v-card-title primary-title>
                <span class="headline">Edit Post</span>
              </v-card-title>
              <v-form v-model="editValid">
                <v-card-text>
                  <v-text-field
                    name="title"
                    v-model="title"
                    :rules="titleRules"
                    label="Title"
                    required
                  ></v-text-field>
                  <v-textarea
                    label="Content"
                    v-model="content"
                    :rules="contentRules"
                    name="content"
                    required
                    outline
                  ></v-textarea>
                </v-card-text>
                <v-card-actions>
                  <v-layout row wrap>
                    <v-flex class="text-xs-right">
                      <v-btn class="grey--text" @click="dialogEdit = false" flat>Cancel</v-btn>
                      <v-btn color="warning" @click="editPost" :disabled="!editValid" flat>Edit</v-btn>
                    </v-flex>
                  </v-layout>
                </v-card-actions>
              </v-form>
            </v-card>
          </v-dialog>

          <v-dialog v-model="dialogDelete" max-width="500px">
            <v-card>
              <v-card-text justify-center>
                <h5 class="headline font-weight-light text-xs-center mt-5">Are you sure?</h5>
                <p class="body-1 text-xs-center grey--text">Do you really want to delete this post? This process cannot be undone.</p>
              </v-card-text justify-center>
              <v-card-actions>
                <v-layout row wrap>
                  <v-flex class="text-xs-right">
                    <v-btn class="grey--text" @click="dialogDelete = false" flat>Cancel</v-btn>
                    <v-btn color="error" @click="deletePost" flat>Delete</v-btn>
                  </v-flex>
                </v-layout>
              </v-card-actions>
            </v-card>
          </v-dialog>

        </v-flex>
      </v-layout>
    </v-container>
  </v-app>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      // Validation
      addValid: false,
      editValid: false,
      titleRules: [
        (v) => !!v || 'Title is required'
      ],
      contentRules: [
        (v) => !!v || 'Content is required'
      ],

      // Show Dialog
      dialogAdd: false,
      dialogEdit: false,
      dialogDelete: false,

      // Post Content
      id: '',
      title: '',
      content: '',

      // Data Post
      posts: '',
      links: '',
      meta: ''
    }
  },
  methods: {
    changePage(numb) {
      axios.get(window.location.href + 'api/posts?page=' + numb)
           .then(response => (
             this.posts = response.data['data'],
             this.links = response.data['links'],
             this.meta = response.data['meta'],
             sessionStorage.setItem('numb', numb)
           ))
           .catch(err => (console.log(err)))
    },
    showDialogAdd() {
      this.title = '';
      this.content = '';
      this.dialogAdd = true;
    },
    showDialogEdit(id) {
      let postToEdit = this.posts.filter(post => post.id == id);
      this.id = postToEdit['0'].id;
      this.title = postToEdit['0'].title;
      this.content = postToEdit['0'].content;
      this.dialogEdit = true;
    },
    showDialogDelete(id) {
      this.dialogDelete = true;
      this.id = id;
    },
    addPost() {
      axios.post(window.location.href + 'api/posts', {
              title: this.title,
              content: this.content
            })
            .then(response => (
              this.dialogAdd = false,
              this.changePage(sessionStorage.getItem('numb'))
            ))
            .catch(err => (console.log(err)))

    },
    editPost() {
      axios.put(window.location.href + 'api/posts/' + this.id, {
              title: this.title,
              content: this.content
            })
            .then(response => (
              this.dialogEdit = false,
              this.changePage(sessionStorage.getItem('numb'))
            ))
            .catch(err => (console.log(err)))
    },
    deletePost() {
      axios.delete(window.location.href + 'api/posts/' + this.id)
           .then(response => (
             this.dialogDelete = false,
             this.changePage(sessionStorage.getItem('numb'))
           ))
           .catch(err => (console.log(err)))
    }
  },
  created() {
    let storageNumb = sessionStorage.getItem('numb');
    if(storageNumb == null) storageNumb = 1;

    this.changePage(storageNumb);
  }
}
</script>

<style lang="css" scoped>
</style>

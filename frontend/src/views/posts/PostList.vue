<script setup>

  import { ref, onMounted } from 'vue';
  import axiosInstance from '@/axios.js';

  const formatDate = (datetime) => {
    const date = new Date(datetime);
    return date.toLocaleDateString();
  };

  const postDetail = ref({});

  const selectedPost = ref({});

  const showModalPostDetail = ref(false);

  const showModalDelete = ref(false);

  const showPostDetail = (post) => {
    postDetail.value = post;
    showModalPostDetail.value = true;
};

  const closePostDetail = () => {
    postDetail.value = null;
    showModalPostDetail.value = false;
  };

  const showDeleteConfirmation = (post) => {
    selectedPost.value = post;
    showModalDelete.value = true;
  };

  const closeDeleteConfirmation = () => {
    selectedPost.value = null;
    showModalDelete.value = false;
  };

  const deletePost = (postID) => {
  alert("deleted");
  axiosInstance
    .delete(`/posts/${postID}`)
    .then((response) => {
      console.log("post deleted successfully");
      closeDeleteConfirmation()
      window.location.reload()
    })
    .catch((error) => {
      alert("error");
      console.error(error);
    });
};

  const posts = ref([]);

  const fetchPosts = () => {
    axiosInstance
      .get('/posts')
      .then((response) => {
        posts.value = response.data.posts;
        console.log(response)
      })
      .catch((error) => {
        alert("error")
      console.error(error);
      });
  };

  onMounted(() => {
    fetchPosts();
  });

  async function downloadPostCSV() {
    try{
      const response = await axiosInstance.get('/export', {
      responseType: 'blob',
    });

    const blob = new Blob([response.data], {
      type: 'application/csv',
    });

    const url = window.URL.createObjectURL(blob);

    const link = document.createElement('a');
    link.href = url;
    link.download = 'posts.csv';
    link.click();

    window.URL.revokeObjectURL(url);
    }catch (error) {
      console.error(error);
    }
  }

</script>

<template>
  <div class="container container-main my-5">
      <div class="card custom-card">
      <div class="card-header card-header-bg">
        Post List
      </div>
      <div class="card-body p-4">
        <div class="row">
          <div class="col-12 col-md-6">
            <div class="row">
              <div class="col-12 col-md-5"></div>
              <div class="col-12 col-md-7">
                <div class="mb-3 row">
                  <label for="name" class="text-right-label col-12 col-md-4 col-form-label">keyword:</label>
                  <div class="col-12 col-md-8">
                    <input type="text" class="form-control form-custom-border" id="name" name="name">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="row">
              <div class="col-12 col-md-3">
                <button type="button" class="btn btn-primary common-btn" style="width: 100%">Search</button>
              </div>
              <div class="col-12 col-md-3">
                <router-link to="/PostCreate" class="btn btn-primary common-btn" style="width: 100%">
                  Create
                </router-link>
              </div>
              <div class="col-12 col-md-3">
                <router-link to="/UploadCSV" class="btn btn-primary common-btn" style="width: 100%">
                Upload
                </router-link>
              </div>
              <div class="col-12 col-md-3">
                <button @click="downloadPostCSV" type="button" class="btn btn-primary common-btn"  style="width: 100%">Download</button>
              </div>
            </div>
          </div>
        </div>
        <table class="table table-striped my-4">
          <thead class="table-header">
            <tr>
              <th scope="col">Post Title</th>
              <th scope="col">Post Description</th>
              <th scope="col">Posted User</th>
              <th scope="col">Posted Date</th>
              <th scope="col">Operation</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="post in posts" :key="post.id">
              <td class="text-success" data-bs-toggle="modal" data-bs-target="#postDetailBtn" @click="showPostDetail(post)">{{ post.title }}</td>
              <td>{{ post.description }}</td>
              <td>{{ post.posted_user }}</td>
              <td>{{ formatDate(post.created_at) }}</td>
              <td>
                <router-link :to="'/PostEdit/' + post.id">
                  <button type="button" class="btn btn-primary">Edit</button>
                </router-link>
                <button
                  type="button"
                  class="btn btn-danger mx-2"
                  data-bs-toggle="modal"
                  data-bs-target="#postDeleteBtn"
                  @click="showDeleteConfirmation(post)"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <div
          class="modal post-modal-custom fade"
          :class="{'show': showModalDelete}"
          id="postDeleteBtn"
          data-bs-backdrop="static"
          data-bs-keyboard="false"
          tabindex="-1"
          aria-labelledby="postDeleteBtn"
          aria-hidden="true"
        >
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <p class="modal-title fs-6" id="postDeleteBtn">
                  Delete Confirm
                </p>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-12 col-md-7">
                    <h5 class="mb-4 modal-custom-txt">
                      Are you sure to delete post?
                    </h5>
                  </div>
                  <div class="col-12 col-md-5"></div>
                </div>
                <div class="row mb-1">
                  <div class="col-12 col-md-4">
                    <p class="text-start mx-3">ID</p>
                  </div>
                  <div class="col-12 col-md-8">
                    <p class="text-start fst-italic text-danger">{{ selectedPost.id }}</p>
                  </div>
                </div>
                <div class="row mb-1">
                  <div class="col-12 col-md-4">
                    <p class="text-start mx-3">Title</p>
                  </div>
                  <div class="col-12 col-md-8">
                    <p class="text-start fst-italic text-danger">{{ selectedPost.title }}</p>
                  </div>
                </div>
                <div class="row mb-1">
                  <div class="col-12 col-md-4">
                    <p class="text-start mx-3">Description</p>
                  </div>
                  <div class="col-12 col-md-8">
                    <p class="text-start fst-italic text-danger">{{ selectedPost.description }}</p>
                  </div>
                </div>
                <div class="row mb-1">
                  <div class="col-12 col-md-4">
                    <p class="text-start mx-3">Status</p>
                  </div>
                  <div class="col-12 col-md-8">
                    <p class="text-start fst-italic text-danger">{{ selectedPost.status }}</p>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  @click="closeDeleteConfirmation"
                  data-bs-dismiss="modal"
                >
                  Close
                </button>
                <button type="button" class="btn btn-danger" @click="deletePost(selectedPost.id)">Delete</button>
              </div>
            </div>
          </div>
        </div>
        <div
          class="modal post-detail-modal-custom fade"
          :class="{'show': showModalPostDetail}"
          id="postDetailBtn"
          data-bs-backdrop="static"
          data-bs-keyboard="false"
          tabindex="-1"
          aria-labelledby="postDetailBtn"
          aria-hidden="true"
        >
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <p class="modal-title fs-6" id="postDetailBtn">
                  Post Detail
                </p>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body mx-4">
                <div class="row">
                  <div class="col-12 col-md-5">
                    <p>Title</p>
                  </div>
                  <div class="col-12 col-md-7">
                    <p class="fst-italic text-danger">{{ postDetail.title }}</p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-md-5">
                    <p>Description</p>
                  </div>
                  <div class="col-12 col-md-7">
                    <p class="fst-italic text-danger">{{ postDetail.description }}</p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-md-5">
                    <p>Status</p>
                  </div>
                  <div class="col-12 col-md-7">
                    <p class="fst-italic text-danger">{{ postDetail.status }}</p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-md-5">
                    <p>Created Date</p>
                  </div>
                  <div class="col-12 col-md-7">
                    <p class="fst-italic text-danger">{{ formatDate(postDetail.created_at) }}</p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-md-5">
                    <p >Created User</p>
                  </div>
                  <div class="col-12 col-md-7">
                    <p class="fst-italic text-danger">{{ postDetail.posted_user }}</p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-md-5">
                    <p>Updated Date</p>
                  </div>
                  <div class="col-12 col-md-7">
                    <p class="fst-italic text-danger">{{ formatDate(postDetail.updated_at) }}</p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12 col-md-5">
                    <p>Updated User</p>
                  </div>
                  <div class="col-12 col-md-7">
                    <p class="fst-italic text-danger">{{ postDetail.updated_user }}</p>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  @click="closePostDetail"
                  data-bs-dismiss="modal"
                >
                  Close
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

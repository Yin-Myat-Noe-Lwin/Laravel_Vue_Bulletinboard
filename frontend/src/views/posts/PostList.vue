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
                    <input v-model="searchQuery" type="text" class="form-control form-custom-border" id="searchData"
                      name="searchData">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="row">
              <div class="col-12 col-md-3">
                <button @click="searchPostData" type="submit" class="btn btn-primary common-btn"
                  style="width: 100%">Search</button>
              </div>
              <div class="col-12 col-md-3">
                <router-link to="/PostCreate" class="btn btn-primary common-btn" style="width: 100%">
                  Create
                </router-link>
              </div>
              <div v-if="currentUser" class="col-12 col-md-3">
                <router-link to="/UploadCSV" class="btn btn-primary common-btn" style="width: 100%">
                  Upload
                </router-link>
              </div>
              <div v-else class="col-12 col-md-3">
                <button class="btn btn-primary common-btn" style="width: 100%" disabled>
                  Upload
                </button>
              </div>
              <div class="col-12 col-md-3">
                <button @click="downloadPostCSV" type="button" class="btn btn-primary common-btn"
                  style="width: 100%">Download</button>
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
          <tbody v-if="posts.length > 0">
            <tr v-for="post in posts" :key="post.id">
              <td v-if="currentUser" class="text-success" data-bs-toggle="modal" data-bs-target="#postDetailBtn"
                @click="showPostDetail(post)">{{ post.title }}</td>
              <td v-else>{{ post.title }}</td>
              <td>{{ post.description }}</td>
              <td>{{ findCreatedUserName(post.create_user_id) }}</td>
              <td>{{ formatDate(post.created_at) }}</td>
              <td>
                <router-link :to="'/PostEdit/' + post.id" v-if="currentUser">
                  <button type="button" class="btn btn-primary mx-3">Edit</button>
                </router-link>
                <button type="button" class="btn btn-primary" v-else disabled>Edit</button>
                <button v-if="currentUser" type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#postDeleteBtn"
                  :disabled= "!currentUser" @click="showDeleteConfirmation(post)">
                  Delete
                </button>
                <button v-else type="button" class="btn btn-danger" disabled>
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td></td>
              <td></td>
              <td>No data available in table.</td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
        <div class="d-flex justify-content-end">
          <paginate :page-count="totalPages" :click-handler="searchPostData" :margin-pages="2" :prev-text="'Previous'"
            :next-text="'Next'" :container-class="'pagination'" :page-class="'page-item'"></paginate>
        </div>
        <div class="modal post-modal-custom fade" :class="{ 'show': showModalDelete }" id="postDeleteBtn"
          data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="postDeleteBtn"
          aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <p class="modal-title fs-6" id="postDeleteBtn">
                  Delete Confirm
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    <p class="text-start fst-italic text-danger">{{ selectedPostStatus }}</p>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="closeDeleteConfirmation" data-bs-dismiss="modal">
                  Close
                </button>
                <button type="button" class="btn btn-danger" @click="deletePost(selectedPost.id)">Delete</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal post-detail-modal-custom fade" :class="{ 'show': showModalPostDetail }" id="postDetailBtn"
          data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="postDetailBtn"
          aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <p class="modal-title fs-6" id="postDetailBtn">
                  Post Detail
                </p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    <p class="fst-italic text-danger">{{ postDetailStatus }}</p>
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
                    <p>Created User</p>
                  </div>
                  <div class="col-12 col-md-7">
                    <p class="fst-italic text-danger">{{ findCreatedUserName(postDetail.create_user_id) }}</p>
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
                    <p class="fst-italic text-danger">{{ findUpdatedUserName(postDetail.updated_user_id) }}</p>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="closePostDetail" data-bs-dismiss="modal">
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

<script setup>

  import { ref, onMounted, computed } from 'vue';
  import axiosInstance from '@/axios.js';
  import Paginate from 'vuejs-paginate-next';
  import { formatDate } from '@/dateUtils';

  //get current logged in user
  const currentUser = localStorage.getItem('user') || sessionStorage.getItem('user') || null;

  const posts = ref([]);

  const allUsers = ref([]);

  const totalPages = ref(0);

  const searchQuery = ref('');

  const postDetail = ref({});

  const selectedPost = ref({});

  const showModalPostDetail = ref(false);

  const showModalDelete = ref(false);

  const showPostDetail = (post) => {

    postDetail.value = post;

    showModalPostDetail.value = true;

  };

  const closePostDetail = () => {

    postDetail.value = '';

    showModalPostDetail.value = false;

  };

  const showDeleteConfirmation = (post) => {

    selectedPost.value = post;

    showModalDelete.value = true;

  };

  const closeDeleteConfirmation = () => {

    selectedPost.value = '';

    showModalDelete.value = false;

  };

  const deletePost = (postID) => {

    axiosInstance.delete(`/posts/${postID}`)
      .then(() => {

        closeDeleteConfirmation();

        window.location.reload();

      })
      .catch((error) => {

        console.error(error);

      });

  };

  const fetchPosts = (page = 1) => {

    axiosInstance.get(`/posts?page=${page}`)
      .then((response) => {

        posts.value = response.data.posts.data;

        totalPages.value = response.data.posts.last_page;

      })
      .catch((error) => {

        console.error(error);

      });

  };

  const fetchLimitedPosts = (page = 1) => {

    axiosInstance.get(`/showActivePosts?page=${page}`)
      .then((response) => {

        posts.value = response.data.posts.data;

        totalPages.value = response.data.posts.last_page;

      })
      .catch((error) => {

        console.error(error);

      });

    };

    const getLimitedUsers = () => {
    axiosInstance.get(`/showAllUsers`)
      .then((response) => {
        allUsers.value = response.data.allUsers;
      })
      .catch((error) => {
        console.error(error);
      });
  }

  const getAllUsers = () => {
    axiosInstance.get(`/showAllUsers`)
      .then((response) => {
        allUsers.value = response.data.allUsers;
      })
      .catch((error) => {
        console.error(error);
      });
  }

  function findCreatedUserName(userId) {

    const createdUser = allUsers.value.find(user => user.id === userId);

    return createdUser ? createdUser.name : null;

  }

  function findUpdatedUserName(userId) {

    const updatedUser = allUsers.value.find(user => user.id === userId);

    return updatedUser ? updatedUser.name : null;

  }

  onMounted(() => {

    if(currentUser) {

      getLimitedUsers();

      fetchPosts();

    } else {

      getAllUsers();

      fetchLimitedPosts();

    }


  });

  async function downloadPostCSV() {

    try {

      const response = await axiosInstance.get('/export', {

        responseType: 'blob'

      });

      const blob = new Blob([response.data], {

        type: 'application/csv'

      });

      const url = window.URL.createObjectURL(blob);

      const link = document.createElement('a');

      link.href = url;

      link.download = 'posts.csv';

      link.click();

      window.URL.revokeObjectURL(url);

    } catch (error) {

      console.error(error);

    }
  }

  async function searchPostData(page = 1) {

    if(currentUser) {
      try {

        const response = await axiosInstance.get(`posts?page=${page}&search=${searchQuery.value}`);

        posts.value = response.data.posts.data;

        totalPages.value = response.data.posts.last_page;

        } catch (error) {

        console.error('Error fetching posts:', error);

        }
    } else {
        try {

          const response = await axiosInstance.get(`showActivePosts?page=${page}&search=${searchQuery.value}`);

          posts.value = response.data.posts.data;

          totalPages.value = response.data.posts.last_page;

          } catch (error) {

          console.error('Error fetching posts:', error);

        }
    }

  }

  const postDetailStatus = computed(() => {

    return postDetail.value.status === 1 ? 'Active' : 'Inactive';

  });

  const selectedPostStatus = computed(() => {

    return selectedPost.value.status === 1 ? 'Active' : 'Inactive';

  });

</script>

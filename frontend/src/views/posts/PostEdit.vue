<template>
  <div class="container container-main my-5">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card custom-card" style="width: 700px">
        <div class="card-header card-header-bg">
          Edit Post
        </div>
        <div class="card-body mt-5">
          <form @submit.prevent="updatePost()">
            <div class="mb-3 row">
              <label for="title" class="text-right-label col-12 col-md-4 col-form-label">Title<span
                  class="text-danger">*</span></label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <input v-model="formData.title" type="text" class="form-control" id="title" name="title">
                    <div v-if="titleError" class="text-danger">{{ titleError }}</div>
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="description" class="text-right-label col-12 col-md-4 col-form-label">Description<span
                  class="text-danger">*</span></label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <textarea v-model="formData.description" class="form-control" id="description" name="description"
                      rows="3"></textarea>
                    <div v-if="descriptionError" class="text-danger">{{ descriptionError }}</div>
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="status" class="text-right-label col-12 col-md-4 col-form-label">Status</label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10 mt-2">
                    <div class="form-check form-switch">
                      <input v-model="formData.status" class="form-check-input" type="checkbox" id="status" name="status"
                        true-value="1" false-value="0">
                      <div v-if="statusError" class="text-danger">{{ statusError }}</div>
                    </div>
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <div class="col-12 col-md-4"></div>
              <div class="col-12 col-md-8">
                <button type="submit" class="btn btn-primary">Edit</button>
                <button type="button" class="btn btn-secondary mx-3" @click="clearPostData">Clear</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>

  import { ref, onMounted } from 'vue';
  import { useRoute, useRouter } from 'vue-router';
  import axiosInstance from '@/axios.js';
  import { useStore } from 'vuex';

  //for route params
  const route = useRoute();

  //for route change
  const router = new useRouter();

  //for vuex
  const store = useStore();

  //get current logged in user
  const user = JSON.parse(localStorage.getItem('user')) || JSON.parse(sessionStorage.getItem('user')) || null;

  //get data of post that will be edited
  const currentPostData = ref({});

  //if there is postedit data
  const postEditData = store.getters.getPostEditData;

  console.log(postEditData)

  const formData = ref({
    title: '',
    description: '',
    status: '',
    create_user_id: '',
    updated_user_id: user.id
  });

  const titleError = ref('');

  const descriptionError = ref('');

  const statusError = ref('');

  async function updatePost() {

    try {

      sessionStorage.removeItem('currentPostData');

      const response = await axiosInstance.put(`/posts/${route.params.postID}`, formData.value);

      titleError.value = '';

      descriptionError.value = '';

      statusError.value = '';

      console.log('Updated post successfully!', response.data);

      store.dispatch('updatePostEditData', formData);

      router.push(`/PostEditConfirm/${route.params.postID}`);

    } catch (error) {

      if (error.response) {

        const { errors } = error.response.data;

        if (errors) {

          if (errors.title) {

            titleError.value = errors.title[0] || '';

          }
          if (errors.description) {

            descriptionError.value = errors.description[0] || '';

          }
          if (errors.status) {

            statusError.value = errors.status[0] || '';

          }
        }
      }
      console.error(error);
    }
  }

  function clearPostData() {

    currentPostData.value = JSON.parse(sessionStorage.getItem('currentPostData'))

    formData.value.title = currentPostData.value.title;

    formData.value.description = currentPostData.value.description;

    formData.value.status = currentPostData.value.status;

    store.dispatch('deletePostEditData')

    }

  const fetchDataAndSetFormData = () => {
    axiosInstance.get(`/posts/${route.params.postID}`)
      .then((response) => {
          formData.value = response.data.post;

          sessionStorage.setItem('currentPostData', JSON.stringify(response.data.post));
    })
        .catch((error) => console.log(error));
  }

  const fetchStoredEditDataAndSetFormData = () => {
    formData.value = postEditData;
  }

  onMounted(() => {
    const jsonData = { "postEditData": {} };
    if (!postEditData &&  Object.keys(jsonData.postEditData).length === 0)  {
      console.log("post edit data is null")
      fetchDataAndSetFormData();
    } else {
      console.log("not null")
      fetchStoredEditDataAndSetFormData();
    }
  });

  const removeDataFromSessionStorage = () => {
    store.dispatch('deletePostEditData');
  };

  const beforeRouteLeave = () => {

    router.beforeEach((to, from, next) => {
      if (from.path.toLowerCase().startsWith('/postedit/') && !to.path.toLowerCase().startsWith('/posteditconfirm/')) {
        alert('remove')
        removeDataFromSessionStorage();
      }
      next();
  });

  };

  beforeRouteLeave();

</script>

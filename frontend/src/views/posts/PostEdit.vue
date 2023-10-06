<template>
  <div class="container container-main my-5">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card custom-card" style="width: 700px">
        <div class="card-header card-header-bg">
          Edit Post
        </div>
        <div class="card-body mt-5">
          {{ $route.path.params }}
          <form @submit.prevent="updatePost()">
            <div class="mb-3 row">
              <label for="title" class="text-right-label col-12 col-md-4 col-form-label">Title<span
                  class="text-danger">*</span></label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <input v-model="formData.title" type="text" class="form-control" id="title" name="title">
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

  const currentPostData = ref({})

  const route = useRoute();

  const router = new useRouter();

  const store = useStore();

  const postEditData = store.getters.getPostEditData;

  const formData = ref({
    title: postEditData?.title ?? '',
    description: postEditData?.description ?? '',
    status: postEditData?.status ?? '',
    create_user_id: postEditData?.create_user_id ?? '',
    updated_user_id: postEditData?.updated_user_id ?? '',
  });

  const titleError = ref('');

  const descriptionError = ref('');

  const statusError = ref('');

  async function updatePost() {

    try {

      localStorage.removeItem('currentPostData');

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

  onMounted(() => {

    axiosInstance.get(`/posts/${route.params.postID}`)
      .then((response) => {

        if (postEditData) {

          formData.value = postEditData

        } else {

          formData.value = response.data.post;

        }

        localStorage.setItem('currentPostData', JSON.stringify(response.data.post))

      })
      .catch((error) => console.log(error));
  });

  function clearPostData() {

    currentPostData.value = JSON.parse(localStorage.getItem('currentPostData'))

    formData.value.title = currentPostData.value.title;

    formData.value.description = currentPostData.value.description;

    formData.value.status = currentPostData.value.status

    store.dispatch('deletePostEditData')

  }

</script>

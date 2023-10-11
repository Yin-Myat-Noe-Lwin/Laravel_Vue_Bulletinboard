<template>
  <div class="container container-main my-5">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card custom-card" style="width: 700px">
        <div class="card-header card-header-bg">
          Create Post
        </div>
        <div class="card-body mt-5">
          <form @submit.prevent="createPost">
            <div class="mb-3 row visually-hidden">
              <label for="flg" class="text-right-label col-12 col-md-4 col-form-label">Confirm flag<span
                  class="text-danger">*</span></label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <input v-model="formData.flg" type="text" class="form-control" id="flg" name="flg">
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
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
            <div class="mb-3 row visually-hidden">
              <label for="status" class="text-right-label col-12 col-md-4 col-form-label">Status<span
                  class="text-danger">*</span></label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <input v-model="formData.status" type="text" class="form-control" id="status" name="status">
                    <div v-if="statusError" class="text-danger">{{ statusError }}</div>
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row visually-hidden">
              <label for="create_user_id" class="text-right-label col-12 col-md-4 col-form-label">Created User</label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <input v-model="formData.create_user_id" class="form-control" id="create_user_id"
                      name="create_user_id">
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row visually-hidden">
              <label for="updated_user_id" class="text-right-label col-12 col-md-4 col-form-label">Updated User</label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <input v-model="formData.updated_user_id" class="form-control" id="updated_user_id"
                      name="updated_user_id">
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <div class="col-12 col-md-4"></div>
              <div class="col-12 col-md-8">
                <button type="submit" class="btn btn-primary">Create</button>
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

  import { ref } from 'vue';

  import axiosInstance from '@/axios.js';

  import { useRouter } from 'vue-router';

  import { useStore } from 'vuex';

  //for route change
  const router = useRouter();

  //for vuex
  const store = useStore();

  //get current logged in user
  const user = JSON.parse(localStorage.getItem('user')) || JSON.parse(sessionStorage.getItem('user')) || null;

  //check if there is previous saved post data
  const postData = store.getters.getPostData;

  const formData = ref({
    title: postData?.title ?? '',
    description: postData?.description ?? '',
    status: 1,
    flg: 'unconfirm',
    create_user_id: user.id,
    updated_user_id: user.id
  });

  const titleError = ref('');

  const descriptionError = ref('');

  const statusError = ref('');

  async function createPost() {

    try {

      const response = await axiosInstance.post('/posts', formData.value);

      titleError.value = '';

      descriptionError.value = '';

      statusError.value = '';

      console.log('Created post successfully!', response.data);

      //store created post data
      store.dispatch('updatePostData', formData);

      router.push('/PostCreateConfirm');

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

    formData.value.title = ''

    formData.value.description = ''

    titleError.value = '';

    descriptionError.value = '';

    statusError.value = '';

    //delete stored post data
    store.dispatch('deletePostData')
  }

</script>

<template>
  <div class="container container-main my-5">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card custom-card" style="width:700px">
        <div class="card-header card-header-bg">
          Edit Post
        </div>
        <div class="card-body mt-5">
          <form @submit.prevent="confirmEditPost">
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
                    <input v-model="formData.title" type="text" class="form-control" id="title" name="title" disabled>
                    <div v-if="titleError" class="text-danger">{{ titleError }}</div>
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="email" class="text-right-label col-12 col-md-4 col-form-label">Description<span
                  class="text-danger">*</span></label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <input v-model="formData.description" type="text" class="form-control" id="description"
                      name="description" disabled>
                    <div v-if="descriptionError" class="text-danger">{{ descriptionError }}</div>
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="status" class="text-right-label col-12 col-md-4 col-form-label">Status<span
                  class="text-danger">*</span></label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10 mt-2">
                    <div class="form-check form-switch">
                      <input v-model="formData.status" class="form-check-input" type="checkbox" id="status" name="status"
                        true-value="1" false-value="0" disabled>
                      <div v-if="statusError" class="text-danger">{{ statusError }}</div>
                    </div>
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
                      name="create_user_id" disabled>
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
                      name="updated_user_id" disabled>
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <div class="col-12 col-md-4"></div>
              <div class="col-12 col-md-8">
                <button type="submit" class="btn btn-primary">Confirm</button>
                <button type="button" class="btn btn-secondary mx-3" @click="cancelCreatePost">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>

  import { useStore } from 'vuex';
  import { ref } from 'vue';
  import { useRoute, useRouter } from 'vue-router';
  import axiosInstance from '@/axios.js';

  //get current logged in user
  const user = JSON.parse(localStorage.getItem('user')) || JSON.parse(sessionStorage.getItem('user')) || null;

  const store = useStore();

  const router = new useRouter();

  const route = new useRoute();

  //get stored data  from post edit page
  const postEditData = ref(null);

  postEditData.value = store.getters.getPostEditData;

  console.log(postEditData.value)

  const formData = ref({
    title: postEditData.value.title,
    description: postEditData.value.description,
    status: postEditData.value.status,
    flg: 'unconfirm',
    create_user_id: postEditData.value.create_user_id,
    updated_user_id: user.id
  })

  const titleError = ref('');

  const descriptionError = ref('');

  const statusError = ref('');

  async function confirmEditPost() {

    try {

      formData.value.flg = 'confirm';

      //delete stored data after update
      store.dispatch('deletePostEditData');

      const response = await axiosInstance.put(`/posts/${route.params.postID}`, formData.value);

      console.log('Confirm post edit successfully!', response.data);

      router.push('/PostList')

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

  const removeDataFromSessionStorage = () => {
    store.dispatch('deletePostEditData');
  };

  const beforeRouteLeave = () => {

    router.beforeEach((to, from, next) => {
      if (from.path.toLowerCase().startsWith('/posteditconfirm/') && !to.path.toLowerCase().startsWith('/postedit/')) {
        alert('remove')
        removeDataFromSessionStorage();
      }
      next();
  });

  };

  beforeRouteLeave();

  function cancelCreatePost() {

    //if cancel, go back to edit page
    router.push(`/PostEdit/${route.params.postID}`)

  }

</script>

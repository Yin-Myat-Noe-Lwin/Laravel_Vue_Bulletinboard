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

  const store = useStore();

  const router = new useRouter();

  const route = new useRoute();

  const postEditData = store.getters.getPostEditData;

  const formData = ref({
    title: postEditData.title,
    description: postEditData.description,
    status: postEditData.status,
    flg: 'unconfirm',
    create_user_id: postEditData.create_user_id,
    updated_user_id: postEditData.updated_user_id
  })

  async function confirmEditPost() {

    try {

      formData.value.flg = 'confirm';

      store.dispatch('deletePostEditData')

      const response = await axiosInstance.put(`/posts/${route.params.postID}`, formData.value);

      console.log('Confirm post edit successfully!', response.data);

      router.push('/PostList')

    } catch (error) {

      console.error(error);

    }
  }

  function cancelCreatePost() {

    router.push(`/PostEdit/${route.params.postID}`)

  }

</script>

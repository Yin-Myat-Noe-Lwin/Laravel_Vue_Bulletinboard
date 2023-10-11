<template>
  <div class="container container-main my-5">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card custom-card" style="width:700px">
        <div class="card-header card-header-bg">
          Create Post
        </div>
        <div class="card-body mt-5">
          <form @submit.prevent="confirmCreatePost" enctype="multipart/form-data">
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
                    <textarea v-model="formData.description" class="form-control" id="description" name="description"
                      rows="3" disabled></textarea>
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
                    <input v-model="formData.status" type="text" class="form-control" id="status" name="status" disabled>
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

  import { ref } from 'vue';
  import axiosInstance from '@/axios.js';
  import { useRouter } from 'vue-router';
  import { useStore } from 'vuex';
  import {firebaseConfig} from '@/firebaseConfig'
  import { initializeApp } from "firebase/app";
  import { getAnalytics } from "firebase/analytics";

  //for route change
  const router = new useRouter();

  //for vuex
  const store = useStore();

  //initialize firebase browser notification configuration
  const app = initializeApp(firebaseConfig);

  const analytics = getAnalytics(app);

  const requestNotificationPermission = async () => {
    try {
      const permission = await Notification.requestPermission();
      if (permission === "granted") {
        //if permission granted, send browser notification
        sendNotification();
      } else {
        console.warn("Notification permission denied. You may not receive notifications.");
      }
    } catch (error) {
      console.error("Error requesting notification permission:", error);
    }
  };

  const sendNotification = () => {
    const notificationOptions = {
      body: "Your Post was created",
    };

    new Notification("Post Create Notification", notificationOptions);
  };

  //get stored post data from create post page
  const postData = store.getters.getPostData;

  const formData = ref({
    title: postData.title,
    description: postData.description,
    status: postData.status,
    flg: 'unconfirm',
    create_user_id: postData.create_user_id,
    updated_user_id: postData.updated_user_id
  })

  const titleError = ref('');

  const descriptionError = ref('');

  const statusError = ref('');

  async function confirmCreatePost() {

    try {

      titleError.value = '';

      descriptionError.value = '';

      statusError.value = '';

      formData.value.flg = 'confirm';

      //create is confirmed then delete stored post data
      store.dispatch('deletePostData');

      //after post is created, user will get browser notification
      requestNotificationPermission();

      const response = await axiosInstance.post('/posts', formData.value);

      console.log('Confirm post creation successfully!', response.data);

      router.push('/PostList');

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

  function cancelCreatePost() {

    //if cancel, go to create post page
    router.push('PostCreate')

  }

</script>

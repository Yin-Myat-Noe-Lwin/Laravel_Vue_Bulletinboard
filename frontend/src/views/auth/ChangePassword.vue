<script setup>

  import { ref } from 'vue';
  import axiosInstance from '@/axios.js';
  import { useRouter } from 'vue-router';

  const user = JSON.parse(localStorage.getItem('user'));

  const formData = ref({
    old_password: '',
    password: '',
    password_confirmation: ''
  });

  const router = useRouter();

  const currentPasswordError = ref('');

  const newPasswordError = ref('');

  const newConfirmPasswordError = ref('');

  async function updatePassword() {
      try{
        const response = await axiosInstance.post(`/users/${user.id}/changePassword`, formData.value);
        currentPasswordError.value = '';
        newPasswordError.value = '';
        newConfirmPasswordError.value = '';
        console.log('Changed password successfully!', response.data);
        router.push('/');
      }catch (error) {
        if(error.response){
        const { errors } = error.response.data;
        if(errors) {
          if (errors.old_password) {
            currentPasswordError.value = errors.old_password[0] || '';
        }
          if (errors.password) {
            newPasswordError.value = errors.password[0] || '';
          }
          if (errors.password_confirmation) {
            newConfirmPasswordError.value = errors.password_confirmation[0] || '';
          }
        }
      }
        console.error(error);
      }
    }

</script>

<template>
  <div class="container container-main my-5">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card" style="width:700px">
      <div class="card-header card-header-bg">
        Change Password
      </div>
      <div class="card-body mt-5">
        <form @submit.prevent = "updatePassword">
          <div class="mb-3 row">
          <label for="password" class="text-right-label col-12 col-md-4 col-form-label">Current Password<span class="text-danger">*</span></label>
          <div class="col-12 col-md-8">
            <div class="row">
              <div class="col-12 col-md-10">
                <input v-model="formData.old_password" type="password" class="form-control" id="old_password" name="old_password">
                <div v-if="currentPasswordError" class="text-danger">{{ currentPasswordError }}</div>
              </div>
              <div class="col-12 col-md-2"></div>
            </div>
          </div>
        </div>
        <div class="mb-3 row">
          <label for="password" class="text-right-label col-12 col-md-4 col-form-label">New Password<span class="text-danger">*</span></label>
          <div class="col-12 col-md-8">
            <div class="row">
              <div class="col-12 col-md-10">
                <input v-model="formData.password" type="password" class="form-control" id="password" name="password">
                <div v-if="newPasswordError" class="text-danger">{{ newPasswordError }}</div>
              </div>
              <div class="col-12 col-md-2"></div>
            </div>
          </div>
        </div>
        <div class="mb-3 row">
          <label for="password" class="text-right-label col-12 col-md-4 col-form-label">New Confirm Password<span class="text-danger">*</span></label>
          <div class="col-12 col-md-8">
            <div class="row">
              <div class="col-12 col-md-10">
                <input v-model="formData.password_confirmation" type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                <div v-if="newConfirmPasswordError" class="text-danger">{{ newConfirmPasswordError }}</div>
              </div>
              <div class="col-12 col-md-2"></div>
            </div>
          </div>
        </div>
        <div class="mb-3 row">
          <div class="col-12 col-md-4">
          </div>
          <div class="col-12 col-md-8">
            <button type="submit" class="btn btn-primary">Update Password</button>
          </div>
        </div>
        </form>
      </div>
    </div>
    </div>
  </div>
</template>

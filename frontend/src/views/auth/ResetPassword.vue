<template>
  <div class="container container-main my-5">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card" style="width:700px">
        <div class="card-header card-header-bg">
          Reset Password
        </div>
        <div class="card-body mt-5">
          <form @submit.prevent="resetPassword">
            <div class="mb-3 row">
              <label for="password" class="text-right-label col-12 col-md-4 col-form-label">Password:</label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <input v-model="formData.password" type="password" class="form-control" id="password" name="password">
                    <div v-if="passwordError" class="text-danger">{{ passwordError }}</div>
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="password_confirmation" class="text-right-label col-12 col-md-4 col-form-label">Password
                Confirmation:</label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <input v-model="formData.password_confirmation" type="password" class="form-control"
                      id="password_confirmation" name="password_confirmation">
                    <div v-if="passwordConfirmationError" class="text-danger">{{ passwordConfirmationError }}</div>
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

<script setup>

  import { ref } from 'vue';
  import axiosInstance from '@/axios.js';
  import { useRoute, useRouter } from 'vue-router';

  const $route = useRoute()

  const userId = $route.params.userId;
  const token = $route.params.token;

  console.log(userId)

  console.log(token)

  const router = useRouter()

  const formData = ref({
    password: '',
    password_confirmation: '',
    userId: userId,
    token: token
  });

  const passwordError = ref('');

  const passwordConfirmationError = ref('');

  const tokenError = ref('');

  async function resetPassword() {
    try {
      const response = await axiosInstance.post(`/resetPassword`, formData.value);
      passwordError.value = '';
      passwordConfirmationError.value = '';
      tokenError.value = '';
      console.log('Changed password successfully!', response.data);
      sessionStorage.setItem("successMessage", response.data.message);
      router.push('/login');
    } catch (error) {
      if (error.response) {
        const { errors } = error.response.data;
        if (errors) {
          if (errors.password) {
            passwordError.value = errors.password[0] || '';
          }
          if (errors.password_confirmation) {
            passwordConfirmationError.value = errors.password_confirmation[0] || '';
          }
        }else {

        tokenError.value = error.response.data.error;

        sessionStorage.setItem('tokenErrorMessage', tokenError.value);

        router.push('/login');

        }
      }
      console.error(error);
    }
  }

</script>


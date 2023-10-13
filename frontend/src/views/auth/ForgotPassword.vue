<template>
  <div class="container container-main my-5">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card" style="width:600px">
        <div class="card-header card-header-bg">
          Forgot Password?
        </div>
        <div class="card-body">
          <div v-if="successMessage">
            {{ successMessage }}
          </div>
          <div v-if="alertError" class="alert alert-danger" role="alert">
            {{ alertError }}
          </div>
          <form @submit.prevent="forgotPassword">
            <div class="mb-3 mt-4 row">
              <label for="email" class="text-right-label col-12 col-md-4 col-form-label">Email:</label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <input v-model="formData.email" type="text" class="form-control" id="email" name="email">
                    <div v-if="emailError" class="text-danger">{{ emailError }}</div>
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <div class="col-12 col-md-4">
              </div>
              <div class="col-12 col-md-8">
                <button type="submit" class="btn btn-primary">Reset Password</button>
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
  import { useRouter } from "vue-router";
  import axiosInstance from '@/axios.js';

  //for route change
  const router = useRouter();

  const formData = ref({
    email: ''
  });

  const emailError = ref('');

  const alertError = ref('');

  async function forgotPassword() {

    try {

      const response = await axiosInstance.post('/forgotPassword', formData.value);

      emailError.value = '';

      alertError.value = '';

      console.log('Forgot password successfully!', response.data);

      //store forgot password success message
      sessionStorage.setItem("successMessage", response.data.message);

      //redirect to login page
      router.push("/login");

    } catch (error) {

      if (error.response) {

        const { errors } = error.response.data;

        if (errors) {

          if (errors.email) {

            alertError.value = '';

            emailError.value = errors.email[0] || '';

          }

        } else {

          emailError.value = '';

          alertError.value = error.response.data.error;

        }

      }

      console.error('Forgot Password failed:', error);

    }

  }

</script>

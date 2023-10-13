<template>
  <div class="container container-main my-5">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card" style="width: 700px">
        <div class="card-header card-header-bg">Log in</div>
        <div class="card-body">
          <div v-if="alertError" class="alert alert-danger" role="alert">
            {{ alertError }}
          </div>
          <div v-if="showSuccessMessage" class="alert alert-success" role="alert">
            {{ successMessage }}
          </div>
          <div v-if="showTokenErrorMessage" class="alert alert-danger" role="alert">
            {{ tokenErrorMessage }}
          </div>
          <form @submit.prevent="login">
            <div class="mb-3 mt-5 row">
              <label for="email" class="text-right-label col-12 col-md-4 col-form-label">Email Address:</label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <input v-model="formData.email" type="text" class="form-control" id="email" name="email" />
                    <div v-if="emailError" class="text-danger">
                      {{ emailError }}
                    </div>
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="password" class="text-right-label col-12 col-md-4 col-form-label">Password:</label>
              <div class="col-12 col-md-8">
                <div class="col-12 col-md-10">
                  <input v-model="formData.password" type="password" class="form-control" id="password" name="password" />
                  <div v-if="passwordError" class="text-danger">
                    {{ passwordError }}
                  </div>
                </div>
                <div class="col-12 col-md-2"></div>
              </div>
            </div>
            <div class="mb-3 row">
              <div class="col-12 col-md-4"></div>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-6">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="rememberMe" v-model="rememberMe" />
                      <label class="form-check-label" for="flexCheckDefault">
                        remember me
                      </label>
                    </div>
                  </div>
                  <div class="col-12 col-md-6">
                    <RouterLink to="/ForgotPassword" aria-current="page" class="link-class text-right-label">forgotten
                      password?
                    </RouterLink>
                  </div>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <div class="col-12 col-md-4"></div>
              <div class="col-12 col-md-8">
                <div class="col-12 col-md-10">
                  <button type="submit" class="btn common-btn" style="width: 100%">
                    Login
                  </button>
                </div>
                <div class="col-12 col-md-2"></div>
              </div>
            </div>
            <div class="mb-3 row">
              <div class="col-12 col-md-4"></div>
              <div class="col-12 col-md-8">
                <RouterLink to="/signup" aria-current="page" class="link-class">Create Account?<font-awesome-icon
                    :icon="['fas', 'user-plus']" /></RouterLink>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>

  import { ref, watch, onMounted } from "vue";
  import { useRouter } from "vue-router";
  import axiosInstance from "@/axios.js";

  //for route change
  const router = useRouter();

  //initially set rememberMe value to false
  const rememberMe = ref(false);

  watch(rememberMe, (newVal) => {

    //rememberMe value on  real time change
    formData.value.rememberme = newVal;

  });

  const formData = ref({
    email: '',
    password: '',
    rememberme: rememberMe.value
  });

  const emailError = ref('');

  const passwordError = ref('');

  const alertError = ref('');

  //fetch success message value if there is
  const successMessage = ref(sessionStorage.getItem('successMessage'));

  const showSuccessMessage = ref(false);

  function showForgotPasswordSuccessMessage() {

    if (successMessage.value) {

      showSuccessMessage.value = true;

      // Automatically hide the message after 5 seconds
      setTimeout(() => {

        showSuccessMessage.value = false;

        //remove stored message after show
        sessionStorage.removeItem('successMessage');

      }, 5000);

    }

  }

  //fetch error message value if there is
  const tokenErrorMessage = ref(sessionStorage.getItem('tokenErrorMessage'));

  const showTokenErrorMessage = ref(false);

  function showResetPasswordTokenErrorMessage() {

    if (tokenErrorMessage.value) {

      showTokenErrorMessage.value = true;

      // Automatically hide the message after 5 seconds
      setTimeout(() => {

        showTokenErrorMessage.value = false;

        //remove stored message after show
        sessionStorage.removeItem('tokenErrorMessage');

      }, 5000);

    }

  }

  async function login() {

    try {

      const response = await axiosInstance.post(`/login`, formData.value);

      emailError.value = '';

      passwordError.value = '';

      alertError.value = '';

      console.log("Logged in successfully!", response.data);

      if (formData.value.rememberme) {

        //if user checks remember me, store in local storage
        localStorage.setItem("token", response.data.token);

        localStorage.setItem("user", JSON.stringify(response.data.user));

      } else {

        //if not, store in session storage
        sessionStorage.setItem("token", response.data.token);

        sessionStorage.setItem("user", JSON.stringify(response.data.user));

      }

      //redirect to Post list page
      router.push("/");

      //reload the page
      setTimeout(() => {

        window.location.reload();

      }, 5);

    } catch (error) {

      if (error.response) {

        const { errors } = error.response.data;

        if (errors) {

          if (errors.email) {

            alertError.value = "";

            emailError.value = errors.email[0] || "";

          }
          if (errors.password) {

            alertError.value = "";

            passwordError.value = errors.password[0] || "";

          }
        } else {

          emailError.value = "";

          passwordError.value = "";

          alertError.value = error.response.data.error;

        }

      }

      console.error("Login failed:", error);

    }

  }

  onMounted(() => {

    showForgotPasswordSuccessMessage();

    showResetPasswordTokenErrorMessage();

  })

</script>

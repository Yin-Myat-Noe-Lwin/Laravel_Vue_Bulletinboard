<template>
  <div class="container container-main my-5">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card custom-card" style="width:700px">
        <div class="card-header card-header-bg">
          Register Confirm
        </div>
        <div class="card-body mt-5">
          <form @submit.prevent="confirmCreateUser" enctype="multipart/form-data">
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
              <label for="name" class="text-right-label col-12 col-md-4 col-form-label">Name<span
                  class="text-danger">*</span></label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <input v-model="formData.name" type="text" class="form-control" id="name" name="name" disabled>
                    <div v-if="nameError" class="text-danger">{{ nameError }}</div>
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="email" class="text-right-label col-12 col-md-4 col-form-label">E-Mail Address<span
                  class="text-danger">*</span></label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <input v-model="formData.email" type="text" class="form-control" id="email" name="email" disabled>
                    <div v-if="emailError" class="text-danger">{{ emailError }}</div>
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="password" class="text-right-label col-12 col-md-4 col-form-label">Password<span
                  class="text-danger">*</span></label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <input v-model="formData.password" type="password" class="form-control" id="password" name="password"
                      disabled>
                    <div v-if="passwordError" class="text-danger">{{ passwordError }}</div>
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="password_confirmation" class="text-right-label col-12 col-md-4 col-form-label">Password
                Confirmation<span class="text-danger">*</span></label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <input v-model="formData.password_confirmation" type="password" class="form-control"
                      id="password_confirmation" name="password_confirmation" disabled>
                    <div v-if="passwordConfirmationError" class="text-danger">{{ passwordError }}</div>
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="type" class="text-right-label col-12 col-md-4 col-form-label">Type</label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <input v-model="userType" type="text" class="form-control" id="type" name="type" disabled>
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="phone" class="text-right-label col-12 col-md-4 col-form-label">Phone</label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <input v-model="formData.phone" type="text" class="form-control" id="phone" name="phone" disabled>
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="dob" class="text-right-label col-12 col-md-4 col-form-label">Date of birth</label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <input v-model="formData.dob" type="date" class="form-control" id="dob" name="dob" disabled>
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="address" class="text-right-label col-12 col-md-4 col-form-label">Address</label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <input v-model="formData.address" type="text" class="form-control" id="address" name="address"
                      disabled>
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="profile" class="text-right-label col-12 col-md-4 col-form-label">Profile<span
                  class="text-danger">*</span></label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <img :src="profileDataURL" class="img-fluid" alt="user-img" style="width:150px; height:150px" />
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
                <button type="button" class="btn btn-secondary mx-3" @click="cancelCreateUser">Cancel</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>

  import { ref, computed } from 'vue';
  import axiosInstance from '@/axios.js';
  import { useRouter } from 'vue-router';
  import { useStore } from 'vuex';
  import { dataURItoFile } from '@/dataUriUtils';

  //for route change
  const router = new useRouter();

  //for vuex
  const store = useStore();

  //get stored userdata filled in user create page
  const userData = store.getters.getUserData;

  const formData = ref({
    name: userData.name,
    email: userData.email,
    password: userData.password,
    password_confirmation: userData.password_confirmation,
    phone: userData.phone,
    address: userData.address,
    dob: userData.dob,
    type: userData.type,
    profile: null,
    flg: 'unconfirm',
    create_user_id: userData.create_user_id,
    updated_user_id: userData.updated_user_id
  })

  const nameError = ref('');

  const emailError = ref('');

  const passwordError = ref('');

  const passwordConfirmationError = ref('');

  const profileError = ref('');

  const profileDataURL = sessionStorage.getItem('file');

   //change profile image into image file
  const imageFile = dataURItoFile(profileDataURL, 'image.jpg', 'image/jpeg');

  formData.value.profile = imageFile

  async function confirmCreateUser() {

    try {

      formData.value.flg = 'confirm';

      const response = await axiosInstance.post('/users', formData.value, { headers: { 'Content-Type': 'multipart/form-data' } });

      nameError.value = '';

      emailError.value = '';

      passwordError.value = '';

      passwordConfirmationError.value = '';

      profileError.value = '';

      console.log('Confirm user creation successfully!', response.data);

      store.dispatch('deleteUserData');

      sessionStorage.removeItem('file');

      router.push('/UserList');

    } catch (error) {

      profileError.value = '';

      if (error.response) {

        const { errors } = error.response.data;

        if (errors) {

          if (errors.name) {

            nameError.value = errors.name[0] || '';

          }
          if (errors.email) {

            emailError.value = errors.email[0] || '';

          }
          if (errors.password) {

            passwordError.value = errors.password[0] || '';

          }
          if (errors.password_confirmation) {

            passwordConfirmationError.value = errors.password_confirmation[0] || '';

          }
          if (errors.profile) {

            profileError.value = errors.profile[0] || '';

          }
        }
      }
      console.error(error);
    }
  }

  function cancelCreateUser() {

    //if cancel, go to user create page
    router.push('/UserCreate');

  }

  const userType = computed(() => {

    return formData.value.type === 0 ? 'Admin' : 'User';

  });

</script>

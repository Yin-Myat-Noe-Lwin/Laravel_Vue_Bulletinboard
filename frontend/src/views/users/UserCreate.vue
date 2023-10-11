<template>
  <div class="container container-main my-5">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card custom-card" style="width:700px">
        <div class="card-header card-header-bg">
          Register
        </div>
        <div class="card-body mt-5">
          <form @submit.prevent="createUser" enctype="multipart/form-data">
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
                    <input v-model="formData.name" type="text" class="form-control" id="name" name="name">
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
                    <input v-model="formData.email" type="text" class="form-control" id="email" name="email">
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
                    <input v-model="formData.password" type="password" class="form-control" id="password" name="password">
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
                      id="password_confirmation" name="password_confirmation">
                    <div v-if="passwordConfirmationError" class="text-danger">{{ passwordConfirmationError }}</div>
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
                    <select v-model="formData.type" class="form-select" aria-label="Type">
                      <option value="0">Admin</option>
                      <option value="1">User</option>
                    </select>
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
                    <input v-model="formData.phone" type="text" class="form-control" id="phone" name="phone">
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
                    <input v-model="formData.dob" type="date" class="form-control" id="dob" name="dob">
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="address" class="text-right-label col-12 col-md-4 col-form-label">Address<span class="text-danger">*</span></label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <input v-model="formData.address" type="text" class="form-control" id="address" name="address">
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div v-if="userProfileImagePreviewUrl" class="mb-3 row">
              <label for="profile-preview" class="text-right-label col-12 col-md-4 col-form-label">Profile Image
                Preview</label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10 preview-img">
                    <font-awesome-icon :icon="['fas', 'circle-xmark']" class="circle-x-btn" @click="removeImg" />
                    <img :src="userProfileImagePreviewUrl" class="img-fluid" alt="user-profile-img-preview"
                      style="width:150px; height:150px" @change="handleImageUpload" />
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
                    <input class="form-control" type="file" id="profile" name="profile" ref="profileInput"
                      @change="handleImageUpload">
                    <div v-if="profileError" class="text-danger">{{ profileError }}</div>
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
                <button type="submit" class="btn btn-primary">Register</button>
                <button type="button" class="btn btn-secondary mx-3" @click="clearUserData">Clear</button>
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
  import axiosInstance from '@/axios.js';
  import { useRouter } from 'vue-router';
  import { useStore } from 'vuex';
  import { dataURItoFile } from '@/dataUriUtils';

  //for route change
  const router = useRouter();

  //for vuex
  const store = useStore();

  //get current logged in user
  const user = JSON.parse(localStorage.getItem('user')) || JSON.parse(sessionStorage.getItem('user')) || null;

  //check if there is any previously stored user data
  const userData = store.getters.getUserData;

  const formData = ref({
    name: userData?.name ?? '',
    email: userData?.email ?? '',
    password: userData?.password ?? '',
    password_confirmation: userData?.password_confirmation ?? '',
    phone: userData?.phone ?? '',
    address: userData?.address ?? '',
    dob: userData?.dob ?? '',
    type: userData?.type ?? '1',
    profile: userData?.profile ?? null,
    flg: 'unconfirm',
    create_user_id: user.id,
    updated_user_id: user.id
  });

  const nameError = ref('');

  const emailError = ref('');

  const passwordError = ref('');

  const passwordConfirmationError = ref('');

  const addressError = ref('');

  const profileError = ref('');

  const profileInput = ref('');

  const userProfileImagePreviewUrl = ref(null);

  //check and control user input profile image
  function handleImageUpload(event) {

    const imgFile = event.target.files[0];

    const imgSize = imgFile.size;

    const maxSize = 2 * 1024 * 1024;

    if (imgSize > maxSize) {

      profileError.value = "Image file size must be less than 2MB";

    } else {

      const reader = new FileReader();

      reader.onload = function (e) {

        //if image is ok to use, put it in image preview
        userProfileImagePreviewUrl.value = e.target.result;

      };

      reader.readAsDataURL(imgFile);

    }

  }

  async function createUser() {

    try {

      const dataURI =  userProfileImagePreviewUrl.value;

      if (dataURI) {
        //store user input profile image
        sessionStorage.setItem('file', dataURI);

        //change profile image into image file
        const imageFile = dataURItoFile(dataURI, 'image.jpg', 'image/jpeg');

        formData.value.profile = imageFile;
      }

      const response = await axiosInstance.post('/users', formData.value, { headers: { 'Content-Type': 'multipart/form-data' } });

      nameError.value = '';

      emailError.value = '';

      passwordError.value = '';

      passwordConfirmationError.value = '';

      addressError.value = '';

      profileError.value = '';

      console.log('Created user successfully!', response.data);

      //store user input data in vuex persist
      store.dispatch('updateUserData', formData);

      router.push('/UserCreateConfirm');

    } catch (error) {

      sessionStorage.removeItem('file');

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

          if (errors.address) {

            addressError.value = errors.address[0] || '';

            }

          if (errors.profile) {

            profileError.value = errors.profile[0] || '';

          }

        }

      }

      console.error(error);

    }

  }

  function clearUserData() {

    //reset some formData input values
    formData.value.name = '';

    formData.value.email = ''

    formData.value.password = ''

    formData.value.password_confirmation = ''

    formData.value.phone = '';

    formData.value.dob = '';

    formData.value.address = '';

    formData.value.type = '1';

    profileInput.value.value  = '';

    //clear stored user data
    store.dispatch('deleteUserData');

    //clear stored user input profile image
    sessionStorage.removeItem('file');

    //clear image preview
    userProfileImagePreviewUrl.value = null;

  }

  function removeImg() {

    //when click circle x button, remove profileInput value and image preview
    profileInput.value.value = '';

    userProfileImagePreviewUrl.value = null;

  }

  const fetchImgFileAndShowPreview = () => {

    //check if there is previously stored user input profile image
    const profileDataURL = sessionStorage.getItem('file');

    userProfileImagePreviewUrl.value = profileDataURL;

  }

  onMounted(() => {

    fetchImgFileAndShowPreview();

  });

</script>

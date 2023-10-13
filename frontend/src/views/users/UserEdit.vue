<template>
  <div class="container container-main my-5">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card custom-card" style="width: 700px">
        <div class="card-header card-header-bg">
          Profile Edit
        </div>
        <div class="card-body mt-5">
          <form @submit.prevent="updateUser(user.id)" enctype="multipart/form-data">
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
                    <input v-model="formData.email" type="email" class="form-control" id="email" name="email">
                    <div v-if="emailError" class="text-danger">{{ emailError }}</div>
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
                      <option value="0" selected>Admin</option>
                      <option value="1">User</option>
                    </select>
                    <div v-if="typeError" class="text-danger">{{ typeError }}</div>
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
                    <div v-if="phoneError" class="text-danger">{{ phoneError }}</div>
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
                    <input type="date" class="form-control" id="dob" name="dob" v-model="formData.dob">
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
                    <input v-model="formData.address" type="text" class="form-control" id="address" name="address">
                    <div v-if="addressError" class="text-danger">{{ addressError }}</div>
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div v-if="userProfileImagePreviewUrl" class="mb-3 row">
              <label for="profile" class="text-right-label col-12 col-md-4 col-form-label">New Profile</label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10 preview-img">
                    <font-awesome-icon :icon="['fas', 'circle-xmark']" class="circle-x-btn" @click="removeEditImg" />
                    <img :src="userProfileImagePreviewUrl" class="img-fluid rounded img-preview" alt="user-img"
                      style="width:150px; height:150px" />
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div v-else class="mb-3 row">
              <label for="profile" class="text-right-label col-12 col-md-4 col-form-label">Old Profile</label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <img :src="userProfileImageUrl" class="img-fluid rounded" alt="user-img" style="width:150px; height:150px" @change="onFileInputChange" />
                  </div>
                  <div class="col-12 col-md-2"></div>
                </div>
              </div>
            </div>
            <div class="mb-3 row">
              <label for="profile" class="text-right-label col-12 col-md-4 col-form-label">New Profile</label>
              <div class="col-12 col-md-8">
                <div class="row">
                  <div class="col-12 col-md-10">
                    <input class="form-control" type="file" id="profile" name="profile" ref="profileInput"
                     @change="onFileInputChange">
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
                <button type="submit" class="btn btn-primary">Edit</button>
                <button type="button" class="btn btn-secondary mx-3" @click="clearUserEditData">Clear</button>
                <router-link to="/ChangePassword" class="link-class text-primary">Change Password</router-link>
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
  import { useRouter } from 'vue-router';
  import axiosInstance from '@/axios.js';
  import { dataURItoFile } from '@/dataUriUtils';
  import { handleImageUpload  } from '@/imageUtils';

  //for route change
  const router = new useRouter();

  //get current logged in user
  const user = JSON.parse(localStorage.getItem('user')) || JSON.parse(sessionStorage.getItem('user')) || null;

  const userProfileImageUrl = ref(null);

  const userProfileImagePreviewUrl = ref(null);

  const profileInput = ref('');

  const nameError = ref('');

  const emailError = ref('');

  const addressError = ref('');

  const phoneError = ref('');

  const profileError = ref('');

  const typeError = ref('');

  const formData = ref({
    name: user.name,
    email: user.email,
    phone: user.phone,
    address: user.address,
    dob: user.dob,
    type: user.type,
    profile: null,
    create_user_id: user.create_user_id,
    updated_user_id: user.id,
    _method: 'put'
  });

  const userProfileImage = async (user) => {

    try {

      const response = await axiosInstance.get(`/users/${user.id}/${user.profile}`, { responseType: 'blob' });

      const blob = new Blob([response.data], { type: response.headers['content-type'] });

      const imageUrl = URL.createObjectURL(blob);

      userProfileImageUrl.value = imageUrl;

    } catch (error) {

      console.error('Error fetching profile image:', error);

    }

  };

  //check and control user input profile image
  const onFileInputChange = (event) => {

    handleImageUpload(event, profileError, userProfileImagePreviewUrl);

  };

  async function updateUser(userID) {

    try {

      if (userProfileImagePreviewUrl.value != null) {

        const dataURI =  userProfileImagePreviewUrl.value;

        //change profile image into image file
        const imageFile = dataURItoFile(dataURI, 'image.jpg', 'image/jpeg');

        formData.value.profile = imageFile;

      }

      const response = await axiosInstance.post(`/users/${userID}`, formData.value, { headers: { 'Content-Type': 'multipart/form-data' } });

      nameError.value = '';

      emailError.value = '';

      profileError.value = '';

      phoneError.value = '';

      typeError.value = '';

      console.log('Updated user successfully!', response.data.user);

      const updatedUserData = response.data.user;

      sessionStorage.setItem('user', JSON.stringify(updatedUserData));

      router.push('/UserProfile');

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

          if (errors.address) {

            addressError.value = errors.address[0] || '';

          }

          if (errors.phone) {

            phoneError.value = errors.phone[0] || '';

          }

          if (errors.profile) {

            profileError.value = errors.profile[0] || '';

          }

          if (errors.type) {

            typeError.value = errors.type[0] || '';

          }

        }
      }
      console.error(error);
    }
  }

  onMounted(() => {

    userProfileImage(user);

  });

  function clearUserEditData() {

    formData.value.name = user.name;

    formData.value.email = user.email;

    formData.value.phone = user.phone;

    formData.value.dob = user.dob;

    formData.value.address = user.address;

    formData.value.type = user.type;

    userProfileImagePreviewUrl.value = '';

    profileInput.value.value = '';
  }

  function removeEditImg() {

    profileInput.value.value = '';

    sessionStorage.removeItem('editFile');

    userProfileImagePreviewUrl.value = null;

  }

</script>

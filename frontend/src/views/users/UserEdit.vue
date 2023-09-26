<script setup>

  import { ref, onMounted } from 'vue';
  import axiosInstance from '@/axios.js';
  import { useRouter } from 'vue-router';

  const user = JSON.parse(localStorage.getItem('user'));

  const userProfileImageUrl = ref(null)

  const userProfileImage = async (user) => {
    try {
      axiosInstance.get(`/users/${user.id}/${user.profile}`, { responseType: 'blob' })
        .then((response) => {
          const blob = new Blob([response.data], { type: response.headers['content-type'] });
          const imageUrl = URL.createObjectURL(blob);
          userProfileImageUrl.value = imageUrl
        })
    } catch (error) {
      console.error('Error fetching profile image:', error);
    }
};

  const formData = ref({
    name: user.name,
    email: user.email,
    phone: user.phone,
    address: user.address,
    dob: user.dob,
    type: user.type,
    profile: null,
    create_user_id: user.id,
    updated_user_id: user.id
  });

  const router = new useRouter();

  const nameError = ref('')

  const emailError = ref('')

  const profileError = ref('')

  function handleImageUpload(event) {
    formData.value.profile = event.target.files[0];

    const imgSize = event.target.files[0].size;

    const maxSize = 2 * 1024 * 1024;
    if (imgSize > maxSize) {
      profileError.value = "Image file size must be less than 2MB"
      formData.value.profile = '';
    }
    else {
      const imgFile = event.target.files[0];
      console.log(imgFile)
      formData.value.profile = imgFile
    }
  }

  async function updateUser(userID) {
    try{
      const response = await axiosInstance.post(`/users/${userID}`, formData.value, {headers: {'Content-Type': 'multipart/form-data'}});
      nameError.value = '';
      emailError.value = '';
      profileError.value = '';
      console.log('Updated user successfully!', response.data.user);
      const updatedUserData = response.data.user
      localStorage.setItem('user', JSON.stringify(updatedUserData));
      router.push('/UserProfile');
    }catch (error) {
      profileError.value = '';
      if(error.response){
        const { errors } = error.response.data;
        if(errors) {
          if (errors.name) {
            nameError.value = errors.name[0] || '';
        }
          if (errors.email) {
              emailError.value = errors.email[0] || '';
          }
          if (errors.profile) {
              profileError.value = errors.profile[0] || '';
          }
        }
      }
      console.error(error);
    }
  }

  console.log(formData)

  onMounted(() => {
    userProfileImage(user);
});

</script>

<template>
  <div class="container container-main my-5">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card custom-card" style="width: 700px">
      <div class="card-header card-header-bg">
        Profile Edit
      </div>
      <div class="card-body mt-5">
        <form @submit.prevent="updateUser(user.id)" enctype="multipart/form-data" >
          <div class="mb-3 row">
          <label for="name" class="text-right-label col-12 col-md-4 col-form-label">Name<span class="text-danger">*</span></label>
          <div class="col-12 col-md-8">
            <div class="row">
              <div class="col-12 col-md-10">
                <input  v-model="formData.name"  type="text" class="form-control" id="name" name="name">
                <div v-if="nameError" class="text-danger">{{ nameError }}</div>
              </div>
              <div class="col-12 col-md-2"></div>
            </div>
          </div>
          </div>
          <div class="mb-3 row">
            <label for="email" class="text-right-label col-12 col-md-4 col-form-label">E-Mail Address<span class="text-danger">*</span></label>
            <div class="col-12 col-md-8">
              <div class="row">
                <div class="col-12 col-md-10">
                  <input  v-model="formData.email"  type="email" class="form-control" id="email" name="email">
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
                  <select  v-model="formData.type"  class="form-select" aria-label="Type">
                    <option value="0" selected>Admin</option>
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
                  <input  v-model="formData.phone"  type="text" class="form-control" id="phone" name="phone">
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
                  <input type="date"  class="form-control" id="dob" name="dob" v-model="formData.dob">
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
                  <input  v-model="formData.address"  type="text" class="form-control" id="address" name="address">
                </div>
                <div class="col-12 col-md-2"></div>
              </div>
            </div>
          </div>
          <div class="mb-3 row">
            <label for="profile" class="text-right-label col-12 col-md-4 col-form-label">Old Profile</label>
            <div class="col-12 col-md-8">
              <div class="row">
                <div class="col-12 col-md-10">
                  <img :src="userProfileImageUrl" class="img-fluid" alt="user-img" style="width:150px; height:150px" />
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
                  <input class="form-control" type="file" id="profile" name="profile" @change="handleImageUpload">
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
                  <input v-model="formData.create_user_id" class="form-control" id="create_user_id" name="create_user_id" >
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
                  <input v-model="formData.updated_user_id" class="form-control" id="updated_user_id" name="updated_user_id">
                </div>
                <div class="col-12 col-md-2"></div>
              </div>
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-12 col-md-4"></div>
            <div class="col-12 col-md-8">
              <button type="submit" class="btn btn-primary">Edit</button>
              <button type="button" class="btn btn-secondary mx-3">Clear</button>
              <router-link to="/ChangePassword" class="link-class text-primary">Change Password</router-link>
            </div>
          </div>
        </form>
      </div>
    </div>
    </div>
  </div>
</template>

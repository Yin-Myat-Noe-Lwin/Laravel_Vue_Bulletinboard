<script setup>
import { ref, computed} from 'vue';
import axiosInstance from '@/axios.js';
import { useRouter } from 'vue-router';
import { useStore } from 'vuex';

const router = new useRouter();

const store = useStore();
const userData = store.getters.getUserData;
console.log(userData)

const formData = ref({
    name: userData.name,
    email: userData.email,
    password: userData.password,
    password_confirmation: userData.password_confirmation,
    phone: userData.phone,
    address: userData.address,
    dob: userData.dob,
    type: userData.type,
    profile: null ,
    flg: 'unconfirm',
    create_user_id: userData.create_user_id,
    updated_user_id: userData.updated_user_id
})

const dataURItoBlob = (dataURI) => {
  const byteString = atob(dataURI.split(',')[1]);
  const mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];
  const ab = new ArrayBuffer(byteString.length);
  const ia = new Uint8Array(ab);

  for (let i = 0; i < byteString.length; i++) {
    ia[i] = byteString.charCodeAt(i);
  }

  return new Blob([ab], { type: mimeString });
};

const profileDataURL = localStorage.getItem('file')
const blobFile =  dataURItoBlob(profileDataURL)
const imageFile = new File([blobFile], 'image.jpg', {
    type: 'image/jpeg',
});
formData.value.profile = imageFile
console.log(imageFile)

async function confirmCreateUser () {
  try{
      formData.value.flg = 'confirm';
      const response = await axiosInstance.post('/users', formData.value, {headers: {'Content-Type': 'multipart/form-data'}});
      console.log('Confirm user creation successfully!', response.data);
      router.push('/UserList');
    }catch (error) {
      console.error(error);
    }
}

function cancelCreateUser () {
  localStorage.removeItem('userData');
}

const userType = computed(() => {
  return formData.value.type === 0 ? 'Admin' : 'User';
});

</script>

<template>
  <div class="container container-main my-5">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card custom-card" style="width:700px">
      <div class="card-header card-header-bg">
         Register Confirm
      </div>
      <div class="card-body mt-5">
        <form @submit.prevent="confirmCreateUser" enctype="multipart/form-data" >
          <div class="mb-3 row visually-hidden">
            <label for="flg" class="text-right-label col-12 col-md-4 col-form-label">Confirm flag<span class="text-danger">*</span></label>
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
            <label for="name" class="text-right-label col-12 col-md-4 col-form-label">Name<span class="text-danger">*</span></label>
            <div class="col-12 col-md-8">
              <div class="row">
                <div class="col-12 col-md-10">
                  <input v-model="formData.name" type="text" class="form-control" id="name" name="name" disabled >
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
                  <input v-model="formData.email" type="text" class="form-control" id="email" name="email" disabled>
                </div>
                <div class="col-12 col-md-2"></div>
              </div>
            </div>
          </div>
          <div class="mb-3 row">
            <label for="password" class="text-right-label col-12 col-md-4 col-form-label">Password<span class="text-danger">*</span></label>
            <div class="col-12 col-md-8">
              <div class="row">
                <div class="col-12 col-md-10">
                  <input v-model="formData.password" type="password" class="form-control" id="password" name="password" disabled>
                </div>
                <div class="col-12 col-md-2"></div>
              </div>
            </div>
          </div>
          <div class="mb-3 row">
            <label for="password_confirmation" class="text-right-label col-12 col-md-4 col-form-label">Password Confirmation<span class="text-danger">*</span></label>
            <div class="col-12 col-md-8">
              <div class="row">
                <div class="col-12 col-md-10">
                  <input v-model="formData.password_confirmation" type="password" class="form-control" id="password_confirmation" name="password_confirmation" disabled>
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
                  <input v-model="formData.address" type="text" class="form-control" id="address" name="address" disabled>
                </div>
                <div class="col-12 col-md-2"></div>
              </div>
            </div>
          </div>
          <div class="mb-3 row">
            <label for="profile" class="text-right-label col-12 col-md-4 col-form-label">Profile<span class="text-danger">*</span></label>
            <div class="col-12 col-md-8">
              <div class="row">
                <div class="col-12 col-md-10">
                  <img :src="profileDataURL"  class="img-fluid" alt="user-img" style="width:150px; height:150px" />
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
                  <input v-model="formData.create_user_id" class="form-control" id="create_user_id" name="create_user_id" disabled>
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
                  <input v-model="formData.updated_user_id" class="form-control" id="updated_user_id" name="updated_user_id" disabled>
                </div>
                <div class="col-12 col-md-2"></div>
              </div>
            </div>
          </div>
          <div class="mb-3 row">
            <div class="col-12 col-md-4"></div>
            <div class="col-12 col-md-8">
              <button type="submit" class="btn btn-primary" @click="confirmCreateUser">Confirm</button>
              <button type="button" class="btn btn-secondary mx-3" @click="cancelCreateUser">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    </div>
  </div>
</template>

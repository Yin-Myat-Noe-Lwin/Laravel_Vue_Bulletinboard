<template>
  <div class="container container-main my-5">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card custom-card" style="width: 600px">
        <div class="card-header card-header-bg">
          Profile
        </div>
        <div class="card-body p-5">
          <div class="row">
            <div class="col-12 col-md-4">
              <img :src="userProfileImageUrl" class="img-fluid rounded" alt="user-img"
                style="width:150px; height:150px" />
            </div>
            <div class="col-12 col-md-8">
              <div class="row mb-3">
                <div class="col-12 col-md-5">
                  Name
                </div>
                <div class="col-12 col-md-7">
                  {{ user.name }}
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-12 col-md-5">
                  Type
                </div>
                <div class="col-12 col-md-7">
                  {{ user.type === "1" ? 'User' : 'Admin' }}
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-12 col-md-5">
                  Email
                </div>
                <div class="col-12 col-md-7">
                  {{ user.email }}
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-12 col-md-5">
                  Phone
                </div>
                <div class="col-12 col-md-7">
                  {{ user.phone }}
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-12 col-md-5">
                  Date of Birth
                </div>
                <div class="col-12 col-md-7">
                  {{ formatDate(user.dob) }}
                </div>
              </div>
              <div class="row mb-3">
                <div class="col-12 col-md-5">
                  Address
                </div>
                <div class="col-12 col-md-7">
                  {{ user.address }}
                </div>
              </div>
              <div class="row">
                <div class="col-12 col-md-5">
                  <router-link to="/UserEdit">
                    <button type="button" class="btn btn-primary">Edit Profile</button>
                  </router-link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>

  import { ref, onMounted } from 'vue';
  import axiosInstance from '@/axios.js';
  import { formatDate } from '@/dateUtils';

  //get current logged in user
  const user = JSON.parse(localStorage.getItem('user')) || JSON.parse(sessionStorage.getItem('user')) || null;

  const userProfileImageUrl = ref(null);

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

  onMounted(() => {

    userProfileImage(user);

  });

</script>

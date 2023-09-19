<script setup>
  import User1Image from '@/assets/images/user-1.jpg';
</script>

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
            <img :src="User1Image" class="img-fluid" alt="user-img" style="width:150px; height:150px" />
          </div>
          <div class="col-12 col-md-8">
            <div class="row mb-3">
              <div class="col-12 col-md-6">
                Name
              </div>
              <div class="col-12 col-md-6">
                Leona
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12 col-md-6">
                Type
              </div>
              <div class="col-12 col-md-6">
                Admin
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12 col-md-6">
                Email
              </div>
              <div class="col-12 col-md-6">
                leona@gmail.com
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12 col-md-6">
                Phone
              </div>
              <div class="col-12 col-md-6">
                09123456789
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12 col-md-6">
                Date of Birth
              </div>
              <div class="col-12 col-md-6">
                2000-09-09
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12 col-md-6">
                Address
              </div>
              <div class="col-12 col-md-6">
                Yangon
              </div>
            </div>
            <div class="row">
              <div class="col-12 col-md-6">
                <button type="button" class="btn btn-primary">Edit Profile</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
  </div>
</template>

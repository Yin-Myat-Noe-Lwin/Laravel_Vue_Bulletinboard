<script setup>
</script>

<template>
  <div class="container container-main my-5">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card" style="width:700px">
      <div class="card-header card-header-bg">
        Change Password
      </div>
      <div class="card-body mt-5">
        <div class="mb-3 row">
          <label for="password" class="text-right-label col-12 col-md-4 col-form-label">Current Password<span class="text-danger">*</span></label>
          <div class="col-12 col-md-8">
            <div class="row">
              <div class="col-12 col-md-10">
                <input type="password" class="form-control" id="password" name="password">
              </div>
              <div class="col-12 col-md-2"></div>
            </div>
          </div>
        </div>
        <div class="mb-3 row">
          <label for="password" class="text-right-label col-12 col-md-4 col-form-label">New Password<span class="text-danger">*</span></label>
          <div class="col-12 col-md-8">
            <div class="row">
              <div class="col-12 col-md-10">
                <input type="password" class="form-control" id="password" name="password">
              </div>
              <div class="col-12 col-md-2"></div>
            </div>
          </div>
        </div>
        <div class="mb-3 row">
          <label for="password" class="text-right-label col-12 col-md-4 col-form-label">New Confirm Password<span class="text-danger">*</span></label>
          <div class="col-12 col-md-8">
            <div class="row">
              <div class="col-12 col-md-10">
                <input type="password" class="form-control" id="password" name="password">
              </div>
              <div class="col-12 col-md-2"></div>
            </div>
          </div>
        </div>
        <div class="mb-3 row">
          <div class="col-12 col-md-4">
          </div>
          <div class="col-12 col-md-8">
            <button type="button" class="btn btn-primary">Update Password</button>
          </div>
        </div>
      </div>
    </div>
    </div>
  </div>
</template>

<script setup>
</script>

<template>
  <div class="container container-main my-5">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card" style="width:600px">
      <div class="card-header card-header-bg">
        Forgot Password?
      </div>
      <div class="card-body mt-5">
        <div class="mb-3 row">
          <label for="email" class="text-right-label col-12 col-md-4 col-form-label">Email:</label>
          <div class="col-12 col-md-8">
            <div class="row">
              <div class="col-12 col-md-10">
                <input type="email" class="form-control" id="email" name="email">
              </div>
              <div class="col-12 col-md-2"></div>
            </div>
          </div>
        </div>
        <div class="mb-3 row">
          <div class="col-12 col-md-4">
          </div>
          <div class="col-12 col-md-8">
            <button type="button" class="btn btn-primary">Reset Password</button>
          </div>
        </div>
      </div>
    </div>
    </div>
  </div>
</template>

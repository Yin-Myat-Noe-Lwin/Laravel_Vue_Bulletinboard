<script setup>
</script>

<template>
  <div class="container container-main my-5">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card custom-card" style="width: 700px">
      <div class="card-header card-header-bg">
        Create Post
      </div>
      <div class="card-body mt-5">
        <div class="mb-3 row">
          <label for="title" class="text-right-label col-12 col-md-4 col-form-label">Title<span class="text-danger">*</span></label>
          <div class="col-12 col-md-8">
            <div class="row">
              <div class="col-12 col-md-10">
                <input type="text" class="form-control" id="title" name="title">
              </div>
              <div class="col-12 col-md-2"></div>
            </div>
          </div>
        </div>
        <div class="mb-3 row">
          <label for="description" class="text-right-label col-12 col-md-4 col-form-label">Description<span class="text-danger">*</span></label>
          <div class="col-12 col-md-8">
            <div class="row">
              <div class="col-12 col-md-10">
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
              </div>
              <div class="col-12 col-md-2"></div>
            </div>
          </div>
        </div>
        <div class="mb-3 row">
          <div class="col-12 col-md-4"></div>
          <div class="col-12 col-md-8">
            <button type="button" class="btn btn-primary">Create</button>
            <button type="button" class="btn btn-secondary mx-3">Clear</button>
          </div>
        </div>
      </div>
    </div>
    </div>
  </div>
</template>

<script setup>
</script>

<template>
  <div class="container container-main my-5">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card custom-card" style="width: 800px">
      <div class="card-header card-header-bg">
        Upload CSV file
      </div>
      <div class="card-body my-4">
        <div class="row">
          <div class="col-12 col-md-4">
            <label for="profile" class="col-md-4 col-form-label csv-label">CSV file</label>
          </div>
          <div class="col-12 col-md-6">
            <input class="form-control" type="file" id="file" name="file">
          </div>
        </div>
        <div class="row pt-3">
          <div class="col-12 col-md-4">
          </div>
          <div class="col-12 col-md-7">
            <button type="button" class="btn common-btn">Edit</button>
            <button type="button" class="btn btn-secondary mx-3">Clear</button>
          </div>
        </div>
      </div>
    </div>
    </div>
  </div>
</template>

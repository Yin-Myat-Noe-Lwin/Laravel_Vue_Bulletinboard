<template>
  <div class="container container-main my-5">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card custom-card" style="width:700px">
      <div class="card-header card-header-bg">
         Register
      </div>
      <div class="card-body">
        <div class="mb-3 row">
          <label for="name" class="text-right-label col-12 col-md-4 col-form-label">Name<span class="text-danger">*</span></label>
          <div class="col-12 col-md-8">
            <div class="row">
              <div class="col-12 col-md-10">
                <input type="text" class="form-control" id="name" name="name">
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
                <input type="email" class="form-control" id="email" name="email">
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
                <input type="password" class="form-control" id="password" name="password">
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
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
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
                <select class="form-select" aria-label="Type">
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
                <input type="text" class="form-control" id="phone" name="phone">
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
                <input type="date" class="form-control" id="dob" name="dob">
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
                <input type="text" class="form-control" id="address" name="address">
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
                <input class="form-control" type="file" id="file" name="file">
              </div>
              <div class="col-12 col-md-2"></div>
            </div>
          </div>
        </div>
        <div class="mb-3 row">
          <div class="col-12 col-md-4"></div>
          <div class="col-12 col-md-8">
            <button type="button" class="btn btn-primary">Register</button>
            <button type="button" class="btn btn-secondary mx-3">Clear</button>
          </div>
        </div>
      </div>
    </div>
    </div>
  </div>
</template>

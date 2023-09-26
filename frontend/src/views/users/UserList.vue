<script setup>

import { ref, onMounted } from 'vue';
import axiosInstance from '@/axios.js';

const profileImageUrl = ref(null);

const formatDate = (datetime) => {
  const date = new Date(datetime);
  return date.toLocaleDateString();
};

const users = ref([]);

const fetchUsers = () => {
  axiosInstance
    .get('/users')
    .then((response) => {
      users.value = response.data.users;
    })
    .catch((error) => {
      alert("error")
     console.error(error);
    });
};

onMounted(() => {
  fetchUsers();
});

const userDetail = ref({});

const selectedUser = ref({});

const showModalUserDetail = ref(false);

const showModalDelete = ref(false);

const showUserDetail = async (user) => {
  userDetail.value = user;
  showModalUserDetail.value = true;
  try {
    axiosInstance.get(`/users/${user.id}/${user.profile}`, { responseType: 'blob' })
      .then((response) => {
        const blob = new Blob([response.data], { type: response.headers['content-type'] });
        const imageUrl = URL.createObjectURL(blob);
        profileImageUrl.value = imageUrl;
      })
  } catch (error) {
    console.error('Error fetching profile image:', error);
  }
};

const closeUerDetail = () => {
  selectedUser.value = null;
  showModalUserDetail.value = false;
};

const showDeleteConfirmation = (user) => {
  selectedUser.value = user;
  showModalDelete.value = true;
};

const closeDeleteConfirmation = () => {
  selectedUser.value = null;
  showModalDelete.value = false;
};

const deleteUser = (userID) => {
  alert("deleted");
  axiosInstance
    .delete(`/users/${userID}`)
    .then((response) => {
      console.log("user deleted successfully");
      closeDeleteConfirmation()
      window.location.reload()
    })
    .catch((error) => {
      alert("error");
      console.error(error);
    });
};

</script>

<template>
  <div class="container container-main my-5">
    <div class="card custom-card">
      <div class="card-header card-header-bg">User List</div>
      <div class="card-body p-4">
        <div class="row">
          <div class="col-12 col-md-10">
            <div class="row">
              <div class="col-12 col-md-3">
                <div class="mb-3 row">
                  <label
                    for="name"
                    class="text-right-label col-12 col-md-3 col-form-label form-custom-label"
                    >Name:</label
                  >
                  <div class="col-12 col-md-9">
                    <input
                      type="text"
                      class="form-control form-custom-border"
                      id="name"
                      name="name"
                    />
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="mb-3 row">
                  <label
                    for="email"
                    class="text-right-label col-12 col-md-3 col-form-label form-custom-label"
                    >Email:</label
                  >
                  <div class="col-12 col-md-9">
                    <input
                      type="text"
                      class="form-control form-custom-border"
                      id="email"
                      name="email"
                    />
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="mb-3 row">
                  <label
                    for="dob"
                    class="text-right-label col-12 col-md-3 col-form-label form-custom-label"
                    >From:</label
                  >
                  <div class="col-12 col-md-9">
                    <input
                      type="date"
                      class="form-control form-custom-border"
                      id="dob"
                      name="dob"
                    />
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-3">
                <div class="mb-3 row">
                  <label
                    for="dob"
                    class="text-right-label col-12 col-md-3 col-form-label form-custom-label"
                    >To:</label
                  >
                  <div class="col-12 col-md-9">
                    <input
                      type="date"
                      class="form-control form-custom-border"
                      id="dob"
                      name="dob"
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-2">
            <button
              type="button"
              class="btn btn-primary mx-3 common-btn"
              style="width: 80%"
            >
              Search
            </button>
          </div>
        </div>
        <table class="table table-striped my-4">
          <thead class="table-header">
            <tr>
              <th scope="col">Number</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">Created User</th>
              <th scope="col">Type</th>
              <th scope="col">Phone</th>
              <th scope="col">Date of Birth</th>
              <th scope="col">Address</th>
              <th scope="col">Created_date</th>
              <th scope="col">Updated_date</th>
              <th scope="col">Operation</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id">
              <th scope="row">{{ user.id }}</th>
              <td class="text-success" data-bs-toggle="modal" data-bs-target="#userDetailBtn" @click="showUserDetail(user)">{{ user.name }}</td>
              <td>{{ user.email }}</td>
              <td>{{ user.create_user_id }}</td>
              <td>{{ user.type === "1" ? 'User' : 'Admin' }}</td>
              <td>{{ user.phone }}</td>
              <td>{{ user.dob }}</td>
              <td>{{ user.address }}</td>
              <td>{{ formatDate(user.created_at) }}</td>
              <td>{{ formatDate(user.updated_at) }}</td>
              <td>
                <button
                  type="button"
                  class="btn btn-danger"
                  data-bs-toggle="modal"
                  data-bs-target="#userDeleteBtn"
                  @click="showDeleteConfirmation(user)"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <div
          class="modal fade"
          :class="{'show': showModalDelete}"
          id="userDeleteBtn"
          data-bs-backdrop="static"
          data-bs-keyboard="false"
          tabindex="-1"
          aria-labelledby="userDeleteBtn"
          aria-hidden="true"
        >
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <p class="modal-title fs-6" id="userDeleteBtn">
                  Delete Confirm
                </p>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-12 col-md-7">
                    <h5 class="text-danger mb-4 modal-custom-txt">
                      Are you sure to delete user?
                    </h5>
                  </div>
                  <div class="col-12 col-md-5"></div>
                </div>
                <div class="row mb-1">
                  <div class="col-12 col-md-6">
                    <p class="text-start mx-3">ID</p>
                  </div>
                  <div class="col-12 col-md-6">
                    <p class="text-start">{{ selectedUser.id }}</p>
                  </div>
                </div>
                <div class="row mb-1">
                  <div class="col-12 col-md-6">
                    <p class="text-start mx-3">Name</p>
                  </div>
                  <div class="col-12 col-md-6">
                    <p class="text-start">{{ selectedUser.name }}</p>
                  </div>
                </div>
                <div class="row mb-1">
                  <div class="col-12 col-md-6">
                    <p class="text-start mx-3">Type</p>
                  </div>
                  <div class="col-12 col-md-6">
                    <p class="text-start">{{ selectedUser.type === "1" ? 'User' : 'Admin' }}</p>
                  </div>
                </div>
                <div class="row mb-1">
                  <div class="col-12 col-md-6">
                    <p class="text-start mx-3">Email</p>
                  </div>
                  <div class="col-12 col-md-6">
                    <p class="text-start">{{ selectedUser.email }}</p>
                  </div>
                </div>
                <div class="row mb-1">
                  <div class="col-12 col-md-6">
                    <p class="text-start mx-3">Phone</p>
                  </div>
                  <div class="col-12 col-md-6">
                    <p class="text-start">{{ selectedUser.phone }}</p>
                  </div>
                </div>
                <div class="row mb-1">
                  <div class="col-12 col-md-6">
                    <p class="text-start mx-3">Date of birth</p>
                  </div>
                  <div class="col-12 col-md-6">
                    <p class="text-start">{{ selectedUser.dob }}</p>
                  </div>
                </div>
                <div class="row mb-4">
                  <div class="col-12 col-md-6">
                    <p class="text-start mx-3">Address</p>
                  </div>
                  <div class="col-12 col-md-6">
                    <p class="text-start">{{ selectedUser.address }}</p>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  @click="closeDeleteConfirmation"
                  data-bs-dismiss="modal"
                >
                  Close
                </button>
                <button type="button" class="btn btn-danger" @click="deleteUser(selectedUser.id)">Delete</button>
              </div>
            </div>
          </div>
        </div>
        <div
          class="modal modal-custom fade"
          :class="{'show': showModalUserDetail}"
          id="userDetailBtn"
          data-bs-backdrop="static"
          data-bs-keyboard="false"
          tabindex="-1"
          aria-labelledby="userDetailBtn"
          aria-hidden="true"
        >
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <p class="modal-title fs-6" id="userDetailBtn">
                  User Detail
                </p>
                <button
                  type="button"
                  class="btn-close"
                  data-bs-dismiss="modal"
                  aria-label="Close"
                ></button>
              </div>
              <div class="modal-body">
                <div class="row p-2">
                  <div class="col-12 col-md-4">
                    <div class="d-flex justify-content-center align-items-center">
                      <img :src="profileImageUrl" class="img-fluid" alt="user-img" style="width:150px; height:150px" />
                    </div>
                  </div>
                  <div class="col-12 col-md-8">
                    <div class="row">
                      <div class="col-12 col-md-6">
                        <p>Name</p>
                      </div>
                      <div class="col-12 col-md-6">
                        <p>{{ userDetail.name }}</p>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-md-6">
                        <p>Type</p>
                      </div>
                      <div class="col-12 col-md-6">
                        <p>{{ userDetail.type === "1" ? 'User' : 'Admin' }}</p>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-md-6">
                        <p>Email</p>
                      </div>
                      <div class="col-12 col-md-6">
                        <p>{{ userDetail.email }}</p>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-md-6">
                        <p>Phone</p>
                      </div>
                      <div class="col-12 col-md-6">
                        <p>{{ userDetail.phone }}</p>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-md-6">
                        <p>Date of Birth</p>
                      </div>
                      <div class="col-12 col-md-6">
                        <p>{{ userDetail.dob }}</p>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-md-6">
                        <p>Address</p>
                      </div>
                      <div class="col-12 col-md-6">
                        <p>{{ userDetail.address }}</p>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-md-6">
                        <p>Created Date</p>
                      </div>
                      <div class="col-12 col-md-6">
                        <p>{{ formatDate(userDetail.created_at) }}</p>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-md-6">
                        <p>Created User</p>
                      </div>
                      <div class="col-12 col-md-6">
                        <p>{{ userDetail.created_user }}</p>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-md-6">
                        <p>Updated Date</p>
                      </div>
                      <div class="col-12 col-md-6">
                        <p>{{ formatDate(userDetail.updated_at) }}</p>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-12 col-md-6">
                        <p>Updated User</p>
                      </div>
                      <div class="col-12 col-md-6">
                        <p>{{ userDetail.updated_user  }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button
                  type="button"
                  class="btn btn-secondary"
                  @click="closeUerDetail"
                  data-bs-dismiss="modal"
                >
                  Close
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

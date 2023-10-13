<template>
  <div class="container container-main my-5">
    <div class="d-flex justify-content-center align-items-center">
      <div class="card custom-card" style="width: 800px">
        <div class="card-header card-header-bg">
          Upload CSV file
        </div>
        <div class="card-body">
          <div v-if="fileError" class="alert alert-danger" role="alert">
            {{ fileError }}
          </div>
          <form class="my-5" @submit.prevent="uploadCSV">
            <div class="row">
              <div class="col-12 col-md-4">
                <label for="profile" class="col-md-4 col-form-label csv-label">CSV file</label>
              </div>
              <div class="col-12 col-md-6">
                <input class="form-control" type="file" id="file" name="file" @change="handleFileUpload"
                  ref="CSVFileInput">
                <div v-if="importDataError" class="text-danger">
                  {{ importDataError }}
                </div>
              </div>
            </div>
            <div class="row pt-3">
              <div class="col-12 col-md-4">
              </div>
              <div class="col-12 col-md-7">
                <button type="submit" class="btn common-btn">Upload</button>
                <button type="button" class="btn btn-secondary mx-3" @click="clearCSVFile">Clear</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>

  import { ref } from 'vue';
  import { useRouter } from 'vue-router';
  import axiosInstance from '@/axios.js';

  const CSVFileInput = ref('');

  const router = useRouter();

  const formData = ref({
    file: ''
  })

  const fileError = ref('');

  const importDataError = ref('');

  function handleFileUpload(event) {

    formData.value.file = event.target.files[0];

    console.log(event.target.files[0]);

  }

  async function uploadCSV() {

    try {

      const response = await axiosInstance.post('/import', formData.value, { headers: { 'Content-Type': 'multipart/form-data' } });

      fileError.value = '';

      importDataError.value = '';

      console.log('Post Data import successfully!', response.data);

      router.push('/');

    } catch (error) {

      if (error.response) {

        const { errors } = error.response.data;

        if (errors) {

          if (errors.file) {

            importDataError.value = '';

            fileError.value = errors.file[0] || '';

          }

          if (errors[0]) {

            fileError.value = '';

            importDataError.value = errors[0][0] || '';

          }

        } else {

          fileError.value = error.response.data.error;

        }

      }

      console.error('Post Data import failed:', error);

    }

  }

  function clearCSVFile() {

    CSVFileInput.value.value = '';

  }

</script>

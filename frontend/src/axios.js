import axios from 'axios';

//fetch token
const token = localStorage.getItem('token') || sessionStorage.getItem('token');

//connect with api
const axiosInstance = axios.create({

  baseURL: 'http://127.0.0.1:8000/api',

  withCredentials: true,

  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'Authorization': 'Bearer ' + token
  }

});

export default axiosInstance;

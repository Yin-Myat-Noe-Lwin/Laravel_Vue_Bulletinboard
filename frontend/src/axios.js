import axios from 'axios';

const token = localStorage.getItem('token') || sessionStorage.getItem('token');

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

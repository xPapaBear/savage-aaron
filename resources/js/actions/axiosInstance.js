import axios from 'axios';
import { getSessionToken } from '@shopify/app-bridge-utils';

const axiosInstance = axios.create();
// intercept all requests on this axios instance
axiosInstance.interceptors.request.use(
  async function (config) {
    const token = await getSessionToken(window.app);
    // append your request headers with an authenticated token
    config.headers['Content-type'] = 'application/json';
    config.headers['Authorization'] = `Bearer ${token}`;
    return config;
  }
);
// export your axios instance to use within your app
export default axiosInstance;
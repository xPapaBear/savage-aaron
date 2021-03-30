import axiosInstance from './axiosInstance'
import { requestOptions } from '../helpers';
import { API_PATH } from '../constants';

export async function fetchFilteredCustomers(requestData) {
  const {
    data,
  } = await axiosInstance.post(
    `${API_PATH}/customers/filter`,
    requestData,
    requestOptions
  );

  return data
}
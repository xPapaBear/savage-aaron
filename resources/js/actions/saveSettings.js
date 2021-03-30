import axiosInstance from './axiosInstance'
import { requestOptions } from '../helpers';
import { API_PATH } from '../constants';

export async function saveSettings(requestData, id) {
  const {
    data,
  } = await axiosInstance.post(`${API_PATH}/multiplier/save/${id}`, requestData, requestOptions);

  return data
}
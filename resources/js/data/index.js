import axios from 'axios'
import useSWR from 'swr'
import axiosInstance from '../actions/axiosInstance'
import { API_PATH, API_TEST } from '../constants'
import { getSessionToken } from '@shopify/app-bridge-utils';
import { requestOptions, getCsrf, getToken } from '../helpers'

// export your axios instance to use within your app

const fetcher = url => axiosInstance.get(url, requestOptions).then(res => res.data)

export function useData(slug) {
  console.log('data',slug);
  const { data, error, mutate } = useSWR(`${API_PATH}/${slug}`, fetcher);
  const loading = !error && !data;

  return {
    full_data: data,
    data: data?.data,
    isLoading: loading,
    isError: error,
    mutate
  };
}

export async function useFetchData(slug) {
  const {
    data: { data, success },
    status,
    message
  } = await axiosInstance.get(`${API_PATH}/${slug}`, requestOptions);

  return {
    data: data,
    status: status,
    success: success
  };
}

export function getMultiplierHistory(slug) {
  const { data } = useSWR(`${API_PATH}/${slug}`, fetcher);
  
  return { historyData: data?.data }
}
import axios from 'axios'
import useSWR from 'swr'
import axiosInstance from '../actions/axiosInstance'
import { API_PATH } from '../constants'
import { getSessionToken } from '@shopify/app-bridge-utils';
import { requestOptions, getCsrf, getToken } from '../helpers'

// export your axios instance to use within your app

const fetcher = url => axiosInstance.get(url, requestOptions).then(res => res.data)

export function useData(slug) {
  const { data, error, mutate } = useSWR(`${API_PATH}/${slug}`, fetcher);
  const loading = !error && !data;

  let arrs = data?.data

  arrs.sort( function ( a, b ) {
    var keyA = new Date( a.total_points ), keyB = new Date( b.total_points )
    if ( keyA < keyB ) return -1
    if ( keyA > keyB ) return 1
    return 0
  } )

  arrs.reverse()

  return {
    full_data: data,
    data: arrs,
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
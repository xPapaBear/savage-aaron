import useSWR from 'swr'
import axiosInstance from '../actions/axiosInstance'
import { API_PATH, DASHBOARD } from '../constants'
import { requestOptions } from '../helpers'

// export your axios instance to use within your app

const fetcher = url => axiosInstance.get( url, requestOptions ).then( res => res.data )

export function multiplierHistoryFive() {
    const { data } = useSWR( `${DASHBOARD}/multiplier-history/five`, fetcher )

    return {
        data: data?.data
    }
}
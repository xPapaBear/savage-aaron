import useSWR from 'swr'
import axiosInstance from '../actions/axiosInstance'
import { API_PATH, DASHBOARD } from '../constants'
import { requestOptions } from '../helpers'

// export your axios instance to use within your app

const fetcher = url => axiosInstance.get( url, requestOptions ).then( res => res.data )

/**
 * Get Latest 5 Multiplier History
 * EP = Entry Points
 */
export function multiplierHistoryFive() {
    const { data } = useSWR( `${DASHBOARD}/multiplier-history/five`, fetcher )

    return {
        multipliers: data?.data
    }
}

/**
 * Get Top 5 Customer based on Entry Points
 * EP = Entry Points
 */
export function topFiveCustomerEP() {
    const { data } = useSWR( `${DASHBOARD}/top-five-customer/entry-points`, fetcher )
    
    return {
        topFivesCustomers: data?.data
    }
}
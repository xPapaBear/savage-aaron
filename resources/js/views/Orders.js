import React, { useEffect, useState, useCallback,  } from 'react'
import {
  Card,
  IndexTable,
  useIndexResourceState,
  TextField,
  Page,
  Select,
  Filters,
  TextStyle,
} from '@shopify/polaris';
import { useData, useFetchData } from '../data'
import { API_PATH, DATE_ERROR, DATE_FORMAT } from '../constants';
import { SCSkeletonPage } from '../components/PageLoadingSkeleton';
import axios from 'axios';
import { isEmpty } from 'lodash';
import axiosInstance from '../actions/axiosInstance';
import { Fragment } from 'react';
import { currencyFormat, formatDateValue, today } from '../helpers';
import moment from 'moment';
import { fetchFilteredCustomers } from '../actions/fetchFilteredCustomers';

let fetchFinal = true;

const Orders = ({ location }) => {
  const { data: orders, isLoading, isError, mutate } = useData('orders');
  const { shop, shop_api: shopApi } = location.state.data || {};

const defOrderValues = [
  {
    id: null,
    store_order_id: null,
    order_id: null,
    orders_number: null,
  }
];
const [newDataOrder, setnewDataOrder] = useState(defOrderValues);

useEffect(() => {
   if (orders != undefined){
    setnewDataOrder(orders);
   }
})


const resourceName = {
  singular: 'order',
  plural: 'orders',
};
const {
  selectedResources,
  allResourcesSelected,
  handleSelectionChange,
} = useIndexResourceState(orders);

// Filter callbacks and function
const [searchVal, setSearchVal] = useState("");
const onSearchChange = useCallback(newValue => setSearchVal(newValue), []);
const handleClearButtonClick = useCallback(() => setSearchVal(""), []);

//Selection filters
const [searchby, setSearchby] = useState('storeid');
const handleselectfilter = useCallback((value) => 
  setSearchby(value), []);

const searchoptions = [
  {label: 'Store Order ID', value: 'storeid'},
  {label: 'Customer ID', value: 'customerid'},
  {label: 'Order Number', value: 'ordernumber'},
];


const rowMarkup = newDataOrder.filter((val)=>{

  if (!searchVal == "" && searchby === 'storeid') {
    return val.store_order_id.toString().includes(searchVal)
  }
  else if (!searchVal == "" && searchby === 'customerid') {
    return val.customer_id.toString().includes(searchVal)
  }
  else if (!searchVal == "" && searchby === 'ordernumber') {
    return val.order_number.toString().includes(searchVal)
  }
  else{
    return val;
  }
}).map(
  ({id, store_order_id, customer_id, order_number}, index) => (
    <IndexTable.Row
       id={id}
       key={id}
       selected={selectedResources.includes(id)}
       position={index}
    >
      <IndexTable.Cell>
        <TextStyle variation="strong">{store_order_id}</TextStyle>
      </IndexTable.Cell>
      <IndexTable.Cell>{customer_id}</IndexTable.Cell>
      <IndexTable.Cell>{order_number}</IndexTable.Cell>
    </IndexTable.Row>
  ),
);


return (
  <Page title="Orders">
  <Card title="Search Orders">
    <TextField 
    value={searchVal}
    onChange={onSearchChange}
    clearButton
    onClearButtonClick={handleClearButtonClick}
    inputMode="numeric"
    placeholder="Search Orders" 
    connectedLeft = {
      <Select
      label="Filter by"
      labelInline
      options={searchoptions}
      onChange={handleselectfilter}
      value={searchby}
    />
    }
    />
    
    <IndexTable
      selectable={false}
      resourceName={resourceName}
      itemCount={newDataOrder.length}
      selectedItemsCount={
        allResourcesSelected ? 'All' : selectedResources.length
      }
      onSelectionChange={handleSelectionChange}
      headings={[
        {title: 'Store Order ID'},
        {title: 'Customer ID'},
        {title: 'Order Number'},
      ]}
    >
      {rowMarkup}
    </IndexTable>
  </Card>
  </Page>
);

}

export default Orders; 
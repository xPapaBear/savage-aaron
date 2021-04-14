import React, { useEffect, useState, useCallback } from 'react'
import {
  Avatar,
  Badge,
  Card,
  DataTable,
  DatePicker,
  Filters,
  Heading,
  InlineError,
  Page,
  RangeSlider,
  ResourceList,
  ResourceItem,
  Stack,
  TextStyle,
  TextField,
  TextContainer,
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

const CustomerEntries = ({ location }) => {
  const { data: customers, isLoading, isError, mutate } = useData('customers');
  const { shop, shop_api: shopApi } = location.state.data || {};

  const [filteredCustomers, setFilteredCustomers] = useState({});
  const [queryValue, setQueryValue] = useState(null);
  const [fieldDates, setFieldDates] = useState({
    start: formatDateValue(new Date()),
    end: formatDateValue(new Date())
  })
  const [endingMinDate, setEndingMinDate] = useState(new Date());
  const [fieldError, setFieldError] = useState({
    start: false,
    end: false
  })

  // Starting date
  const [{month: startingMonth, year: startingYear}, setStartingDate] = useState({
    month: new Date().getMonth(),
    year: new Date().getFullYear()
  });
  const [selectedStartingDates, setSelectedStartingDates] = useState({
    start: new Date(),
    end: new Date(),
  });
  const [fieldStartingDate, setFieldStartingDate] = useState('');
  const handleStartingMonthChange = useCallback(
    (month, year) => setStartingDate({month, year}),
  [], );
  const handleSelectedStartingDates = useCallback( (data) => {
    setSelectedStartingDates(data);
    setEndingMinDate(new Date(data.start));
    setFieldDates(({end}) => {
      return {end: end, start: formatDateValue(data.start)}
    });
    handleFieldOnBlur('start')
  }, []);

  // Ending date
  const [{month: endingMonth, year: endingYear}, setEndingDate] = useState({
    month: new Date().getMonth(),
    year: new Date().getFullYear()
  });
  const [selectedEndingDates, setSelectedEndingDates] = useState({
    start: new Date(),
    end: new Date(),
  });
  const [fieldEndingDate, setFieldEndingDate] = useState('');
  const handleEndingMonthChange = useCallback(
    (month, year) => setEndingDate({month, year}),
  [], );
  const handleSelectedEndingDates = useCallback( (data) => {
    setSelectedEndingDates(data);
    setFieldDates(({start}) => {
      return {start: start, end: formatDateValue(data.end)}
    });
    handleFieldOnBlur('end')
  }, []);

  const handleFieldDates = useCallback((data, type) => {
    const isValid = moment(data, 'YYYY-MM-DD', true).isValid();

    setFieldDates((fields) => {
      return {...fields, [type]: data}
    });

    if ( isValid ) {
      const selectedDates = {
        start: new Date(data),
        end: new Date(data)
      }

      const newMonth = selectedDates.start.getMonth()
      const newYear = selectedDates.start.getFullYear()

      if ( type == 'start' ) {
        setSelectedStartingDates(selectedDates)
        setStartingDate({month: newMonth, year: newYear})
      } else {
        setSelectedEndingDates(selectedDates)
        setEndingDate({month: newMonth, year: newYear})
      }
    }
  }, []);

  const handleFieldOnBlur = useCallback((type) => {
    const isValid = moment(fieldDates[type], 'YYYY-MM-DD', true).isValid();
    setFieldError((fields) => {
      return {...fields, [type]: ! isValid}
    });
  }, [fieldDates]);

  const handleFiltersQueryChange = useCallback(
    (value) => setQueryValue(value),
    [],
  );

  const handleQueryValueRemove = useCallback(() => setQueryValue(null), []);

  const handleFiltersClearAll = useCallback(() => {
    handleQueryValueRemove();
  }, [
    handleQueryValueRemove,
  ]);

  const handleFetchFiltered = useCallback( async () => {
//     const { data } = await fetchFilteredCustomers({
//       start: selectedStartingDates.end,
//       end: selectedEndingDates.end
//     });
//     setFilteredCustomers(data)
  }, [selectedStartingDates, selectedEndingDates])

  const filters = [
    {
      key: 'dateRange',
      label: 'Date Range',
      filter: (
        <TextContainer>
          <TextField
            id="startingDate"
            label="Starting"
            value={fieldDates.start}
            inputMode="text"
            type="text"
            pattern={DATE_FORMAT}
            name="Starting"
            labelHidden={false}
            placeholder={DATE_FORMAT}
            error={ fieldError.start ? DATE_ERROR : ''}
            onChange={(data) => handleFieldDates(data, 'start')}
            onBlur={data => handleFieldOnBlur('start')}
          />
          <DatePicker
            month={startingMonth}
            year={startingYear}
            onChange={handleSelectedStartingDates}
            onMonthChange={handleStartingMonthChange}
            selected={selectedStartingDates}
          />
          <TextField
            id="endingDate"
            label="Ending"
            value={fieldDates.end}
            inputMode="text"
            type="text"
            pattern={DATE_FORMAT}
            name="Ending"
            labelHidden={false}
            placeholder={DATE_FORMAT}
            onChange={(data) => handleFieldDates(data, 'end')}
            onBlur={data => handleFieldOnBlur('end')}
            error={ fieldError.end ? DATE_ERROR : ''}
          />
          <DatePicker
            month={endingMonth}
            year={endingYear}
            onChange={handleSelectedEndingDates}
            onMonthChange={handleEndingMonthChange}
            selected={selectedEndingDates}
            disableDatesBefore={endingMinDate}
          />
        </TextContainer>
      ),
    }
  ];

  const appliedFilters = [];

  useEffect(() => {
  }, [])

  useEffect(() => {
    if ( ! isLoading ) {
      fetchFinal = true
      setTimeout(async () => {
        if ( fetchFinal ) {
          handleFetchFiltered();
        }
      }, 700)
    }
    return () => {
      fetchFinal = false
    }
  }, [fieldDates, selectedStartingDates, selectedEndingDates]);

  const rowsFormat = ({ id, full_name, email, phone, total_points, total_spent}) => [
    full_name,
    email,
    phone ?? '--',
    <Badge status="success">{total_points}</Badge>,
    <Badge status="info">{shopApi ? currencyFormat(shopApi, total_spent) : total_spent}</Badge>,
  ]

  const rows = ! isEmpty(filteredCustomers) ? filteredCustomers?.data?.map(
    (data) => rowsFormat(data)) : ! isEmpty(customers) ? customers?.data?.map(
    (data) => rowsFormat(data)) : null;

  const pageMarkup = (
    <Page
      title="Customer Entries"
      fullWidth={! isLoading}
    >
      <Card>
        <Card.Section>
          <Filters
            queryValue={queryValue}
            filters={filters}
            appliedFilters={appliedFilters}
            onQueryChange={handleFiltersQueryChange}
            onQueryClear={handleQueryValueRemove}
            onClearAll={handleFiltersClearAll}
          />
        </Card.Section>
        <DataTable
          columnContentTypes={[
            'text',
            'text',
            'text',
            'text',
            'text',
          ]}
          headings={[
            'Name',
            'Email',
            'Phone Number',
            'Total Entries',
            'Total Spent',
          ]}
          rows={rows}
        />
      </Card>
    </Page>
  )

  const loadingMarkup = <SCSkeletonPage title="Customer Entries" />

  const actualPageMarkup = isLoading ? loadingMarkup : pageMarkup;

  return actualPageMarkup;
}

export default CustomerEntries;
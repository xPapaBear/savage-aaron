import React, { useState, useCallback, useRef, useEffect } from 'react'
import {
  Card,
  Layout,
  Page,
  SettingToggle,
  TextStyle,
  FormLayout,
  TextField,
  DatePicker,
  Tag,
  Badge,
  Label,
  Form,
  ContextualSaveBar,
  DataTable
} from '@shopify/polaris';
/* import { SaveMinor } from '@shopify/polaris-icons';
import axios from 'axios';
import { requestOptions } from '../helpers';
import { API_PATH } from '../constants'; */
import { useData, getMultiplierHistory } from '../data';
import { PageSkeleton } from '../components/Skeletons';
import { Fragment } from 'react';
import { isEmpty, isFunction } from 'lodash';
import { saveSettings } from '../actions/saveSettings';

const today = new Date();
const nextWeek = new Date(today);
nextWeek.setDate(nextWeek.getDate() + 3);
const Settings = ({ loading }) => {
  const { data, isLoading, isError, mutate } = useData('multiplier');

  const { historyData } = getMultiplierHistory('multiplier/history');

  const [defaultData, setDefaultData] = useState(null);

  const [isInitial, setIsInitial] = useState(true);

  const [isDirty, setIsDirty] = useState(false);

  const [multiplier, setMultiplier] = useState({
    value: null,
    label: null
  });

  const [active, setActive] = useState(false);

  const [{month, year}, setDate] = useState({
    month: 1,
    year: 2021
  });
  const [selectedDates, setSelectedDates] = useState({
    start: today,
    end: nextWeek
  });

  const handleMonthChange = useCallback( (month, year) => {
    setDate({month, year})
    month && setIsDirty(true);
  }, [],);

  const handleDateChange = useCallback( (start, end) => {
    setSelectedDates({start: start, end: end});
    start && setIsDirty(true);
  }, [],)

  const handleEnableToggle = useCallback(() => {
    setActive((active) => !active);
    setIsDirty(true);
  }, []);

  const handleMultiplier = useCallback( (key, value) => {
    setMultiplier( (oldMultiplier) => {
      return {...oldMultiplier, [key]: value}
    });
    value && setIsDirty(true)
  }, [],)

  const handleSaveSettings = useCallback( async (_event) => {
    try {
      if ( isFunction(loading) ) {
        loading(true)
      }
      const requestData = {
        status: active,
        value: parseInt(multiplier?.value),
        label: multiplier?.label,
        giveaway_start_date: selectedDates.start,
        giveaway_end_date: selectedDates.end
      }

      const {data, message, success} = await saveSettings(requestData, defaultData.id);

      setDefaultData(data);
    } catch (error) {
      console.log(error.message)
    } finally {
      setIsDirty(false);
      if ( isFunction(loading) ) {
        loading(false)
      }
    }
  }, [multiplier, selectedDates, defaultData])

  const handleDiscard = useCallback(() => {
    formatSettingsData(defaultData);
    setIsDirty(false);
  }, [defaultData]);

  const formatSettingsData = (data) => {
    const newStartDate = new Date(data.giveaway_start_date)
    const newEndDate = new Date(data.giveaway_end_date)

    setActive(data.status)

    setMultiplier({
      value: data.value,
      label: data.label
    })

    setDate({
      month: newStartDate.getMonth(),
      year: newStartDate.getFullYear(),
    })

    setSelectedDates({
      start: newStartDate,
      end: newEndDate
    });

    setDefaultData(data)
  }

  const contentStatus = active ? 'Disable' : 'Enable';
  const textStatus = active ? 'enabled' : 'disabled';

  useEffect(() => {
    if ( isInitial && ! isEmpty(data) ) {
      setIsInitial(false);

      formatSettingsData(data)

      if ( isFunction(loading) ) {
        loading(false)
      }
    }
  }, [isLoading])

  const setHistoryRows = () => {
    let arrs = []
    if ( historyData && historyData.length > 0 ) {
      historyData.forEach( el => {
        let temp = []
        let keys = [ 'id', 'value', 'label', 'created_format' ]
        Object.keys( el ).forEach( key => {
          if ( keys.indexOf( key ) !== -1 ) {
            temp.push( el[key] )
          }
        })
        arrs.push( temp )
      } )
    }

    return arrs
  }

  const contextualSaveBarMarkup = isDirty ? (
    <ContextualSaveBar
      message="Unsaved changes"
      saveAction={{
        onAction: handleSaveSettings,
      }}
      discardAction={{
        onAction: handleDiscard,
      }}
    />
  ) : null;

  const loadingMarkup = <PageSkeleton />

  const pageMarkup = (
    <Fragment>
      {contextualSaveBarMarkup}
      <Page
        title="Settings"
        subtitle="Set your multipler settings here"
      >
        <Layout>
          <Layout.AnnotatedSection
            title="General Settings"
          >
            <SettingToggle
              action={{
                content: contentStatus,
                onAction: handleEnableToggle,
              }}
              enabled={active}
            >
              The Giveaway Entry Collection is <TextStyle variation="strong">{textStatus}</TextStyle>.
            </SettingToggle>
          </Layout.AnnotatedSection>
          <Layout.AnnotatedSection
            title="Multiplier"
            description="This will be the settings for the Giveaway Entries"
          >
            <Card sectioned>
              <Form>
                <FormLayout>
                  <TextField
                    value={multiplier.value?.toString()}
                    label="Value"
                    type="number"
                    onChange={(value) => handleMultiplier('value', value)}
                    helpText={<>This will be the multiplier value for the giveaway entries. E.g $10 x 5 = <Badge status="info">50 Entries</Badge></>}
                  />
                  <TextField
                    value={multiplier.label}
                    label="Tag Label"
                    onChange={(label) => handleMultiplier('label', label)}
                    helpText="This will be displayed after the entry points. Default is 'Entries'"
                  />
                </FormLayout>
              </Form>
            </Card>
          </Layout.AnnotatedSection>
          <Layout.AnnotatedSection
            title="Giveaway Duration"
            description="This will be use as the current duration for the newly created entries."
          >
            <Card sectioned>
              <DatePicker
                id="giveaway_duration"
                label="Label"
                month={month}
                year={year}
                onChange={({start, end}) => handleDateChange(start, end)}
                onMonthChange={handleMonthChange}
                selected={selectedDates}
                multiMonth
                allowRange
              />
            </Card>
          </Layout.AnnotatedSection>
          <Layout.AnnotatedSection
            title="Multiplier History"
            description="This will show multiplier changes history."
          >
            <Card sectioned>
              <DataTable
                columnContentTypes={[
                  'text',
                  'text',
                  'text',
                  'text',
                ]}
                headings={[
                  'No',
                  'Value',
                  'Tag Label',
                  'Date Changed',
                ]}
                rows={setHistoryRows()}
              />
            </Card>
          </Layout.AnnotatedSection>
        </Layout>
      </Page>
    </Fragment>
  )

  return (isLoading ? loadingMarkup : pageMarkup);
}

export default Settings;
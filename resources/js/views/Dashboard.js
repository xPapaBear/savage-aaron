import React, { useCallback, useEffect } from 'react'
import { BrowserRouter as Router, Route, Switch, useHistory, useLocation } from 'react-router-dom';
import {
  AppProvider,
  Avatar,
  Card,
  Page,
  Layout,
  ResourceList,
  ResourceItem,
  TextStyle,
  Badge,
  Stack,
  TextContainer,
  Button
} from '@shopify/polaris';
import { useData } from '../data';
import { currencyFormat, arraySort } from '../helpers';
import { isEmpty } from 'lodash';

const Dashboard = ({ location, response }, data, test) => {
  const { data: dashboard, isLoading, isError, mutate } = useData('dashboard');
  const { shop, shop_api: shopApi } = location.state?.user || {};
  const history = useHistory();

  const {
    customers,
  } = dashboard || {}

  const handleNavigation = useCallback( (slug) => {
    if ( slug ) {
      history.push(slug, {
        data: dashboard // your data array of objects
      })
    }
  }, [history, dashboard]);

  useEffect(() => {
    console.log(shopApi, location);
  }, [dashboard, shopApi])

  const customersMarkup = ! isEmpty(customers) ? (
    <AppProvider
      i18n={{
        Polaris: {
          ResourceList: {
            showing: 'Showing Top Customers',
          },
        },
      }}
    >
    <Card>
      <ResourceList
        resourceName={{singular: 'customer', plural: 'customers'}}
        items={arraySort(customers, 'total_points')}
        alternateTool={
          <Button
            onClick={handleNavigation('/customer-entries')}
            primary={true}
          >
            View Entries
          </Button>}
        renderItem={(customer) => {
          const {id, full_name, email, total_points, total_spent, store_customer_id} = customer;
          const media = <Avatar customer size="medium" name={full_name} />;

          return (
            <ResourceItem
              id={id}
              url={`/admin/customers/${store_customer_id}`}
              media={media}
              accessibilityLabel={`View details for ${full_name}`}
            >
              <Stack distribution="equalSpacing" alignment="center">
                <Stack.Item>
                  <TextContainer>
                    <p>{full_name}</p>
                    {email}
                  </TextContainer>
                </Stack.Item>
                <Stack.Item>
                  <Stack>
                    <Badge status="info">
                      {currencyFormat(shopApi, total_spent)}
                    </Badge>
                    <Badge status="success">
                      {total_points} Entries
                    </Badge>
                  </Stack>
                </Stack.Item>
              </Stack>
            </ResourceItem>
          );
        }}
      />
    </Card>
  </AppProvider>
  ) : null;
  return (
    <Page title="Dashboard">
        <Layout>
          <Layout.Section>
            {/* Top 5 Customers */}
            {customersMarkup}
            {/* End of Top 5 Customers */}
          </Layout.Section>
        </Layout>
    </Page>
  );
}

export default Dashboard;
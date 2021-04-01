import { Card, Page, Layout } from '@shopify/polaris';
import React from 'react'
import { multiplierHistoryFive, topFiveCustomerEP } from '../data/dashboard'

const Dashboard = ({loading}) => {

  const { multipliers } = multiplierHistoryFive()

  const { topFivesCustomers } = topFiveCustomerEP()

  const setMultipliers = () => {
    let arrs = []
    if ( multipliers ) {
      multipliers.forEach( ( multiplier, index ) => {
        let el = <Card.Section title={`Multiplier #${index + 1}`}>
          <p>{ `Value: ${multiplier.value}` } -- { `Label: ${multiplier.label}` }</p>
          <br />
          <small>{multiplier.created_format}</small>
        </Card.Section>

        arrs.push( el )
      } )

      return arrs
    }
  }

  const setTopFivesCustomers = () => {
    let arrs = []
    console.log( topFivesCustomers )
    if ( topFivesCustomers ) {
      topFivesCustomers.forEach( ( customer, index ) => {
        if ( index < 5 ) {
          let el = <Card.Section title={`Customer #${index + 1}`}>
            <p>{ `Name: ${customer.full_name}` } -- { `Total Points: ${customer.total_points}` }</p>
            <br />
            <small>{customer.entries[0].created_format}</small>
          </Card.Section>

          arrs.push( el )
        }
      } )

      return arrs
    }
  }

  return (
    <Page title="Dashboard">
      <Layout>
        <Layout.Section oneHalf>
          <Card title="Latest 5 ( Five ) Multipliers">
            { setMultipliers() }
          </Card>
        </Layout.Section>
        
        <Layout.Section oneHalf>
          <Card title="Top 5 ( Five ) Customers Total Points">
            { setTopFivesCustomers() }
          </Card>
        </Layout.Section>
      </Layout>
    </Page>
  );
}

export default Dashboard;
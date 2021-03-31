import { Card, Page, List } from '@shopify/polaris';
import React from 'react'
import { multiplierHistoryFive } from '../data/dashboard'

const Dashboard = ({loading}) => {

  const { data } = multiplierHistoryFive()

  const setMultipliers = () => {
    let multipliers = []
    if ( data ) {
      data.forEach( ( multiplier, index ) => {
        multipliers.push( <li className="Polaris-List__Item">{multiplier.value} - {multiplier.label}</li> )
      } )

      return multipliers
    }
  }

  return (
    <Page
      title="Dashboard"
    >
      <Card
        title="Latest 5 Multipliers"
        sectioned
      >
        <ol className="Polaris-List Polaris-List--typeNumber">
          { setMultipliers() }
        </ol>
      </Card>
    </Page>
  );
}

export default Dashboard;
import React from 'react'
import {
  Card,
  EmptyState
} from '@shopify/polaris'

const Error = () => {
  return (
    <Card sectioned>
      <EmptyState
        heading="Manage your inventory transfers"
        action={{content: 'Add transfer'}}
        secondaryAction={{content: 'Learn more', url: 'https://help.shopify.com'}}
        image="https://cdn.shopify.com/s/files/1/0262/4071/2726/files/emptystate-files.png"
      >
        <p>Oooops</p>
      </EmptyState>
    </Card>
  )
}

export default Error;
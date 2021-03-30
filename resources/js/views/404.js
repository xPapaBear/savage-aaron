import React from 'react'
import { DisplayText, EmptyState } from '@shopify/polaris'

const NoPageFound = () => {
  return (
    <EmptyState
      heading="The page you’re looking for could not be found"
      action={{content: 'Back to Dashboard', url: '/'}}
      image="svg/empty-state.svg"
    >
      <DisplayText small>Please make sure the web address is correct.</DisplayText>
    </EmptyState>
  )
}

export default NoPageFound
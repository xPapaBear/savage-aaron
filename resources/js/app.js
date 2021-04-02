import React, { useState, useCallback, useRef, Fragment, useEffect } from 'react';
import ReactDOM from 'react-dom';

import { BrowserRouter as Router, Route, Switch, useHistory, useLocation } from 'react-router-dom';
import {
  AppProvider,
  Frame,
  Layout,
  Card,
  TextContainer,
  Navigation,
  Loading,
  Toast,
  TopBar,
  ActionList,
  SkeletonPage,
  SkeletonDisplayText,
  SkeletonBodyText,
  FooterHelp,
  Link
} from '@shopify/polaris'
import {
  HomeMajor,
  GiftCardMajor,
  SettingsMajor,
} from '@shopify/polaris-icons'
import en from '@shopify/polaris/locales/en.json';
import '@shopify/polaris/dist/styles.css';
import { APP_PATH } from './constants';
import Dashboard from './views/Dashboard'
import Customers from './views/Customers'
import CustomerEntries from './views/CustomerEntries'
import Settings from './views/Settings'
import { useData } from './data';
import Error from './components/Error';
import { PageSkeleton } from './components/Skeletons';

const App = () => {
  const { data: response, isError, isLoading: isFetching, mutate } = useData('user');

  const history = useHistory();
  const skipToContentRef = useRef(null);

  const [toastActive, setToastActive] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [hasError, setHasError] = useState(false);
  const [loadingCount, setLoadingCount] = useState(1);
  const [isDirty, setIsDirty] = useState(false);
  const [mobileNavigationActive, setMobileNavigationActive] = useState(false);
  const [modalActive, setModalActive] = useState(false);

  const handleError = useCallback( (state) => {
    setHasError(state);
  }, [])

  const toggleToastActive = useCallback(
    () => setToastActive((toastActive) => !toastActive),
    [],
  );
  const toggleMobileNavigationActive = useCallback(
    () =>
      setMobileNavigationActive(
        (mobileNavigationActive) => !mobileNavigationActive,
      ),
    [],
  );
  const handleNavigation = useCallback( (slug) => {
    if ( slug ) {
      history.push(slug, {
        user: response,
        data: response // your data array of objects
      })
    }
  }, [history, response]);

  const toggleModalActive = useCallback(
    () => setModalActive((modalActive) => !modalActive),
    [],
  );

  const handleLoading = (state = false) => {
    let count = loadingCount;

    count = state ? count + 1 : count - 1;
    count = count > 0 ? count : 0;

    if ( count <= 0 ) {
      setIsLoading(false)
    } else {
      setIsLoading(true)
    }

    setLoadingCount(count)
  };

  useEffect(() => {
    history.push("/", {
      user: response // your data array of objects
    })
  }, [response])

  const toastMarkup = toastActive ? (
    <Toast onDismiss={toggleToastActive} content="Changes saved" />
  ) : null;

  const contextualSaveBarMarkup = isDirty ? (
    <ContextualSaveBar
      message="Unsaved changes"
      saveAction={{
        onAction: handleSave,
      }}
      discardAction={{
        onAction: handleDiscard,
      }}
    />
  ) : null;

  const topBarMarkup = (
    <TopBar
      showNavigationToggle
      onNavigationToggle={toggleMobileNavigationActive}
    />
  );

  const navigationMarkup = (
    <Navigation location="/">
      <Navigation.Section
        title="Giveaway Entry Collection App"
        items={[
          {
            label: 'Dashboard',
            icon: HomeMajor,
            onClick: () => handleNavigation('/'),
          },
          {
            label: 'Entries',
            icon: GiftCardMajor,
            onClick: () => handleNavigation(`/customer-entries`),
          },
          {
            label: 'Settings',
            icon: SettingsMajor,
            onClick: () => handleNavigation(`/settings`),
          },
        ]}
      />
    </Navigation>
  );

  const loadingMarkup = isLoading ? <Loading /> : null;

  const skipToContentTarget = (
    <a id="SkipToContentTarget" ref={skipToContentRef} tabIndex={-1} />
  );

  const Routes = () => {
    return (
      <Switch>
        <Route
          exact
          exact path="/"
          render={(props) => (
            <Dashboard {...props} data={response} />
          )}
        />
        <Route
          exact
          path={`/customer-entries`}
          render={(props) => (
            <CustomerEntries {...props} />
          )}
        />
        <Route
          exact
          path={`/settings`}
          render={(props) => (
            <Settings {...props} />
          )}
        />
      </Switch>
    )
  };

  const errorMarkup = (
    <Error />
  );

  const loadingPageMarkup = (
    <PageSkeleton />
  );

  const footerHelpMarkup = (
    <FooterHelp>
      Learn more about{' '}
      <Link url="https://help.shopify.com/manual/orders/fulfill-orders">
        fulfilling orders
      </Link>
    </FooterHelp>
  )

  const pageMarkup = isLoading || isFetching ? loadingPageMarkup : (
    <Fragment>
      <Routes />
      {footerHelpMarkup}
    </Fragment>
  );

  const theme = {
    logo: {
      width: 124,
      topBarSource:
        '/images/giveaway-entry-collection-logo.png',
      contextualSaveBarSource:
        '/images/giveaway-entry-collection-logo.png',
      url: `${APP_PATH}`,
      accessibilityLabel: 'Giveaway Entry Collection',
    },
  };

  return (
    <div>
      <AppProvider
        theme={theme}
        i18n={en}
      >
        <Frame
          topBar={topBarMarkup}
          navigation={navigationMarkup}
          showMobileNavigation={mobileNavigationActive}
          onNavigationDismiss={toggleMobileNavigationActive}
          skipToContentTarget={skipToContentRef.current}
        >
          {contextualSaveBarMarkup}
          {loadingMarkup}
          {pageMarkup}
          {toastMarkup}
        </Frame>
      </AppProvider>
    </div>
  );
}

export default App

if ( document.getElementById('root') ) {
  ReactDOM.render(<Router><App /></Router>, document.getElementById('root'));
}
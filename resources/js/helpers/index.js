import moment from "moment";

export const getCsrf = () => document.getElementsByName('csrf-token')[0].content;

export const requestOptions = () => {
  const requestOptions = {
    method: 'POST',
    headers: {
      'Content-Type': 'pplication/json',
      'csrf-token': getCsrf()
    }
  };
  return requestOptions; 
}

export const today = () => {
  const date = new Date();
  return date;
}

export const currencyFormat = ({primary_locale, country_code, currency}, amount) =>
  new Intl.NumberFormat(`${primary_locale}-${country_code}`, {
    style: 'currency',
    currency: currency
  }).format(amount);

export const formatDateValue = (date) => moment(date).format('YYYY-MM-DD');

// Sort data based on column key
export const arraySort = (data, key) => {
  if ( ! data ) return data;

  return data.sort( (a, b) => {
      if(a[key] < b[key]) { return 1; }
      if(a[key] > b[key]) { return -1; }
      return 0;
  });
}
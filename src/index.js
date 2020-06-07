import React from 'react';
import ReactDOM from 'react-dom';
import { IntlProvider } from 'react-intl';
//import {}

import App from './components/App';

// import 'purecss/build/pure-min.css';
import './index.css';

ReactDOM.render(
  <IntlProvider locale="en">
    <App />
  </IntlProvider>,
  document.getElementById('root')
);

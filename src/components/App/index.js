import React from 'react';
import './style.css';
import UrlHarvester from '../UrlHarvester';
import UrlList from '../UrlList';

class App extends React.Component {
  render() {
    return (
      <div>
        <h1>Shorty</h1>
        <div id="results">
          <div className="result">
            <UrlHarvester></UrlHarvester>
          </div>
        </div>
        <div className="url-list">
          <UrlList></UrlList>
        </div>
      </div>
    );
  }
}

export default App;
import React from 'react';
import './style.css';

class UrlHarvester extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      url: ''
    };

    this.handleChange = this.handleChange.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
  }

  handleChange(event) {
    this.setState({url: event.target.value});
  }

  handleSubmit(event) {
    this.create();
    event.preventDefault();
  }

  create() {
    const params = {
      method: 'POST',
      headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
      body: JSON.stringify({form: {url: this.state.url}})
    };

    fetch('/api/urls', params)
      .then(res => {
        console.log(res);
        this.setState({url: ''});
      });
  }

  render() {
    return (
      <form className="pure-form" method="POST" onSubmit={this.handleSubmit}>
        <input
          type="text"
          className="pure-input url"
          placeholder="Enter really long URL"
          value={this.state.url}
          onChange={this.handleChange}/>
        <button type="submit" className="pure-button pure-button-primary button-go">Go</button>
      </form>
    );
  }
};

export default UrlHarvester;
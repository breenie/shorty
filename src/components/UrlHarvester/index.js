import React from "react";
import Success from "../Success";
import "./style.css";

/**
 * Gets a string of random characters.
 *
 * @param int count
 */
const shortId = (count = 7) => {
  const chars =
    "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
  return "x".repeat(count).replace(/x/g, c => {
    return chars.charAt((Math.random() * chars.length) | 0);
  });
};

class UrlHarvester extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      url: "",
      created: null
    };

    this.handleChange = this.handleChange.bind(this);
    this.handleSubmit = this.handleSubmit.bind(this);
    this.handleReset = this.handleReset.bind(this);
  }

  handleChange(event) {
    this.setState({ url: event.target.value });
  }

  handleSubmit(event) {
    this.create();
    event.preventDefault();
  }

  handleReset(event) {
    this.setState({
      url: "",
      created: null
    });
    event.preventDefault();
  }

  create() {
    const params = {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        Accept: "application/json"
      },
      body: JSON.stringify({ id: shortId(6), url: this.state.url, visits: 0 })
    };

    fetch(`${process.env.REACT_APP_INVOKE_URL}/urls`, params)
      .then(res => res.json())
      .then(json => {
        this.setState({ created: json.id, url: `/${json.id}` });
      })
      .catch(err => {
        console.log(err);
      });
  }

  render() {
    if (this.state.created) {
      return (
        <Success onClick={this.handleReset} shortUrl={this.state.created} />
      );
    }

    return (
      <div>
        <form
          className="url-harvester pure-form"
          method="POST"
          onSubmit={this.handleSubmit}
        >
          <input
            type="url"
            className="pure-input url"
            placeholder="Enter a really long URL"
            value={this.state.url}
            onChange={this.handleChange}
          />
          <button
            type="submit"
            className="pure-button pure-button-primary button-go"
          >
            Go
          </button>
        </form>
      </div>
    );
  }
}

export default UrlHarvester;

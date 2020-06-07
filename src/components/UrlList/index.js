import React from "react";
import { FormattedDate, FormattedNumber, FormattedTime } from "react-intl";

import "./style.css";

function removeScheme(url) {
  return (url || "").replace(/.*?:\/\//g, "");
}

function checkStatus(res) {
  if (res.status >= 200 && res.status < 300) {
    return res;
  } else {
    let err = new Error(res.statusText);
    err.response = res;
    throw err;
  }
}

class UrlList extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      urls: [],
      next: null,
      limit: 20,
      history: [],
    };
  }

  getUrls(limit, last) {
    limit = limit || 20;
    last = last || "";

    return new Promise((resolve, reject) => {
      fetch(`/api/urls?limit=${limit}&last=${last}`)
        .then(checkStatus)
        .then(res => res.json())
        .then(response => {
          resolve({
            next: response.last || null,
            urls: response.urls,
            limit: limit,
          });
        })
        .catch(error => {
          console.log(error);
        });
    });
  }

  next() {
    const last = this.state.next;
    this.getUrls(this.state.limit, last).then(result => {
      this.state.history.push(last);
      this.setState({
        next: result.next,
        urls: result.urls,
        limit: result.limit,
      });
    });
  }

  prev() {
    const history = this.state.history;
    history.pop();
    const prev = history.slice(-1);
    this.getUrls(this.state.limit, prev).then(result => {
      this.setState({
        history: history,
        next: result.next,
        urls: result.urls,
        limit: result.limit,
      });
    });
  }

  start() {
    return 1 === this.state.history.length;
  }

  end() {
    return null === this.state.next;
  }

  componentDidMount() {
    this.next();
  }

  render() {
    return (
      <div className="url-list">
        <table className="pure-table">
          <thead>
            <tr>
              <th className="long-url">Original URL</th>
              <th className="short-url">Short URL</th>
              <th className="created">Created</th>
              <th className="visits">Visits</th>
            </tr>
          </thead>

          <tfoot>
            <tr>
              <td colSpan="4">
                <div className="pagination">
                  <p className="details">
                    Page <FormattedNumber value={this.state.history.length} />
                  </p>

                  <button
                    onClick={() => this.prev()}
                    className="pure-button pure-button-primary button-go"
                    disabled={this.start()}
                  >
                    &lsaquo;
                  </button>
                  <button
                    onClick={() => this.next()}
                    className="pure-button pure-button-primary button-go"
                    disabled={this.end()}
                  >
                    &rsaquo;
                  </button>
                </div>
              </td>
            </tr>
          </tfoot>

          <tbody>
            {this.state.urls.map(url => (
              <tr key={url.id}>
                <td>
                  <a href={url.url}>{url.url}</a>
                </td>
                <td>
                  <a href={url.id}>{removeScheme(url.id)}</a>
                </td>
                <td>
                  <FormattedDate value={url.created} />{" "}
                  <FormattedTime value={url.created} />
                </td>
                <td className="visits">
                  <FormattedNumber value={url.visits} />
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    );
  }
}

export default UrlList;

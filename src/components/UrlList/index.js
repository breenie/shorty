import React from 'react';
import {FormattedDate, FormattedNumber, FormattedTime} from 'react-intl';

import './style.css';

function removeScheme(url) {
  return (url || '').replace(/.*?:\/\//g, '');
}

class UrlList extends React.Component {
  constructor(props) {
    super(props);

    this.state = {
      urls:   [],
      total:  0,
      limit:  20,
      offset: 0
    };
  }

  getUrls(offset, limit) {
    offset = offset || 0;
    limit  = limit || 10;
    fetch(`/api/urls?offset=${offset}'&limit=${limit}`)
      .then(res => res.json())
      .then((response) => {
        this.setState({
          urls:   response.results,
          total:  response.total,
          limit:  limit,
          offset: offset
        })
      });
  };

  next() {
    this.getUrls(this.state.offset + this.state.limit, this.state.limit);
  }

  prev() {
    this.getUrls(this.state.offset - this.state.limit, this.state.limit);
  }

  start() {
    return 0 === this.state.offset;
  }

  end() {
    return this.state.offset + this.state.limit >= this.state.total;
  }

  to() {
    return this.end() ? this.state.total : this.state.offset + this.state.limit;
  }

  last() {
    const remain = this.state.total % this.state.limit;
    return this.state.total - (0 === remain ? this.state.limit : remain);
  }

  componentDidMount() {
    this.getUrls(0, 15);
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
            <th className="clicks">Clicks</th>
          </tr>
          </thead>

          <tfoot>
          <tr>
            <td colSpan="4">
              <div className="pagination">
                <p className="details">
                  Displaying <FormattedNumber value={this.state.offset + 1} /> to <FormattedNumber value={this.to()}/> of <FormattedNumber value={this.state.total}/> urls
                </p>

                <button
                  onClick={() => this.getUrls(0, this.state.limit)}
                  className="pure-button pure-button-primary button-go"
                  disabled={this.start()}>&laquo;</button>
                <button
                  onClick={() => this.prev()}
                  className="pure-button pure-button-primary button-go"
                  disabled={this.start()}>&lsaquo;</button>
                <button
                  onClick={() => this.next()}
                  className="pure-button pure-button-primary button-go"
                  disabled={this.end()}>&rsaquo;</button>
                <button
                  onClick={() => this.getUrls(this.last(), this.state.limit)}
                  className="pure-button pure-button-primary button-go"
                  disabled={this.end()}>&raquo;</button>
              </div>
            </td>
          </tr>
          </tfoot>

          <tbody>

          {this.state.urls.map(
            url => <tr key={url.hash}>
              <td><a href={url.long_url}>{url.long_url}</a></td>
              <td><a href={url.short_url}>{removeScheme(url.short_url)}</a></td>
              <td>
                <FormattedDate value={url.created}/> <FormattedTime value={url.created}/>
              </td>
              <td className="clicks"><FormattedNumber value={url.clicks}/></td>
            </tr>
          )}
          </tbody>
        </table>
      </div>
    );
  }
}

export default UrlList;
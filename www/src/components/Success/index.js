import React from 'react';
import './style.css';

function Success(props) {
    return (
        <div className="success">
            <p>Created new short URL: <a href={props.shortUrl}>{props.shortUrl}</a></p>
            <button type="submit" className="pure-button pure-button-primary button-go" onClick={props.onClick}>Create another</button>
        </div>
    );
}

export default Success;
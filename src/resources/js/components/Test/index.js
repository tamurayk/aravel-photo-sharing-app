import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class Test extends Component {
  render() {
    return (
      <div style={{backgroundColor: 'green'}}>This is react component.</div>
    );
  }
}

ReactDOM.render(<Test />, document.getElementById('test'));

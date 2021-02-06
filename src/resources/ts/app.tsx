import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import Test from './components/Test'

if (document.getElementById('test')) {
    ReactDOM.render(<Test />, document.getElementById('test'));
}

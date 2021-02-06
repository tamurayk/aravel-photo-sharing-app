import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import Test from './components/Test'
import hgo from './bootstrap'

// note: import vs require
//  Export/Import: ES6によって定義されているもの
//  require(): Node.jsのモジュールローディングシステム
//
// ひとまず、TypeScript化前の app.js にあったものをそのまま残した
// https://github.com/tamurayk/laravel-photo-sharing-app/pull/17/commits/90c4e94c92d0868a01374719d5f31d7ebb65ec64#diff-7b0f685825ce375ce9551b3b9b9f1c0dd1abe8b65f989357a56805559355f9e5L7
require('./bootstrap')

if (document.getElementById('test')) {
    ReactDOM.render(<Test />, document.getElementById('test'));
}

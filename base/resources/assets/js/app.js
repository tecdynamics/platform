import { axios, HttpClient } from './utilities'

window._ = require('lodash')

window.axios = axios
// window.Vue = require('vue');


window.$httpClient = new HttpClient()

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
})

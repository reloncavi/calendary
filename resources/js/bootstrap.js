import axios from 'axios';
import $ from 'jquery';
import * as bootstrap from 'bootstrap';
import 'flatpickr/dist/flatpickr.css';
import 'select2/dist/css/select2.min.css';

// Make jQuery globally available (required by DataTables and Select2)
window.$ = window.jQuery = $;

// Bootstrap 5
window.bootstrap = bootstrap;

// Axios CSRF setup
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

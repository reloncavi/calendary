import './bootstrap';
import flatpickr from 'flatpickr';
import { Spanish } from 'flatpickr/dist/l10n/es.js';

// Initialize datetime pickers on all pages
document.addEventListener('DOMContentLoaded', function () {
    const datetimeConfig = {
        enableTime: true,
        dateFormat: 'Y-m-d H:i:S',
        time_24hr: true,
        locale: Spanish,
    };

    const dateConfig = {
        enableTime: false,
        dateFormat: 'Y-m-d',
        locale: Spanish,
    };

    document.querySelectorAll('.flatpickr-datetime').forEach(el => {
        flatpickr(el, datetimeConfig);
    });

    document.querySelectorAll('.flatpickr-date').forEach(el => {
        flatpickr(el, dateConfig);
    });

    // Select2 initialization via jQuery (still used by DataTables and Select2)
    if (window.jQuery && window.$.fn.select2) {
        window.$('.select2').select2({ width: '100%' });
    }
});

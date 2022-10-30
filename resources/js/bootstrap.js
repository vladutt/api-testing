import _ from 'lodash';
window._ = _;

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import { useLoading } from 'vue3-loading-overlay';
window.loading = useLoading;

let loader = loading();
let currentAxiosCalls = 0;

window.axios.interceptors.request.use(config => {
    console.log(typeof config.data !== 'undefined' && typeof config.data.withoutSpinner === 'undefined');

    if (typeof config.data === 'undefined' && typeof config.params === 'undefined'
        || (typeof config.data !== 'undefined' && typeof config.data.withoutSpinner === 'undefined')
        || (typeof config.params !== 'undefined' && typeof config.params.wihtoutSpinner === 'undefined')) {

        currentAxiosCalls++;
        if (currentAxiosCalls === 1) {
            loader.show({
                container: null
            }); // for every request start the progress
        }
    }

    return config;
});

window.axios.interceptors.response.use(response => {
    if (typeof response.config.data === 'undefined' && typeof response.config.params === 'undefined'
        || (typeof response.config.data !== 'undefined' && typeof response.config.data.withoutSpinner === 'undefined')
        || (typeof response.config.params !== 'undefined' && typeof response.config.params.wihtoutSpinner === 'undefined')) {

        currentAxiosCalls--;
        if (currentAxiosCalls === 0) {
            loader.hide(); // hide when a response is received
        }
    }

    return response;
});
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });

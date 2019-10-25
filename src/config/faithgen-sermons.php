<?php

return [
    /**
     * The prefix to your sermons routes
     */
    'prefix' => 'xxx',
    'middlewares' => [
        'auth:api', // this filters for authenticated requests from the ministry
        'ministry.activated', // this filters only activated ministries to use
        /**
         * add more middlewares here if any
         */
    ],
];

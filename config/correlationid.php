<?php

return [

    /*
     * True: Middleware will inject on Logs whatever sent correlation id on Headers array
     * False: Will not propagate
     */
    'propagates' => true,

    /*
     * Used to fetch from Headers array a correlation id
     */
    'header_name' => 'X-CORRELATION-ID',

    /*
     * Used to inject within context array in logs
     */
    'param_name' => 'x_correlation_id',
];
const $ = require( 'jquery' );

global.$ = global.jQuery = $;

require('bootstrap');

require('bootstrap/dist/css/bootstrap.min.css');
require('bootstrap/dist/js/bootstrap.bundle.js');

require('jquery.easing/jquery.easing.js');

require('@fortawesome/fontawesome-free/css/all.css');
require('@fortawesome/fontawesome-free/js/all.js');
//require('../css/app.css');
require('startbootstrap-sb-admin-2/css/sb-admin-2.min.css');

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});
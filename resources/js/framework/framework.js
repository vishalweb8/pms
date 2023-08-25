try {
    window.Popper = require('popper.js').default;
    global.$ = window.$ = window.jQuery = require('jquery');
    window.bootstrap = require('bootstrap');
    window.select2 = require('select2');
    require( 'datatables.net' );
    require("datatables.net-buttons-bs4");
    require("datatables.net-responsive-bs4");
} catch (e) {
    console.log('error in framework.js' + e)
}

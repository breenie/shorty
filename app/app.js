import '../node_modules/bootstrap-without-jquery/bootstrap3/bootstrap-without-jquery.min.js';
import '../node_modules/angular/angular.js';
import '../node_modules/angular-route/angular-route.js';
import '../node_modules/angular-resource/angular-resource.js';
import '../node_modules/angular-flash/dist/angular-flash.js';
import '../node_modules/angular-ui-bootstrap/ui-bootstrap-tpls.js';
import '../node_modules/tagged-infinite-scroll/taggedInfiniteScroll.js';

import '../node_modules/bootstrap/dist/css/bootstrap.min.css';
import './styles/shorty.css'
import './favicon.ico';


function requireAll(context) {
  context.keys().forEach(context);
}

requireAll(require.context(
  './scripts',
  true,
));
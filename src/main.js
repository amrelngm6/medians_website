import Vue from 'vue'
import App from './App.vue'
import "@andresouzaabreu/vue-data-table/dist/DataTable.css";

import VueSimpleAlert from "vue-simple-alert";
Vue.use(VueSimpleAlert);

import vSelectMenu from 'v-selectmenu';
Vue.use(vSelectMenu);

import VueNativeNotification from 'vue-native-notification'
Vue.use(VueNativeNotification, {requestOnNotify: false})


import DataTable from "@andresouzaabreu/vue-data-table";
Vue.component("data-table", DataTable);

Vue.component('moment', () => import ('moment'));
Vue.component('login-dashboard', () => import('./components/login-dashboard'));
Vue.component('side-menu', () => import('./components/side-menu'));
Vue.component('close_icon', () => import('./components/svgs/Close'));

import QrcodeVue from 'qrcode.vue';
Vue.component('qr_code', QrcodeVue);
Vue.component('vue-medialibrary-manager', () => import('./components/Manager'));
Vue.component('vue-medialibrary-field', () => import('./components/Field'));

Vue.config.productionTip = false

global.jQuery = require('jquery');
var jQuery = global.jQuery;
var $ = global.jQuery;
window.$ = $;


function pushScreenshotToServer(dataURL, err, info) {  
    $.ajax({ 
        url: "/api/bug_report",  
        type: "POST",  
        data: {info:info, err: err, image: dataURL},  
        dataType: "html", 
        success: function() {}  
    });  
}   
Vue.config.errorHandler = function (err, vm, info)  {
  console.log('[Global Error Handler]: Error in ' + info + ': ' + err);
  if (!enable_debug)
    return null;
  const screenshotTarget = document.body;
  html2canvas(screenshotTarget).then(canvas => {
      document.body.appendChild(canvas);  
      pushScreenshotToServer(canvas.toDataURL(), err, info); 
  });
};


new Vue({
  render: h => h(App),
}).$mount('#app')

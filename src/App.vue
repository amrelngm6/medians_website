<template>
    <div>
        <div v-if="show" class="left-4">
            <!-- component -->
            <div class="w-full ">
                <navbar class="w-full"  v-if="auth" style="z-index: 99999;" :setting="setting" :lang="lang" :conf="conf" :auth="auth"></navbar>
                <a href="javascript:;" class="mainmenu-close w-6 text-lg absolute top-4 mx-3 block" style="z-index:99999" @click="showSide = !showSide"><i class="fa fa-bars"></i></a>
                <div class="gap gap-6 h-full flex w-full overflow-hidden py-4 pb-10 ">
                    <side-menu :samepage="activeTab" :auth="auth" :url="conf.url ? conf.url : '/'" :menus="main_menu" v-if="auth && showSide" class="sidebar mx-1" id="sidebar" style="z-index:999">
                    </side-menu>

                    <div v-if="auth" class="w-full flex overflow-auto" style="height: 85vh; z-index: 9999;">
                        <div class="w-full">
                            <transition   :duration="550">
                                <component ref="activeTab" :key="activeTab" :path="activeTab" :setting="setting" :lang="lang" :conf="conf" :auth="auth" :is="component"></component>
                            </transition>

                        </div>
                    </div>
                    <div v-else class="w-full flex overflow-auto" style="height: 85vh; z-index: 9999;">
                        <login form_action="/" ></login>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
const axios = require('axios').default;


import login from './components/login-dashboard.vue'
import SideMenu from './components/side-menu.vue'
import navbar from './components/navbar.vue'
import dashboard from './components/dashboard.vue'
import categories from './components/categories.vue'
import settings from './components/settings.vue'
import users from './components/users.vue'
import customers from './components/customers.vue'
import blog from './components/blog.vue'
import pages from './components/pages.vue'
import notifications from './components/notifications.vue'
import notifications_events from './components/notifications_events.vue'

export default {
    name: 'app',
    components: {
        login,
        SideMenu,
        dashboard,
        categories,
        settings,
        users,
        customers,
        blog,
        pages,
        notifications,
        notifications_events,
        navbar
    },
    data() {
        return {

            date: '',
            activeItem: null,
            showAddSide: false,
            showEditSide: false,
            showTab: true,
            component: {},
            activeTab: 'dashboard',
            status: null,
            lang: {},
            auth: {},
            setting: {},
            conf: {},
            main_menu: [],
            typesList: [],
            show: false,
            showSide: true,
            showModal: false,
            activeModal: null,
        };
    },
    mounted() {
        
        const t = this;

        this.setProps();

        $(window).on('popstate', function(e) {
            t.switchTab({link:window.location.pathname.replace('/','')})
        });

        jQuery(document).on('submit', 'form',function (e) {
            e.preventDefault();
            t.submit(this, e)
        })

        this.showSide =  (window.screen.availWidth > 1000 ) ? true : false;
        this.notify()
    },
    methods: {
        /**
         * Check notifications permission
         * Send welcome notification
         */ 
        notify (title, body) {

            if ("Notification" in window) 
            {
                if (Notification.permission !== "granted") {
                    Notification.requestPermission().then(permission => {
                        if (permission === "granted") {
                            const notification = new Notification("Thanks & welcome", {
                                body: "Welcome to " + this.setting.sitename,
                                icon: this.setting.logo
                            });
                        }
                    });
                }
            }
        },


        /**
         * Get the props for App root
         */
        setProps()
        {
            const mountEl = document.querySelector("#root-parent");
            let props = { ...mountEl.dataset };
            this.auth = props ? JSON.parse(props.auth) : {};
            this.main_menu = props ? JSON.parse(props.menu) : {};
            this.lang = props ? JSON.parse(props.lang) : {};
            this.setting = props ? JSON.parse(props.setting) : {};
            this.conf = props ? JSON.parse(props.conf) : {};
            this.activeTab = (props && props.page) ? props.page : 'dashboard';
            this.component = (props && props.component) ? props.component : 'dashboard';
            this.typesList = (props && props.typesList) ? props.typesList : [];
            this.show = true
        },


        /**
         * Switch between Tabs
         */ 
        switchTab(tab) {
            if (!tab.sub)
            {
                this.show = false
                this.activeTab = (tab && tab.link) ? tab.link : 'dashboard';
                this.component = (tab && tab.component) ? tab.component : this.activeTab;
                this.show = true
                history.pushState({menu: tab}, '', this.conf.url+this.activeTab);
            }
        },
        
        checkIsInvoice()
        {
            return this.activeTab.includes('invoices/show') ? true : null;

        },
        setValues(data) {
            this.content = JSON.parse(JSON.stringify(data)); return this
        },

        delete(item, type) {
            
            if (!window.confirm(this.__('confirm_delete')))
            {
                return null;
            }

            var params = new URLSearchParams();
            params.append('type', type)
            params.append('params[id]', item.id)
            this.handleRequest(params, '/api/delete').then(response => {
                this.$alert(response.result)
            })
        },

        /**
         * Handle login access result 
         * 
         */
        handleAccess(response) 
        {
            if (response && response.success == 1)
            {
                this.$alert(response.result).then(() => {
                    location.reload();
                });
            } else {
                response ? this.$alert(response.error ? response.error : response.result) : '';
            }
        },

        submit(element, props)
        {
            let Things = jQuery(element).serializeArray()
            var params = new URLSearchParams();
            Things.map(function(n){
                params.append([n['name']],  n['value']);
            });

            this.handleRequest(params, jQuery(element).attr('action')).then(response => {
                this.handleAccess(response)
            })
        },
        async handleRequest(params, url = '/api') {

            // Demo json data
            return await axios.post(url, params.toString()).then(response => {
                if (response.data.status)
                    return response.data.result;
                else
                    return response.data;
            });
        },

        async handleGetRequest(url) {

            var t = this;
            // Demo json data
            return await axios.get(url).then(response => {
                t.showLoader = false;

                if (response.data.status)
                    return response.data.result;
                else
                    return response.data;
            });
        },
        __(i) {
                
            let key = i.toLowerCase().replaceAll(' ', '_');
            let k = i.replaceAll('_', ' ');
            let un_key = k.charAt(0).toUpperCase() + k.slice(1);

              return this.lang[key] ? this.lang[key] : un_key;

        }
    }
}
</script>
<style>
@import './assets/webfonts/fontawesome.min.css';
@import './assets/bootstrap-grid.min.css';
@import './assets/media-library.css';
@import './assets/alertify/alertify.min.css';
@import './assets/style.css';
@import './assets/theme.css';
@import './assets/theme.min.css';
</style>
<template>
    <div class="w-full flex overflow-auto" style="height: 85vh; z-index: 9999;">
        <div class=" w-full">

            <main v-if="setting && !showLoader" class=" flex-1 overflow-x-hidden overflow-y-auto  w-full">
                <!-- New releases -->
                <div class="px-4 mb-6 py-4 rounded-lg shadow-lg bg-white dark:bg-gray-700 flex w-full">
                    <h1 class="font-bold text-lg w-full" v-text="__('settings')"></h1>
                </div>
                <hr class="mt-2" />
                <div class="w-full flex gap gap-6">
                    <div class="w-full">
                        <form action="/api/update" method="POST" data-refresh="1" id="add-device-form" class="action  py-0 m-auto rounded-lg pb-10" v-if="showForm">

                            <input name="type" type="hidden" value="Settings.update">

                            <div class="w-full flex gap-4" v-if="activeTab == 'basic'">
                                <div class="card w-full " >
                                    <div class="card-body pt-0">
                                        <div class="settings-form">
                                            <label class="block py-3" >
                                                <input name="params[settings][logo]" type="hidden" :value="setting.logo">
                                                <div class="w-full block  cursor-pointer">
                                                    <span class="text-gray-700 w-20">{{__('Logo')}} <span class="star-red">*</span></span>
                                                    <vue-medialibrary-field name="params[settings][logo]" :api_url="conf.url" v-model="setting.logo"></vue-medialibrary-field>
                                                </div>

                                            </label>


                                            <label class="block py-3">
                                                <span class="text-gray-700">{{__('Sitename')}} <span class="star-red">*</span></span>
                                                <input name="params[settings][sitename]" type="text" class="h-10 mt-3 rounded w-full border px-3 text-gray-400  focus:border-blue-100 dark:bg-gray-800 dark:border-gray-600" required :placeholder="__('Sitename')" :value="setting.sitename">
                                            </label>

                                            <label class="block py-3">
                                                <span class="text-gray-700">{{__('Language')}} <span class="star-red">*</span></span>
                                                <select class="select h-10 mt-3 rounded w-full border px-3 text-gray-400  focus:border-blue-100 dark:bg-gray-800 dark:border-gray-600 " name="params[settings][lang]" :value="setting.lang">
                                                    <option value="english">English</option>
                                                    <option value="arabic">العربية</option>
                                                </select>
                                            </label>

                                        </div>
                                    </div>
                                </div>

                                <div class="w-full ">                               
                                    <div class="card">
                                        <div class="card-body pt-0">
                                            <div class="settings-form">
                                                <label class="block py-3">
                                                    <span class="text-gray-700">{{__('Currency')}} <span class="star-red">*</span></span>
                                                    <input name="params[settings][currency]" type="text" class="h-10 mt-3 rounded w-full border px-3 text-gray-400  focus:border-blue-100 dark:bg-gray-800 dark:border-gray-600" :placeholder="__('Currency')" required :value="setting.currency">
                                                </label>
                                                <label class="block py-5">
                                                    <span class="text-gray-700"> {{__('Minimum_Stock_alert')}} ( {{__('Number')}} ) <span class="star-red">*</span></span>
                                                    <input name="params[settings][stock_alert]" type="number" class="h-10 mt-3 rounded w-full border px-3 text-gray-400  focus:border-blue-100 dark:bg-gray-800 dark:border-gray-600" :placeholder="__('Stock_alert')" required :value="setting.stock_alert">
                                                </label>

                                                <label class="block py-5">
                                                    <span class="text-gray-700"> {{__('tax')}}  ( % ) </span>
                                                    <input name="params[settings][tax]" type="number" max="99" min="0" class="h-10 mt-3 rounded w-full border px-3 text-gray-400  focus:border-blue-100 dark:bg-gray-800 dark:border-gray-600" :placeholder="__('tax')" required :value="setting.tax">
                                                </label>


                                                <label class="block py-3">
                                                    <label>{{__('Enable debugging')}} <span class="star-red">*</span></label>
                                                    <select class="select h-10 mt-3 rounded w-full border px-3 text-gray-400  focus:border-blue-100 dark:bg-gray-800 dark:border-gray-600 " name="params[settings][enable_debug]" :value="setting.enable_debug">
                                                        <option value="1" v-text="__('enabled')" ></option>
                                                        <option value="0" v-text="__('disabled')" ></option>
                                                    </select>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>




                            <div class="w-full block" v-if="activeTab == 'address'">
                                <div class="card" >
                                    <div class="card-body pt-0">
                                        <div class="settings-form">
                                            <div class="form-group">
                                                <label>{{__('Address')}}  <span class="star-red">*</span></label>
                                                <input name="params[settings][address]" type="text" class="h-10 mt-3 rounded w-full border px-3 text-gray-400  focus:border-blue-100 dark:bg-gray-800 dark:border-gray-600" placeholder="Enter Address Line 2" required :value="setting.address">
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{__('City')}} <span class="star-red">*</span></label>
                                                        <input name="params[settings][city]" type="text" class="h-10 mt-3 rounded w-full border px-3 text-gray-400  focus:border-blue-100 dark:bg-gray-800 dark:border-gray-600" required :value="setting.city">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{__('Country')}} </label>
                                                        <select class="select h-10 mt-3 rounded w-full border px-3 text-gray-400  focus:border-blue-100 dark:bg-gray-800 dark:border-gray-600 " name="params[settings][country]" :value="setting.country">
                                                            <option value="Egypt">Egypt</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="w-full block" v-if="activeTab == 'calendar'">
                                <div class="card" >
                                    <div class="card-body pt-0">
                                        <div class="settings-form">
                                            <div class="form-group">

                                                <label class="block py-3">
                                                    <label>{{__('Enable Notifications')}} <span class="star-red">*</span></label>
                                                    <select class="select h-10 mt-3 rounded w-full border px-3 text-gray-400  focus:border-blue-100 dark:bg-gray-800 dark:border-gray-600 " name="params[settings][enable_notifications]" :value="setting.enable_notifications">
                                                        <option value="1" v-text="__('enabled')" ></option>
                                                        <option value="0" v-text="__('disabled')" ></option>
                                                    </select>
                                                </label>

                                                <label class="block py-3">
                                                    <label>{{__('calendar interval for notifications')}} {{__('in minutes')}} <span class="star-red">*</span></label>
                                                    <select class="select h-10 mt-3 rounded w-full border px-3 text-gray-400  focus:border-blue-100 dark:bg-gray-800 dark:border-gray-600 " name="params[settings][calendar_notification_interval]" :value="setting.calendar_notification_interval">
                                                        <option value="60000" >1</option>
                                                        <option value="300000" >5</option>
                                                        <option value="600000" >10</option>
                                                    </select>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="w-full block" v-if="activeTab == 'invoices'">
                                <div class="card w-full " >
                                    <div class="card-body pt-0">
                                        <div class="settings-form">
                                            
                                            <div class="form-group">
                                                <label>{{__('Invoice_notes')}}  </label>
                                                <textarea name="params[settings][invoice_notes]" rows="4" class="mt-2 rounded w-full border px-3 text-gray-400  focus:border-blue-100 dark:bg-gray-800 dark:border-gray-600" :placeholder="__('Invoice_notes')"  v-model="setting.invoice_notes"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>{{__('Invoice_terms___conditions')}}  </label>
                                                <textarea name="params[settings][invoice_terms]" rows="4" class="mt-2 rounded w-full border px-3 text-gray-400  focus:border-blue-100 dark:bg-gray-800 dark:border-gray-600" :placeholder="Invoice_terms___conditions" v-model="setting.invoice_terms" ></textarea>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>


                            <button class="uppercase h-12 mt-3 text-white w-40 mx-auto rounded bg-red-700 hover:bg-red-800">{{__('Save')}}</button>
                        </form>
                    </div>
                    <div class="col-md-3" >
                        <ul class="bg-white p-4 rounded-lg">
                            <li :class="tab.link == activeTab ? 'font-bold' : ''" class="cursor-pointer py-2 px-1 border-b border-gray-200 py-2" :key="index" v-for="(tab, index) in setting_tabs" @click="switchTab(tab)" v-text="tab.title"></li>
                        </ul>
                    </div>
                </div>
                <!-- END New releases -->
            </main>
        </div>
    </div>
</template>
<script>
export default 
{
    name:'Settings',
    data() {
        return {
            url: this.conf.url+this.path+'?load=json',
            content: {

                title: '',
                startdate: '',
                enddate: '',
                products: [],
                stockList: [],
            },
            setting_tabs: [
                {title:this.__('Basic_Details'), link:'basic'},
                {title:this.__('Calendar'), link:'calendar'},
                {title:this.__('Address_Details'), link:'address'},
                {title:this.__('invoice_info'), link:'invoices'},
            ],
            activeItem:null,
            activeTab:'basic',
            showAddSide:false,
            showEditSide:false,
            showLoader: false,
            showForm:true,
        }
    },
    props: [
        'path',
        'lang',
        'setting',
        'conf',
        'auth',
    ],
    mounted() 
    {
    },

    methods: 
    {
        switchTab(tab)
        {
            this.showForm = false;
            this.activeTab = tab.link;
            this.showForm = true;
        },
        
        __(i)
        {
            return this.$root.$children[0].__(i);
        }
    }
};
</script>
<style lang="css">
    .rtl #side-cart-container
    {
        right: auto;
        left:0;
    }
</style>
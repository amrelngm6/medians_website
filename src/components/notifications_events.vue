<template>
    <div class="w-full flex overflow-auto" style="height: 85vh; z-index: 9999;">
        <div class=" w-full">

            <main v-if="content && !showLoader" class=" flex-1 overflow-x-hidden overflow-y-auto  w-full">
                <!-- New releases -->
                <div class="px-4 mb-6 py-4 rounded-lg shadow-lg bg-white dark:bg-gray-700 flex w-full">
                    <h1 class="font-bold text-lg w-full" v-text="content.title"></h1>
                    <a href="javascript:;" class="uppercase p-2 mx-2 text-center text-white w-32 rounded bg-gradient-purple hover:bg-red-800" @click="showLoader = true, showAddSide = true,activeItem = {}, showLoader = false; ">{{__('add_new')}}</a>
                </div>
                <hr class="mt-2" />
                <div class="w-full flex gap gap-6">
                    <data-table ref="devices_orders" @actionTriggered="handleAction" v-bind="bindings"/>

                    <div class="col-md-3 sidebar-create-form" v-if="showAddSide">
                        <div class="mb-6 p-4 rounded-lg shadow-lg bg-white dark:bg-gray-700 ">
                            <form action="/api/create" method="POST" data-refresh="1" id="add-device-form" class="action  py-0 m-auto rounded-lg max-w-xl pb-10">
                                <div class="w-full flex">
                                    <h1 class="w-full m-auto max-w-xl text-base mb-10 ">{{__('ADD_new')}}</h1>
                                    <span class="cursor-pointer py-1 px-2" @click="showAddSide = false"><close_icon /></span>
                                </div>
                                <input name="type" type="hidden" value="NotificationEvent.create">

                                <input name="params[title]" required="" type="text" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('event title')">

                                <input name="params[subject]" type="text" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('subject')">

                                <textarea name="params[body]" type="text" rows="4" class="mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('body')"></textarea>

                                <label class="block mt-3">
                                    <span class="block mb-2" v-text="__('model')"></span>
                                    <select name="params[model]" class="form-checkbox p-2 px-3 w-full text-orange-600 border border-1 border-gray-400 rounded-lg">
                                        <option v-for="(model, index) in content.models" :value="model" v-text="index"></option>
                                    </select>
                                </label>

                                <label class="block mt-3">
                                    <span class="block mb-2" v-text="__('action')"></span>
                                    <select name="params[action]" class="form-checkbox p-2 px-3 w-full text-orange-600 border border-1 border-gray-400 rounded-lg">
                                        <option value="create" v-text="__('Create')"></option>
                                        <option value="update" v-text="__('Update')"></option>
                                        <option value="delete" v-text="__('Delete')"></option>
                                    </select>
                                </label>

                                <input name="params[action_field]" type="text" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('action_field')">

                                <input name="params[action_value]" type="text" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('action_value')">

                                <label class="block mt-3">
                                    <span class="block mb-2" v-text="__('receiver_model')"></span>
                                    <select name="params[receiver_model]" class="form-checkbox p-2 px-3 w-full text-orange-600 border border-1 border-gray-400 rounded-lg">
                                        <option value="Branch" v-text="__('Branch')"></option>
                                        <option value="User" v-text="__('User')"></option>
                                    </select>
                                </label>
                                
                                <label class="flex gap gap-2 items-center mt-3">
                                    <input name="params[status]" type="checkbox" class="form-checkbox h-5 w-5 text-orange-600" v-model="activeItem.status" :checked="activeItem.status > 0 ? true : false" >
                                    <span class="ml-2 mx-2 text-gray-700">{{__('Status')}}</span>
                                </label>
                                

                                <button class="uppercase h-12 mt-3 text-white w-full rounded bg-red-700 hover:bg-red-800" v-text="__('save')"></button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-3 mb-6 p-4 rounded-lg shadow-lg bg-white dark:bg-gray-700  sidebar-edit-form" v-if="showEditSide && !showAddSide ">
                        <div class="w-full">

                            <div class="w-full flex">
                                <h1 class="w-full m-auto max-w-xl text-base mb-10 " v-text="__('update')"></h1>
                                <span class="cursor-pointer py-1 px-2" @click="showEditSide = false"><close_icon /></span>
                            </div>
                            <div >
                                <form action="/api/update" method="POST" data-refresh="1" id="add-device-form" class="action py-0 m-auto rounded-lg max-w-xl pb-10">


                                    <input name="type" type="hidden" value="NotificationEvent.update">
                                    <input name="params[id]" type="hidden" v-model="activeItem.id">


                                    <input name="params[title]" required="" type="text" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('event title')" v-model="activeItem.title">

                                    <input name="params[subject]" type="text" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('subject')"  v-model="activeItem.subject">

                                    <textarea name="params[body]" type="text" rows="4" class="mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('body')"  v-model="activeItem.body"></textarea>

                                    <label class="block mt-3">
                                        <span class="block mb-2" v-text="__('model')"></span>
                                        <select name="params[model]" class="form-checkbox p-2 px-3 w-full text-orange-600 border border-1 border-gray-400 rounded-lg"  v-model="activeItem.model">
                                            <option v-for="(model, index) in content.models" :value="model" v-text="index"></option>
                                        </select>
                                    </label>

                                    <label class="block mt-3">
                                        <span class="block mb-2" v-text="__('action')"></span>
                                        <select name="params[action]" class="form-checkbox p-2 px-3 w-full text-orange-600 border border-1 border-gray-400 rounded-lg"  v-model="activeItem.action">
                                            <option value="create" v-text="__('Create')"></option>
                                            <option value="update" v-text="__('Update')"></option>
                                            <option value="delete" v-text="__('Delete')"></option>
                                        </select>
                                    </label>

                                    <input name="params[action_field]" type="text" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('action_field')"  v-model="activeItem.action_field">

                                    <input name="params[action_value]" type="text" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('action_value')"  v-model="activeItem.action_value">

                                    <label class="block mt-3">
                                        <span class="block mb-2" v-text="__('receiver_model')"></span>
                                        <select name="params[receiver_model]" class="form-checkbox p-2 px-3 w-full text-orange-600 border border-1 border-gray-400 rounded-lg"  v-model="activeItem.receiver_model">
                                            <option value="Branch" v-text="__('Branch')"></option>
                                            <option value="User" v-text="__('User')"></option>
                                        </select>
                                    </label>
                                    
                                    <label class="flex gap gap-2 items-center mt-3">
                                        <input name="params[status]" type="checkbox" class="form-checkbox h-5 w-5 text-orange-600" v-model="activeItem.status" :checked="activeItem.status == 1 ? true : false"  v-model="activeItem.status">
                                        <span class="ml-2 mx-2 text-gray-700">{{__('Status')}}</span>
                                    </label>
                                    

                                    <button class="uppercase h-10 mt-3 text-white w-full rounded bg-red-700 hover:bg-red-800">{{__('Update')}}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END New releases -->
            </main>
        </div>
    </div>
</template>
<script>

import dataTableActions from './includes/data-table-actions.vue';

export default 
{
    components:{
        dataTableActions,
    },
    name:'plans',
    data() {
        return {
            url: this.conf.url+this.path+'?load=json',
            content: {
                title: '',
                items: [],
                columns: [],
                models: [],
            },

            activeItem:{},
            showAddSide:false,
            showEditSide:false,
            showLoader: true,
        }
    },

    computed: {
        bindings() {

            this.content.columns.push({
                    key: this.__("actions"),
                    component: dataTableActions,
                    sortable: false,
                });

            return {

                columns: this.content.columns,
                data: this.content.items
            }
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
        this.load()
    },

    methods: 
    {


        handleAction(actionName, data) {
            switch(actionName) 
            {
                case 'view':
                    // window.open(this.conf.url+data.content.prefix)
                    break;  

                case 'edit':
                    this.showEditSide = true; 
                    this.showAddSide = false; 
                    this.activeItem = data
                    break;  

                case 'delete':
                    this.$parent.delete(data, 'NotificationEvent.delete');
                    break;  
            }
        },

        load()
        {
            this.showLoader = true;
            this.$parent.handleGetRequest( this.url ).then(response=> {
                this.setValues(response)
                this.showLoader = false;
                // this.$alert(response)
            });
        },
        
        setValues(data) {
            this.content = JSON.parse(JSON.stringify(data)); return this
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
<template>
    <div class="w-full flex overflow-auto" style="height: 85vh; z-index: 9999;">
        <div class=" w-full relative">
            <div v-if="showLoader" class="mx-auto mt-10 absolute top-0 left-0 right-0 bottom-0 m-auto w-40 h-40" >
                <img :src="conf.url+'uploads/images/loader.gif'"  />
            </div>
            <main v-if="content && !showLoader" class=" flex-1 overflow-x-hidden overflow-y-auto  w-full">
                <!-- Begin -->
                <div class="px-4 mb-6 py-4 rounded-lg shadow-lg bg-white dark:bg-gray-700 flex w-full">
                    <h1 class="font-bold text-lg w-full" v-text="content.title"></h1>
                    <a href="javascript:;" class="uppercase p-2 mx-2 text-center text-white w-32 rounded bg-gradient-purple hover:bg-red-800" @click="showLoader = true, showAddSide = true,showLoader = false; ">{{__('add_new')}}</a>
                </div>
                <hr class="mt-2" />
                <div class="w-full flex gap gap-6">
                    <data-table class='w-full' ref="blog"  @actionTriggered="handleAction" v-bind="bindings"/>

                    <div class="col-md-3 sidebar-create-form" v-if="showAddSide">
                        <div class="mb-6 p-4 rounded-lg shadow-lg bg-white dark:bg-gray-700 ">
                            <form action="/api/create" method="POST" data-refresh="1" id="add-device-form" class="action  py-0 m-auto rounded-lg max-w-xl pb-10">
                                <div class="w-full flex">
                                    <h1 class="w-full m-auto max-w-xl text-base mb-10 ">{{__('ADD_NEW')}}</h1>
                                    <span class="cursor-pointer py-1 px-2" @click="showAddSide = false, activeItem = {}"><close_icon /></span>
                                </div>
                                <input name="type" type="hidden" value="Blog.create">
                                <input name="params[status]" type="hidden" value="1">

                                <span class="block mb-2" v-text="__('category')"></span>
                                <select name="params[category_id]" class="form-checkbox p-2 px-3 w-full text-orange-600 border border-1 border-gray-400 rounded-lg" v-if="content.categories">
                                    <option  :key="index" v-for="(type, index) in content.categories" :value="type.id" v-text="type.name"></option>
                                </select>

                                <span class="block my-2" v-text="__('title')+' AR'"></span>
                                <input name="params[content][ar][title]" required="" type="text" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('Story')" >

                                <span class="block mb-2" v-text="__('title')+' EN'"></span>
                                <input name="params[content][en][title]" required="" type="text" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('Story')" >

                                <span class="block mb-2" v-text="__('author_name')"></span>
                                <input name="params[field][author_name]" required="" type="text" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('author_name')" >

                                <span class="block my-2" v-text="__('picture')"></span>
                                <vue-medialibrary-field name="params[picture]" :key="activeItem.id" :api_url="conf.url" v-model="activeItem.picture"></vue-medialibrary-field>

                                
                                <button class="uppercase h-12 mt-3 text-white w-full rounded bg-red-700 hover:bg-red-800" v-text="__('save')"></button>
                            </form>
                        </div>
                    </div>


                    <div class="col-md-3 mb-6 p-4 rounded-lg shadow-lg bg-white dark:bg-gray-700 sidebar-edit-form" v-if="showEditSide && !showAddSide ">

                        <div class="w-full flex">
                            <h1 class="w-full m-auto max-w-xl text-base mb-10 " v-text="__('edit')"></h1>
                            <span class="cursor-pointer py-1 px-2" @click="showEditSide = false"><close_icon /></span>
                        </div>
                        <div >
                            <form action="/api/update" method="POST" data-refresh="1" id="add-device-form" class="action py-0 m-auto rounded-lg max-w-xl pb-10">


                                <span class="block mb-2" v-text="__('category')"></span>
                                <select name="params[category_id]" class="form-checkbox p-2 px-3 w-full text-orange-600 border border-1 border-gray-400 rounded-lg" v-if="content.categories" v-model="activeItem.category_id">
                                    <option  :key="index" v-for="(type, index) in content.categories" :value="type.id" v-text="type.name"></option>
                                </select>

                                <span class="block mb-2" v-text="__('author_name')"></span>
                                <input v-if="activeItem.field" name="params[field][author_name]" required="" type="text" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" v-model="activeItem.field.author_name" >

                                <span class="block my-2" v-text="__('picture')"></span>
                                <vue-medialibrary-field name="params[picture]" :key="activeItem.id" :api_url="conf.url" v-model="activeItem.picture"></vue-medialibrary-field>

                                
                                <input name="type" type="hidden" value="Blog.update">
                                <input name="params[id]" type="hidden" v-model="activeItem.id">


                                <button class="uppercase h-10 mt-3 text-white w-full rounded bg-red-700 hover:bg-red-800">{{__('Update')}}</button>
                            </form>
                        
                            <a @click="$parent.delete(activeItem, 'Blog.delete')" href="javascript:;" class=" my-2 py-2 uppercase block text-center  pb-1 mt-1 text-white w-full rounded text-gray-700 hover:bg-red-800 hover:text-white">{{__('Delete')}}</a>

                        </div>
                    </div>

                </div>
                <!-- END  -->
            </main>
        </div>
    </div>
</template>
<script>


import dataTableActions from './includes/data-table-actions.vue';
import dataTablePicture from './includes/data-table-picture.vue';
import dataTableSideActions from './includes/data-table-side-actions.vue';

export default 
{
    components:{
        dataTablePicture,
        dataTableSideActions,
        dataTableActions
    },
    name:'blog',
    data() {
        return {
            url: this.conf.url+this.path+'?load=json',
            content: {
                title: '',
                items: [],
                categories: [],
                columns: [],
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
                    key: this.__("picture"),
                    component: dataTablePicture,
                    sortable: false,
                });

            this.content.columns.push({
                    key: this.__("options"),
                    component: dataTableSideActions,
                    sortable: false,
                });

            this.content.columns.push({
                    key: this.__("actions"),
                    component: dataTableActions,
                    sortable: false,
                });
            return {
                sortingMode: 'single',
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

        editFields(data)
        {
            this.activeItem = null;
            this.showEditSide = true; 
            this.activeItem = data; 
            this.showAddSide = false; 

        },

        handleAction(actionName, data) {
            switch(actionName) 
            {
                case 'view':
                    window.open(this.conf.url+data.content.prefix)
                    break;  

                case 'edit':
                    window.open(this.conf.url+'builder?prefix='+data.content.prefix)
                    break;  

                case 'delete':
                    this.$parent.delete(data, 'Blog.delete');
                    break;  
            }
            console.log(actionName, data);
        },

        load()
        {
            this.showLoader = true;
            this.$parent.handleGetRequest( this.url ).then(response=> {
                this.setValues(response)
                this.showLoader = false;
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
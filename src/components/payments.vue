<template>
    <div class="w-full flex overflow-auto" style="height: 85vh; z-index: 9999;">
        <div class=" w-full">

            <main v-if="content && !showLoader" class=" flex-1 overflow-x-hidden overflow-y-auto  w-full">
                <!-- New releases -->
                <div class="px-4 mb-6 py-4 rounded-lg shadow-lg bg-white dark:bg-gray-700 flex w-full">
                    <h1 class="font-bold text-lg w-full" v-text="content.title"></h1>
                    <a href="javascript:;" class="uppercase p-2 mx-2 text-center text-white w-32 rounded bg-gradient-purple hover:bg-red-800" @click="showLoader = true, showAddSide = true,showLoader = false; ">{{__('add_new')}}</a>
                </div>
                <hr class="mt-2" />
                <div class="w-full flex gap gap-6">
                    <div class="w-full">
                        <div v-if="content.items" class="px-4 mb-6 py-4 rounded-lg shadow-lg bg-white dark:bg-gray-700 ">
                            <table class="table table-striped table-nowrap custom-table mb-0 datatable w-full">
                                <thead>
                                    <tr>
                                        <th class="w-10">#</th>
                                        <th class="text-default">{{__('Name')}}</th>
                                        <th>{{__('Amount')}}</th>
                                        <th>{{__('Invoice_Number')}}</th>
                                        <th>{{__('By')}}</th>
                                        <th>{{__('date')}}</th>
                                        <th>{{__('actions')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in content.items" class="text-center">
                                        <td>{{item.id}}</td>
                                        <td class="text-default">{{item.name}}</td>
                                        <td>{{item.amount}}</td>
                                        <td>{{item.invoice_id}}</td>
                                        <td>{{item.user.name}}</td>
                                        <td>{{timeFormat(item.created_at)}}</td>
                                        <td>
                                            <a @click="showEditSide = true, showAddSide = false, activeItem = item"  class="text-gray-400 hover:text-gray-100  mx-2">
                                                <i class="material-icons-outlined text-base">edit</i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-3 sidebar-create-form" v-if="showAddSide">
                        <div class="mb-6 p-4 rounded-lg shadow-lg bg-white dark:bg-gray-700 ">
                            <form action="/api/create" method="POST" data-refresh="1" id="add-device-form" class="action  py-0 m-auto rounded-lg max-w-xl pb-10">
                                <div class="w-full flex">
                                    <h1 class="w-full m-auto max-w-xl text-base mb-10 ">{{__('ADD_new')}}</h1>
                                    <span class="cursor-pointer py-1 px-2" @click="showAddSide = false"><close_icon /></span>
                                </div>
                                <input name="type"  type="hidden" value="Payment.create" > 

                                <div class="w-full flex gap gap-2">
                                    <label class="w-40 mt-6 ">{{__('name')}}</label>
                                    <input name="params[payment][name]" required="" type="text" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" > 
                                </div>

                                <div class="w-full flex gap gap-2">
                                    <label class="w-40 mt-6  ">{{__('Amount')}}</label>
                                    <input required name="params[payment][amount]" type="number" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600"> 
                                </div>

                                <div class="w-full flex gap gap-2">
                                    <label class="w-40 mt-6  ">{{__('date')}}</label>
                                    <input disabled :value="today" name="params[payment][date]" type="date" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600"> 
                                </div>

                                <div class="w-full flex gap gap-2">
                                    <label class="w-40 mt-6 ">{{__('invoice_id')}}</label>
                                    <input name="params[payment][invoice_id]" type="text" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600"> 
                                </div>

                                <div class="w-full flex gap gap-2">
                                    <label class="w-40 mt-6 ">{{__('notes')}}</label>
                                    <textarea name="params[payment][notes]" class="h-40 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('Notes')" ></textarea> 
                                </div>


                                <button class="uppercase h-12 mt-3 text-white w-full rounded bg-red-700 hover:bg-red-800" v-text="__('save')"></button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-3" v-if="showEditSide">
                        <div class="mb-6 p-4 rounded-lg shadow-lg bg-white dark:bg-gray-700 ">
                            <form action="/api/update" method="POST" data-refresh="1" id="add-device-form" class="action  py-0 m-auto rounded-lg max-w-xl pb-10">
                                <div class="w-full flex">
                                    <h1 class="w-full m-auto max-w-xl text-base mb-10 ">{{__('Update')}}</h1>
                                    <span class="cursor-pointer py-1 px-2" @click="showEditSide = false"><close_icon /></span>
                                </div>
                                <input name="type"  type="hidden" value="Payment.create" > 

                                <div class="w-full flex gap gap-2">
                                    <label class="w-40 mt-6 ">{{__('name')}}</label>
                                    <input name="params[payment][name]" required="" type="text" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :value="activeItem.name"> 
                                </div>

                                <div class="w-full flex gap gap-2">
                                    <label class="w-40 mt-6  ">{{__('Amount')}}</label>
                                    <input disabled name="params[payment][amount]" type="number" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :value="activeItem.amount"> 
                                </div>

                                <div class="w-full flex gap gap-2">
                                    <label class="w-40 mt-6  ">{{__('date')}}</label>
                                    <input disabled name="params[payment][date]" type="date" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :value="activeItem.date"> 
                                </div>

                                <div class="w-full flex gap gap-2">
                                    <label class="w-40 mt-6 ">{{__('invoice_id')}}</label>
                                    <input name="params[payment][invoice_id]" type="text" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :value="activeItem.name"> 
                                </div>

                                <div class="w-full flex gap gap-2">
                                    <label class="w-40 mt-6 ">{{__('notes')}}</label>
                                    <textarea name="params[payment][notes]" class="h-40 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('Notes')" >{{activeItem.notes}}</textarea> 
                                </div>


                                <button class="uppercase h-12 mt-3 text-white w-full rounded bg-red-700 hover:bg-red-800" v-text="__('Update')"></button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- END New releases -->
            </main>
        </div>
    </div>
</template>
<script>
import moment from 'moment';

export default 
{
    components: {
        moment
    },
    name:'products',
    data() {
        return {
            url: this.conf.url+'payments?load=json',
            content: {

                title: '',
                items: [],
                typesList: [],
            },

            activeItem:null,
            today:moment().format('YYYY-MM-DD'),
            showAddSide:false,
            showEditSide:false,
            showLoader: false,
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
        timeFormat (time)
        {
            return moment(time).format('YYYY-MM-DD hh:mm a')
        },
        __(i)
        {
            return this.$root.$children[0].__(i);
        }
    }
};
</script>
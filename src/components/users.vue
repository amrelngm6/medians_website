<template>
    <div class="w-full flex overflow-auto" style="height: 85vh; z-index: 9999;">
        <div class=" w-full">
            <main v-if="content && !showLoader" class=" flex-1 overflow-x-hidden overflow-y-auto  w-full">
                <!-- New releases -->
                <div class="px-4 mb-6 py-4 rounded-lg shadow-lg bg-white dark:bg-gray-700 flex w-full">
                    <h1 class="font-bold text-lg w-full" v-text="content.title"></h1>
                    <a href="javascript:;" class="uppercase p-2 mx-2 text-center text-white w-32 rounded bg-gradient-purple hover:bg-red-800" @click="showLoader = true, showAddSide = true,showLoader = false, activeItem = {}; ">{{__('add_new')}}</a>
                </div>
                <hr class="mt-2" />
                <div class="w-full flex gap gap-6">

                    <div v-if="content.users" class="w-full grid lg:grid-cols-3 gap gap-6">
                        <div :key="user" v-for="user in content.users" class="mb-2 rounded-lg flex items-center space-x-4 gap gap-4  bg-white p-4 ">
                            <div class="flex-shrink-0 w-20">
                                <div class="relative">
                                    <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
                                    <img class="relative w-12 h-12 rounded-full" :src="user.photo" alt="User avatar">
                                </div>
                            </div>
                            <div class="flex-grow w-full">
                                <div class="text-lg font-medium text-gray-900">{{user.first_name}} {{user.last_name}}</div>
                                <div class="text-sm font-medium text-gray-500" v-text="user.phone"></div>
                                <div class="text-sm font-medium text-gray-500" v-text="user.email"></div>
                            </div>
                            <div class="flex-shrink-0 text-center">
                                <span  v-text="user.active ? __('Active') : __('Pending')" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"></span>
                                <span  v-text="__('edit')" class="my-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 cursor-pointer" v-if="user.id == auth.id || auth.role_id == 1" @click="showEditSide = true; showAddSide = false; activeItem = user">
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 sidebar-create-form" v-if="showAddSide">
                        <div class="mb-6 p-4 rounded-lg shadow-lg bg-white dark:bg-gray-700 ">
                            <form action="/api/create" method="POST" data-refresh="1" id="add-device-form" class="action  py-0 m-auto rounded-lg max-w-xl pb-10">
                                <div class="w-full flex">
                                    <h1 class="w-full m-auto max-w-xl text-base mb-10 ">{{__('ADD_new')}}</h1>
                                    <span class="cursor-pointer py-1 px-2" @click="showAddSide = false,showEditSide = false">
                                        <close_icon /></span>
                                </div>
                                <input name="type" type="hidden" value="User.create">
                                <input name="params[active]" type="hidden" value="1">
                                <input name="params[first_name]" required="true" type="text" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('first_name')" >
                                <input name="params[last_name]" type="text" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('last_name')" >
                                <input name="params[email]" required="true" type="email" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('email')" >

                                <input name="params[phone]" type="number" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('mobile')">

                                <input name="params[password]" required="true" type="password" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('password')">

                                <label class="block mt-3" v-if="isMaster">
                                    <span class="block mb-2" v-text="__('branch')"></span>
                                    <select name="params[active_branch]" class="form-checkbox p-2 px-3 w-full text-orange-600 border border-1 border-gray-400 rounded-lg">
                                        <option v-for="branch in content.branches" :value="branch.id" v-text="branch.name"></option>
                                    </select>
                                </label>

                                <span class="block my-2" v-text="__('picture')"></span>
                                <vue-medialibrary-field name="params[profile_image]" :api_url="conf.url" v-model="profile_image"></vue-medialibrary-field>

                                <button class="uppercase h-12 mt-3 text-white w-full rounded bg-red-700 hover:bg-red-800" v-text="__('save')"></button>
                            </form>
                        </div>
                    </div>
                    <div class="col-md-3 mb-6 p-4 rounded-lg shadow-lg bg-white dark:bg-gray-700  sidebar-edit-form" v-if="showEditSide && !showAddSide ">
                        <div class="w-full">
                            <div class="w-full flex">
                                <h1 class="w-full m-auto max-w-xl text-base mb-10 " v-text="__('update')"></h1>
                                <span class="cursor-pointer py-1 px-2" @click="showEditSide = false">
                                    <close_icon /></span>
                            </div>
                            <div>
                                <form action="/api/update" method="POST" data-refresh="1" id="add-device-form" class="action py-0 m-auto rounded-lg max-w-xl pb-10">
                                    <input name="type" type="hidden" value="User.update">
                                    <input name="params[id]" type="hidden" v-model="activeItem.id">
                                    <span class="block mb-2" v-text="__('first_name')"></span>
                                    <input name="params[first_name]" required="true" type="text" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('first_name')" v-model="activeItem.first_name">
                                    <span class="block mb-2 mt-3" v-text="__('last_name')"></span>
                                    <input name="params[last_name]" required="true" type="text" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('last_name')" v-model="activeItem.last_name">
                                    <span class="block mb-2 mt-3" v-text="__('email')"></span>
                                    <input name="params[email]" required="true" type="email" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('email')" v-model="activeItem.email">
                                    <span class="block mb-2 mt-3" v-text="__('mobile')"></span>
                                    <input name="params[phone]" required="true" type="number" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('mobile')" v-model="activeItem.phone">

                                    <span class="block mb-2 mt-3" v-text="__('change password')"></span>
                                    <input name="params[password]" type="password" class="h-12 mt-3 rounded w-full border px-3 text-gray-700  focus:border-blue-100 dark:bg-gray-800  dark:border-gray-600" :placeholder="__('password')">

                                    <label class="block mt-3" v-if="isMaster">
                                        <span class="block mb-2" v-text="__('branch')"></span>
                                        <select v-model="activeItem.active_branch" name="params[active_branch]" class="form-checkbox p-2 px-3 w-full text-orange-600 border border-1 border-gray-400 rounded-lg">
                                            <option v-for="branch in content.branches" :value="branch.id" v-text="branch.name"></option>
                                        </select>
                                    </label>

                                    <span class="block my-2" v-text="__('picture')"></span>
                                    <vue-medialibrary-field name="params[profile_image]" :key="activeItem.id" :api_url="conf.url" v-model="activeItem.photo"></vue-medialibrary-field>

                                    <label class="inline-flex items-center mt-3">
                                        <input name="params[active]" type="checkbox" v-model="activeItem.active" class="form-checkbox h-5 w-5 text-orange-600">
                                        <span class="ml-2 text-gray-700  mx-2">{{__('PUBLISH')}}</span>
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
export default {
    name: 'Users',
    data() {
        return {
            url: this.conf.url +this.path+ '?load=json',
            content: {

                title: this.__('users'),
                branches: [],
                users: [],
            },

            activeItem: null,
            showAddSide: false,
            showEditSide: false,
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
    mounted() {
        this.load()
    },

    methods: {

        /**
         * Check if the user is Master
         */
        isMaster()
        {
            return (this.auth && this.auth.role_id == 1);
        } , 

        load() {
            this.showLoader = true;
            this.$parent.handleGetRequest(this.url).then(response => {
                this.setValues(response)
                this.showLoader = false;
                // this.$alert(response)
            });
        },

        setValues(data) {
            this.content = JSON.parse(JSON.stringify(data));
            return this
        },
        __(i) {
            return this.$root.$children[0].__(i);
        }
    }
};
</script>
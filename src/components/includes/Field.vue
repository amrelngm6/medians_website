<template>
    <div>
        <div class="media-library-field">

            <input v-if="file" :name="name" type="hidden" :value="file">

            <div class="media-library-field__selector" v-if="content == null">
                <span 
                     @click="showLibrary"
                    class="media-library-field__selector__button"
                >Attach {{ types.images && types.files ? 'file' : (types.images && !types.files) ? 'image' : 'file' }}</span>

            </div>
            <div v-if="file && content" class="media-library-field__selected">
                <div class="media-library-field__selected__inner">
                    <div class="w-full">
                        <div>
                            <img :src="file" style="width: auto; height: auto; max-width: 180px;">
                        </div>
                        <div class="block w-full">
                            <div class="w-full flex" style="  margin: 2rem -0.5rem 0 -0.5rem;">
                                <div style="flex-grow: 1; padding: 0 0.5rem;">
                                    <span class="media-library-field__selected__inner__details__button font-semibold" @click="showLibrary">Edit</span>
                                </div>
                                <!-- <div style="flex-grow: 1; padding: 0 0.5rem;">
                                    <a :href="file.download_url" class="media-library-field__selected__inner__details__button">Download</a>
                                </div> -->
                                <div style="flex-grow: 1; padding: 0 0.5rem;">
                                    <button @click="clear" class="media-library-field__selected__inner__details__button media-library-field__selected__inner__details__button--delete">Remove</button>
                                </div>
                            </div>
                        </div>


                        <!-- <p v-if="helper" v-html="helper" class="media-library-field__selected__inner__details__helper" /> -->
                    </div>
                </div>
            </div>
        </div>


        <vue-medialibrary-manager
            :key="showManager"
            :api_url="api_url"
            :filetypes="filetypes"
            v-if="showManager"
            :types="types"
            :selected="value"
            :selectable="true"
            @close="showManager = false, file = content = value"
            @select="insert"
            @fail-to-find="clear"
        ></vue-medialibrary-manager>
    </div>
</template>

<script>
    import Loader from './Loader';
    import Manager from './Manager';
    import axios from 'axios';

    export default {
        name: 'vue-medialibrary-field',

        components: {
            'vue-medialibrary-manager': Manager,
            'app-medialibrary-loader': Loader
        },

        props: {
            name: {
                type: String,
                required: false
            },
            api_url: {
                type: String,
                required: false
            },
            value: {
                type: Object|String,
                required: false,
                default: () => ({
                })
            },
            types: {
                type: Object,
                required: false,
                default: () => ({
                    images: true,
                    files: true
                })
            },
            filetypes: {
                type: Array,
                required: false,
                default: () => ([])
            },
            helper: {
                type: String,
                required: false
            }
        },

        data: () => ({
            loading: true,
            showManager: false,
            file: {},
            content: null,
        }),

        mounted() {
            this.content = this.value ? this.value : this.content;
            if (this.content ) {
                this.file = this.content;
            } else {
                this.content = null;
            }
            this.loading = false;
        },

        methods: {
            showLibrary()
            {
                this.showManager = !this.showManager;
            },
            insert(value) {

                this.loading = false;
                this.showManager = false;
                
                this.file = value.file_name;
                this.content = value.file_name;

                this.change();
            },
            clear() {
                this.loading = false;
                this.content = this.file = null;

                this.$emit('input', null);
            },
            change() {
                this.$emit('input', this.file);
            }
        },

        watch: {
            value() {
                if (typeof this.file == 'undefined') {
                    this.loading = true;
                }
            },
            showManager()
            {
                console.log('changed')
            }
        },
    }
</script>



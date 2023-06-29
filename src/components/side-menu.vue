<template>
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu rounded-lg">
            <ul class="rounded-lg">
                <li class="nav-item nav-profile bg-gray-800 rounded-lg">
                    <span class="nav-link block py-1  bg-gray-800">

                        <div class="nav-profile-text d-flex flex-column">
                            <span class="font-weight-bold  py-2 px-3 block" v-text="$parent.__('Dashboard menu')"></span>
                        </div>
                        <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
                    </span>
                </li>
            </ul>
                    
            <ul   v-if="showMenu" class="overflow-y-auto" style="max-height: calc(100vh - 170px);">
                <li v-for="(menu, i) in pages">
                    <a v-on:click.prevent="openPage(menu)" class="w-full font-thin uppercase flex items-center p-4 my-2 transition-colors duration-200 justify-start text-gray-500 " :class="menu.class" :href="url+menu.link">
                        <span class="text-left" >
                            <i class="fa" :class="menu.icon"></i>
                        </span>
                        <span class="w-full text-base " v-text="menu.title"></span>
                        <i v-if="menu.sub && !menu.show_sub" class="text-sm fa fa-caret-down"></i>
                    </a>
                    <ul v-if="menu.sub && menu.show_sub " class="pb-4" >
                        <li v-for="submenu in menu.sub" >
                            <a v-on:click.prevent="openPage(submenu)" class=" text-purple-800 hover:text-white"  :class="submenu.class" :href="url+submenu.link">
                                <span class="mx-2 text-base " v-text="submenu.title"> </span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</template>
<script>


export default {
    computed: {},
    data() {
        return {
            showMenu: true,
            same_page: false,
            pages: []
        }
    },
    props: ['url','menus', 'samepage', 'auth'],
    created: function() {
        this.pages = this.menus;
        this.activePage = this.samepage;
        this.resetClasses() 

    },
    methods: {

        openPage(page)
        {

            this.showMenu = false
            this.activePage = page.link;
            page.sub ? null : this.$parent.switchTab(page);
            this.resetClasses() 
        },
        resetClasses()
        {
            for (var a = this.pages.length - 1; a >= 0; a--) {
                this.pages[a].class = this.pages[a].link == this.activePage ? 'bg-gradient-to-r' : null;
                this.pages[a].show_sub = this.pages[a].link == this.activePage ? true : false;
                if (this.pages[a].sub)
                {

                    for (var i = this.pages[a].sub.length - 1; i >= 0; i--) {
                        this.pages[a].sub[i].class = this.pages[a].sub[i].link == this.activePage ? 'active' : null;
                        this.pages[a].class = this.pages[a].sub[i].link == this.activePage ? 'bg-gradient-to-r' : this.pages[a].class;
                        this.pages[a].show_sub = this.pages[a].sub[i].link == this.activePage ? true : this.pages[a].show_sub;

                    }
                }
            }
            this.showMenu = true
            return this;
        },
    }
};
</script>

<style lang="css">
    .sidebar-menu
    {
        min-height: calc(100vh - 100px);
    }
</style>
<template>
        
    <div class="flex w-full">
        <img class="rounded-full h-12 w-12  object-cover" src="/uploads/img/cup.jpg" alt="unsplash image">
        <div class="ml-3 mt-2 text-default ">
            <div class="font-semibold" v-text="data.name"></div>
            <div class="text-gray-500 text-sm" v-text="data.description"></div>
        </div>
    </div>
</template>
<script>
export default {
    name: "ActionButtons",
    methods: {
        handleAction(actionName) {
            this.$root.$children[0].$refs.activeTab.handleAction(actionName, this.data);
        }

    },
    props: {
        data: {
            type: Object,
            required: true,
        },
    },
};
</script>
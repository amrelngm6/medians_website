<template>
    <div class="text-base">
        <img :src="data.picture" width="80" height="80"  />
    </div>
</template>
<script>
export default {
    name: "Picture",
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
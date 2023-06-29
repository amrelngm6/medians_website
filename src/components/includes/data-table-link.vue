<template>
        
    <div class="w-full">
          <a v-if="data.invoice" target="_blank" :href="'/admin/invoices/show/'+data.invoice.code" v-text="data.invoice.code"></a>
          <a v-if="data.code" target="_blank" :href="'/admin/invoices/show/'+data.code" v-text="data.code"></a>
    </div>
</template>
<script>
export default {
    name: "ActionLink",
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
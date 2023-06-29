<template>
    <div class="action-buttons flex gap-6 gap text-base">
        <button v-if="!data.not_editable" class=" hover:text-gray-600 text-purple" @click="handleAction('edit')">
            <i class="fa fa-edit"></i>
        </button>
        <button v-if="!data.not_removeable" class="hover:text-red-400 text-red-600" @click="handleAction('delete')">
            <i class="fa fa-trash"></i>
        </button>
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
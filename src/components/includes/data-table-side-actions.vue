<template>
    <div class="action-buttons flex gap-6 gap text-base">

        <a @click="handleAction()" class="text-gray-400 hover:text-gray-100  mx-2" href="javascript:;">
            <i class="material-icons-outlined text-base">edit</i>
        </a>

    </div>
</template>
<script>
export default {
    name: "ActionButtons",
    methods: {
        handleAction() {
            this.$root.$children[0].$refs.activeTab.editFields(this.data);
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
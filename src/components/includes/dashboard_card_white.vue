<template>
    <div class="widget-item bg-white p-6 flex justify-between rounded-md">
        <div>
            <h4 class="text-xl font-semibold text-slate-700 mb-1 leading-none" v-text="value"></h4>
            <p class="text-tiny leading-4" v-text="title"></p>
        </div>
        <div>
            <span :class="classes"  class="text-lg text-purple-300 rounded-full flex items-center justify-center h-12 w-12 shrink-0 ">
                <img width="25" height="25" class="mx-auto" :src="icon" />
            </span>
        </div>
    </div>
</template>
<script>

export default 
{
    props: [
        'title',
        'value',
        'classes',
        'icon',
    ],

};
</script>

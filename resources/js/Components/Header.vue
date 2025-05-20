<script setup>

const props = defineProps({
    pageTitle : String,
    loggedInUsername : String,
})
</script>

<template>
    <div  class="bg-amber-500 text-white min-h-[4rem] flex justify-between items-center rounded-lg w-full py-2 px-4 shadow-lg z-10">
        <h1 class="text-xl font-semibold uppercase">{{ props.pageTitle }}</h1>
        <span class="text-xl font-semibold uppercase">{{ props.loggedInUsername }}</span>
    </div>

</template>

<style scoped>
</style>

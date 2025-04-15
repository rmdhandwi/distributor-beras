<script setup>
import { Head } from '@inertiajs/vue3';
// import layout
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
</script>

<template>
    <Head title="Dashboard"/>
    <AuthenticatedLayout>
        <template #pageContent>
            <h1>Hello World!</h1>
        </template>
    </AuthenticatedLayout>
</template>

<style scoped>
</style>

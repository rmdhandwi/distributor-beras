<script setup>
import { Head } from '@inertiajs/vue3';
// import layout
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const pageTitle = "Dashboard"
</script>

<template>
    <Head :title="pageTitle"/>
    <AuthenticatedLayout :pageTitle="pageTitle">
        <template #pageContent>
            <h1>Hello World!</h1>
        </template>
    </AuthenticatedLayout>
</template>

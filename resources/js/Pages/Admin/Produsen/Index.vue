<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'


const pageTitle = ref('Daftar Produsen')
</script>

<template>
    <Head :title="pageTitle" />
    <AuthenticatedLayout :page-title="pageTitle">
        <template #pageContent>
            <div class="p-2">
                <h1>Halaman Produsen</h1>
            </div>
        </template>
    </AuthenticatedLayout>
</template>

<style scoped>
</style>

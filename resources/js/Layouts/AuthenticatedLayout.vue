<script setup>
import { onMounted, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

import Header from '@/Components/Header.vue'
import Sidebar from '@/Components/Sidebar.vue'

onMounted(() =>
{
    loggedInUsername.value = usePage().props.auth?.user.username
})

const props = defineProps({
    pageTitle : String,
})

const loggedInUsername = ref(null)

</script>

<template>
     <!-- toast notifikasi -->
    <Toast class="z-50" position="top-center"/>
    <!-- dialog logout -->
    <ConfirmDialog class="w-[24rem]"></ConfirmDialog>
    <!-- layout utama -->
    <div class="bg-slate-200 flex p-1 min-h-screen overflow-hidden">
        <!-- Sidebar -->
        <Sidebar/>
        <!-- Sidebar Selesai  -->
        <!-- Konten Halaman -->
        <div class="transition-all duration-[450ms] ml-[200px] w-full h-full px-1 overflow-hidden flex flex-col gap-2">
            <!-- header -->
            <Header :page-title="props.pageTitle" :logged-in-username="loggedInUsername"/>
            <!-- header selesai -->
            <!-- body -->
            <div class="bg-slate-50 rounded-lg w-full p-4 min-h-screen">
                <slot name="pageContent"/>
            </div>
            <!-- body selesai -->
        </div>
        <!-- Konten Halaman Selesai -->
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue';
import { useConfirm, useToast } from 'primevue';

import { adminMenu } from '@/Composables/SidebarLists'

const adminMenus = ref(adminMenu)

const confirm = useConfirm()

const toast = useToast()

const confirmLogout = () =>
{
    confirm.require({
        message: 'Yakin ingin logout dari aplikasi?',
        header: 'Peringatan',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Batal',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Logout',
            severity: 'danger'
        },
        accept : () => {
            toast.add({ severity: 'info', summary: 'Notifikasi', detail: 'Proses Logout', life: 3000 });
            setTimeout(() =>
                router.get(route('logout'))
            ,3000)
        },
    });
}

</script>

<template>
   <div class="w-[200px] transition-all ease-in-out duration-[450ms] rounded-lg h-[99vh] p-2 flex flex-col bg-gradient-to-b from-amber-500 to-amber-400 gap-[2rem] justify-between fixed">

        <div class="flex flex-col gap-4 text-lg items-center" v-if="$page.props.auth.user.role === 'Admin'">
            <Button v-for="menu in adminMenus" :key="menu.route" :label="menu.label" :icon="menu.icon" class="cursor-pointer w-full p-1 flex items-center justify-center gap-2 rounded" :class="{'bg-slate-50 text-amber-500' : route().current(menu.current), 'text-slate-50 bg-transparent' : !route().current(menu.route)}" @click="router.visit(route(menu.route))" unstyled/>

        </div>
        <Button @click="confirmLogout()" class="w-full self-end" severity="danger" label="Logout" icon="pi pi-power-off"/>
    </div>
</template>

<style scoped>
</style>

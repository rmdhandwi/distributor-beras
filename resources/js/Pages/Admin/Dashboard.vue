<script setup>
import { onMounted } from 'vue'
import { Head } from '@inertiajs/vue3'

import { useToast } from 'primevue'

// import layout
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

onMounted(() =>
{
    checkNotif()
})

const props = defineProps({
    flash : Object,
})

const toast = useToast()

const pageTitle = "Dashboard"

const checkNotif = async () =>
{
    if(props.flash.notif_status)
    {
        await new Promise(resolve =>
        {
            toast.add({
                severity : props.flash.notif_status,
                summary : 'Notifikasi',
                detail : props.flash.notif_message,
                life : 4000,
            })

            setTimeout(() => { resolve()}, 4000)
        })
    }
}
</script>

<template>
    <Head :title="pageTitle"/>
    <AuthenticatedLayout :pageTitle="pageTitle">
        <template #pageContent>
            <h1>Hello World!</h1>
        </template>
    </AuthenticatedLayout>
</template>

<style scoped>
</style>

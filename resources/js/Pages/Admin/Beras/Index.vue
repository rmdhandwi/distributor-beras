<script setup>
import { computed, defineAsyncComponent, onMounted, ref} from 'vue'
import { Head } from '@inertiajs/vue3'

import { useConfirm, useToast } from 'primevue'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'


onMounted(() =>
{
    checkNotif()
})

const props = defineProps({
    flash : Object,
    dataProdusen : Object
})

const confirm = useConfirm()
const toast = useToast()

const pageTitle = 'Daftar Beras'
const currentTab = ref('DaftarBeras')


const checkNotif = async () =>
{
    if(props.flash.notif_status)
    {
        await new Promise(resolve =>
        {
            toast.add({
                severity : props.flash.notif_status,
                summary : 'notifikasi',
                detail : props.flash.notif_message,
                life : 2000,
            })

            setTimeout(() => { resolve()}, 2000)
        })
    }
}

// async component
const loadComponent = componentName =>
{
    if(componentName === 'TambahBeras' || componentName === 'EditBeras')
    {
        return  defineAsyncComponent({
            loader : () => import(`./FormBeras.vue`),
            loadingComponent : LoadingSpinner,
            delay : 200,
            timeout: 3000
        })
    }
    else {
        return  defineAsyncComponent({
            loader : () => import(`./${componentName}.vue`),
            loadingComponent : LoadingSpinner,
            delay : 200,
            timeout: 3000
        })
    }
}

const currentComponent = computed(() => {
  return loadComponent(currentTab.value)
})

const componentProps = computed(() => {
    switch (currentTab.value) {

        case 'DaftarBeras':
        return {
            dataBeras: props.dataBeras?.map((p, i) => ({ nomor: i + 1, ...p })),
        };

        case 'TambahBeras':
        return {
            formType: 'Create',
            dataProdusen : props.dataProdusen
        };

        case 'EditBeras':
        return {
            formType: 'Edit',
            dataBeras: { ...dataEditBeras.value },
        };

        default:
        return {};
    }
})

</script>

<template>
    <Head :title="pageTitle" />
    <AuthenticatedLayout :page-title="pageTitle">
        <template #pageContent>

        </template>
    </AuthenticatedLayout>
</template>

<style scoped>
</style>

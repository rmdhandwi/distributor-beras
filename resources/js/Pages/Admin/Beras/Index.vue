<script setup>
import { computed, defineAsyncComponent, onMounted, ref} from 'vue'
import { Head } from '@inertiajs/vue3'

import { useConfirm, useToast } from 'primevue'

import LoadingSpinner from '@/Components/LoadingSpinner.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'


onMounted(() =>
{
    checkNotif()
})

const props = defineProps({
    flash : Object,
    dataBeras : Object,
    dataProdusen : Object,
})

const confirm = useConfirm()
const toast = useToast()

const pageTitle = ref('Daftar Beras')
const currentTab = ref('DaftarBeras')


const refreshPage = async () =>
{
    switchComponents('DaftarProdusen','Daftar Produsen')
    await checkNotif()
}

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

const switchComponents = (component,title) =>
{
    currentTab.value = component
    pageTitle.value = title
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
            <div class="flex gap-x-4" v-if="currentTab==='EditBeras'">
                <Button @click="batalkanEditBeras()" label="Batal" severity="danger" icon="pi pi-times"/>
                <Button label="Edit Beras" icon="pi pi-pen-to-square"/>
            </div>
            <div class="flex gap-x-4" v-else>
                <Button @click="switchComponents('DaftarBeras','Daftar Beras')" label="Daftar Beras" :severity="currentTab==='DaftarBeras'?'primary':'secondary'" icon="pi pi-list"/>
                <Button @click="switchComponents('TambahBeras','Tambah Beras')" label="Tambah Beras" :severity="currentTab==='DaftarBeras'?'secondary':'primary'" icon="pi pi-plus"/>
            </div>
            <div class="flex flex-col mt-4">
                <component :is="currentComponent" v-bind="componentProps" @refreshPage="refreshPage()"/>
            </div>
        </template>
    </AuthenticatedLayout>
</template>

<style scoped>
</style>

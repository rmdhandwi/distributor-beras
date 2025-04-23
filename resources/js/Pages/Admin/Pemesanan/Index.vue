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
    dataPemesanan : Object,
    dataProdusen : Object,
})

const confirm = useConfirm()
const toast = useToast()

const pageTitle = ref('Daftar Pemesanan')
const currentTab = ref('DaftarPemesanan')

const dataEditPemesanan = ref(null)

const refreshPage = () =>
{
    checkNotif()
    switchComponents('DaftarPemesanan','Daftar Pemesanan')
}

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

const editData = dataEmit =>
{
    dataEditPemesanan.value = dataEmit
    switchComponents('EditPemesanan','Edit Pemesanan')
}

// async component
const loadComponent = componentName =>
{
    if(componentName === 'TambahPemesanan' || componentName === 'EditPemesanan')
    {
        return  defineAsyncComponent({
            loader : () => import(`./FormPemesanan.vue`),
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

        case 'DaftarPemesanan':
        return {
            dataPemesanan: props.dataPemesanan?.map((p, i) => ({ nomor: i + 1, ...p })),
        };

        case 'TambahPemesanan':
        return {
            formType: 'Create',
            dataBeras : props.dataBeras,
            dataProdusen : props.dataProdusen,
        };

        case 'EditPemesanan':
        return {
            formType: 'Edit',
            dataPemesanan: { ...dataEditPemesanan.value },
            dataProdusen : props.dataProdusen,
        };

        default:
        return {};
    }
})

const batalkanEditPemesanan = () =>
{
    confirm.require({
        message: 'Batal Edit Pemesanan?',
        header: 'Peringatan',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Lanjut',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Batalkan',
            severity: 'danger'
        },
        accept : () => {
            toast.add({ severity: 'info', summary: 'Notifikasi', detail: 'Membatalkan...', life: 2000 });
            dataEditPemesanan.value = null
            setTimeout(() =>
                switchComponents('DaftarPemesanan','Daftar Pemesanan')
            ,2000)
        },
    });
}

</script>

<template>
    <Head :title="pageTitle" />
    <AuthenticatedLayout :page-title="pageTitle">
        <template #pageContent>
            <div class="flex gap-x-4" v-if="currentTab==='EditPemesanan'">
                <Button @click="batalkanEditPemesanan()" label="Batal" severity="danger" icon="pi pi-times"/>
                <Button label="Edit Pemesanan" icon="pi pi-pen-to-square"/>
            </div>
            <div class="flex gap-x-4" v-else>
                <Button @click="switchComponents('DaftarPemesanan','Daftar Pemesanan')" label="Daftar Pemesanan" :severity="currentTab==='DaftarPemesanan'?'primary':'secondary'" icon="pi pi-list"/>
                <Button @click="switchComponents('TambahPemesanan','Tambah Pemesanan')" label="Tambah Pemesanan" :severity="currentTab==='DaftarPemesanan'?'secondary':'primary'" icon="pi pi-plus"/>
            </div>
            <div class="flex flex-col mt-4">
                <component :is="currentComponent" v-bind="componentProps" @refreshPage="refreshPage()" @editData="editData"/>
            </div>
        </template>
    </AuthenticatedLayout>
</template>

<style scoped>
</style>

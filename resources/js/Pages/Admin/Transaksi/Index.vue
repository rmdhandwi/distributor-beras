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
    dataTransaksi : Object,
})

const confirm = useConfirm()
const toast = useToast()

const pageTitle = ref('Daftar Transaksi')
const currentTab = ref('DaftarTransaksi')

const dataEditTransaksi = ref(null)

const refreshPage = () =>
{
    checkNotif()
    switchComponents('DaftarTransaksi','Daftar Transaksi')
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
                life : 4000,
            })

            setTimeout(() => { resolve()}, 4000)
        })
    }
}

const switchComponents = (component,title) =>
{
    currentTab.value = component
    pageTitle.value = title
}

// const editData = dataEmit =>
// {
//     dataEditTransaksi.value = dataEmit
//     switchComponents('EditTransaksi','Edit Transaksi')
// }

// async component
const loadComponent = componentName =>
{
    // if(componentName === 'TambahTransaksi' || componentName === 'EditTransaksi')
    // {
    //     return  defineAsyncComponent({
    //         loader : () => import(`./FormTransaksi.vue`),
    //         loadingComponent : LoadingSpinner,
    //         delay : 200,
    //         timeout: 3000
    //     })
    // }
    // else {
    //     return  defineAsyncComponent({
    //         loader : () => import(`./${componentName}.vue`),
    //         loadingComponent : LoadingSpinner,
    //         delay : 200,
    //         timeout: 3000
    //     })
    // }
    return  defineAsyncComponent({
        loader : () => import(`./${componentName}.vue`),
        loadingComponent : LoadingSpinner,
        delay : 200,
        timeout: 3000
    })
}

const currentComponent = computed(() => {
  return loadComponent(currentTab.value)
})

const componentProps = computed(() => {
    switch (currentTab.value) {

        case 'DaftarTransaksi':
        return {
            dataTransaksi: props.dataTransaksi?.map((p, i) => ({ nomor: i + 1, ...p })),
        };

        // case 'TambahTransaksi':
        // return {
        //     formType: 'Create',
        //     dataBeras : props.dataBeras,
        //     dataProdusen : props.dataProdusen,
        // };

        // case 'EditTransaksi':
        // return {
        //     formType: 'Edit',
        //     dataTransaksi: { ...dataEditTransaksi.value },
        //     dataProdusen : props.dataProdusen,
        // };

        default:
        return {};
    }
})

// const batalkanEditTransaksi = () =>
// {
//     confirm.require({
//         message: 'Batal Edit Transaksi?',
//         header: 'Peringatan',
//         icon: 'pi pi-exclamation-triangle',
//         rejectProps: {
//             label: 'Lanjut',
//             severity: 'secondary',
//             outlined: true
//         },
//         acceptProps: {
//             label: 'Batalkan',
//             severity: 'danger'
//         },
//         accept : () => {
//             toast.add({ severity: 'info', summary: 'Notifikasi', detail: 'Membatalkan...', life: 2000 });
//             dataEditTransaksi.value = null
//             setTimeout(() =>
//                 switchComponents('DaftarTransaksi','Daftar Transaksi')
//             ,2000)
//         },
//     });
// }

</script>

<template>
    <Head :title="pageTitle" />
    <AuthenticatedLayout :page-title="pageTitle">
        <template #pageContent>
            <!-- <div class="flex gap-x-4" v-if="currentTab==='EditTransaksi'">
                <Button @click="batalkanEditTransaksi()" label="Batal" severity="danger" icon="pi pi-times"/>
                <Button label="Edit Transaksi" icon="pi pi-pen-to-square"/>
            </div> -->
            <div class="flex gap-x-4">
                <Button @click="switchComponents('DaftarTransaksi','Daftar Transaksi')" label="Daftar Transaksi" icon="pi pi-list"/>
            </div>
            <div class="flex flex-col mt-4">
                <component :is="currentComponent" v-bind="componentProps" @refreshPage="refreshPage"/>
            </div>
        </template>
    </AuthenticatedLayout>
</template>

<style scoped>
</style>

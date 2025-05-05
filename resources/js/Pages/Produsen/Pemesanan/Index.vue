<script setup>
import { computed, defineAsyncComponent, onMounted, ref} from 'vue'
import { Head, useForm } from '@inertiajs/vue3'

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

const refreshPage = () =>
{
    checkNotif()
    switchComponents('DaftarPemesanan','Daftar Pemesanan')
}

const pemesananForm = useForm({
    id_pemesanan : null,
    nama_pesanan : null,
})

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

// async component
const loadComponent = componentName =>
{
    if(componentName === 'KonfirmasiPemesanan')
    {
        return  defineAsyncComponent({
            loader : () => import(`./DaftarPemesanan.vue`),
            loadingComponent : LoadingSpinner,
            delay : 200,
            timeout: 3000
        })
    }
    else {
    }
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

        case 'DaftarPemesanan':
        return {
            dataPemesanan: props.dataPemesanan?.map((p, i) => ({ nomor: i + 1, ...p })),
        };

        case 'KonfirmasiPemesanan':
        return {
            pageType : 'Konfirmasi',
            dataPemesanan : props.dataPemesanan?.filter(pemesanan => pemesanan.editable === true).map((p, i) => ({ nomor: i + 1, ...p })),
        };

        default:
        return {};
    }
})

const confirmPemesanan = confirmData =>
{

    pemesananForm.id_pemesanan = confirmData[0]?.id_pemesanan ?? null
    pemesananForm.nama_pesanan = confirmData[0]?.beras.nama_beras ?? null

    confirm.require({
        message: `Konfirmasi Pemesananan ${pemesananForm.nama_pesanan}?`,
        header: 'Peringatan',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Batalkan',
            severity: 'danger',
            outlined: true
        },
        acceptProps: {
            label: 'Konfirmasi',
        },
        accept : () => {
            toast.add({ severity: 'info', summary: 'Notifikasi', detail: 'Memproses...', life: 4000 });
            pemesananForm.post(route('produsen.pemesanan.confirm'), {
                onError : () => {
                    toast.add({
                        severity : 'error',
                        summary : 'Notifikasi',
                        detail : 'Terjadi kesalahan',
                        life : 3000,
                    })
                },
                onSuccess : () => {
                    pemesananForm.reset()
                    pemesananForm.clearErrors()
                    refreshPage()
                }
            })

        },
    });
}

</script>

<template>
    <Head :title="pageTitle" />
    <AuthenticatedLayout :page-title="pageTitle">
        <template #pageContent>
            <div class="flex gap-x-4">
                <Button @click="switchComponents('DaftarPemesanan','Daftar Pemesanan')" label="Daftar Pemesanan" :severity="currentTab==='DaftarPemesanan'?'primary':'secondary'" icon="pi pi-list"/>
                <Button @click="switchComponents('KonfirmasiPemesanan','Konfirmasi Pemesanan')" label="Menunggu Konfirmasi" :severity="currentTab==='DaftarPemesanan'?'secondary':'primary'" icon="pi pi-info-circle"/>
            </div>
            <div class="flex flex-col mt-4">
                <component :is="currentComponent" v-bind="componentProps" @refreshPage="refreshPage()" @confirmData="confirmPemesanan"/>
            </div>
        </template>
    </AuthenticatedLayout>
</template>

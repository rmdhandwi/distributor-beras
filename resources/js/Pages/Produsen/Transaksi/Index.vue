<script setup>
import { computed, defineAsyncComponent, onMounted, ref} from 'vue'
import { Head } from '@inertiajs/vue3'

import { useToast } from 'primevue'

import LoadingSpinner from '@/Components/LoadingSpinner.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'


onMounted(() =>
{
    checkNotif()
})

const props = defineProps({
    flash : Object,
    dataTransaksi : Object,
    daftarNoRek : Object
})

const toast = useToast()

const pageTitle = ref('Daftar Transaksi')
const currentTab = ref('DaftarTransaksi')

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

// async component
const loadComponent = componentName =>
{
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
            daftarNoRek : props.daftarNoRek
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

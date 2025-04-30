<script setup>
import { computed, defineAsyncComponent, onMounted, ref,} from 'vue'
import { Head } from '@inertiajs/vue3'

import { useToast } from 'primevue'

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import LoadingSpinner from '@/Components/LoadingSpinner.vue'


onMounted(() =>
{
    checkNotif()
})

const props = defineProps({
    flash : Object,
    dataProdusen : Object,
})

const toast = useToast()

const refreshPage = () =>
{
    switchComponents('DaftarProdusen','Daftar Produsen')
    checkNotif()
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

const pageTitle = ref('Daftar Produsen')

const currentTab = ref('DaftarProdusen')

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

        case 'DaftarProdusen':
        return {
            dataProdusen: props.dataProdusen?.map((p, i) => ({ nomor: i + 1, ...p })),
        };

        case 'ValidasiProdusen':
        return {
            dataProdusen: props.dataProdusen?.filter( produsen => produsen.status === 0).map((p, i) => ({ nomor: i + 1, ...p })) ,
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
                <Button @click="switchComponents('DaftarProdusen','Daftar Produsen')" label="Daftar Produsen" :variant="currentTab==='DaftarProdusen'?'':'outlined'" icon="pi pi-list"/>
                <Button @click="switchComponents('ValidasiProdusen','Validasi Produsen')" label="Menunggu Validasi" :variant="currentTab==='DaftarProdusen'?'outlined':''" icon="pi pi-exclamation-circle"/>
            </div>
            <div class="flex flex-col mt-4">
                <component :is="currentComponent" v-bind="componentProps" @refreshPage="refreshPage()"/>
            </div>
        </template>
    </AuthenticatedLayout>
</template>

<style scoped>
</style>

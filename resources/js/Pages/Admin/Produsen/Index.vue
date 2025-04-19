<script setup>
import { computed, defineAsyncComponent, onMounted, ref,} from 'vue'
import { Head } from '@inertiajs/vue3'

import { useConfirm, useToast } from 'primevue'

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

const confirm = useConfirm()

const toast = useToast()

const dataEditProdusen = ref(null)

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
    if(componentName === 'TambahProdusen' || componentName === 'EditProdusen')
    {
        return  defineAsyncComponent({
            loader : () => import(`./FormProdusen.vue`),
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

        case 'DaftarProdusen':
        return {
            dataProdusen: props.dataProdusen?.map((p, i) => ({ nomor: i + 1, ...p })),
        };

        case 'TambahProdusen':
        return {
            formType: 'Create',
        };

        case 'EditProdusen':
        return {
            formType: 'Edit',
            dataProdusen: { ...dataEditProdusen.value },
        };

        default:
        return {};
    }
})

const editData = dataEmit =>
{
    dataEditProdusen.value = dataEmit
    switchComponents('EditProdusen','Edit Produsen')
}

const batalkanEditProdusen = () =>
{
    confirm.require({
        message: 'Batal Edit Produsen?',
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
            dataEditProdusen.value = null
            setTimeout(() =>
                switchComponents('DaftarProdusen','Daftar Produsen')
            ,2000)
        },
    });
}
</script>

<template>
    <Head :title="pageTitle" />
    <AuthenticatedLayout :page-title="pageTitle">
        <template #pageContent>
            <div class="flex gap-x-4" v-if="currentTab==='EditProdusen'">
                <Button @click="batalkanEditProdusen()" label="Batal" severity="danger" icon="pi pi-times"/>
                <Button label="Edit Produsen" icon="pi pi-pen-to-square"/>
            </div>
            <div class="flex gap-x-4" v-else>
                <Button @click="switchComponents('DaftarProdusen','Daftar Produsen')" label="Daftar Produsen" :severity="currentTab==='DaftarProdusen'?'primary':'secondary'" icon="pi pi-list"/>
                <Button @click="switchComponents('TambahProdusen','Tambah Produsen')" label="Tambah Produsen" :severity="currentTab==='DaftarProdusen'?'secondary':'primary'" icon="pi pi-plus"/>
            </div>
            <div class="flex flex-col mt-4">
                <component :is="currentComponent" v-bind="componentProps" @refreshPage="refreshPage()" @editData="editData"/>
            </div>
        </template>
    </AuthenticatedLayout>
</template>

<style scoped>
</style>

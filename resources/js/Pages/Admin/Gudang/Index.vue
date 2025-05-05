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
    dataBeras : Object,
    dataGudang : Object,
})

const toast = useToast()

const pageTitle = ref('Daftar Stok')
const currentTab = ref('DaftarStok')

const dataEdit = ref(null)

const refreshPage = () =>
{
    checkNotif()
    switchComponents('DaftarStok','Daftar Stok')
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

// async component
const loadComponent = componentName =>
{
    if(componentName === 'TambahStok' || componentName === 'EditStok')
    {
        return  defineAsyncComponent({
            loader : () => import(`./FormStok.vue`),
            loadingComponent : LoadingSpinner,
            delay : 200,
            timeout: 3000
        })
    }
    else
    {
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

        case 'DaftarStok':
        return {
            dataGudang: props.dataGudang?.map((p, i) => ({ nomor: i + 1, ...p })),
        };

        case 'TambahStok':
        return {
            dataBeras : props.dataBeras,
            formType  : 'Create'
        };

        case 'EditStok':
        return {
            dataBeras : props.dataBeras,
            dataGudang : dataEdit.value,
            formType  : 'Edit'
        };

        default:
        return {};
    }
})

const editData = dataEmit =>
{
    dataEdit.value = dataEmit
    switchComponents('EditStok','Edit Stok')
}

</script>

<template>
    <Head :title="pageTitle" />
    <AuthenticatedLayout :page-title="pageTitle">
        <template #pageContent>
            <div class="flex gap-x-4">
                <Button @click="switchComponents('DaftarStok','Daftar Stok')" label="Daftar Stok" :severity="currentTab==='DaftarStok'?'primary':'secondary'" icon="pi pi-list"/>
                <Button @click="switchComponents('TambahStok','Tambah Stok')" label="Tambah Stok" :severity="currentTab==='TambahStok'?'primary':'secondary'" icon="pi pi-list"/>
            </div>
            <div class="flex flex-col mt-4">
                <component :is="currentComponent" v-bind="componentProps" @refreshPage="refreshPage" @editData="editData"/>
            </div>
        </template>
    </AuthenticatedLayout>
</template>

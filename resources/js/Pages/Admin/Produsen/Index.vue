<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { useToast } from 'primevue'
import { computed, defineAsyncComponent, onMounted, ref,} from 'vue'


onMounted(() =>
{
    checkNotif()
})

const props = defineProps({
    flash: Object,
})


const toast = useToast()

const refreshPage = async () =>
{
    await checkNotif()
    router.visit(route('admin.produsen.index'))
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
                life : 3000,
            })

            setTimeout(() => { resolve()}, 3000)
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
const DaftarProdusen = defineAsyncComponent(() => import('./DaftarProdusen.vue'))
const TambahProdusen = defineAsyncComponent(() => import('./TambahProdusen.vue'))

const currentComponent = computed(() => {
  return currentTab.value === 'DaftarProdusen' ? DaftarProdusen : TambahProdusen
})
</script>

<template>
    <Head :title="pageTitle" />
    <AuthenticatedLayout :page-title="pageTitle">
        <template #pageContent>
            <div class="flex gap-x-4">
                <Button @click="switchComponents('DaftarProdusen','Daftar Produsen')" label="Daftar Produsen" :severity="currentTab==='DaftarProdusen'?'primary':'secondary'" icon="pi pi-list"/>
                <Button @click="switchComponents('TambahProdusen','Tambah Produsen')" label="Tambah Produsen" :severity="currentTab==='DaftarProdusen'?'secondary':'primary'" icon="pi pi-plus"/>
            </div>
            <div class="p-4 flex flex-col">
                <component :is="currentComponent" @refreshPage="refreshPage()"/>
            </div>
        </template>
    </AuthenticatedLayout>
</template>

<style scoped>
</style>

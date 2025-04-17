<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import { computed, defineAsyncComponent, ref,} from 'vue'


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
                <component :is="currentComponent"  />
            </div>
        </template>
    </AuthenticatedLayout>
</template>

<style scoped>
</style>

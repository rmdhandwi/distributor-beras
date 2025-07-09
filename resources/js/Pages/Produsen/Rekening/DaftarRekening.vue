<script setup>
import { onBeforeMount, ref } from 'vue'

import {FilterMatchMode} from '@primevue/core/api'

onBeforeMount(() =>
{
    dataRekeningFix.value = props.dataRekening
})

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
})

const props = defineProps({
    dataRekening : Object,
})

const dataRekeningFix = ref([])
</script>

<template>
    <div class="flex flex-col">
        <DataTable :value="dataRekeningFix" dataKey="index" class="shadow border border-amber-500 rounded-lg overflow-hidden" showGridlines removable-sort striped-rows scrollable v-model:filters="filters">
            <template #header>
                <div class="flex flex-col gap-y-2">
                    <!-- Basic Filter -->
                    <div class="flex justify-between items-center gap-x-2">
                        <IconField class="w-full">
                            <InputIcon>
                                <i class="pi pi-search me-4" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" placeholder="Cari Data Rekening" size="small" fluid/>
                        </IconField>
                    </div>
                </div>
            </template>
            <template #footer>
                <span>Jumlah Data Rekening ({{ dataRekeningFix.length }})</span>
            </template>
            <template #loading>
                <span class="flex justify-center">Sedang Memuat Data...</span>
            </template>
            <template #empty>
                <span class="flex justify-center">Tidak Ada Rekening</span>
            </template>
            <Column sortable field="nomor" header="No"/>
            <Column sortable field="no_rekening" header="Nomor Rekening"/>
            <Column sortable field="nama_rekening" header="Nama Rekening"/>
            <Column header="Action" frozen align-frozen="right">
                <template #body="{data}">
                    <div class="flex place-content-center gap-2">
                        <Button severity="danger" size="small" icon="pi pi-trash"/>
                    </div>
                </template>
            </Column>
        </DataTable>
    </div>
</template>

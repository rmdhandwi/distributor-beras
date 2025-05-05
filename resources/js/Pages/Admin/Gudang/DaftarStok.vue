<script setup>
import { ref } from 'vue'

import {FilterMatchMode} from '@primevue/core/api'

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
})

const props = defineProps({
    dataGudang : Object,
})


</script>

<template>
    <div class="flex flex-col">
        <DataTable :value="props.dataGudang" dataKey="index" class="shadow border border-amber-500 rounded-lg overflow-hidden" showGridlines removable-sort striped-rows scrollable v-model:filters="filters">
            <template #header>
                <div class="flex justify-between items-center gap-x-2">
                    <IconField class="w-full">
                        <InputIcon>
                            <i class="pi pi-search me-4" />
                        </InputIcon>
                        <InputText v-model="filters['global'].value" placeholder="Cari Data Gudang" size="small" fluid/>
                    </IconField>
                    <Button icon="pi pi-print" severity="contrast" variant="outlined" label="CSV" size="small" />
                </div>
            </template>
            <template #footer>
                <span>Jumlah Data Gudang ({{ props.dataGudang.length }})</span>
            </template>
            <template #loading>
                <span class="flex justify-center">Sedang Memuat Data...</span>
            </template>
            <template #empty>
                <span class="flex justify-center">Tidak Ada Gudang</span>
            </template>
            <Column sortable field="nomor" header="No" frozen/>
            <Column sortable header="Nama Beras" style="min-width: 180px;" frozen>
                <template #body="{data}">
                    {{ data.beras[0]?.nama_beras }}
                </template>
            </Column>
            <Column sortable header="Produsen" style="min-width: 180px;" frozen>
                <template #body="{data}">
                    {{ data.produsen[0]?.nama_produsen }}
                </template>
            </Column>
            <Column field="stok_awal" header="Stok Awal" style="min-width: 100px;"/>
            <Column field="rusak" header="Rusak" style="min-width: 100px;"/>
            <Column field="hilang" header="Hilang" style="min-width: 100px;"/>
            <Column field="stok_sisa" header="Stok Sisa" style="min-width: 100px;"/>
        </DataTable>
    </div>
</template>

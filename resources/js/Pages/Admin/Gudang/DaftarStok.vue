<script setup>
import { ref } from 'vue'

import {FilterMatchMode} from '@primevue/core/api'

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
})

const props = defineProps({
    dataGudang : Object,
})

const emit = defineEmits(['editData'])

const editStok = id_gudang =>
{
    const dataFilter = props.dataGudang.filter((gudang) => gudang.id_gudang === id_gudang)
    emit('editData', dataFilter)
    console.log('filter data : '+dataFilter)
}

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
                    {{ data.beras?.nama_beras }}
                </template>
            </Column>
            <Column sortable header="Produsen" style="min-width: 180px;" frozen>
                <template #body="{data}">
                    {{ data.produsen?.nama_produsen }}
                </template>
            </Column>
            <Column field="stok_awal" header="Stok Awal" style="min-width: 100px;"/>
            <Column field="rusak" header="Rusak" style="min-width: 100px;"/>
            <Column field="hilang" header="Hilang" style="min-width: 100px;"/>
            <Column field="stok_sisa" header="Stok Sisa" style="min-width: 100px;"/>
            <Column header="Action" frozen align-frozen="right">
                <template #body="{data}">
                    <div class="flex place-content-center gap-2">
                        <Button @click="editStok(data.id_gudang)" severity="info" size="small" icon="pi pi-pen-to-square"/>
                    </div>
                </template>
            </Column>
        </DataTable>
    </div>
</template>

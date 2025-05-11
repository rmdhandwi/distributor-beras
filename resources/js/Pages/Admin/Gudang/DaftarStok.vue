<script setup>
import { onMounted, ref } from 'vue'

import {FilterMatchMode} from '@primevue/core/api'

onMounted(() =>
{
    dataGudangFix.value = props.dataGudang
    setDaftarBeras()
    setDaftarProdusen()
})

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
})

const props = defineProps({
    dataGudang : Object,
})

const dataGudangFix = ref([])

const daftarNamaBeras = ref([])
const daftarNamaProdusen = ref([])

const selectedNamaBeras = ref(null)
const selectedNamaProdusen = ref(null)

const emit = defineEmits(['editData'])

const editStok = id_gudang =>
{
    const dataFilter = props.dataGudang.filter((gudang) => gudang.id_gudang === id_gudang)
    emit('editData', dataFilter)
}

const resetData = () =>
{
    dataGudangFix.value = props.dataGudang
}

const filterByNamaBeras = () =>
{
    if(selectedNamaBeras.value)
    {
        const sorted = props.dataGudang?.filter(item => item.beras.id_beras === selectedNamaBeras.value).map((p, i) => ({ ...p, nomor: i + 1}))
        dataGudangFix.value = sorted
    }
    else resetData()

}

const filterByNamaProdusen = () =>
{
    if(selectedNamaProdusen.value)
    {
        const sorted = props.dataGudang?.filter(item => item.produsen.id_produsen === selectedNamaProdusen.value).map((p, i) => ({ ...p, nomor: i + 1}))
        dataGudangFix.value = sorted
    }
    else resetData()
}

const setDaftarBeras = () =>
{
    const seen = new Set();
    daftarNamaBeras.value =  props.dataGudang.filter(item => {
        if (seen.has(item.beras.id_beras)) return false;
        seen.add(item.beras.id_beras);
        return true;
    }).map(item => ({
        id_beras: item.beras.id_beras,
        nama_beras: item.beras.nama_beras
    }));
}

const setDaftarProdusen = () =>
{
    const seen = new Set();
    daftarNamaProdusen.value =  props.dataGudang.filter(item => {
        if (seen.has(item.produsen.id_produsen)) return false;
        seen.add(item.produsen.id_produsen);
        return true;
    }).map(item => ({
        id_produsen: item.produsen.id_produsen,
        nama_produsen: item.produsen.nama_produsen
    }));
}

</script>

<template>
    <div class="flex flex-col">
        <DataTable :value="dataGudangFix" dataKey="index" class="shadow border border-amber-500 rounded-lg overflow-hidden" showGridlines removable-sort striped-rows scrollable v-model:filters="filters">
            <template #header>
                <div class="flex flex-col gap-y-2">
                    <!-- Basic Filter -->
                    <div class="flex justify-between items-center gap-x-2">
                        <IconField class="w-full">
                            <InputIcon>
                                <i class="pi pi-search me-4" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" placeholder="Cari Data Gudang" size="small" fluid/>
                        </IconField>
                        <Button icon="pi pi-print" severity="contrast" variant="outlined" label="CSV" size="small" />
                    </div>
                    <!-- Custom Filter -->
                    <div class="flex items-center gap-x-2">
                        <Select :show-clear="true" @value-change="filterByNamaBeras()" placeholder="Nama Beras" v-model="selectedNamaBeras" :options="daftarNamaBeras" option-label="nama_beras" option-value="id_beras" fluid/>

                        <Select :show-clear="true" @value-change="filterByNamaProdusen()" placeholder="Nama Produsen" v-model="selectedNamaProdusen" :options="daftarNamaProdusen" option-label="nama_produsen" option-value="id_produsen" fluid/>
                    </div>
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

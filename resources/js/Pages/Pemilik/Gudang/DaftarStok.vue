<script setup>
import { onMounted, ref } from 'vue'
import { useConfirm, useToast } from 'primevue'
import { router } from '@inertiajs/vue3'

import {FilterMatchMode} from '@primevue/core/api'

onMounted(() =>
{
    dataGudangFix.value = props.dataGudang
    setDaftarBeras()
    setDaftarProdusen()
    setDataStats()
})

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
})

const props = defineProps({
    dataGudang : Object,
})

const confirm = useConfirm()
const toast = useToast()

const dataGudangFix = ref([])

const daftarNamaBeras = ref([])
const daftarNamaProdusen = ref([])

const selectedNamaBeras = ref(null)
const selectedNamaProdusen = ref(null)

const dataStats = ref({
    stok_awal : null,
    rusak : null,
    hilang : null,
    stok_sisa : null,
})

const resetData = () =>
{
    resetDataStats()
    dataGudangFix.value = props.dataGudang
    setDataStats()

}

const filterByNamaBeras = () =>
{
    if(selectedNamaBeras.value)
    {
        resetDataStats()
        const sorted = props.dataGudang?.filter(item => item.beras.id_beras === selectedNamaBeras.value).map((p, i) => ({ ...p, nomor: i + 1}))
        dataGudangFix.value = sorted
        setDataStats()
    }
    else resetData()

}

const filterByNamaProdusen = () =>
{
    if(selectedNamaProdusen.value)
    {
        resetDataStats()
        const sorted = props.dataGudang?.filter(item => item.produsen.id_produsen === selectedNamaProdusen.value).map((p, i) => ({ ...p, nomor: i + 1}))
        dataGudangFix.value = sorted
        setDataStats()

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

const resetDataStats = () =>
{
    dataStats.value = {
        stok_awal: null,
        rusak: null,
        hilang: null,
        stok_sisa: null,
    }
}

const setDataStats = () =>
{
    dataGudangFix.value.forEach(item => {
        dataStats.value.stok_awal += item.stok_awal
        dataStats.value.rusak += item.rusak
        dataStats.value.hilang += item.hilang
        dataStats.value.stok_sisa += item.stok_sisa
    })
}

const cetakLaporan = () =>
{
    confirm.require({
        message: `Cetak Laporan ?`,
        header: 'Peringatan',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Batal',
            severity: 'secondary',
            outlined: true,
        },
        acceptProps: {
            label: `Cetak`,
        },
        accept: () => {
            toast.add({
                severity : 'info',
                summary : 'Notifikasi',
                detail : 'Memproses',
                life : 2000,
            })
            router.post(route('gudang.laporan.cetak'), {
                data : dataGudangFix.value,
                fromRoute : route().current(),
            })
        },
    })
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
                        <Button @click="cetakLaporan" icon="pi pi-print" severity="danger" variant="outlined" label="PDF" size="small" />
                    </div>
                    <!-- Custom Filter -->
                    <div class="flex items-center gap-x-2">
                        <Select :show-clear="true" @value-change="filterByNamaBeras()" placeholder="Nama Beras" v-model="selectedNamaBeras" :options="daftarNamaBeras" option-label="nama_beras" option-value="id_beras" fluid/>

                        <Select :show-clear="true" @value-change="filterByNamaProdusen()" placeholder="Nama Produsen" v-model="selectedNamaProdusen" :options="daftarNamaProdusen" option-label="nama_produsen" option-value="id_produsen" fluid/>
                    </div>
                </div>
            </template>
            <template #footer>
                <span>Jumlah Data Gudang ({{ dataGudangFix.length }})</span>
            </template>
            <template #loading>
                <span class="flex justify-center">Sedang Memuat Data...</span>
            </template>
            <template #empty>
                <span class="flex justify-center">Tidak Ada Gudang</span>
            </template>
            <Column sortable field="nomor" header="No"/>
            <Column header="Nama Beras" filter-field="beras.nama_beras" style="min-width: 180px;">
                <template #body="{data}">
                    {{ data.beras?.nama_beras }}
                </template>
            </Column>
            <Column header="Produsen" filter-field="produsen.nama_produsen" style="min-width: 180px;">
                <template #body="{data}">
                    {{ data.produsen?.nama_produsen }}
                </template>
            </Column>
            <Column field="stok_awal" header="Stok Awal" style="min-width: 100px;"/>
            <Column field="rusak" header="Rusak" style="min-width: 100px;"/>
            <Column field="hilang" header="Hilang" style="min-width: 100px;"/>
            <Column field="stok_sisa" header="Stok Sisa" style="min-width: 100px;"/>
            <ColumnGroup type="footer">
                <Row>
                    <Column footer="Total :" colspan="3" footerStyle="text-align:right"/>
                    <Column :footer="dataStats.stok_awal"/>
                    <Column :footer="dataStats.rusak"/>
                    <Column :footer="dataStats.hilang"/>
                    <Column :footer="dataStats.stok_sisa" colspan="2"/>
                </Row>
            </ColumnGroup>
        </DataTable>
    </div>
</template>

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
    stok10kg : {
        stok_awal : 0,
        rusak : 0,
        hilang : 0,
        stok_sisa : 0,
    },
    stok20kg : {
        stok_awal : 0,
        rusak : 0,
        hilang : 0,
        stok_sisa : 0,
    },
    stok50kg : {
        stok_awal : 0,
        rusak : 0,
        hilang : 0,
        stok_sisa : 0,
    },
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
        stok10kg : {
            stok_awal : 0,
            rusak : 0,
            hilang : 0,
            stok_sisa : 0,
        },
        stok20kg : {
            stok_awal : 0,
            rusak : 0,
            hilang : 0,
            stok_sisa : 0,
        },
        stok50kg : {
            stok_awal : 0,
            rusak : 0,
            hilang : 0,
            stok_sisa : 0,
        },
    }
}

const setDataStats = () =>
{
    dataGudangFix.value.forEach(item => {
        dataStats.value.stok10kg.stok_awal += item.stok10kg?.stok_awal
        dataStats.value.stok10kg.rusak += item.stok10kg?.rusak
        dataStats.value.stok10kg.hilang += item.stok10kg?.hilang
        dataStats.value.stok10kg.stok_sisa += item.stok10kg?.stok_sisa
        dataStats.value.stok20kg.stok_awal += item.stok20kg?.stok_awal
        dataStats.value.stok20kg.rusak += item.stok20kg?.rusak
        dataStats.value.stok20kg.hilang += item.stok20kg?.hilang
        dataStats.value.stok20kg.stok_sisa += item.stok20kg?.stok_sisa
        dataStats.value.stok50kg.stok_awal += item.stok50kg?.stok_awal
        dataStats.value.stok50kg.rusak += item.stok50kg?.rusak
        dataStats.value.stok50kg.hilang += item.stok50kg?.hilang
        dataStats.value.stok50kg.stok_sisa += item.stok50kg?.stok_sisa
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
            <ColumnGroup type="header">
                <Row>
                    <Column sortable field="nomor" header="No" rowspan="2"/>
                    <Column sortable field="beras.nama_beras" header="Nama Beras" filter-field="beras.nama_beras" rowspan="2" style="min-width: 150px;"/>
                    <Column sortable field="beras.jenis_beras" header="Jenis" rowspan="2"/>
                    <Column sortable field="produsen.nama_produsen" header="Produsen" filter-field="produsen.nama_produsen" rowspan="2"/>
                    <Column header="Stok 10kg" colspan="4"/>
                    <Column header="Stok 20kg" colspan="4"/>
                    <Column header="Stok 50kg" colspan="4"/>
                </Row>
                <Row>
                    <!-- 10kg -->
                    <Column sortable field="stok10kg.stok_awal"  header="Awal"/>
                    <Column sortable field="stok10kg.rusak"  header="Rusak"/>
                    <Column sortable field="stok10kg.hilang"  header="Hilang"/>
                    <Column sortable field="stok10kg.stok_sisa"  header="Sisa"/>
                    <!-- 20kg -->
                    <Column sortable field="stok20kg.stok_awal"  header="Awal"/>
                    <Column sortable field="stok20kg.rusak"  header="Rusak"/>
                    <Column sortable field="stok20kg.hilang"  header="Hilang"/>
                    <Column sortable field="stok20kg.stok_sisa"  header="Sisa"/>
                    <!-- 50kg -->
                    <Column sortable field="stok50kg.stok_awal"  header="Awal"/>
                    <Column sortable field="stok50kg.rusak"  header="Rusak"/>
                    <Column sortable field="stok50kg.hilang"  header="Hilang"/>
                    <Column sortable field="stok50kg.stok_sisa"  header="Sisa"/>
                </Row>
            </ColumnGroup>
            <Column sortable field="nomor"/>
            <Column field="beras.nama_beras" style="min-width: 180px;">
                <template #body="{data}">
                    {{ data.beras?.nama_beras }}
                </template>
            </Column>
            <Column style="min-width: 180px;">
                <template #body="{data}">
                    {{ data.beras?.jenis_beras }}
                </template>
            </Column>
            <Column style="min-width: 180px;">
                <template #body="{data}">
                    {{ data.produsen?.nama_produsen }}
                </template>
            </Column>
            <!-- 10kg -->
            <Column>
                <template #body="{data}">
                    {{ data.stok10kg?.stok_awal ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok10kg?.rusak ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok10kg?.hilang ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok10kg?.stok_sisa ?? '-' }}
                </template>
            </Column>
            <!-- 20kg -->
            <Column>
                <template #body="{data}">
                    {{ data.stok20kg?.stok_awal ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok20kg?.rusak ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok20kg?.hilang ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok20kg?.stok_sisa ?? '-' }}
                </template>
            </Column>
            <!-- 50kg -->
            <Column>
                <template #body="{data}">
                    {{ data.stok50kg?.stok_awal ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok50kg?.rusak ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok50kg?.hilang ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok50kg?.stok_sisa ?? '-' }}
                </template>
            </Column>
            <ColumnGroup type="footer">
                <Row>
                    <Column footer="Total :" colspan="4" footerStyle="text-align:right"/>
                    <!-- 10kg -->
                    <Column :footer="dataStats.stok10kg?.stok_awal"/>
                    <Column :footer="dataStats.stok10kg?.rusak"/>
                    <Column :footer="dataStats.stok10kg?.hilang"/>
                    <Column :footer="dataStats.stok10kg?.stok_sisa"/>
                    <!-- 20kg -->
                    <Column :footer="dataStats.stok20kg?.stok_awal"/>
                    <Column :footer="dataStats.stok20kg?.rusak"/>
                    <Column :footer="dataStats.stok20kg?.hilang"/>
                    <Column :footer="dataStats.stok20kg?.stok_sisa"/>
                    <!-- 50kg -->
                    <Column :footer="dataStats.stok50kg?.stok_awal"/>
                    <Column :footer="dataStats.stok50kg?.rusak"/>
                    <Column :footer="dataStats.stok50kg?.hilang"/>
                    <Column :footer="dataStats.stok50kg?.stok_sisa"/>
                    <Column frozen align-frozen="right"/>
                </Row>
            </ColumnGroup>
        </DataTable>
    </div>
</template>

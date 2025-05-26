<script setup>
import { nextTick, onMounted, ref } from 'vue'
import { router } from '@inertiajs/vue3'

import {FilterMatchMode} from '@primevue/core/api'
import { useConfirm, useToast } from 'primevue'

onMounted(() =>
{
    dataBerasFix.value = props.dataBeras
    setDataStats()
})

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
})


const props = defineProps({
    dataBeras : Object,
})

const emit = defineEmits(['editData'])

const dt = ref()

const confirm = useConfirm()

const toast = useToast()

const dataBerasFix = ref([])

const isLoading = ref(false)

const selectedDataFilter = ref(null)

const selectedDateProduksiRange = ref(null)

const priceFilterData = [
    {label : 'Termurah', value : 'ASC'},
    {label : 'Termahal', value : 'DESC'},
]

const dataStats = ref({
    total_tersedia : null,
    jumlah10kg : null,
    jumlah20kg : null,
    jumlah50kg : null,
})

const setDataStats = () =>
{
    dataBerasFix.value.forEach(item => {
        dataStats.value.total_tersedia += item.stok_tersedia
        dataStats.value.jumlah10kg += item.stok10kg?.jumlah ?? 0
        dataStats.value.jumlah20kg += item.stok20kg?.jumlah ?? 0
        dataStats.value.jumlah50kg += item.stok50kg?.jumlah ?? 0
    })
}

function formatDecimal(angka)
{
    if(angka)
    {
        return angka.toLocaleString('id-ID');
    }

    return 0
}

function formatRupiah(angka) {
    if(angka)
    {
        return new Intl.NumberFormat('id-ID', {
          style: 'currency',
          currency: 'IDR',
          minimumFractionDigits: 0
        }).format(angka);
    }
}

function formatTanggal(tanggal) {
    if(tanggal)
    {
        const [tahun, bulan, hari] = tanggal.split('-')
        return `${hari}/${bulan}/${tahun}`
    }
    return '-'
}

const editBeras = id_beras =>
{
    const dataBeras = props.dataBeras.filter((beras) => beras.id_beras === id_beras).map(item => {
        const copy = {...item}
        delete copy.nomor
        return copy
    })

    emit('editData', dataBeras)
}


const normalizeDate = (date) => {
  const d = new Date(date);
  d.setHours(0, 0, 0, 0);
  return d;
};

const resetData = () =>
{
    isLoading.value = true
    dataBerasFix.value = props.dataBeras.map((p, i) => ({...p, nomor: i + 1}))

    nextTick(() => {
        isLoading.value = false
    })
}

const filterByDateProduksiRange = () =>
{
    isLoading.value = true

    const start = normalizeDate(selectedDateProduksiRange.value[0])

    const end = normalizeDate(selectedDateProduksiRange.value[1]) ?? start

    const sorted  = props.dataBeras.filter(item => normalizeDate(item.tgl_produksi) >= start && normalizeDate(item.tgl_produksi) <= end).map((p, i) => ({...p, nomor: i + 1}))

    nextTick(() =>
    {
        dataBerasFix.value = sorted
        filterByPrice()
    })

    isLoading.value = false
}

const filterByPrice = () =>
{
    if(selectedDataFilter.value === 'ASC')
    {
        termurahFilter()
    }
    if(selectedDataFilter.value === 'DESC')
    {
        termahalFilter()
    }
}

const termurahFilter = () =>
{
    isLoading.value = true
    const dataSource = dataBerasFix.value ?? props.dataBeras

    const sorted = dataSource.sort((a, b) => a.harga_jual - b.harga_jual).map((p, i) => ({...p, nomor: i + 1}))

    nextTick(() =>
    {
        dataBerasFix.value = sorted
        isLoading.value = false
    })
}

const termahalFilter = () =>
{
    isLoading.value = true
    const dataSource = dataBerasFix.value ?? props.dataBeras
    const sorted = dataSource.sort((a, b) => b.harga_jual - a.harga_jual).map((p, i) => ({ ...p, nomor: i + 1}))

    nextTick(() =>
    {
        dataBerasFix.value = sorted
        isLoading.value = false
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
            router.post(route('beras.laporan.cetak'), {
                data : dataBerasFix.value,
                fromRoute : route().current(),
            })
        },
    })
}
</script>

<template>
     <div class="flex flex-col">
        <DataTable :loading="isLoading" ref="dt" :value="dataBerasFix" paginator :rows="10" :rowsPerPageOptions="[5, 10, 20, 50]" dataKey="index" class="shadow border border-amber-500 rounded-lg overflow-hidden" showGridlines removable-sort striped-rows scrollable v-model:filters="filters">
            <template #header>
                <div class="flex flex-col gap-y-2">
                    <!-- basic filter -->
                    <div class="flex justify-between items-center gap-x-2">
                        <IconField class="w-full">
                            <InputIcon>
                                <i class="pi pi-search me-4" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" placeholder="Cari Data Beras" size="small" fluid/>
                        </IconField>
                        <Button @click="cetakLaporan" icon="pi pi-print" severity="danger" variant="outlined" label="PDF" size="small" />
                    </div>
                    <!-- custom filter -->
                    <div class="flex items-center gap-x-2">
                        <!-- filter by harga -->
                        <Select :show-clear="true" @change="filterByPrice()" v-model="selectedDataFilter" placeholder="Filter Harga" :options="priceFilterData" optionLabel="label" optionValue="value" fluid>
                            <template #dropdownicon>
                                <i class="pi pi-filter-fill" />
                            </template>
                        </Select>
                        <!-- filter by tanggal -->
                        <FloatLabel variant="on">
                            <DatePicker class="w-[20rem]" show-button-bar @clear-click="resetData()" @date-select="filterByDateProduksiRange" showIcon iconDisplay="input" inputId="filterTanggal" v-model="selectedDateProduksiRange" selectionMode="range" :manual-input="false" date-format="yy-mm-dd"  fluid/>
                            <label for="filterTanggal">Filter Tanggal Produksi</label>
                        </FloatLabel>
                    </div>
                </div>
            </template>
            <template #footer>
                <span>Jumlah Data Beras ({{ props.dataBeras.length }})</span>
            </template>
            <template #loading>
                <span class="flex justify-center">Sedang Memuat Data...</span>
            </template>
            <template #empty>
                <span class="flex justify-center">Tidak Ada Beras</span>
            </template>
            <ColumnGroup type="header">
                <Row>
                    <Column sortable field="nomor" header="No" frozen rowspan="2"/>
                    <Column sortable field="nama_beras" header="Nama Beras" style="min-width: 180px;" frozen rowspan="2"/>
                    <Column header="Produsen" style="min-width: 180px;" rowspan="2"/>
                    <Column header="Jenis Beras" style="min-width: 140px;" rowspan="2"/>
                    <Column header="Stok Tersedia" style="min-width: 160px;" rowspan="2"/>
                    <Column header="Status" rowspan="2"/>
                    <Column header="Kualitas" rowspan="2"/>
                    <Column header="10kg" colspan="2"/>
                    <Column header="20kg" colspan="2"/>
                    <Column header="50kg" colspan="2"/>
                    <Column header="Sertifikat" style="min-width: 100px;" rowspan="2"/>
                    <Column header="Tanggal Produksi" style="min-width: 160px;" rowspan="2"/>
                    <Column header="Action" frozen align-frozen="right" rowspan="2"/>
                </Row>
                <Row>
                    <Column header="Jumlah"/>
                    <Column header="Harga"/>
                    <Column header="Jumlah"/>
                    <Column header="Harga"/>
                    <Column header="Jumlah"/>
                    <Column header="Harga"/>
                </Row>
            </ColumnGroup>
            <Column field="nomor" frozen/>
            <Column field="nama_beras" style="min-width: 180px;" frozen/>
            <Column field="produsen.nama_produsen"  style="min-width: 180px;"/>
            <Column field="jenis_beras" style="min-width: 140px;"/>
            <Column field="stok_tersedia" style="min-width: 160px;">
                <template #body="{data}">
                    {{ data.stok_tersedia+' kg'}}
                </template>
            </Column>
            <Column >
                <template #body="{data}">
                    <Badge :value="data.status_beras" :severity="data.status_beras==='Tersedia'?'success':'danger'"/>
                </template>
            </Column>
            <Column field="kualitas_beras"/>
            <Column>
                <template #body="{data}">
                    {{ data.stok10kg?.jumlah ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ formatRupiah(data.stok10kg?.harga) ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok20kg?.jumlah ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ formatRupiah(data.stok20kg?.harga) ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.stok50kg?.jumlah ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ formatRupiah(data.stok50kg?.harga) ?? '-' }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    <div class="size-10 overflow-hidden border rounded" v-if="data?.sertifikat_beras">
                        <Image :src="data?.sertifikat_beras" class="size-full" preview />
                    </div>
                    <span class="text-sm" v-else>Tidak ada sertifikat</span>
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_produksi) }}</span>
                </template>
            </Column>
            <Column frozen align-frozen="right">
                <template #body="{data}">
                    <div class="flex place-content-center gap-2">
                        <Button @click="editBeras(data.id_beras)" severity="info" size="small" icon="pi pi-pen-to-square"/>
                    </div>
                </template>
            </Column>
            <ColumnGroup type="footer" >
                <Row>
                    <Column colspan="2" frozen align-frozen="left"/>
                    <Column footer="Total :" colspan="2" footerStyle="text-align:right"/>
                    <Column :footer="formatDecimal(dataStats.total_tersedia)+' kg'" colspan="3"/>
                    <Column :footer="formatDecimal(dataStats.jumlah10kg)" colspan="2"/>
                    <Column :footer="formatDecimal(dataStats.jumlah20kg)" colspan="2"/>
                    <Column :footer="formatDecimal(dataStats.jumlah50kg)" colspan="4"/>
                    <Column colspan="1" frozen align-frozen="right"/>
                </Row>
            </ColumnGroup>
        </DataTable>

    </div>
</template>

<style scoped>
</style>

<script setup>
import { computed, nextTick, onMounted, ref } from 'vue'

import {FilterMatchMode} from '@primevue/core/api'

onMounted(() =>
{
    dataBerasFix.value = [...props.dataBeras]
    setDaftarProdusen()
})

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
})


const props = defineProps({
    dataBeras : Object,
})

const dt = ref()

const dataBerasFix = ref([])

const isLoading = ref(false)

const selectedDataFilter = ref(null)

const selectedDateProduksiRange = ref(null)

const selectedDateExpireRange = ref(null)

const selectedIdProdusen = ref(null)

const daftarProdusen = ref([])

const priceFilterData = [
    {label : 'Termurah', value : 'ASC'},
    {label : 'Termahal', value : 'DESC'},
]

function formatRupiah(angka) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(angka);
}

function formatTanggal(tanggal) {
  const [tahun, bulan, hari] = tanggal.split('-')
  return `${hari}/${bulan}/${tahun}`
}

const normalizeDate = (date) => {
  const d = new Date(date);
  d.setHours(0, 0, 0, 0);
  return d;
};

const setDaftarProdusen = () =>
{
    const seen = new Set();
    daftarProdusen.value =  props.dataBeras.filter(item => {
        if (seen.has(item.id_produsen)) return false;
        seen.add(item.id_produsen);
        return true;
    }).map(item => ({
        id_produsen: item.produsen.id_produsen,
        nama_produsen: item.produsen.nama_produsen
    }));
}

const filterByProdusen = () =>
{
    isLoading.value = true

    if(selectedIdProdusen.value)
    {
        const sorted = props.dataBeras.filter(item => item.id_produsen === selectedIdProdusen.value).map((p, i) => ({...p, nomor: i + 1}))

        nextTick(() => {
            dataBerasFix.value = sorted
            filterByPrice()
            isLoading.value = false
        })
    }

    else resetData()
}

const resetData = () =>
{
    isLoading.value = true
    dataBerasFix.value = props.dataBeras

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

    console.log(sorted)

    nextTick(() =>
    {
        dataBerasFix.value = sorted
        filterByPrice()
        isLoading.value = false
    })

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
</script>

<template>
     <div class="flex flex-col">
        <DataTable :loading="isLoading" ref="dt" :value="dataBerasFix" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" dataKey="index" class="shadow border border-amber-500 rounded-lg overflow-hidden" showGridlines removable-sort striped-rows scrollable v-model:filters="filters">
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
                        <Button icon="pi pi-print" severity="contrast" variant="outlined" label="CSV" size="small" />
                    </div>
                    <!-- custom filter -->
                    <div class="flex items-center gap-x-2">
                        <!-- filter by produsen -->
                        <Select :show-clear="true" @value-change="filterByProdusen()" v-model="selectedIdProdusen" placeholder="Filter Produsen" :options="daftarProdusen" optionLabel="nama_produsen" optionValue="id_produsen" fluid>
                            <template #dropdownicon>
                                <i class="pi pi-users" />
                            </template>
                        </Select>
                        <!-- filter by harga -->
                        <Select :show-clear="true" @change="filterByPrice()" v-model="selectedDataFilter" placeholder="Filter Harga" :options="priceFilterData" optionLabel="label" optionValue="value" fluid>
                            <template #dropdownicon>
                                <i class="pi pi-filter-fill" />
                            </template>
                        </Select>
                        <!-- filter by tanggal -->
                        <!-- <FloatLabel variant="on">
                            <DatePicker class="w-[20rem]" show-button-bar @clear-click="resetData()" @date-select="filterByDateProduksiRange" showIcon iconDisplay="input" inputId="filterTanggal" v-model="selectedDateProduksiRange" selectionMode="range" :manual-input="false" date-format="yy-mm-dd"  fluid/>
                            <label for="filterTanggal">Filter Tanggal Produksi</label>
                        </FloatLabel> -->

                    </div>
                </div>
            </template>
            <template #footer>
                <span>Jumlah Data Beras ({{ dataBerasFix.length ?? 0 }})</span>
            </template>
            <template #loading>
                <span class="flex justify-center">Sedang Memuat Data...</span>
            </template>
            <template #empty>
                <span class="flex justify-center">Tidak Ada Beras</span>
            </template>
            <Column sortable field="nomor" header="No" frozen/>
            <Column sortable field="nama_beras" header="Nama Beras" style="min-width: 180px;" frozen/>
            <Column sortable field="produsen.nama_produsen" header="Produsen" style="min-width: 180px;"/>
            <Column field="jenis_beras" header="Jenis Beras" style="min-width: 140px;"/>
            <Column sortable field="harga_jual" header="Harga Jual" style="min-width: 180px;">
                <template #body="{data}">
                    <span>{{ formatRupiah(data.harga_jual) }}</span>
                </template>
            </Column>
            <Column sortable field="stok_awal" header="Stok Awal" style="min-width: 140px;"/>
            <Column sortable field="stok_tersedia" header="Stok Tersedia" style="min-width: 160px;"/>
            <Column header="Status">
                <template #body="{data}">
                    <Badge :value="data.status_beras" :severity="data.status_beras==='Tersedia'?'success':'danger'"/>
                </template>
            </Column>
            <Column field="kualitas_beras" header="Kualitas" style="min-width: 240px;"/>
            <Column header="Sertifikat" style="min-width: 100px;">
                <template #body="{data}">
                    <div class="size-10 overflow-hidden border rounded" v-if="data?.sertifikat_beras">
                        <Image :src="data?.sertifikat_beras" class="size-full" preview />
                    </div>
                    <span class="text-sm" v-else>Tidak ada sertifikat</span>
                </template>
            </Column>
            <Column sortable header="Tanggal Produksi" style="min-width: 240px;">
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_produksi) }}</span>
                </template>
            </Column>
            <Column sortable header="Tanggal Kadaluarsa" style="min-width: 240px;">
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_kadaluarsa) }}</span>
                </template>
            </Column>
        </DataTable>

    </div>
</template>

<style scoped>
</style>

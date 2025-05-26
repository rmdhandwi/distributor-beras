<script setup>
import { nextTick, onMounted, ref } from 'vue'
import { router } from '@inertiajs/vue3'

import {FilterMatchMode} from '@primevue/core/api'
import { useConfirm, useToast } from 'primevue'

onMounted(() =>
{
    dataTransaksiFix.value = props.dataTransaksi
    setDaftarBeras()
    setDaftarProdusen()
    setDataStats()
})

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
})

const props = defineProps({
    dataTransaksi : Object,
})

const toast = useToast()
const confirm = useConfirm()

const dataStats = ref({
    jumlah_pesan : 0,
    total_bayar : 0,
    stok10kg : {
        jumlah : 0,
        total_harga : 0,
    },
    stok20kg : {
        jumlah : 0,
        total_harga : 0,
    },
    stok50kg : {
        jumlah : 0,
        total_harga : 0,
    },
})

const isLoading = ref(false)

const daftarBeras = ref([])
const daftarProdusen = ref([])

const selectedIdBeras = ref(null)
const selectedIdProdusen = ref(null)

const selectedTransaksiDate = ref(null)
const selectedPengirimanDate = ref(null)

const emit = defineEmits(['editData', 'refreshPage'])

const dataTransaksiFix = ref([])

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
    return 0
}

function formatTanggal(tanggal) {
    if(tanggal)
    {
        const [tahun, bulan, hari] = tanggal.split('-')
        return `${hari}/${bulan}/${tahun}`
    }

    return '-'
}

const resetDataStats = () =>
{
    dataStats.value.jumlah_pesan = 0
    dataStats.value.total_bayar = 0
    dataStats.value.stok10kg.jumlah = 0
    dataStats.value.stok10kg.total_harga = 0
    dataStats.value.stok20kg.jumlah = 0
    dataStats.value.stok20kg.total_harga = 0
    dataStats.value.stok50kg.jumlah = 0
    dataStats.value.stok50kg.total_harga = 0
}

const setDataStats = () =>
{
    dataTransaksiFix.value.forEach(item => {
        dataStats.value.jumlah_pesan += item.jmlh
        dataStats.value.total_bayar += item.total_bayar
        dataStats.value.stok10kg.jumlah += item.stok10kg?.jumlah ?? 0
        dataStats.value.stok10kg.total_harga += item.stok10kg?.total_harga ?? 0
        dataStats.value.stok20kg.jumlah += item.stok20kg?.jumlah ?? 0
        dataStats.value.stok20kg.total_harga += item.stok20kg?.total_harga ?? 0
        dataStats.value.stok50kg.jumlah += item.stok50kg?.jumlah ?? 0
        dataStats.value.stok50kg.total_harga += item.stok50kg?.total_harga ?? 0
    })
}

const normalizeDate = (date) => {
  const d = new Date(date);
  d.setHours(0, 0, 0, 0);
  return d;
};

const resetData = () =>
{
    selectedIdBeras.value = null
    selectedIdProdusen.value = null
    selectedTransaksiDate.value = null
    selectedPengirimanDate.value = null

    isLoading.value = true
    dataTransaksiFix.value = props.dataTransaksi.map((p, i) => ({...p, nomor: i + 1}))

    nextTick(() => {
        setDataStats()
        isLoading.value = false
    })
}

const filterByTransaksiDate = () =>
{
    resetDataStats()

    isLoading.value = true

    const start = normalizeDate(selectedTransaksiDate.value[0])

    const end = normalizeDate(selectedTransaksiDate.value[1]) ?? start

    const sorted  = props.dataTransaksi.filter(item => normalizeDate(item.tgl_transaksi) >= start && normalizeDate(item.tgl_transaksi) <= end).map((p, i) => ({...p, nomor: i + 1}))

    nextTick(() =>
    {
        dataTransaksiFix.value = sorted
        setDataStats()
    })

    isLoading.value = false
}

const filterByPengirimanDate = () =>
{
    resetDataStats()

    isLoading.value = true

    const start = normalizeDate(selectedPengirimanDate.value[0])

    const end = normalizeDate(selectedPengirimanDate.value[1]) ?? start

    const sorted  = props.dataTransaksi.filter(item => normalizeDate(item.tgl_pengiriman) >= start && normalizeDate(item.tgl_pengiriman) <= end).map((p, i) => ({...p, nomor: i + 1}))


    nextTick(() =>
    {
        dataTransaksiFix.value = sorted
        setDataStats()
    })

    isLoading.value = false
}

const setDaftarBeras = () =>
{
    const seen = new Set();
    const dataSource = dataTransaksiFix.value ?? props.dataTransaksi

    daftarBeras.value =  dataSource.filter(item => {
        if (seen.has(item.pemesanan.beras.id_beras)) return false;
        seen.add(item.pemesanan.beras.id_beras);
        return true;
    }).map(item => ({
        id_beras: item.pemesanan.beras.id_beras,
        nama_beras: item.pemesanan.beras.nama_beras
    }));
}

const setDaftarProdusen = () =>
{
    const seen = new Set();
    daftarProdusen.value =  props.dataTransaksi.filter(item => {
        if (seen.has(item.pemesanan.id_produsen)) return false;
        seen.add(item.pemesanan.id_produsen);
        return true;
    }).map(item => ({
        id_produsen: item.pemesanan.produsen.id_produsen,
        nama_produsen: item.pemesanan.produsen.nama_produsen
    }));
}

const filterByBeras = () =>
{
    resetDataStats()

    isLoading.value = true

    if(selectedIdBeras.value)
    {
        const sorted = props.dataTransaksi.filter(item => item.pemesanan.beras.id_beras === selectedIdBeras.value).map((p, i) => ({...p, nomor: i + 1}))

        nextTick(() => {
            dataTransaksiFix.value = sorted
            setDataStats()
            isLoading.value = false
        })
    }

    else resetData()
}

const filterByProdusen = () =>
{
    resetDataStats()

    isLoading.value = true

    if(selectedIdProdusen.value)
    {
        const sorted = props.dataTransaksi.filter(item => item.pemesanan.id_produsen === selectedIdProdusen.value).map((p, i) => ({...p, nomor: i + 1}))

        nextTick(() => {
            dataTransaksiFix.value = sorted
            setDataStats()
            setDaftarBeras()
            isLoading.value = false
        })
    }

    else resetData()
}

const switchStatus = status =>
{
    switch(status)
    {
        case 'Pending' : return 'warn';

        case 'Dijadwalkan' : return 'info';

        case 'Dalam Pengiriman' : return 'info';

        case 'Selesai' : return 'success';
    }
}

const switchStatusBayar = status =>
{
    switch(status)
    {
        case 'Pending' : return 'warn';

        case 'Selesai' : return 'success';
    }
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
            router.post(route('transaksi.laporan.cetak'), {
                data : dataTransaksiFix.value,
                fromRoute : route().current(),
            })
        },
    })
}

</script>

<template>
    <div class="flex flex-col">
        <DataTable :loading="isLoading" :value="dataTransaksiFix" dataKey="index" class="shadow border border-amber-500 rounded-lg overflow-hidden" showGridlines removable-sort striped-rows scrollable v-model:filters="filters">
            <template #header>
                <div class="flex flex-col gap-y-2">
                    <!-- basic filter -->
                    <div class="flex justify-between items-center gap-x-2">
                        <IconField class="w-full">
                            <InputIcon>
                                <i class="pi pi-search me-4" />
                            </InputIcon>
                            <InputText v-model="filters['global'].value" placeholder="Cari Data Transaksi" size="small" fluid/>
                        </IconField>
                        <Button @click="cetakLaporan" icon="pi pi-print" severity="danger" variant="outlined" label="PDF" size="small" />
                    </div>
                    <!-- custom filter -->
                    <div class="flex items-center gap-x-2">
                        <!-- filter by beras -->
                        <Select :show-clear="true" @value-change="filterByBeras()" v-model="selectedIdBeras" placeholder="Filter Beras" :options="daftarBeras" optionLabel="nama_beras" optionValue="id_beras" fluid>
                            <template #dropdownicon>
                                <i class="pi pi-box" />
                            </template>
                        </Select>
                        <!-- filter by produsen -->
                        <Select :show-clear="true" @value-change="filterByProdusen()" v-model="selectedIdProdusen" placeholder="Filter Produsen" :options="daftarProdusen" optionLabel="nama_produsen" optionValue="id_produsen" fluid>
                            <template #dropdownicon>
                                <i class="pi pi-users" />
                            </template>
                        </Select>
                        <!-- filter by tanggal -->
                        <FloatLabel variant="on">
                            <DatePicker class="w-[18rem]" show-button-bar @clear-click="resetData()" @date-select="filterByTransaksiDate" showIcon iconDisplay="input" inputId="filterTanggal" v-model="selectedTransaksiDate" selectionMode="range" :manual-input="false" date-format="yy-mm-dd"  fluid/>
                            <label for="filterTanggal">Filter Tanggal Transaksi</label>
                        </FloatLabel>
                        <FloatLabel variant="on">
                            <DatePicker class="w-[18rem]" show-button-bar @clear-click="resetData()" @date-select="filterByPengirimanDate" showIcon iconDisplay="input" inputId="filterTanggalPengiriman" v-model="selectedPengirimanDate" selectionMode="range" :manual-input="false" date-format="yy-mm-dd"  fluid/>
                            <label for="filterTanggalPengiriman">Filter Tanggal Pengiriman</label>
                        </FloatLabel>
                    </div>
                </div>
            </template>
            <template #footer>
                <span>Jumlah Data Transaksi ({{ dataTransaksiFix?.length }})</span>
            </template>
            <template #loading>
                <span class="flex justify-center">Sedang Memuat Data...</span>
            </template>
            <template #empty>
                <span class="flex justify-center">Tidak Ada Transaksi</span>
            </template>
            <ColumnGroup type="header">
                <Row>
                    <Column sortable field="nomor" header="No" frozen rowspan="2"/>
                    <Column sortable field="pemesanan.beras.nama_beras" header="Nama Beras" style="min-width: 150px;" frozen rowspan="2"/>
                    <Column filter-field="pemesanan.produsen.nama_produsen" header="Produsen" style="min-width: 150px;" rowspan="2"/>
                    <Column sortable field="tgl_transaksi" header="Tanggal Transaksi" style="min-width: 200px;" rowspan="2"/>
                    <Column field="jmlh" header="Jumlah Pesan" style="min-width: 150px;" rowspan="2"/>
                    <Column header="10kg" colspan="2"/>
                    <Column header="20kg" colspan="2"/>
                    <Column header="50kg" colspan="2"/>
                    <Column header="Total Bayar" style="min-width: 100px;" rowspan="2"/>
                    <Column header="Bukti Bayar" style="min-width: 150px;" rowspan="2"/>
                    <Column header="Status Pembayaran" style="min-width: 150px;" rowspan="2"/>
                    <Column header="Status Pengiriman" style="min-width: 150px;" rowspan="2"/>
                    <Column field="tgl_pengiriman" sortable header="Tanggal Pengiriman" style="min-width: 100px;" rowspan="2"/>
                    <Column field="catatan" header="Catatan" style="min-width: 150px;" rowspan="2"/>
                </Row>
                <Row>
                    <!-- 10kg -->
                    <Column sortable field="stok10kg.jumlah" header="Jumlah"/>
                    <Column sortable field="stok10kg.total_bayar" header="Total Harga" style="min-width: 140px;"/>
                    <!-- 20kg -->
                    <Column sortable field="stok20kg.jumlah" header="Jumlah"/>
                    <Column sortable field="stok20kg.total_bayar" header="Total Harga" style="min-width: 140px;"/>
                    <!-- 50kg -->
                    <Column sortable field="stok50kg.jumlah" header="Jumlah"/>
                    <Column sortable field="stok50kg.total_bayar" header="Total Harga" style="min-width: 140px;"/>
                </Row>
            </ColumnGroup>
            <Column field="nomor" frozen/>
            <Column frozen>
                <template #body="{data}">
                    {{ data.pemesanan?.beras?.nama_beras }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ data.pemesanan?.produsen?.nama_produsen }}
                </template>
            </Column>
            <Column sortable>
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_transaksi) ?? 'Belum dijadwalkan' }}</span>
                </template>
            </Column>
            <Column field="jmlh"/>
            <!-- 10kg -->
            <Column>
                <template #body="{data}">
                    {{ formatDecimal(data.stok10kg?.jumlah) }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ formatRupiah(data.stok10kg?.total_harga) }}
                </template>
            </Column>
            <!-- 20kg -->
            <Column>
                <template #body="{data}">
                    {{ formatDecimal(data.stok20kg?.jumlah) }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ formatRupiah(data.stok20kg?.total_harga) }}
                </template>
            </Column>
            <!-- 50kg -->
            <Column>
                <template #body="{data}">
                    {{ formatDecimal(data.stok50kg?.jumlah) }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ formatRupiah(data.stok50kg?.total_harga) }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    {{ formatRupiah(data.total_bayar) }}
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    <div class="size-10 overflow-hidden border rounded" v-if="data?.bukti_bayar">
                        <Image :src="data?.bukti_bayar" class="size-full" preview />
                    </div>
                    <Tag severity="warn" value="Belum Upload" v-else/>
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    <Tag :value="data.status_pembayaran" :severity="switchStatusBayar(data.status_pembayaran)"/>
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    <Tag :value="data.status_pengiriman" :severity="switchStatus(data.status_pengiriman)"/>
                </template>
            </Column>
            <Column>
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_pengiriman) ??'Belum dijadwalkan'  }}</span>
                </template>
            </Column>
            <Column field="catatan"/>
            <ColumnGroup type="footer">
                <Row>
                    <Column frozen/>
                    <Column frozen/>
                    <Column footer="Total :" footerStyle="text-align:right" colspan="2"/>
                    <Column :footer="dataStats.jumlah_pesan"/>
                    <!-- 10kg -->
                    <Column :footer="dataStats.stok10kg?.jumlah"/>
                    <Column :footer="formatRupiah(dataStats.stok10kg?.total_harga)"/>
                    <!-- 20kg -->
                    <Column :footer="dataStats.stok20kg?.jumlah"/>
                    <Column :footer="formatRupiah(dataStats.stok20kg?.total_harga)"/>
                    <!-- 50kg -->
                    <Column :footer="dataStats.stok50kg?.jumlah"/>
                    <Column :footer="formatRupiah(dataStats.stok50kg?.total_harga)"/>
                    <Column colspan="2" :footer="formatRupiah(dataStats.total_bayar)"/>
                    <Column colspan="4"/>
                </Row>
            </ColumnGroup>
        </DataTable>
    </div>
</template>

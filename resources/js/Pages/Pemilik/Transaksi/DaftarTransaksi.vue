<script setup>
import { nextTick, onMounted, ref } from 'vue'
import { router, useForm } from '@inertiajs/vue3'

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

const totalStats = ref(0)

const isLoading = ref(false)

const daftarBeras = ref([])
const daftarProdusen = ref([])

const selectedIdBeras = ref(null)
const selectedIdProdusen = ref(null)

const selectedTransaksiDate = ref(null)
const selectedPengirimanDate = ref(null)

const emit = defineEmits(['editData', 'refreshPage'])

const dataTransaksiFix = ref([])

function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
    }).format(angka);
}

function formatTanggal(tanggal) {
    if(tanggal)
    {
        const [tahun, bulan, hari] = tanggal.split('-')
        return `${hari}/${bulan}/${tahun}`
    }
}

const setDataStats = () =>
{
    dataTransaksiFix.value.forEach(item => {
        totalStats.value += item.total_harga
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
    totalStats.value = 0
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
    totalStats.value = 0
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
    totalStats.value = 0

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
    totalStats.value = 0

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

        case 'Transaksi Selesai' : return 'success';
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
            <Column sortable field="nomor" header="No" frozen/>
            <Column filter-field="pemesanan.beras.nama_beras" header="Nama Beras" style="min-width: 150px;" frozen>
                <template #body="{data}">
                    {{ data.pemesanan?.beras?.nama_beras }}
                </template>
            </Column>
            <Column filter-field="pemesanan.produsen.nama_produsen" header="Produsen" style="min-width: 150px;">
                <template #body="{data}">
                    {{ data.pemesanan?.produsen?.nama_produsen }}
                </template>
            </Column>
            <Column header="Tanggal Transaksi" style="min-width: 100px;">
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_transaksi) ?? 'Belum dijadwalkan' }}</span>
                </template>
            </Column>
            <Column field="jmlh" header="Jumlah Pesan" style="min-width: 100px;"/>
            <Column filter-field="harga_satuan" header="Harga Satuan" style="min-width: 100px;">
                <template #body="{data}">
                    {{ formatRupiah(data.harga_satuan) }}
                </template>
            </Column>
            <Column header="Total Harga" style="min-width: 100px;">
                <template #body="{data}">
                    {{ formatRupiah(data.total_harga) }}
                </template>
            </Column>
            <Column header="Bukti Bayar" style="min-width: 150px;">
                <template #body="{data}">
                    <div class="size-10 overflow-hidden border rounded" v-if="data?.bukti_bayar">
                        <Image :src="data?.bukti_bayar" class="size-full" preview />
                    </div>
                    <Tag severity="warn" value="Silahkan Upload Bukti Pembayaran" v-else/>
                </template>
            </Column>
            <Column header="Status Pembayaran" style="min-width: 150px;">
                <template #body="{data}">
                    <Tag :value="data.status_pembayaran" :severity="switchStatusBayar(data.status_pembayaran)"/>
                </template>
            </Column>
            <Column header="Status Pengiriman" style="min-width: 150px;">
                <template #body="{data}">
                    <Tag :value="data.status_pengiriman" :severity="switchStatus(data.status_pengiriman)"/>
                </template>
            </Column>
            <Column header="Tanggal Pengiriman" style="min-width: 100px;">
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_pengiriman) ??'Belum dijadwalkan'  }}</span>
                </template>
            </Column>
            <Column field="catatan" header="Catatan" style="min-width: 150px;"/>
            <ColumnGroup type="footer">
                <Row>
                    <Column footer="Total :" colspan="6" footerStyle="text-align:right"/>
                    <Column :footer="formatRupiah(totalStats)" colspan="7"/>
                </Row>
            </ColumnGroup>
        </DataTable>
    </div>
</template>

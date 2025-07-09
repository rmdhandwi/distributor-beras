<script setup>
import { nextTick, onMounted, ref } from 'vue'

import {FilterMatchMode} from '@primevue/core/api'
import { router, useForm } from '@inertiajs/vue3'
import { useConfirm, useToast } from 'primevue'

onMounted(() =>
{
    dataTransaksiFix.value = props.dataTransaksi
    daftarNoRekFix.value = props.daftarNoRek
    setDaftarBeras()
    setDataStats()
})

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
})

const props = defineProps({
    dataTransaksi : Object,
    daftarNoRek : Object,
})

const emit = defineEmits(['refreshPage'])

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

const selectedIdBeras = ref(null)
const selectedIdProdusen = ref(null)

const selectedTransaksiDate = ref(null)
const selectedPengirimanDate = ref(null)

const dataTransaksiFix = ref([])
const daftarNoRekFix = ref([])

const showPengiriman = ref(false)

const transaksiForm = useForm({
    id_transaksi : null,
    nama_pesanan : null,
    total_bayar : null,
    rekening : null,
    jumlah_pesanan : null,
    tgl_transaksi : null,
    tgl_pengiriman : null,
    status_pengiriman : null,
    bukti_bayar : null,
    status_pembayaran : null,
})

const statusPembayaran = [
    {status : 'Selesai'},
]

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
        dataStats.value.stok10kg.jumlah += item.stok10kg?.jumlah ?? ''
        dataStats.value.stok10kg.total_harga += item.stok10kg?.total_harga ?? ''
        dataStats.value.stok20kg.jumlah += item.stok20kg?.jumlah ?? ''
        dataStats.value.stok20kg.total_harga += item.stok20kg?.total_harga ?? ''
        dataStats.value.stok50kg.jumlah += item.stok50kg?.jumlah ?? ''
        dataStats.value.stok50kg.total_harga += item.stok50kg?.total_harga ?? ''
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

const setRekening = idRekening =>
{
    const getRekening = props.daftarNoRek.find(data => data.id_rekening === idRekening)

    if(getRekening) return getRekening?.nama_rekening+'-'+getRekening?.no_rekening
    else return 'Belum melakukan pembayaran'
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

const setJadwal = id_transaksi =>
{
    const dataFilter = props.dataTransaksi.filter((transaksi) => transaksi.id_transaksi === id_transaksi)
    transaksiForm.id_transaksi = id_transaksi
    transaksiForm.nama_pesanan = dataFilter[0]?.pemesanan?.beras?.nama_beras
    transaksiForm.jumlah_pesanan = dataFilter[0]?.jmlh
    transaksiForm.total_bayar = formatRupiah(dataFilter[0]?.total_bayar)
    transaksiForm.rekening = dataFilter[0]?.rekening
    transaksiForm.tgl_transaksi = formatTanggal(dataFilter[0]?.tgl_transaksi)
    transaksiForm.bukti_bayar = dataFilter[0]?.bukti_bayar

    showPengiriman.value = true
}
const cancelJadwal = () =>
{
    confirm.require({
        message: 'Batalkan penjadwalan?',
        header: 'Peringatan',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Lanjut',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Batalkan',
            severity : 'danger',
        },
        accept : () => {
            toast.add({ severity: 'info', summary: 'Notifikasi', detail: 'Membatalkan...', life: 2000 });
            showPengiriman.value = false
            transaksiForm.reset()
        },
    });
}
const confirmJadwal = () =>
{
    confirm.require({
        message: 'Konfirmasi penjadwalan?',
        header: 'Peringatan',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Batal',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Konfirmasi',
            severity : 'success',
        },
        accept : () => {
            toast.add({ severity: 'info', summary: 'Notifikasi', detail: 'Mengkonfirmasi...', life: 2000 });
            transaksiForm.post(route('produsen.transaksi.konfirmJadwal'), {
                onError : () => {
                    toast.add({
                        severity : 'error',
                        summary : 'Notifikasi',
                        detail : 'Terjadi kesalahan',
                        life : 2000,
                    })
                },
                onSuccess : () => {
                    showPengiriman.value = false
                    transaksiForm.reset()
                    transaksiForm.clearErrors()
                    emit('refreshPage')
                }
            })
        },
    });
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
    <!-- Dialog preview Bukti -->
    <Dialog :closable="false" v-model:visible="showPengiriman" modal header="Jadwalkan Pengiriman" :style="{ width: '25rem' }">
        <form @submit.prevent class="flex flex-col gap-4 mt-4" autocomplete="off">
            <!-- Nama pesanan -->
            <div>
                <FloatLabel variant="on">
                    <InputText id="nama_pesanan" v-model="transaksiForm.nama_pesanan" disabled fluid/>
                    <label for="nama_pesanan">Nama Pesanan</label>
                </FloatLabel>
            </div>
            <!-- Jumlah pesanan -->
            <div>
                <FloatLabel variant="on">
                    <InputText id="jumlah_pesanan" v-model="transaksiForm.jumlah_pesanan" disabled fluid/>
                    <label for="jumlah_pesanan">Jumlah Pesan</label>
                </FloatLabel>
            </div>
            <!-- Total Harga pesanan -->
            <div>
                <FloatLabel variant="on">
                    <InputText id="total_bayar" v-model="transaksiForm.total_bayar" disabled fluid/>
                    <label for="total_bayar">Total Harga</label>
                </FloatLabel>
            </div>
            <!-- Tanggal Transaksi pesanan -->
            <div>
                <FloatLabel variant="on">
                    <InputText id="tgl_transaksi" v-model="transaksiForm.tgl_transaksi" disabled fluid/>
                    <label for="tgl_transaksi">Tanggal Transaksi</label>
                </FloatLabel>
            </div>

            <!-- Rekening pesanan -->
            <div>
                <FloatLabel variant="on">
                    <InputText id="tgl_transaksi" :model-value="setRekening(transaksiForm.rekening)"  disabled fluid/>
                    <label for="tgl_transaksi">Rekening</label>
                </FloatLabel>
            </div>
            <!-- Bukti Bayar -->
            <div>
                <span>Bukti Pembayaran</span>
                <div class="size-40 overflow-hidden border rounded mt-2">
                    <Image :src="transaksiForm.bukti_bayar" class="size-full" preview />
                </div>
            </div>
            <!-- Tanggal Pengiriman -->
            <div>
                <FloatLabel variant="on">
                    <DatePicker v-model="transaksiForm.tgl_pengiriman" inputId="tgl_pengiriman" showIcon iconDisplay="input" dateFormat="dd-mm-yy" fluid/>
                    <label for="tgl_pengiriman">Tanggal Pengiriman</label>
                </FloatLabel>
                <span class="text-red-500" v-if="transaksiForm.errors.tgl_pengiriman"> {{ transaksiForm.errors.tgl_pengiriman }} </span>
            </div>

            <!-- Status Pembayaran -->
            <div>
                <FloatLabel variant="on">
                    <Select v-model="transaksiForm.status_pembayaran" inputId="status_pembayaran" :options="statusPembayaran" optionLabel="status" optionValue="status" fluid/>
                    <label for="status_pembayaran">Status Pembayaran</label>
                </FloatLabel>
                <Message size="small" severity="error" variant="simple">*Cek mutasi sebelum konfirmasi status pembayaran.</Message>
            </div>
            <div class="flex justify-end gap-2">
                <Button type="button" label="Batalkan" severity="danger" @click="cancelJadwal()"/>
                <Button type="button" label="Upload" severity="success" @click="confirmJadwal()" :disabled="!transaksiForm.tgl_pengiriman|| !transaksiForm.status_pembayaran"/>
            </div>
        </form>
    </Dialog>
    <!-- Dialog preview Bukti Selesai -->
    <div class="flex flex-col">
        <DataTable :value="dataTransaksiFix" dataKey="index" class="shadow border border-amber-500 rounded-lg overflow-hidden" showGridlines removable-sort striped-rows scrollable v-model:filters="filters">
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
                <span>Jumlah Data Transaksi ({{ props.dataTransaksi?.length }})</span>
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
                    <Column sortable field="tgl_transaksi" header="Tanggal Transaksi" style="min-width: 200px;" rowspan="2"/>
                    <Column field="jmlh" header="Jumlah Pesan" style="min-width: 150px;" rowspan="2"/>
                    <Column header="10kg" colspan="2"/>
                    <Column header="20kg" colspan="2"/>
                    <Column header="50kg" colspan="2"/>
                    <Column header="Total Bayar" style="min-width: 100px;" rowspan="2"/>
                    <Column header="Rekening" style="min-width: 100px;" rowspan="2"/>
                    <Column header="Bukti Bayar" style="min-width: 150px;" rowspan="2"/>
                    <Column header="Status Pembayaran" style="min-width: 150px;" rowspan="2"/>
                    <Column header="Status Pengiriman" style="min-width: 150px;" rowspan="2"/>
                    <Column field="tgl_pengiriman" sortable header="Tanggal Pengiriman" style="min-width: 100px;" rowspan="2"/>
                    <Column field="catatan" header="Catatan" style="min-width: 150px;" rowspan="2"/>
                    <Column header="Action" frozen align-frozen="right" rowspan="2"/>
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
                    {{ setRekening(data.rekening) }}
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
            <Column frozen align-frozen="right">
                <template #body="{data}">
                    <div class="flex place-content-center gap-2">
                        <OverlayBadge severity="info" v-if="data.bukti_bayar&&!data.tgl_pengiriman">
                            <Button @click="setJadwal(data.id_transaksi)" severity="info" variant="outlined" size="small" icon="pi pi-truck"/>
                        </OverlayBadge>
                        <Tag value="Selesai" icon="pi pi-check" severity="success" v-else-if="data.bukti_bayar&&data.tgl_pengiriman"/>
                    </div>
                </template>
            </Column>
            <ColumnGroup type="footer">
                <Row>
                    <Column frozen/>
                    <Column frozen/>
                    <Column footer="Total :" footerStyle="text-align:right"/>
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
                    <Column frozen align-frozen="right"/>
                </Row>
            </ColumnGroup>
        </DataTable>
    </div>
</template>

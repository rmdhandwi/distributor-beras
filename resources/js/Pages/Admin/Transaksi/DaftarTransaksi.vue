<script setup>
import { nextTick, onMounted, ref } from 'vue'
import { router, useForm } from '@inertiajs/vue3'

import {FilterMatchMode} from '@primevue/core/api'
import { useConfirm, useToast } from 'primevue'

onMounted(() =>
{
    console.log(props.dataTransaksi)
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

const showPengiriman = ref(false)

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

const previewImg = ref(null)
const showPreviewBukti = ref(false)

const transaksiForm = useForm({
    id_transaksi : null,
    nama_pesanan : null,
    total_bayar : null,
    jumlah_pesanan : null,
    tgl_transaksi : null,
    tgl_pengiriman : null,
    status_pengiriman : null,
    bukti_bayar : null,
    status_pembayaran : null,
    status_pengiriman : null,
    catatan : null,
})

const statusPengiriman = [
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
    dataStats.value = 0
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
    dataStats.value = 0
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
    dataStats.value = 0

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
    dataStats.value = 0

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

const setSelesaiTransaksi = id_transaksi =>
{
    const dataFilter = props.dataTransaksi.filter((transaksi) => transaksi.id_transaksi === id_transaksi)
    transaksiForm.id_transaksi = id_transaksi
    transaksiForm.nama_pesanan = dataFilter[0]?.pemesanan?.beras?.nama_beras
    transaksiForm.jumlah_pesanan = dataFilter[0]?.jmlh
    transaksiForm.total_bayar = formatRupiah(dataFilter[0]?.total_bayar)
    transaksiForm.tgl_transaksi = formatTanggal(dataFilter[0]?.tgl_transaksi)
    transaksiForm.tgl_pengiriman = formatTanggal(dataFilter[0]?.tgl_pengiriman)
    transaksiForm.bukti_bayar = dataFilter[0]?.bukti_bayar
    transaksiForm.status_pembayaran = dataFilter[0]?.status_pembayaran
    transaksiForm.status_pengiriman = dataFilter[0]?.status_pengiriman
    transaksiForm.catatan = dataFilter[0]?.catatan

    showPengiriman.value = true
}

const uploadBukti = id_transaksi =>
{
    const dataFilter = props.dataTransaksi.filter((transaksi) => transaksi.id_transaksi === id_transaksi)
    transaksiForm.id_transaksi = id_transaksi
    transaksiForm.nama_pesanan = dataFilter?.pemesanan?.beras?.nama_beras
    showPreviewBukti.value = true
}

const onUpload = (e) =>
{
    toast.add({
            severity: 'info',
            summary: 'Notifikasi',
            detail: 'Proses Upload...',
            life: 2000
    })

    setTimeout(() => {

        transaksiForm.bukti_bayar = e.files[0]

        if(transaksiForm.bukti_bayar?.size < 1000000)
        {
            transaksiForm.clearErrors('bukti_bayar')
            toast.add({
                severity: 'info',
                summary: 'Notifikasi',
                detail: 'foto terupload!',
                life: 2000
            })
        }
        else
        {
            toast.add({
                severity: 'danger',
                summary: 'Notifikasi',
                detail: 'Ukuran Melebihi 1Mb!',
                life: 2000
            })
        }
        const reader = new FileReader();

        reader.onloadend = async (e) => {
            previewImg.value = e.target.result;
        };

        reader.readAsDataURL(transaksiForm.bukti_bayar);
    }, 2000)

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

const confirmTransaksi = () =>
{
    confirm.require({
        message: 'Upload Bukti Bayar?',
        header: 'Peringatan',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Batalkan',
            severity: 'danger',
            outlined: true
        },
        acceptProps: {
            label: 'Upload',
        },
        accept : () => {
            toast.add({ severity: 'info', summary: 'Notifikasi', detail: 'Mengupload...', life: 1000 });
            transaksiForm.post(route('admin.transaksi.uploadBukti'), {
                onError : () => {
                    toast.add({
                        severity : 'error',
                        summary : 'Notifikasi',
                        detail : 'Terjadi kesalahan',
                        life : 2000,
                    })
                },
                onSuccess : () => {
                    showPreviewBukti.value = false
                    transaksiForm.reset()
                    transaksiForm.clearErrors()
                    emit('refreshPage')
                }
            })

        },
    });
}

const cancelTransaksiSelesai = () =>
{
    showPengiriman.value = false
    transaksiForm.reset()
}
const confirmTransaksiSelesai = () =>
{
    confirm.require({
        message: 'Konfirmasi transaksi telah selesai?',
        header: 'Peringatan',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Batalkan',
            severity: 'danger',
            outlined: true
        },
        acceptProps: {
            label: 'Ya',
        },
        accept : () => {
            toast.add({ severity: 'info', summary: 'Notifikasi', detail: 'Mengkonfirmasi...', life: 1000 });
            transaksiForm.post(route('admin.transaksi.selesai'), {
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

const cancelUpload = () =>
{
    previewImg.value = null
    showPreviewBukti.value = false
    transaksiForm.reset()
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
    <Dialog v-model:visible="showPreviewBukti" modal header="Preview Bukti" :style="{ width: '25rem' }">
        <div class="w-full min-h-60 overflow-hidden border rounded mb-2">
            <Image :src="previewImg" class="size-full" preview/>
        </div>
        <div class="flex justify-end gap-2">
            <FileUpload mode="basic" accept=".jpg,.jpeg,.png"  invalidFileSizeMessage="Ukuran File Melebihi 1Mb" @uploader="onUpload($event)" auto customUpload severity="info" chooseLabel="Upload Bukti" fluid/>
            <Button type="button" label="Batalkan" severity="danger" @click="cancelUpload"/>
            <Button type="button" label="Upload" severity="success" @click="confirmTransaksi()" :disabled="transaksiForm.bukti_bayar===null"/>
        </div>
    </Dialog>
    <!-- Dialog preview Bukti Selesai -->

    <!-- Dialog konfirmasi transaksi -->
    <Dialog :closable="false" v-model:visible="showPengiriman" modal header="Jadwalkan Pengiriman" :style="{ width: '25rem' }">
        <form @submit.prevent class="flex flex-col gap-4 mt-4" autocomplete="off">
            <!-- Nama pesanan -->
            <div>
                <FloatLabel variant="on">
                    <InputText id="nama_pesanan" v-model="transaksiForm.nama_pesanan" readonly fluid/>
                    <label for="nama_pesanan">Nama Pesanan</label>
                </FloatLabel>
            </div>
            <!-- Jumlah pesanan -->
            <div>
                <FloatLabel variant="on">
                    <InputText id="jumlah_pesanan" v-model="transaksiForm.jumlah_pesanan" readonly fluid/>
                    <label for="jumlah_pesanan">Jumlah Pesan</label>
                </FloatLabel>
            </div>
            <!-- Total Harga pesanan -->
            <div>
                <FloatLabel variant="on">
                    <InputText id="total_bayar" v-model="transaksiForm.total_bayar" readonly fluid/>
                    <label for="total_bayar">Total Bayar</label>
                </FloatLabel>
            </div>
            <!-- Tanggal Transaksi pesanan -->
            <div>
                <FloatLabel variant="on">
                    <InputText id="tgl_transaksi" v-model="transaksiForm.tgl_transaksi" readonly fluid/>
                    <label for="tgl_transaksi">Tanggal Transaksi</label>
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
                    <DatePicker v-model="transaksiForm.tgl_pengiriman" inputId="tgl_pengiriman" readonly showIcon iconDisplay="input" dateFormat="dd-mm-yy" fluid/>
                    <label for="tgl_pengiriman">Tanggal Pengiriman</label>
                </FloatLabel>
                <span class="text-red-500" v-if="transaksiForm.errors.tgl_pengiriman"> {{ transaksiForm.errors.tgl_pengiriman }} </span>
            </div>

            <!-- Status Pembayaran -->
            <div>
                <FloatLabel variant="on">
                    <InputText v-model="transaksiForm.status_pembayaran" inputId="status_pembayaran"readonly fluid/>
                    <label for="status_pembayaran">Status Pembayaran</label>
                </FloatLabel>
            </div>

            <div>
                <FloatLabel variant="on">
                    <Textarea id="on_label" v-model="transaksiForm.catatan" rows="5" cols="30" style="resize: none" fluid />
                    <label for="on_label">Catatan</label>
                </FloatLabel>
                <span class="text-red-500" v-if="transaksiForm.errors.catatan"> {{ transaksiForm.errors.catatan }} </span>
            </div>

            <!-- Status Pembayaran -->
            <div>
                <FloatLabel variant="on">
                    <Select v-model="transaksiForm.status_pengiriman" inputId="status_pengiriman" :options="statusPengiriman" optionLabel="status" optionValue="status" fluid/>
                    <label for="status_pengiriman">Status Pengiriman</label>
                </FloatLabel>
            </div>
            <div class="flex justify-end gap-2">
                <Button type="button" label="Batalkan" severity="danger" @click="cancelTransaksiSelesai()"/>
                <Button type="button" label="Konfirmasi Selesai" severity="success" @click="confirmTransaksiSelesai()"/>
            </div>
        </form>
    </Dialog>

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
            <Column header="Action" frozen align-frozen="right">
                <template #body="{data}">
                    <div class="flex place-content-center gap-2">
                        <Button @click="uploadBukti(data.id_transaksi)" severity="info" size="small" icon="pi pi-camera" :disabled="data.bukti_bayar" v-if="!data.bukti_bayar"/>
                        <Button severity="success" @click="setSelesaiTransaksi(data.id_transaksi)" size="small" label="Konfirmasi" v-if="data.status_pengiriman==='Dijadwalkan'"/>
                        <Tag icon="pi pi-check" severity="success" v-else/>
                    </div>
                </template>
            </Column>
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
                    <Column frozen align-frozen="right"/>
                </Row>
            </ColumnGroup>
        </DataTable>
    </div>
</template>

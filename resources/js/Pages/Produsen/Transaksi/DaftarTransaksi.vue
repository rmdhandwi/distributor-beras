<script setup>
import { ref } from 'vue'

import {FilterMatchMode} from '@primevue/core/api'
import { useForm } from '@inertiajs/vue3'
import { useConfirm, useToast } from 'primevue'

const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS }
})

const props = defineProps({
    dataTransaksi : Object,
})

const emit = defineEmits(['refreshPage'])

const toast = useToast()
const confirm = useConfirm()

const showPengiriman = ref(false)

const transaksiForm = useForm({
    id_transaksi : null,
    nama_pesanan : null,
    total_harga : null,
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

const setJadwal = id_transaksi =>
{
    const dataFilter = props.dataTransaksi.filter((transaksi) => transaksi.id_transaksi === id_transaksi)
    transaksiForm.id_transaksi = id_transaksi
    transaksiForm.nama_pesanan = dataFilter[0]?.pemesanan?.beras?.nama_beras
    transaksiForm.jumlah_pesanan = dataFilter[0]?.jmlh
    transaksiForm.total_harga = formatRupiah(dataFilter[0]?.total_harga)
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
                    <InputText id="total_harga" v-model="transaksiForm.total_harga" disabled fluid/>
                    <label for="total_harga">Total Harga</label>
                </FloatLabel>
            </div>
            <!-- Tanggal Transaksi pesanan -->
            <div>
                <FloatLabel variant="on">
                    <InputText id="tgl_transaksi" v-model="transaksiForm.tgl_transaksi" disabled fluid/>
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
        <DataTable :value="props.dataTransaksi" dataKey="index" class="shadow border border-amber-500 rounded-lg overflow-hidden" showGridlines removable-sort striped-rows scrollable v-model:filters="filters">
            <template #header>
                <div class="flex justify-between items-center gap-x-2">
                    <IconField class="w-full">
                        <InputIcon>
                            <i class="pi pi-search me-4" />
                        </InputIcon>
                        <InputText v-model="filters['global'].value" placeholder="Cari Data Transaksi" size="small" fluid/>
                    </IconField>
                    <Button icon="pi pi-print" severity="contrast" variant="outlined" label="CSV" size="small" />
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
            <Column sortable field="nomor" header="No" frozen/>
            <Column sortable header="Nama Beras" style="min-width: 150px;" frozen>
                <template #body="{data}">
                    {{ data.pemesanan?.beras?.nama_beras }}
                </template>
            </Column>
            <Column sortable header="Produsen" style="min-width: 150px;">
                <template #body="{data}">
                    {{ data.pemesanan?.produsen?.nama_produsen }}
                </template>
            </Column>
            <Column sortable header="Tanggal Transaksi" style="min-width: 100px;">
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_transaksi) ?? 'Belum dijadwalkan' }}</span>
                </template>
            </Column>
            <Column field="jmlh" header="Jumlah Pesan" style="min-width: 100px;"/>
            <Column header="Harga Satuan" style="min-width: 100px;">
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
                    <Tag severity="warn" value="Belum Upload" v-else/>
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
            <Column sortable header="Tanggal Pengiriman" style="min-width: 100px;">
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_pengiriman) ??'Belum dijadwalkan'  }}</span>
                </template>
            </Column>
            <Column field="catatan" header="Catatan" style="min-width: 150px;"/>
            <Column header="Action" frozen align-frozen="right">
                <template #body="{data}">
                    <div class="flex place-content-center gap-2">
                        <OverlayBadge severity="info" v-if="data.bukti_bayar&&!data.tgl_pengiriman">
                            <Button @click="setJadwal(data.id_transaksi)" severity="info" variant="outlined" size="small" icon="pi pi-truck"/>
                        </OverlayBadge>
                        <!-- <Tag icon="pi pi-check" severity="success" v-else/> -->
                    </div>
                </template>
            </Column>
        </DataTable>
    </div>
</template>

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

const toast = useToast()
const confirm = useConfirm()

const emit = defineEmits(['editData', 'refreshPage'])

const previewImg = ref(null)
const showPreviewBukti = ref(false)

const transaksiForm = useForm({
    id_transaksi : null,
    nama_pesanan : null,
    bukti_bayar : null,
})

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

const cancelUpload = () =>
{
    previewImg.value = null
    showPreviewBukti.value = false
    transaksiForm.reset()
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
            <Column sortable header="Tanggal Pengiriman" style="min-width: 100px;">
                <template #body="{data}">
                    <span>{{ formatTanggal(data.tgl_pengiriman) ??'Belum dijadwalkan'  }}</span>
                </template>
            </Column>
            <Column field="catatan" header="Catatan" style="min-width: 150px;"/>
            <Column header="Action" frozen align-frozen="right">
                <template #body="{data}">
                    <div class="flex place-content-center gap-2">
                        <Button @click="uploadBukti(data.id_transaksi)" severity="info" size="small" icon="pi pi-camera" :disabled="data.bukti_bayar" v-if="!data.bukti_bayar"/>
                        <Tag icon="pi pi-check" severity="success" v-else/>
                    </div>
                </template>
            </Column>
        </DataTable>
    </div>
</template>

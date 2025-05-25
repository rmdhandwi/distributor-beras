<script setup>
import { onMounted, ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useConfirm, useToast } from 'primevue'

onMounted(() =>
{
    countTotalHarga()
    if(props.formType === 'Edit')
    {
        selectIdProdusen()
    }
})

const props = defineProps({
    formType : String,
    dataBeras : Object,
    dataPemesanan : Object,
    dataProdusen : Object,
})

const emit = defineEmits(['refreshPage'])

const confirm = useConfirm()
const toast = useToast()

const today = new Date()

const selectedBeras = ref(null)

const totalBayar = ref(0)

const pemesananForm = useForm({
    id_pemesanan: props.dataPemesanan?.[0]?.id_pemesanan ?? null,
    id_produsen: props.dataPemesanan?.[0]?.id_produsen ?? null,
    id_beras: props.dataPemesanan?.[0]?.id_beras ?? null,
    jmlh: props.dataPemesanan?.[0]?.jmlh ?? null,
    tgl_pemesanan: props.dataPemesanan?.[0]?.tgl_pemesanan ?? today,
    status_pesanan: props.dataPemesanan?.[0]?.status_pesanan ?? null,
    catatan: props.dataPemesanan?.[0]?.catatan ?? null,
    stok10kg : {
        id_detail_pemesanan : props.dataPemesanan?.[0]?.stok10kg?.id_detail_pemesanan ?? null,
        berat : '10',
        jumlah : props.dataPemesanan?.[0]?.stok10kg?.jumlah ?? 0,
        harga_satuan : props.dataPemesanan?.[0]?.stok10kg?.harga_satuan ?? 0,
        total_harga : props.dataPemesanan?.[0]?.stok10kg?.total_harga ?? 0,
    },
    stok20kg : {
        id_detail_pemesanan : props.dataPemesanan?.[0]?.stok20kg?.id_detail_pemesanan ?? null,
        berat : '20',
        jumlah : props.dataPemesanan?.[0]?.stok20kg?.jumlah ?? 0,
        harga_satuan : props.dataPemesanan?.[0]?.stok20kg?.harga_satuan ?? 0,
        total_harga : props.dataPemesanan?.[0]?.stok20kg?.total_harga ?? 0,
    },
    stok50kg : {
        id_detail_pemesanan : props.dataPemesanan?.[0]?.stok50kg?.id_detail_pemesanan ?? null,
        berat : '50',
        jumlah : props.dataPemesanan?.[0]?.stok50kg?.jumlah ?? 0,
        harga_satuan : props.dataPemesanan?.[0]?.stok50kg?.harga_satuan ?? 0,
        total_harga : props.dataPemesanan?.[0]?.stok50kg?.total_harga ?? 0,
    },
})

watch(
    () => [
        pemesananForm.stok10kg.jumlah,
        pemesananForm.stok20kg.jumlah,
        pemesananForm.stok50kg.jumlah,
    ],
    () => {
        countTotalHarga()
    }
)

function countTotalHarga ()
{
    pemesananForm.stok10kg.total_harga = pemesananForm.stok10kg.harga_satuan * pemesananForm.stok10kg.jumlah
    pemesananForm.stok20kg.total_harga = pemesananForm.stok20kg.harga_satuan * pemesananForm.stok20kg.jumlah
    pemesananForm.stok50kg.total_harga = pemesananForm.stok50kg.harga_satuan * pemesananForm.stok50kg.jumlah

    totalBayar.value = pemesananForm.stok10kg.total_harga + pemesananForm.stok20kg.total_harga + pemesananForm.stok50kg.total_harga
}


const selectIdProdusen = () =>
{
    if(props.formType === 'Create')
    {
        pemesananForm.stok10kg.jumlah = 0
        pemesananForm.stok20kg.jumlah = 0
        pemesananForm.stok50kg.jumlah = 0
    }

    selectedBeras.value = props.dataBeras.find( (beras) => beras.id_beras === pemesananForm.id_beras)

    if (selectedBeras.value) {
        pemesananForm.id_produsen = selectedBeras.value.id_produsen
        pemesananForm.stok10kg.harga_satuan = selectedBeras.value.stok10kg?.harga ?? 0
        pemesananForm.stok20kg.harga_satuan = selectedBeras.value.stok20kg?.harga ?? 0
        pemesananForm.stok50kg.harga_satuan = selectedBeras.value.stok50kg?.harga ?? 0
    } else {
        pemesananForm.id_produsen = null
    }
}

const submitPesan = Action =>
{

    pemesananForm.jmlh = pemesananForm.stok10kg.jumlah + pemesananForm.stok20kg.jumlah + pemesananForm.stok50kg.jumlah

    confirm.require({
        message: `${Action} Beras ${selectedBeras.value?.nama_beras ?? ''}?`,
        header: 'Peringatan',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Batal',
            severity: 'secondary',
            outlined: true,
        },
        acceptProps: {
            label: `${Action}`,
        },
        accept: () => {
            if(props.formType === 'Create')
            {
                pemesananForm.post(route('admin.pemesanan.store'), {
                    onError : () => {
                        toast.add({
                            severity : 'error',
                            summary : 'Notifikasi',
                            detail : 'Terjadi kesalahan',
                            life : 3000,
                        })
                    },
                    onSuccess : () => {
                        pemesananForm.reset()
                        pemesananForm.clearErrors()
                        emit('refreshPage')
                    }
                })
            }
            else pemesananForm.post(route('admin.pemesanan.update'), {
                onError : () => {
                    toast.add({
                        severity : 'error',
                        summary : 'Notifikasi',
                        detail : 'Terjadi kesalahan',
                        life : 3000,
                    })
                },
                onSuccess : () => {
                    pemesananForm.reset()
                    pemesananForm.clearErrors()
                    emit('refreshPage')
                }
            })
        },
    })
}

const confirmHapus = () => {
    confirm.require({
        message: 'Hapus dan Batalkan Data pemesanan?',
        header: 'Peringatan',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Batalkan',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Hapus',
            severity: 'danger'
        },
        accept : () => {
            toast.add({ severity: 'info', summary: 'Notifikasi', detail: 'Menghapus...', life: 2000 });
            pemesananForm.post(route('admin.pemesanan.destroy'), {
                onError : () => {
                    toast.add({
                        severity : 'error',
                        summary : 'Notifikasi',
                        detail : 'Terjadi kesalahan',
                        life : 3000,
                    })
                },
                onSuccess : () => {
                    pemesananForm.reset()
                    pemesananForm.clearErrors()
                    emit('refreshPage')
                }
            })

        },
    });
}

</script>

<template>
    <form @submit.prevent class="flex flex-col gap-4 mt-4" autocomplete="off">
        <!-- Beras -->
        <div v-if="props.formType==='Create'">
            <FloatLabel variant="on">
                <Select v-model="pemesananForm.id_beras" :options="props.dataBeras" option-label="nama_beras" option-value="id_beras" @change="selectIdProdusen" fluid>
                    <template #option="slotProps">
                        {{ slotProps.option.nama_beras +" ("+slotProps.option.produsen.nama_produsen+")"}}
                    </template>
                </Select>
                <label>Pilih Beras</label>
            </FloatLabel>
            <span class="text-red-500" v-if="pemesananForm.errors.id_beras"> {{ pemesananForm.errors.id_beras }} </span>
        </div>
        <div v-else>
            <FloatLabel variant="on">
                <InputText id="nama_beras" :default-value="props.dataPemesanan[0]?.beras.nama_beras" fluid disabled/>
                <label for="nama_beras">Pilih Beras</label>
            </FloatLabel>
            <span class="text-red-500" v-if="pemesananForm.errors.id_beras"> {{ pemesananForm.errors.id_beras }} </span>
        </div>

        <!-- 10kg -->
        <div class="flex items-center gap-x-12">
            <Tag value="10kg"/>

            <div>
                <FloatLabel variant="on">
                    <InputNumber mode="decimal" :disabled="!selectedBeras?.stok10kg?.jumlah" showButtons :min="0" :max="selectedBeras?.stok10kg?.jumlah" id="jumlah_10kg" v-model="pemesananForm.stok10kg.jumlah" fluid/>
                    <label for="jumlah_10kg">Jumlah Pesan</label>
                </FloatLabel>
                <span class="text-red-500" v-if="pemesananForm.errors['stok10kg.jumlah']"> {{ pemesananForm.errors['stok10kg.jumlah'] }} </span>
            </div>

            <div>
                <FloatLabel variant="on">
                    <InputNumber id="harga_10kg" v-model="pemesananForm.stok10kg.harga_satuan" readonly locale="id-ID" prefix="Rp" fluid/>
                    <label for="harga_10kg">Harga Satuan</label>
                </FloatLabel>
                <span class="text-red-500" v-if="pemesananForm.errors['stok10kg.harga_satuan']"> {{ pemesananForm.errors['stok10kg.harga_satuan'] }} </span>
            </div>

            <div>
                <FloatLabel variant="on">
                    <InputNumber id="harga_10kg" v-model="pemesananForm.stok10kg.total_harga" readonly locale="id-ID" prefix="Rp" fluid/>
                    <label for="harga_10kg">Total</label>
                </FloatLabel>
                <span class="text-red-500" v-if="pemesananForm.errors['stok10kg.total_harga']"> {{ pemesananForm.errors['stok10kg.total_harga'] }} </span>
            </div>
        </div>

        <!-- 20kg -->
        <div class="flex items-center gap-x-12">
            <Tag value="20kg"/>

            <div>
                <FloatLabel variant="on">
                    <InputNumber mode="decimal" :disabled="!selectedBeras?.stok20kg?.jumlah" showButtons :min="0" :max="selectedBeras?.stok20kg?.jumlah" id="jumlah_20kg" v-model="pemesananForm.stok20kg.jumlah" fluid/>
                    <label for="jumlah_20kg">Jumlah Pesan</label>
                </FloatLabel>
                <span class="text-red-500" v-if="pemesananForm.errors['stok20kg.jumlah']"> {{ pemesananForm.errors['stok20kg.jumlah'] }} </span>
            </div>

            <div>
                <FloatLabel variant="on">
                    <InputNumber id="harga_20kg" v-model="pemesananForm.stok20kg.harga_satuan" readonly locale="id-ID" prefix="Rp" fluid/>
                    <label for="harga_20kg">Harga Satuan</label>
                </FloatLabel>
                <span class="text-red-500" v-if="pemesananForm.errors['stok20kg.harga_satuan']"> {{ pemesananForm.errors['stok20kg.harga_satuan'] }} </span>
            </div>

            <div>
                <FloatLabel variant="on">
                    <InputNumber id="harga_20kg" v-model="pemesananForm.stok20kg.total_harga" readonly locale="id-ID" prefix="Rp" fluid/>
                    <label for="harga_20kg">Total</label>
                </FloatLabel>
                <span class="text-red-500" v-if="pemesananForm.errors['stok20kg.total_harga']"> {{ pemesananForm.errors['stok20kg.total_harga'] }} </span>
            </div>
        </div>

        <!-- 50kg -->
        <div class="flex items-center gap-x-12">
            <Tag value="50kg"/>

            <div>
                <FloatLabel variant="on">
                    <InputNumber mode="decimal" :disabled="!selectedBeras?.stok50kg?.jumlah"  showButtons :min="0" :max="selectedBeras?.stok50kg?.jumlah" id="jumlah_50kg" v-model="pemesananForm.stok50kg.jumlah" fluid/>
                    <label for="jumlah_50kg">Jumlah Pesan</label>
                </FloatLabel>
                <span class="text-red-500" v-if="pemesananForm.errors['stok50kg.jumlah']"> {{ pemesananForm.errors['stok50kg.jumlah'] }} </span>
            </div>

            <div>
                <FloatLabel variant="on">
                    <InputNumber id="harga_50kg" v-model="pemesananForm.stok50kg.harga_satuan" readonly locale="id-ID" prefix="Rp" fluid/>
                    <label for="harga_50kg">Harga Satuan</label>
                </FloatLabel>
                <span class="text-red-500" v-if="pemesananForm.errors['stok50kg.harga_satuan']"> {{ pemesananForm.errors['stok50kg.harga_satuan'] }} </span>
            </div>

            <div>
                <FloatLabel variant="on">
                    <InputNumber id="harga_50kg" v-model="pemesananForm.stok50kg.total_harga" readonly locale="id-ID" prefix="Rp" fluid/>
                    <label for="harga_50kg">Total</label>
                </FloatLabel>
                <span class="text-red-500" v-if="pemesananForm.errors['stok50kg.total_harga']"> {{ pemesananForm.errors['stok50kg.total_harga'] }} </span>
            </div>
        </div>

        <span class="text-red-500" v-if="pemesananForm.errors.jmlh">Jumlah pemesanan wajib diisi</span>

        <!-- total bayar -->
        <div>
            <FloatLabel variant="on">
                <InputNumber mode="decimal" :min="0" id="totalBayar" readonly v-model="totalBayar" locale="id-ID" prefix="Rp" fluid/>
                <label for="totalBayar">Total Bayar</label>
            </FloatLabel>
        </div>

        <!-- tanggal pesan -->
        <div>
            <FloatLabel variant="on">
                <DatePicker :disabled="formType!=='Create'" v-model="pemesananForm.tgl_pemesanan" inputId="on_label" showIcon iconDisplay="input" dateFormat="dd-mm-yy" readonly fluid/>
                <label for="on_label">Tanggal Pemesanan</label>
            </FloatLabel>
            <span class="text-red-500" v-if="pemesananForm.errors.tgl_pemesanan"> {{ pemesananForm.errors.tgl_pemesanan }} </span>
        </div>

        <div>
            <FloatLabel variant="on">
                <Textarea id="on_label" v-model="pemesananForm.catatan" rows="5" cols="30" style="resize: none" fluid />
                <label for="on_label">Catatan</label>
            </FloatLabel>
            <span class="text-red-500" v-if="pemesananForm.errors.catatan"> {{ pemesananForm.errors.catatan }} </span>
        </div>

        <Button :disabled="!pemesananForm.id_beras" @click="submitPesan('Pesan')" label="Pesan Beras" v-if="props.formType==='Create'"/>
        <Button @click="submitPesan('Update')" label="Update" v-if="props.formType==='Edit'"/>
        <Button @click="confirmHapus"  label="Hapus" severity="danger" v-if="props.formType==='Edit'"/>
    </form>

</template>

<style scoped>
</style>

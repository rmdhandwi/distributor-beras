<script setup>
import { useForm } from '@inertiajs/vue3'
import { useConfirm, useToast } from 'primevue'
import { onMounted, ref } from 'vue'

const props = defineProps({
    formType : String,
    dataBeras : Object,
    dataPemesanan : Object,
    dataProdusen : Object,
})

const emit = defineEmits(['refreshPage'])

const confirm = useConfirm()
const toast = useToast()

const selectedBeras = ref(null)

const pemesananForm = useForm({
    id_pemesanan: props.dataPemesanan?.[0]?.id_pemesanan ?? null,
    id_produsen: props.dataPemesanan?.[0]?.id_produsen ?? null,
    id_beras: props.dataPemesanan?.[0]?.id_beras ?? null,
    jmlh: props.dataPemesanan?.[0]?.jmlh ?? null,
    tgl_pemesanan: props.dataPemesanan?.[0]?.tgl_pemesanan ?? null,
    status_pesanan: props.dataPemesanan?.[0]?.status_pesanan ?? null,
    catatan: props.dataPemesanan?.[0]?.catatan ?? null,
})

const selectIdProdusen = () =>
{
    selectedBeras.value = props.dataBeras.find( (beras) => beras.id_beras === pemesananForm.id_beras)

    if (selectedBeras) {
        pemesananForm.id_produsen = selectedBeras.value.id_produsen
    } else {
        pemesananForm.id_produsen = null
    }
}

const submitPesan = Action =>
{
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
                <Select v-model="pemesananForm.id_beras" :options="props.dataBeras" option-label="nama_beras" option-value="id_beras" @change="selectIdProdusen" fluid />
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

        <!-- Jumlah Pemesanan -->
        <div>
            <FloatLabel variant="on">
                <InputNumber mode="decimal" showButtons :min="0" :max="100" id="on_label" v-model="pemesananForm.jmlh" fluid/>
                <label for="on_label">Jumlah Pesan</label>
            </FloatLabel>
            <span class="text-red-500" v-if="pemesananForm.errors.jmlh"> {{ pemesananForm.errors.jmlh }} </span>
        </div>

        <!-- tanggal pesan -->
        <div>
            <FloatLabel variant="on">
                <DatePicker :disabled="formType!=='Create'" v-model="pemesananForm.tgl_pemesanan" inputId="on_label" showIcon iconDisplay="input" dateFormat="dd-mm-yy" fluid/>
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

        <Button @click="submitPesan('Pesan')" label="Pesan Beras" v-if="props.formType==='Create'"/>
        <Button @click="submitPesan('Update')" label="Update" v-if="props.formType==='Edit'"/>
        <Button @click="confirmHapus"  label="Hapus" severity="danger" v-if="props.formType==='Edit'"/>
    </form>

</template>

<style scoped>
</style>

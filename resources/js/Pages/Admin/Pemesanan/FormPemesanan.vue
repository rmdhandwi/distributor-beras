<script setup>
import { useForm } from '@inertiajs/vue3'
import { useConfirm, useToast } from 'primevue'
import { ref } from 'vue'


const props = defineProps({
    formType : null,
    dataBeras : Object,
    dataPemesanan : Object,
    dataProdusen : Object,
})

const emit = defineEmits(['refreshPage'])

const confirm = useConfirm()
const toast = useToast()

const selectedBeras = ref(null)

const pemesananForm = useForm({
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

    console.log(selectedBeras.value)

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

</script>

<template>
    <form @submit.prevent class="flex flex-col gap-4 mt-4" autocomplete="off">
        <!-- Beras -->
        <div>
            <FloatLabel variant="on">
                <Select v-model="pemesananForm.id_beras" :options="props.dataBeras" option-label="nama_beras" option-value="id_beras" @change="selectIdProdusen" fluid />
                <label>Pilih Beras</label>
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
                <DatePicker v-model="pemesananForm.tgl_pemesanan" inputId="on_label" showIcon iconDisplay="input" dateFormat="dd-mm-yy" fluid/>
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
    </form>

</template>

<style scoped>
</style>

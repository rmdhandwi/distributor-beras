<script setup>
import { useForm } from '@inertiajs/vue3'
import { useConfirm, useToast } from 'primevue'

const props = defineProps({
    formType : String,
    dataProdusen : Object,
})

const emit = defineEmits(['refreshPage'])

const confirm = useConfirm()
const toast = useToast()

const berasForm = useForm({
  id_beras: props.dataProdusen?.[0]?.id_beras ?? null,
  nama_beras: props.dataProdusen?.[0]?.nama_beras ?? null,
  id_produsen: props.dataProdusen?.[0]?.id_produsen ?? null,
  jenis_beras: props.dataProdusen?.[0]?.jenis_beras ?? null,
  harga_jual: props.dataProdusen?.[0]?.harga_jual ?? null,
  stok_awal: props.dataProdusen?.[0]?.stok_awal ?? null,
  stok_tersedia: props.dataProdusen?.[0]?.stok_tersedia ?? null,
  tgl_produksi: props.dataProdusen?.[0]?.tgl_produksi ?? null,
  tgl_kadaluarsa: props.dataProdusen?.[0]?.tgl_kadaluarsa ?? null,
  kualitas_beras: props.dataProdusen?.[0]?.kualitas_beras ?? null,
  sertifikat_beras: props.dataProdusen?.[0]?.sertifikat_beras ?? null,
  status_beras: props.dataProdusen?.[0]?.status_beras ?? null,
})

const submitBeras = (Action) =>
{
    const controllerRoute = props.formType === 'Create' ? route('admin.beras.store') : route('admin.beras.update')

    console.log('route : ' + controllerRoute )

    confirm.require({
        message: `Tambah Beras ${berasForm.nama_beras??''}?`,
        header: 'Peringatan',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Batal',
            severity: 'secondary',
            outlined: true,
        },
        acceptProps: {
            label: 'Tambah',
        },
        accept: () => {
            // berasForm.transform((data) => {
            //     const {id_beras,...dataFix } = data
            //     return dataFix
            // }).post(route('admin.beras.store'), {
            //     onError : () => {
            //         toast.add({
            //             severity : 'error',
            //             summary : 'Notifikasi',
            //             detail : 'Terjadi kesalahan',
            //             life : 3000,
            //         })
            //     },
            //     onSuccess : () => {
            //         berasForm.reset()
            //         berasForm.clearErrors()
            //         emit('refreshPage')
            //     }
            // })
        },
    })
}

</script>

<template>
    <div>
    </div>
</template>

<style scoped>
</style>

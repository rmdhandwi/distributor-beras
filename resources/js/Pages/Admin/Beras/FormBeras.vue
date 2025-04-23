<script setup>
import { useForm } from '@inertiajs/vue3'
import { useConfirm, useToast } from 'primevue'

const props = defineProps({
    formType : String,
    dataProdusen : Object,
    dataBeras : Object,
})

const emit = defineEmits(['refreshPage'])

const confirm = useConfirm()
const toast = useToast()

const jenisBeras = [
    { value : 'A', label : 'A' },
    { value : 'B', label : 'B' },
    { value : 'C', label : 'C' },
    { value : 'D', label : 'D' },
]

const berasForm = useForm({
  id_beras: props.dataBeras?.[0]?.id_beras ?? null,
  nama_beras: props.dataBeras?.[0]?.nama_beras ?? null,
  id_produsen: props.dataBeras?.[0]?.id_produsen ?? null,
  jenis_beras: props.dataBeras?.[0]?.jenis_beras ?? null,
  harga_jual: props.dataBeras?.[0]?.harga_jual ?? null,
  stok_awal: props.dataBeras?.[0]?.stok_awal ?? null,
  stok_tersedia: props.dataBeras?.[0]?.stok_tersedia ?? null,
  tgl_produksi: props.dataBeras?.[0]?.tgl_produksi ?? null,
  tgl_kadaluarsa: props.dataBeras?.[0]?.tgl_kadaluarsa ?? null,
  kualitas_beras: props.dataBeras?.[0]?.kualitas_beras ?? null,
  sertifikat_beras: props.dataBeras?.[0]?.sertifikat_beras ?? null,
  status_beras: props.dataBeras?.[0]?.status_beras ?? null,
})

const submitBeras = (Action) =>
{
    confirm.require({
        message: `${Action} Beras ${berasForm.nama_beras??''}?`,
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
                berasForm.transform((data) => {
                    const {id_beras,...dataFix } = data
                    return dataFix
                }).post(route('admin.beras.store'), {
                    onError : () => {
                        toast.add({
                            severity : 'error',
                            summary : 'Notifikasi',
                            detail : 'Terjadi kesalahan',
                            life : 3000,
                        })
                    },
                    onSuccess : () => {
                        berasForm.reset()
                        berasForm.clearErrors()
                        emit('refreshPage')
                    }
                })
            }
            else berasForm.post(route('admin.beras.update'), {
                onError : () => {
                    toast.add({
                        severity : 'error',
                        summary : 'Notifikasi',
                        detail : 'Terjadi kesalahan',
                        life : 3000,
                    })
                },
                onSuccess : () => {
                    berasForm.reset()
                    berasForm.clearErrors()
                    emit('refreshPage')
                }
            })
        },
    })
}

const confirmHapus = () => {
    confirm.require({
        message: 'Hapus Data Beras?',
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
            toast.add({ severity: 'info', summary: 'Notifikasi', detail: 'Menghapus Beras...', life: 2000 });
            berasForm.post(route('admin.beras.destroy'), {
                onError : () => {
                    toast.add({
                        severity : 'error',
                        summary : 'Notifikasi',
                        detail : 'Terjadi kesalahan',
                        life : 3000,
                    })
                },
                onSuccess : () => {
                    berasForm.reset()
                    berasForm.clearErrors()
                    emit('refreshPage')
                }
            })

        },
    });
}

</script>

<template>
    <form @submit.prevent class="flex flex-col gap-4 mt-4" autocomplete="off">
        <!-- Nama Beras -->
        <div>
            <FloatLabel variant="on">
                <InputText id="on_label" v-model="berasForm.nama_beras" fluid/>
                <label for="on_label">Nama Beras</label>
            </FloatLabel>
            <span class="text-red-500" v-if="berasForm.errors.nama_beras"> {{ berasForm.errors.nama_beras }} </span>
        </div>

        <!-- Pilih produsen -->
        <div>
            <FloatLabel variant="on">
                <Select v-model="berasForm.id_produsen" inputId="on_label" :options="props.dataProdusen" optionLabel="nama_produsen" optionValue="id_produsen" fluid/>
                <label for="on_label">Pilih Produsen</label>
            </FloatLabel>
        </div>

        <!-- Jenis Beras -->
        <div>
            <div class="flex flex-wrap gap-4">
                <span>Jenis Beras :</span>
                <div class="flex items-center gap-2" v-for="item in jenisBeras" key="index">
                    <RadioButton v-model="berasForm.jenis_beras" :inputId="item.label" :value="item.value" />
                    <label :for="item.label">{{ item.label }}</label>
                </div>
            </div>
            <span class="text-red-500" v-if="berasForm.errors.jenis_beras"> {{ berasForm.errors.jenis_beras }} </span>
        </div>

        <!-- Harga Beras -->
        <div>
            <FloatLabel variant="on">
                <InputNumber id="on_label" v-model="berasForm.harga_jual" locale="id-ID" prefix="Rp" fluid/>
                <label for="on_label">Harga Jual</label>
            </FloatLabel>
            <span class="text-red-500" v-if="berasForm.errors.harga_jual"> {{ berasForm.errors.harga_jual }} </span>
        </div>

        <!-- Stok Awal -->
        <div>
            <FloatLabel variant="on">
                <InputNumber mode="decimal" showButtons :min="0" :max="100" id="on_label" v-model="berasForm.stok_awal" fluid/>
                <label for="on_label">Stok Awal</label>
            </FloatLabel>
            <span class="text-red-500" v-if="berasForm.errors.stok_awal"> {{ berasForm.errors.stok_awal }} </span>
        </div>

        <!-- Stok Tersedia -->
        <div>
            <FloatLabel variant="on">
                <InputNumber mode="decimal" showButtons :min="0" :max="100" id="on_label" v-model="berasForm.stok_tersedia" fluid/>
                <label for="on_label">Stok Tersedia</label>
            </FloatLabel>
            <span class="text-red-500" v-if="berasForm.errors.stok_tersedia"> {{ berasForm.errors.stok_tersedia }} </span>
        </div>

        <!-- tanggal produksi -->
        <div>
            <FloatLabel variant="on">
                <DatePicker v-model="berasForm.tgl_produksi" inputId="on_label" showIcon iconDisplay="input" dateFormat="dd-mm-yy" fluid/>
                <label for="on_label">Tanggal Produksi</label>
            </FloatLabel>
        </div>

        <!-- tanggal kadaluarsa -->
        <div>
            <FloatLabel variant="on">
                <DatePicker v-model="berasForm.tgl_kadaluarsa" inputId="on_label" showIcon iconDisplay="input" dateFormat="dd-mm-yy" fluid/>
                <label for="on_label">Tanggal Kadaluarsa</label>
            </FloatLabel>
        </div>

        <!-- kualitas Beras -->
        <div>
            <FloatLabel variant="on">
                <InputText id="on_label" v-model="berasForm.kualitas_beras" fluid/>
                <label for="on_label">Kualitas Beras</label>
            </FloatLabel>
            <span class="text-red-500" v-if="berasForm.errors.kualitas_beras"> {{ berasForm.errors.kualitas_beras }} </span>
        </div>

        <!-- sertifikat Beras -->
        <div>
            <FloatLabel variant="on">
                <InputText id="on_label" v-model="berasForm.sertifikat_beras" fluid/>
                <label for="on_label">Sertifikat Beras</label>
            </FloatLabel>
            <span class="text-red-500" v-if="berasForm.errors.sertifikat_beras"> {{ berasForm.errors.sertifikat_beras }} </span>
        </div>

        <Button @click="submitBeras('Tambah')" label="Submit" v-if="props.formType==='Create'"/>
        <Button @click="submitBeras('Update')" label="Update"  v-if="props.formType==='Edit'"/>
        <Button @click="confirmHapus()" label="Hapus Beras" severity="danger"  v-if="props.formType==='Edit'"/>
    </form>

</template>

<style scoped>
</style>

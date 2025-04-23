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

const produsenForm = useForm({
    id_produsen: props.dataProdusen?.[0]?.id_produsen ?? null,
    nama_produsen: props.dataProdusen?.[0]?.nama_produsen ?? null,
    alamat: props.dataProdusen?.[0]?.alamat ?? null,
    no_telp: props.dataProdusen?.[0]?.no_telp ?? null,
    email: props.dataProdusen?.[0]?.email ?? null,
    jenis_beras: props.dataProdusen?.[0]?.jenis_beras ?? null,
    harga_beras: props.dataProdusen?.[0]?.harga_beras ?? null,
    jml_stok: props.dataProdusen?.[0]?.jml_stok ?? null,
})

const jenisBeras = [
    { value : 'A', label : 'A' },
    { value : 'B', label : 'B' },
    { value : 'C', label : 'C' },
    { value : 'D', label : 'D' },
]

const submitProdusen = () =>
{
    confirm.require({
        message: `Tambah Produsen ${produsenForm.nama_produsen??''}?`,
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
            produsenForm.transform((data) => {
                const {id_produsen,...dataFix } = data
                return dataFix
            }).post(route('admin.produsen.store'), {
                onError : () => {
                    toast.add({
                        severity : 'error',
                        summary : 'Notifikasi',
                        detail : 'Terjadi kesalahan',
                        life : 3000,
                    })
                },
                onSuccess : () => {
                    produsenForm.reset()
                    produsenForm.clearErrors()
                    emit('refreshPage')
                }
            })
        },
    })
}

const updateProdusen = () =>
{
    confirm.require({
        message: `Update Data Produsen ${produsenForm.nama_produsen??''}?`,
        header: 'Peringatan',
        icon: 'pi pi-exclamation-triangle',
        rejectProps: {
            label: 'Batal',
            severity: 'secondary',
            outlined: true,
        },
        acceptProps: {
            label: 'Update',
        },
        accept: () => {
            produsenForm.post(route('admin.produsen.update'), {
                onSuccess : () => {
                    produsenForm.reset()
                    produsenForm.clearErrors()
                    emit('refreshPage')
                }
            })
        },
    })
}

const hapusProdusen = () => {
    confirm.require({
        message: 'Hapus Data produsen?',
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
            toast.add({ severity: 'info', summary: 'Notifikasi', detail: 'Menghapus Produsen...', life: 2000 });
            produsenForm.post(route('admin.produsen.destroy'), {
                onError : () => {
                    toast.add({
                        severity : 'error',
                        summary : 'Notifikasi',
                        detail : 'Terjadi kesalahan',
                        life : 3000,
                    })
                },
                onSuccess : () => {
                    produsenForm.reset()
                    produsenForm.clearErrors()
                    emit('refreshPage')
                }
            })

        },
    });
}

</script>

<template>
    <form @submit.prevent class="flex flex-col gap-4 mt-4" autocomplete="off">
        <!-- Nama Produsen -->
        <div>
            <FloatLabel variant="on">
                <InputText id="on_label" v-model="produsenForm.nama_produsen" fluid/>
                <label for="on_label">Nama Produsen</label>
            </FloatLabel>
            <span class="text-red-500" v-if="produsenForm.errors.nama_produsen"> {{ produsenForm.errors.nama_produsen }} </span>
        </div>

        <!-- Alamat -->
        <div>
            <FloatLabel variant="on">
                <InputText id="on_label" v-model="produsenForm.alamat" fluid/>
                <label for="on_label">Alamat</label>
            </FloatLabel>
            <span class="text-red-500" v-if="produsenForm.errors.alamat"> {{ produsenForm.errors.alamat }} </span>
        </div>

        <!-- Telepon -->
        <div>
            <FloatLabel variant="on">
                <InputText id="on_label" v-model="produsenForm.no_telp" fluid/>
                <label for="on_label">No Telepon</label>
            </FloatLabel>
            <span class="text-red-500" v-if="produsenForm.errors.no_telp"> {{ produsenForm.errors.no_telp }} </span>
        </div>

        <!-- Email -->
        <div>
            <FloatLabel variant="on">
                <InputText id="on_label" v-model="produsenForm.email" fluid/>
                <label for="on_label">Email</label>
            </FloatLabel>
            <span class="text-red-500" v-if="produsenForm.errors.email"> {{ produsenForm.errors.email }} </span>
        </div>

        <!-- Jenis Beras -->
        <div>
            <div class="flex flex-wrap gap-4">
                <span>Jenis Beras :</span>
                <div class="flex items-center gap-2" v-for="item in jenisBeras" key="index">
                    <RadioButton v-model="produsenForm.jenis_beras" :inputId="item.label" :value="item.value" />
                    <label :for="item.label">{{ item.label }}</label>
                </div>
            </div>
            <span class="text-red-500" v-if="produsenForm.errors.jenis_beras"> {{ produsenForm.errors.jenis_beras }} </span>
        </div>
        <!-- Harga Beras -->
        <div>
            <FloatLabel variant="on">
                <InputNumber id="on_label" v-model="produsenForm.harga_beras" locale="id-ID" prefix="Rp" fluid/>
                <label for="on_label">Harga Beras</label>
            </FloatLabel>
            <span class="text-red-500" v-if="produsenForm.errors.harga_beras"> {{ produsenForm.errors.harga_beras }} </span>
        </div>

        <!-- Stok Beras -->
        <div>
            <FloatLabel variant="on">
                <InputNumber id="on_label" v-model="produsenForm.jml_stok" fluid/>
                <label for="on_label">Jumlah Stok</label>
            </FloatLabel>
            <span class="text-red-500" v-if="produsenForm.errors.jml_stok"> {{ produsenForm.errors.jml_stok }} </span>
        </div>

        <Button @click="submitProdusen" label="Submit" v-if="props.formType==='Create'"/>
        <Button @click="updateProdusen" label="Update"  v-if="props.formType==='Edit'"/>
        <Button @click="hapusProdusen()" label="Hapus Produsen" severity="danger"  v-if="props.formType==='Edit'"/>

    </form>

</template>

<style scoped>
</style>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { useConfirm } from 'primevue'

const props = defineProps({
    dataProdusen : Object,
})

const confirm = useConfirm()

const editForm = useForm({
    id_produsen: props.dataProdusen[0]?.id_produsen??null,
    nama_produsen: props.dataProdusen[0]?.nama_produsen??null,
    alamat: props.dataProdusen[0]?.alamat??null,
    no_telp: props.dataProdusen[0]?.no_telp??null,
    email: props.dataProdusen[0]?.email??null,
    jenis_beras: props.dataProdusen[0]?.jenis_beras??null,
    harga_beras: props.dataProdusen[0]?.harga_beras??null,
    jml_stok: props.dataProdusen[0]?.jml_stok??null,
})

const jenisBeras = [
    { value : 'A', label : 'A' },
    { value : 'B', label : 'B' },
    { value : 'C', label : 'C' },
    { value : 'D', label : 'D' },
]

const emit = defineEmits(['refreshPage'])

const updateProdusen = () =>
{
    confirm.require({
        message: `Update Data Produsen ${editForm.nama_produsen??''}?`,
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
            editForm.post(route('admin.produsen.update'), {
                onSuccess : () => {
                    editForm.reset()
                    editForm.clearErrors()
                    emit('refreshPage')
                }
            })
        },
    })
}

</script>

<template>
    <form @submit.prevent="updateProdusen" class="flex flex-col gap-4 mt-4" autocomplete="off">
        <!-- Nama Produsen -->
        <div>
            <FloatLabel variant="on">
                <InputText id="on_label" v-model="editForm.nama_produsen"  fluid/>
                <label for="on_label">Nama Produsen</label>
            </FloatLabel>
            <span class="text-red-500" v-if="editForm.errors.nama_produsen"> {{ editForm.errors.nama_produsen }} </span>
        </div>

        <!-- Alamat -->
        <div>
            <FloatLabel variant="on">
                <InputText id="on_label" v-model="editForm.alamat" fluid/>
                <label for="on_label">Alamat</label>
            </FloatLabel>
            <span class="text-red-500" v-if="editForm.errors.alamat"> {{ editForm.errors.alamat }} </span>
        </div>

        <!-- Telepon -->
        <div>
            <FloatLabel variant="on">
                <InputText id="on_label" v-model="editForm.no_telp" fluid/>
                <label for="on_label">No Telepon</label>
            </FloatLabel>
            <span class="text-red-500" v-if="editForm.errors.no_telp"> {{ editForm.errors.no_telp }} </span>
        </div>

        <!-- Email -->
        <div>
            <FloatLabel variant="on">
                <InputText id="on_label" v-model="editForm.email" fluid/>
                <label for="on_label">Email</label>
            </FloatLabel>
            <span class="text-red-500" v-if="editForm.errors.email"> {{ editForm.errors.email }} </span>
        </div>

        <!-- Jenis Beras -->
        <div>
            <div class="flex flex-wrap gap-4">
                <span>Jenis Beras :</span>
                <div class="flex items-center gap-2" v-for="item in jenisBeras" key="index">
                    <RadioButton v-model="editForm.jenis_beras" :inputId="item.label" :value="item.value" />
                    <label :for="item.label">{{ item.label }}</label>
                </div>
            </div>
            <span class="text-red-500" v-if="editForm.errors.jenis_beras"> {{ editForm.errors.jenis_beras }} </span>
        </div>
        <!-- Harga Beras -->
        <div>
            <FloatLabel variant="on">
                <InputNumber id="on_label" v-model="editForm.harga_beras" locale="id-ID" prefix="Rp" fluid/>
                <label for="on_label">Harga Beras</label>
            </FloatLabel>
            <span class="text-red-500" v-if="editForm.errors.harga_beras"> {{ editForm.errors.harga_beras }} </span>
        </div>

        <!-- Stok Beras -->
        <div>
            <FloatLabel variant="on">
                <InputNumber id="on_label" v-model="editForm.jml_stok" fluid/>
                <label for="on_label">Jumlah Stok</label>
            </FloatLabel>
            <span class="text-red-500" v-if="editForm.errors.jml_stok"> {{ editForm.errors.jml_stok }} </span>
        </div>

        <Button @click="updateProdusen" label="Update" />
    </form>

</template>

<style scoped>
</style>

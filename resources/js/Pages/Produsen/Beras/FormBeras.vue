<script setup>
import { onMounted, ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { useConfirm, useToast } from 'primevue'

onMounted(() =>
{
    berasForm.id_produsen = props.dataProdusen[0].id_produsen
})

const props = defineProps({
    formType : String,
    dataProdusen : Object,
    dataBeras : Object,
})

const emit = defineEmits(['refreshPage'])

const confirm = useConfirm()
const toast = useToast()

const previewImg = ref(false)
const lihatSerti = ref(false)

const jenisBeras = [
    { value : 'Putih', label : 'Putih' },
    { value : 'Merah', label : 'Merah' },
    { value : 'Hitam', label : 'Hitam' },
    { value : 'Ketan', label : 'Ketan' },
]

const berasForm = useForm({
  id_beras: props.dataBeras?.[0]?.id_beras ?? null,
  nama_beras: props.dataBeras?.[0]?.nama_beras ?? null,
  id_produsen: null,
  jenis_beras: props.dataBeras?.[0]?.jenis_beras ?? null,
  stok_tersedia: props.dataBeras?.[0]?.stok_tersedia ?? 0,
  tgl_produksi: props.dataBeras?.[0]?.tgl_produksi ?? null,
  kualitas_beras: props.dataBeras?.[0]?.kualitas_beras ?? null,
  sertifikat_beras: props.dataBeras?.[0]?.sertifikat_beras ?? null,
  status_beras: props.dataBeras?.[0]?.status_beras ?? null,
  stok10kg : {
      berat : '10',
      id_detail : props.dataBeras?.[0]?.stok10kg?.id_detail ?? null,
      jumlah : props.dataBeras?.[0]?.stok10kg?.jumlah ?? 0,
      harga  : props.dataBeras?.[0]?.stok10kg?.harga ?? 0
  },
  stok20kg : {
      berat : '20',
      id_detail : props.dataBeras?.[0]?.stok20kg?.id_detail ?? null,
      jumlah : props.dataBeras?.[0]?.stok20kg?.jumlah ?? 0,
      harga : props.dataBeras?.[0]?.stok20kg?.harga ?? 0
  },
  stok50kg : {
      berat : '50',
      id_detail : props.dataBeras?.[0]?.stok50kg?.id_detail ?? null,
      jumlah : props.dataBeras?.[0]?.stok50kg?.jumlah ?? 0,
      harga : props.dataBeras?.[0]?.stok50kg?.harga ?? 0
  },
})

// Fungsi untuk menghitung total stok
const hitungStokTersedia = () => {
  const d = berasForm
  return (
    d.stok10kg.jumlah * Number(d.stok10kg.berat) +
    d.stok20kg.jumlah * Number(d.stok20kg.berat) +
    d.stok50kg.jumlah * Number(d.stok50kg.berat)
  )
}

// Watcher agar stok_tersedia selalu ter-update
watch(
  () => [
    berasForm.stok10kg.jumlah,
    berasForm.stok20kg.jumlah,
    berasForm.stok50kg.jumlah,
  ],
  () => {
    berasForm.stok_tersedia = hitungStokTersedia()
  },
  { immediate: true }
)

const onUpload = (e) =>
{
    berasForm.sertifikat_beras = e.files[0]

    if(berasForm.sertifikat_beras?.size < 1000000)
    {
        berasForm.clearErrors('sertifikat_beras')
        toast.add({ severity: 'info', summary: 'Notifikasi', detail: 'foto terupload!', life: 2000, group : 'tr' })
    }
    else
    {
        berasForm.errors.sertifikat_beras = 'Ukuran File melebihi 1Mb'
        disableSubmit.value = true
    }
    const reader = new FileReader();

    reader.onloadend = async (e) => {
        previewImg.value = e.target.result;
    };

    reader.readAsDataURL(berasForm.sertifikat_beras);
}

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
                }).post(route('produsen.beras.store'), {
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
            else berasForm.post(route('produsen.beras.update'), {
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
            berasForm.post(route('produsen.beras.destroy'), {
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
        <!-- Dialog Sertifikat -->
        <Dialog header="Sertifikat" class="w-fit" v-model:visible="lihatSerti" modal>
            <div class="w-full flex items-center justify-center">
                <div class="size-80 overflow-hidden border">
                    <img :src="previewImg" class="size-full">
                </div>
            </div>
        </Dialog>

        <!-- Dialog Tambah Permohonan -->
        <!-- Nama Beras -->
        <div>
            <FloatLabel variant="on">
                <InputText id="nama_beras" v-model="berasForm.nama_beras" fluid/>
                <label for="nama_beras">Nama Beras</label>
            </FloatLabel>
            <span class="text-red-500" v-if="berasForm.errors.nama_beras"> {{ berasForm.errors.nama_beras }} </span>
        </div>

        <!-- Jenis Beras -->
        <div>
            <div class="flex flex-wrap gap-4">
                <Tag value="Jenis Beras"/>
                <div class="flex items-center gap-2" v-for="item in jenisBeras" key="index">
                    <RadioButton v-model="berasForm.jenis_beras" :inputId="item.label" :value="item.value" />
                    <label :for="item.label">{{ item.label }}</label>
                </div>
            </div>
            <span class="text-red-500" v-if="berasForm.errors.jenis_beras"> {{ berasForm.errors.jenis_beras }} </span>
        </div>

        <!-- 10kg -->
        <div class="flex items-center gap-x-12">
            <Tag value="10kg"/>

            <div>
                <FloatLabel variant="on">
                    <InputNumber mode="decimal" showButtons :min="0" id="jumlah_10kg" v-model="berasForm.stok10kg.jumlah" fluid/>
                    <label for="jumlah_10kg">Jumlah</label>
                </FloatLabel>
                <span class="text-red-500" v-if="berasForm.errors['stok10kg.jumlah']"> {{ berasForm.errors['stok10kg.jumlah'] }} </span>
            </div>

            <div>
                <FloatLabel variant="on">
                    <InputNumber id="harga_10kg" v-model="berasForm.stok10kg.harga" locale="id-ID" prefix="Rp" fluid/>
                    <label for="harga_10kg">Harga Jual</label>
                </FloatLabel>
                <span class="text-red-500" v-if="berasForm.errors['stok10kg.harga']"> {{ berasForm.errors['stok10kg.harga'] }} </span>
            </div>
        </div>

        <!-- 20kg -->
        <div class="flex items-center gap-x-12">
            <Tag value="20kg"/>

            <div>
                <FloatLabel variant="on">
                    <InputNumber mode="decimal" showButtons :min="0" id="jumlah_20kg" v-model="berasForm.stok20kg.jumlah" fluid/>
                    <label for="jumlah_20kg">Jumlah</label>
                </FloatLabel>
                <span class="text-red-500" v-if="berasForm.errors['stok20kg.jumlah']"> {{ berasForm.errors['stok20kg.jumlah'] }} </span>
            </div>

            <div>
                <FloatLabel variant="on">
                    <InputNumber id="harga_20kg" v-model="berasForm.stok20kg.harga" locale="id-ID" prefix="Rp" fluid/>
                    <label for="harga_20kg">Harga Jual</label>
                </FloatLabel>
                <span class="text-red-500" v-if="berasForm.errors['stok20kg.harga']"> {{ berasForm.errors['stok20kg.harga'] }} </span>
            </div>
        </div>

        <!-- 50kg -->
        <div class="flex items-center gap-x-12">
            <Tag value="50kg"/>

            <div>
                <FloatLabel variant="on">
                    <InputNumber mode="decimal" showButtons :min="0" id="jumlah_50kg" v-model="berasForm.stok50kg.jumlah" fluid/>
                    <label for="jumlah_50kg">Jumlah</label>
                </FloatLabel>
                <span class="text-red-500" v-if="berasForm.errors['stok50kg.jumlah']"> {{ berasForm.errors['stok50kg.jumlah'] }} </span>
            </div>

            <div>
                <FloatLabel variant="on">
                    <InputNumber id="harga_50kg" v-model="berasForm.stok50kg.harga" locale="id-ID" prefix="Rp" fluid/>
                    <label for="harga_50kg">Harga Jual</label>
                </FloatLabel>
                <span class="text-red-500" v-if="berasForm.errors['stok50kg.harga']"> {{ berasForm.errors['stok50kg.harga'] }} </span>
            </div>
        </div>

        <!-- Stok Tersedia -->
        <div>
            <FloatLabel variant="on">
                <InputNumber disabled mode="decimal" :min="0" id="stok_tersedia" v-model="berasForm.stok_tersedia" fluid/>
                <label for="stok_tersedia">Stok Tersedia (Kg)</label>
            </FloatLabel>
            <span class="text-red-500" v-if="berasForm.errors.stok_tersedia"> {{ berasForm.errors.stok_tersedia }} </span>
        </div>

        <!-- tanggal produksi -->
        <div>
            <FloatLabel variant="on">
                <DatePicker v-model="berasForm.tgl_produksi" inputId="tgl_produksi" showIcon iconDisplay="input" dateFormat="dd-mm-yy" fluid/>
                <label for="tgl_produksi">Tanggal Produksi</label>
            </FloatLabel>
            <span class="text-red-500" v-if="berasForm.errors.tgl_produksi"> {{ berasForm.errors.tgl_produksi }} </span>
        </div>

        <!-- kualitas Beras -->
        <div>
            <FloatLabel variant="on">
                <InputText id="kualitas_beras" v-model="berasForm.kualitas_beras" fluid/>
                <label for="kualitas_beras">Kualitas Beras</label>
            </FloatLabel>
            <span class="text-red-500" v-if="berasForm.errors.kualitas_beras"> {{ berasForm.errors.kualitas_beras }} </span>
        </div>

        <!-- sertifikat Beras -->
        <div class="mb-10">
            <FileUpload mode="basic" name="demo[]" accept=".jpg,.jpeg,.png"  invalidFileSizeMessage="Ukuran File Melebihi 1Mb" @uploader="onUpload($event)" auto customUpload chooseLabel="Upload Sertifikat Beras" fluid/>
            <span class="text-red-500" v-if="!!berasForm.errors.sertifikat_beras">
                {{ berasForm.errors.sertifikat_beras }}
            </span>
        </div>
        <!-- button lihat bukti -->
        <Button @click="lihatSerti=true" label="Lihat Sertifikat" icon="pi pi-eye" severity="success" v-if="previewImg"/>

        <Button @click="submitBeras('Tambah')" label="Submit" v-if="props.formType==='Create'"/>
        <Button @click="submitBeras('Update')" label="Update"  v-if="props.formType==='Edit'"/>
        <Button @click="confirmHapus()" label="Hapus Beras" severity="danger"  v-if="props.formType==='Edit'"/>
    </form>

</template>

<style scoped>
</style>

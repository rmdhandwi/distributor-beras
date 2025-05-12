import { reactive } from "vue"

export const adminMenu = reactive([
    { label: "Dashboard", icon: "pi pi-home", route: "admin.dashboard" },
    { label: "Produsen", icon: "pi pi-truck", route: "admin.produsen.index" },
    { label: "Beras", icon: "pi pi-box", route: "admin.beras.index" },
    { label: "Gudang", icon: "pi pi-warehouse", route: "admin.gudang.index" },
    { label: "Pemesanan", icon: "pi pi-shopping-cart", route: "admin.pemesanan.index" },
    { label: "Transaksi", icon: "pi pi-arrow-right-arrow-left", route: "admin.transaksi.index" },
]);

export const pemilikMenu = reactive([
    { label: "Dashboard", icon: "pi pi-home", route: "pemilik.dashboard" },
    { label: "Produsen", icon: "pi pi-truck", route: "pemilik.produsen.index" },
]);

export const produsenMenu = reactive([
    { label: "Dashboard", icon: "pi pi-home", route: "produsen.dashboard" },
    { label: "Beras", icon: "pi pi-box", route: "produsen.beras.index" },
    { label: "Pemesanan", icon: "pi pi-shopping-cart", route: "produsen.pemesanan.index" },
    { label: "Transaksi", icon: "pi pi-arrow-right-arrow-left", route: "produsen.transaksi.index" },
]);

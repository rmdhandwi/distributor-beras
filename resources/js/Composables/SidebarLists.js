import { computed, reactive } from "vue";

export const adminMenu = reactive([
    { label: "Dashboard", icon: "pi pi-home", route: "admin.dashboard" },
    { label: "Produsen", icon: "pi pi-truck", route: "admin.produsen" },
    { label: "Transaksi", icon: "pi pi-arrow-right-arrow-left", route: "admin.produsen" },
    { label: "Users", icon: "pi pi-user", route: "admin.users" },
]);

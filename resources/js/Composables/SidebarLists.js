import { computed, reactive } from "vue";

export const adminMenu = reactive([
    { label: "Dashboard", icon: "pi pi-home", route: "admin.dashboard" },
    { label: "Users", icon: "pi pi-user", route: "admin.users" },
]);

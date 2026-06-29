import { Modal } from "bootstrap";
import { nextTick, ref } from "vue";

const modalRef = ref(null);
let modalInstance = null;

export const currencyFormat = (n) => {
    return Number(n).toLocaleString("en-PH", {
        style: "currency",
        currency: "PHP",
    });
};

export const openModal = (modalRef) => {
    nextTick(() => {
        if (!modalRef) return;
        const modalInstance = new Modal(modalRef.value);
        modalInstance.show();
    });
};

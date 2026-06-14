import './bootstrap';
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import Alpine from 'alpinejs';


window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('booking-form');
    const roomSelect = document.getElementById('room_select');
    if (!form || !roomSelect) return;

    const disabledByRoom = JSON.parse(form.dataset.disabled || "{}");
    let fpFrom, fpTo;

    function updateFlatpickr() {
        const roomId = roomSelect.value;
        const ranges = disabledByRoom[roomId] || [];
        
        console.log("Выбран номер:", roomId, "Занятые даты:", ranges); // <-- СМОТРИТЕ ЭТО В КОНСОЛИ

        const config = {
            dateFormat: "Y-m-d",
            minDate: "today",
            disable: ranges
        };

        if (fpFrom) fpFrom.destroy();
        if (fpTo) fpTo.destroy();
        
        fpFrom = flatpickr("#date_from", config);
        fpTo = flatpickr("#date_to", config);
    }

    roomSelect.addEventListener('change', updateFlatpickr);
    updateFlatpickr();
});
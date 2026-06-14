import './bootstrap';
import Alpine from 'alpinejs';
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('booking-form');
    const roomSelect = document.getElementById('room_select');
    if (!form) return;

    const disabledByRoom = JSON.parse(form.dataset.disabled || "{}");
    
    let fpFrom, fpTo;

    function updateFlatpickr() {
        const roomId = roomSelect.value;
        const ranges = disabledByRoom[roomId] || [];

        const config = {
            dateFormat: "Y-m-d",
            minDate: "today",
            disable: ranges, 
            onChange: function(selectedDates, dateStr, instance) {
                if (instance.element.name === "date_from") {
                    fpTo.set("minDate", dateStr);
                }
            }
        };

        if (fpFrom) fpFrom.destroy();
        if (fpTo) fpTo.destroy();
        
        fpFrom = flatpickr("#date_from", config);
        fpTo = flatpickr("#date_to", config);
    }

    roomSelect.addEventListener('change', updateFlatpickr);
    updateFlatpickr();
});
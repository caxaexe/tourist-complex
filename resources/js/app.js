import './bootstrap';
import Alpine from 'alpinejs';
// Обязательно импортируем flatpickr (убедитесь, что он установлен через npm)
import flatpickr from "flatpickr"; 
import "flatpickr/dist/flatpickr.min.css";

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('booking-form');
    
    if (!form) return;

    let disabledRanges = [];
    try {
        disabledRanges = JSON.parse(form.dataset.disabled);
    } catch (e) {
        console.error("Ошибка парсинга дат:", e);
    }

    const config = {
        dateFormat: "Y-m-d",
        minDate: "today",
        disable: disabledRanges 
    };

    flatpickr("input[name='date_from']", config);
    flatpickr("input[name='date_to']", config);
});
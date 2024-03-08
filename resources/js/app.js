import './bootstrap';
import 'sweetalert2/dist/sweetalert2.all.js';
import * as sweetalert2 from "sweetalert2";
import Swal from 'sweetalert2';

function confirmDelete() {
    // utilisation de sweetAlert2 pour afficher une boite de dialogue de confirmation
    event.preventDefault();
    Swal.fire({
        title: 'Êtes-vous sûr?',
        text: "Vous ne pourrez pas revenir en arrière!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Oui, supprimez-le!',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            event.target.submit();
        }
    })

}

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

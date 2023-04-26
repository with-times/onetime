import axios from "axios";
import Swal from "sweetalert2";

const keys = document.getElementById('keys')
const submits = document.getElementById('submit')
submits.addEventListener('click', function () {
    axios.post('/email/verify', {
        keys: keys.value
    }).then(r => {
        Swal.fire({
            icon: 'success',
            text: r.data.message,
        }).then(r => {
            window.location.href = '/';
        })
    }).catch(function (e) {
        Swal.fire({
            icon: 'error',
            text: e.response.data.message,
        }).then(r => {

        })
    })
})

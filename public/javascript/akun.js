// Modal Functions
function openEditModal (idAkun) {
    // Find the clicked row
    const button = document.querySelector(`button[data-id="${idAkun}"]`)
    if (!button) {
        console.error('Edit button not found')
        return
    }

    const row = button.closest('tr')
    if (!row) {
        console.error('Row not found')
        return
    }

    // Get all cells in the row
    const cells = row.querySelectorAll('td')

    // Populate form with existing data
    document.getElementById('editIdAkun').value = cells[0].textContent.trim()
    document.getElementById('editNama').value = cells[1].textContent.trim()
    document.getElementById('editNoHp').value = cells[2].textContent.trim()
    document.getElementById('editEmail').value = cells[3].textContent.trim()

    // Set Peran (1=Owner, 2=Staff)
    const peranText = cells[4].textContent.trim()
    document.getElementById('editPeran').value =
        peranText === 'Owner' ? '1' : '2'

    // Set Status (1=Aktif, 0=Tidak Aktif)
    const statusText = cells[5].textContent.trim()
    document.getElementById('editStatus').value =
        statusText === 'Aktif' ? '1' : '0'

    // Get the form
    const form = document.getElementById('editAkunForm')

    // Set the action directly
    form.action = `/akun/update/${idAkun}`

    // Show modal
    document.getElementById('editModal').classList.remove('hidden')
}

function closeEditModal () {
    document.getElementById('editModal').classList.add('hidden')
}

// Close modal when clicking outside
document.getElementById('editModal')?.addEventListener('click', function (e) {
    if (e.target === this) {
        closeEditModal()
    }
})

// Close modal when clicking outside
document.getElementById('editModal').addEventListener('click', function (e) {
    if (e.target === this) {
        closeEditModal()
    }
})

// Formatting and live validation for No HP
const editNoHp = document.getElementById('editNoHp')

// Create or get the error message element
let editNoHpError = document.getElementById('editNoHpError')
if (!editNoHpError && editNoHp) {
    editNoHpError = document.createElement('div')
    editNoHpError.id = 'editNoHpError'
    editNoHpError.className = 'text-red-500 text-xs mt-1'
    editNoHpError.style.display = 'none'
    editNoHp.parentNode.appendChild(editNoHpError)
}

if (editNoHp) {
    editNoHp.addEventListener('input', function () {
        const value = this.value.trim()
        if (!(value.startsWith('08') || value.startsWith('628'))) {
            this.classList.add('border-red-500')
            editNoHpError.textContent = 'No HP harus dimulai dengan 08 atau 628'
            editNoHpError.style.display = ''
        } else {
            this.classList.remove('border-red-500')
            editNoHpError.textContent = ''
            editNoHpError.style.display = 'none'
        }
    })
}

const submitBtn = document.getElementById('submitData');
    submitBtn.addEventListener('click', function(e) {
        if (rowCount === 0) {
            e.preventDefault();
            alert('Silahkan menambahkan barang terlebih dahulu.')
        }
    })

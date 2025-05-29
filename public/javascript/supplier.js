function openEditModal(idSupplier) {
    // Find the clicked row
    const button = document.querySelector(`button[data-id="${idSupplier}"]`);
    if (!button) {
        console.error('Edit button not found');
        return;
    }

    const row = button.closest('tr');
    if (!row) {
        console.error('Row not found');
        return;
    }

    // Get all cells in the row
    const cells = row.querySelectorAll('td');
    
    // Populate form with existing data
    document.getElementById('editIdSupplier').value = cells[1].textContent.trim();
    document.getElementById('editNama').value = cells[2].textContent.trim();
    document.getElementById('editNoHp').value = cells[3].textContent.trim();
    document.getElementById('editAlamat').value = cells[4].textContent.trim();

    // Set Status (1=Aktif, 0=Tidak Aktif)
    const statusText = cells[4].textContent.trim();
    document.getElementById('editStatus').value = statusText === 'Aktif' ? '1' : '0';

    // Get the form
    const form = document.getElementById('editSupplierForm');
    
    // Set the action directly
    form.action = `/supplier/update/${idSupplier}`;

    // Show modal
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('editModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

// Close modal when clicking outside
document.getElementById('editModal').addEventListener('click', function (e) {
    if (e.target === this) {
        closeEditModal()
    }
})

// Formatting and live validation for No HP
const editNoHp = document.getElementById('editNoHp');

// Create or get the error message element
let noHpError = document.getElementById('editNoHpError');
if (!noHpError && editNoHp) {
    noHpError = document.createElement('div');
    noHpError.id = 'editNoHpError';
    noHpError.className = 'text-red-500 text-xs mt-1';
    noHpError.style.display = 'none';
    editNoHp.parentNode.appendChild(noHpError);
}

if (editNoHp) {
    editNoHp.addEventListener('input', function() {
        const value = this.value.trim();
        if (!(value.startsWith('08') || value.startsWith('628'))) {
            this.classList.add('border-red-500');
            noHpError.textContent = 'No HP harus dimulai dengan 08 atau 628';
            noHpError.style.display = '';
        } else {
            this.classList.remove('border-red-500');
            noHpError.textContent = '';
            noHpError.style.display = 'none';
        }
    });
}
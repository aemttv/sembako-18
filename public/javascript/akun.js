// Modal Functions
    function openEditModal(idAkun) {
        // Find the clicked row's data
        const row = document.querySelector(`tr:has(button[onclick="openEditModal('${idAkun}')"`);
        
        // Populate form with existing data
        document.getElementById('editIdAkun').value = idAkun;
        document.getElementById('editNama').value = row.cells[1].textContent;
        document.getElementById('editNoHp').value = row.cells[2].textContent;
        document.getElementById('editEmail').value = row.cells[3].textContent;
        
        // Set Peran (1=Owner, 2=Staff)
        const peranText = row.cells[4].textContent.trim();
        document.getElementById('editPeran').value = peranText === 'Owner' ? '1' : '2';
        
        // Set Status (1=Aktif, 0=Tidak Aktif)
        const statusText = row.cells[5].textContent.trim();
        document.getElementById('editStatus').value = statusText === 'Aktif' ? '1' : '0';
        
        // Show modal
        document.getElementById('editModal').classList.remove('hidden');
    }
    
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
    
    // Form Submission
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = {
            idAkun: document.getElementById('editIdAkun').value,
            nama: document.getElementById('editNama').value,
            password: document.getElementById('editPassword').value,
            nohp: document.getElementById('editNoHp').value,
            email: document.getElementById('editEmail').value,
            peran: document.getElementById('editPeran').value,
            statusAkun: document.getElementById('editStatus').value
        };
        
        console.log("Data to save:", formData);
        
        // Send data to backend
        // Here you would typically make an AJAX call to your backend
        fetch(`/akun/update/${formData.idAkun}`, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    },
    body: JSON.stringify(formData)
})

        
        // Close modal
        closeEditModal();
        
    });
    
    // Close modal when clicking outside
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });
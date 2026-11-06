
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('patientDetailModal');
    const deleteModal = document.getElementById('patientDeleteModal');

    if (!modal || !deleteModal) return;

    const editLink = document.getElementById('editPatientLink');
    const deleteButton = document.getElementById('deletePatientButton');
    const deleteForm = document.getElementById('patientDeleteForm');

    const genders = {
        male: 'Nam',
        female: 'Nữ',
        other: 'Khác'
    };

    function setText(id, value) {
        document.getElementById(id).textContent = value;
    }

    document.querySelectorAll('[data-patient-modal]').forEach(button => {
        button.addEventListener('click', function () {
            const data = this.dataset;

            setText('modalPatientName', data.name);
            setText('modalPatientId', 'Mã hồ sơ #' + data.id);
            setText('modalAvatar', data.name.trim().charAt(0).toUpperCase());

            setText('modalBirth', data.birth);
            setText('modalGender', genders[data.gender] || 'Chưa cập nhật');
            setText('modalPhone', data.phone);
            setText('modalOwner', data.owner);

            editLink.hidden = !data.editUrl;

            if (data.editUrl) {
                editLink.href = data.editUrl;
            } else {
                editLink.removeAttribute('href');
            }

            deleteButton.hidden = !data.deleteUrl;
            deleteButton.dataset.url = data.deleteUrl || '';
            deleteButton.dataset.name = data.name;

            modal.showModal();
        });
    });

    // Đóng Modal chi tiết
    document.getElementById('closePatientModal')
        .addEventListener('click', () => modal.close());

    document.getElementById('donePatientModal')
        .addEventListener('click', () => modal.close());

    // Mở Modal xác nhận xóa
    deleteButton.addEventListener('click', function () {
        if (!this.dataset.url) return;

        deleteForm.action = this.dataset.url;
        setText('deletePatientName', this.dataset.name);

        modal.close();
        deleteModal.showModal();
    });

    // Hủy xóa và quay về Modal chi tiết
    document.getElementById('cancelDeletePatient')
        .addEventListener('click', function () {
            deleteModal.close();
            modal.showModal();
        });
});

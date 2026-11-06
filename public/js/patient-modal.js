
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('patientDetailModal');
    const deleteModal = document.getElementById('patientDeleteModal');

    if (!modal || !deleteModal) return;

    const editLink = document.getElementById('editPatientLink');
    const pairLink = document.getElementById('pairPatientLink');
    const shareLink = document.getElementById('sharePatientLink');
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

    // Mở Modal xem chi tiết hồ sơ
    document.querySelectorAll('[data-patient-modal]').forEach(button => {
        button.addEventListener('click', function () {
            const data = this.dataset;

            // Hiển thị thông tin người bệnh
            setText('modalPatientName', data.name);
            setText('modalPatientId', 'Mã hồ sơ #' + data.id);
            setText('modalAvatar', data.name.trim().charAt(0).toUpperCase());

            setText('modalBirth', data.birth);
            setText('modalGender', genders[data.gender] || 'Chưa cập nhật');
            setText('modalPhone', data.phone);
            setText('modalOwner', data.owner);

            // Nút chỉnh sửa
            if (editLink) {
                editLink.hidden = !data.editUrl;

                if (data.editUrl) {
                    editLink.href = data.editUrl;
                } else {
                    editLink.removeAttribute('href');
                }
            }

            // Nút gỡ hồ sơ
            if (deleteButton) {
                deleteButton.hidden = !data.deleteUrl;
                deleteButton.dataset.url = data.deleteUrl || '';
                deleteButton.dataset.name = data.name || '';
            }

            // Nút chia sẻ
            if (shareLink) {
                shareLink.hidden = !data.shareUrl;

                if (data.shareUrl) {
                    shareLink.href = data.shareUrl;
                } else {
                    shareLink.removeAttribute('href');
                }
            }

            // Nút ghép thiết bị người bệnh
            if (pairLink) {
                pairLink.hidden = !data.pairUrl;

                if (data.pairUrl) {
                    pairLink.href = data.pairUrl;
                } else {
                    pairLink.removeAttribute('href');
                }
            }

            modal.showModal();
        });
    });

    // Đóng Modal chi tiết
    document.getElementById('closePatientModal')
        .addEventListener('click', () => modal.close());

    document.getElementById('donePatientModal')
        .addEventListener('click', () => modal.close());

    // Mở Modal xác nhận gỡ hồ sơ
    if (deleteButton && deleteForm) {
        deleteButton.addEventListener('click', function () {
            if (!this.dataset.url) return;

            deleteForm.action = this.dataset.url;
            setText('deletePatientName', this.dataset.name);

            modal.close();
            deleteModal.showModal();
        });
    }

    // Hủy thao tác gỡ hồ sơ
    document.getElementById('cancelDeletePatient')
        .addEventListener('click', function () {
            deleteModal.close();
            modal.showModal();
        });
});

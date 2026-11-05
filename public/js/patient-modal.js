
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('patientDetailModal');

    if (!modal) return;

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
            setText('modalPatientId', 'Hồ sơ #' + data.id);
            setText('modalAvatar', data.name.trim().charAt(0).toUpperCase());
            setText('modalRole', data.role);

            setText('modalName', data.name);
            setText('modalBirth', data.birth);
            setText('modalGender', genders[data.gender] || 'Chưa cập nhật');
            setText('modalPhone', data.phone);
            setText('modalOwner', data.owner);

            modal.showModal();
        });
    });

    document.getElementById('closePatientModal')
        .addEventListener('click', () => modal.close());

    document.getElementById('donePatientModal')
        .addEventListener('click', () => modal.close());
});

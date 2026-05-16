@extends('layouts.admin.admin')

@section('title', 'Monitoring Feedback — SkinQuo Admin')

@section('content')
<div class="feedback-monitor-page">
    {{-- TODO [BACKEND]: Replace sample data with @foreach($feedbacks as $feedback) from controller --}}

    <div class="feedback-header-grid">
        <div>
            <h1>Monitoring Feedback</h1>
            <p class="page-description">Pantau semua pesan dari pengguna</p>
        </div>

        <div class="total-feedback-card">
            <div class="total-feedback-icon">
                <i class="bi bi-chat-square-text"></i>
            </div>
            <div class="total-feedback-stats">
                <strong>24</strong>
                <span>Total Feedback</span>
            </div>
        </div>
    </div>

    <section class="feedback-panel card-admin">
        <div class="feedback-toolbar">
            <label class="search-wrapper">
                <i class="bi bi-search"></i>
                <input type="search" placeholder="Cari pesan atau nama..." aria-label="Cari pesan atau nama" />
            </label>

            <div class="filter-actions">
                <select class="input-admin filter-select">
                    <option>Filter Tipe</option>
                    <option>Semua</option>
                    <option>Keluhan</option>
                    <option>Saran</option>
                    <option>Pujian</option>
                </select>
                <button class="btn-primary-admin filter-button">Terapkan</button>
            </div>
        </div>

        <div class="feedback-table-card">
            <table class="feedback-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Pesan</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="Nama">Elena Miller</td>
                        <td data-label="Email">elena.m@example.com</td>
                        <td data-label="Pesan">Mungkin bisa ditambahkan varian untuk serum malam hari yang lebih fokus pada hidrasi mendalam...</td>
                        <td data-label="Tanggal">12 Okt 2023</td>
                        <td data-label="Aksi"><button class="detail-button" data-name="Elena Miller" data-email="elena.m@example.com" data-date="October 24, 2023" data-message="Mungkin bisa ditambahkan varian baru untuk serum malam hari yang lebih fokus pada hidrasi mendalam dan perbaikan skin barrier. Saya sangat menyukai tekstur produk yang sekarang, tapi merasa butuh sesuatu yang sedikit lebih kaya untuk cuaca dingin.">Lihat Detail</button></td>
                    </tr>
                    <tr>
                        <td data-label="Nama">Julian S.</td>
                        <td data-label="Email">j.smith@webmail.id</td>
                        <td data-label="Pesan">Paket yang saya terima sedikit penyok di bagian kemasan...</td>
                        <td data-label="Tanggal">11 Okt 2023</td>
                        <td data-label="Aksi"><button class="detail-button" data-name="Julian S." data-email="j.smith@webmail.id" data-date="October 11, 2023" data-message="Paket yang saya terima sedikit penyok di bagian kemasan, tetapi isi produk masih aman. Mungkin bisa ditingkatkan lapisan pelindung agar pengiriman lebih aman.">Lihat Detail</button></td>
                    </tr>
                    <tr>
                        <td data-label="Nama">Anita Rahma</td>
                        <td data-label="Email">anita.r@global.net</td>
                        <td data-label="Pesan">Apakah produk serum malam bisa dipakai untuk kulit sensitif?</td>
                        <td data-label="Tanggal">11 Okt 2023</td>
                        <td data-label="Aksi"><button class="detail-button" data-name="Anita Rahma" data-email="anita.r@global.net" data-date="October 11, 2023" data-message="Apakah produk serum malam bisa dipakai untuk kulit sensitif? Saya khawatir ada reaksi jika digunakan setiap hari, jadi mohon klarifikasi bahan yang aman.">Lihat Detail</button></td>
                    </tr>
                    <tr>
                        <td data-label="Nama">Kevin Brown</td>
                        <td data-label="Email">kevin.b@mail.com</td>
                        <td data-label="Pesan">Desain website sangat tenang, pertahankan palet warna ini.</td>
                        <td data-label="Tanggal">10 Okt 2023</td>
                        <td data-label="Aksi"><button class="detail-button" data-name="Kevin Brown" data-email="kevin.b@mail.com" data-date="October 10, 2023" data-message="Desain website sangat tenang, pertahankan palet warna ini. Navigasinya juga mudah dipahami.">Lihat Detail</button></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <p>Menampilkan 1-10 dari 1,284 feedback</p>
            <nav class="pagination">
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">3</button>
                <span>...</span>
                <button class="page-btn">128</button>
            </nav>
        </div>
    </section>
</div>

<div id="feedback-detail-modal" class="feedback-modal hidden" role="dialog" aria-modal="true" aria-labelledby="feedbackModalTitle">
    <div class="feedback-modal-backdrop"></div>
    <div class="feedback-modal-card">
        <button type="button" class="close-modal" aria-label="Tutup detail feedback">×</button>
        <div class="feedback-modal-content">
            <div class="modal-profile-panel">
                <div class="modal-avatar"></div>
                <div class="modal-user-info">
                    <strong class="modal-name">Elena Miller</strong>
                    <span class="modal-email">elena.m@example.com</span>
                    <p class="modal-date-label">Date Received</p>
                    <p class="modal-date">October 24, 2023</p>
                </div>
            </div>
            <div class="modal-message-panel">
                <div class="modal-header-row">
                    <h2 id="feedbackModalTitle">Feedback Details</h2>
                </div>
                <span class="modal-message-label">User Message</span>
                <div class="modal-quote">
                    <p id="feedbackDetailMessage">Mungkin bisa ditambahkan varian baru untuk serum malam hari yang lebih fokus pada hidrasi mendalam dan perbaikan skin barrier. Saya sangat menyukai tekstur produk yang sekarang, tapi merasa butuh sesuatu yang sedikit lebih kaya untuk cuaca dingin.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const modal = document.getElementById('feedback-detail-modal');
    const detailButtons = document.querySelectorAll('.detail-button');
    const closeModalButton = document.querySelector('.close-modal');
    const modalName = document.querySelector('.modal-name');
    const modalEmail = document.querySelector('.modal-email');
    const modalDate = document.querySelector('.modal-date');
    const modalMessage = document.getElementById('feedbackDetailMessage');
    const modalAvatar = document.querySelector('.modal-avatar');

    function openModal({ name, email, date, message }) {
        modalName.textContent = name;
        modalEmail.textContent = email;
        modalDate.textContent = date;
        modalMessage.textContent = message;
        modalAvatar.textContent = '';
        modal.classList.remove('hidden');
    }

    function closeModal() {
        modal.classList.add('hidden');
    }

    detailButtons.forEach(button => {
        button.addEventListener('click', () => {
            openModal({
                name: button.dataset.name,
                email: button.dataset.email,
                date: button.dataset.date,
                message: button.dataset.message,
            });
        });
    });

    closeModalButton.addEventListener('click', closeModal);
    document.querySelector('.feedback-modal-backdrop').addEventListener('click', closeModal);
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
</script>
@endpush

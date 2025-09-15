@extends("template")
@section("title", "Tagihan SPP")
@section("page", "Tagihan SPP")
@section("body")
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Daftar Tagihan SPP</h6>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#buatTagihanModal">
                        Buat Tagihan Bulanan
                    </button>
                    <button class="btn btn-success btn-sm" id="bayarMultipleBtn" disabled>
                        Bayar Terpilih
                    </button>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                @if (session("success"))
                    <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
                        {{ session("success") }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session("error"))
                    <div class="alert alert-danger alert-dismissible fade show mx-3" role="alert">
                        {{ session("error") }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($groupedTagihan->count() > 0)
                    <form id="pembayaranForm" method="POST" action="">
                        @csrf
                        <div class="table-responsive p-0">
                            <table class="datatable table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No
                                        </th>
                                        <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                            <div class="form-check">
                                                <input type="checkbox" id="selectAll" class="form-check-input">
                                                <label class="form-check-label" for="selectAll">Pilih</label>
                                            </div>
                                        </th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Kode
                                            Tagihan</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Siswa</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Kelas</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Bulan</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Nominal</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Status</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($groupedTagihan as $index => $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $loop->iteration }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ps-2">
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        @if ($item->status != "lunas")
                                                            <div class="form-check">
                                                                <input type="checkbox" name="tagihan_ids[]"
                                                                    value="{{ $item->id }}"
                                                                    class="form-check-input tagihan-checkbox"
                                                                    data-nominal="{{ $item->tarif->nominal ?? 0 }}"
                                                                    data-bulan="{{ \Carbon\Carbon::createFromFormat("Y-m", $item->bulan)->locale("id")->translatedFormat("M Y") }}"
                                                                    id="tagihan_{{ $item->id }}">
                                                                <label class="form-check-label"
                                                                    for="tagihan_{{ $item->id }}"></label>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        @if (isset($item->is_grouped) && $item->is_grouped)
                                                            <p class="text-xs text-secondary mb-0">
                                                                {{ $item->transaksi->kd_transaksi ?? "-" }}
                                                                <span
                                                                    class="badge badge-sm bg-gradient-info ms-1">Multiple</span>
                                                            </p>
                                                        @else
                                                            <p class="text-xs text-secondary mb-0">
                                                                {{ $item->kode_tagihan ?? "-" }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $item->siswa->nama_siswa ?? "-" }}</h6>
                                                        <p class="text-xs text-secondary mb-0">
                                                            {{ $item->siswa->nisn ?? "-" }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <p class="text-xs text-secondary mb-0">
                                                            Kelas {{ $item->siswa->kelas->tingkat_kelas ?? "-" }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-column="bulan">
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        @if (isset($item->is_grouped) && $item->is_grouped)
                                                            <div class="badge-group">
                                                                @foreach ($item->grouped_items as $groupItem)
                                                                    <span
                                                                        class="badge badge-sm bg-gradient-info bulan-badge">
                                                                        {{ \Carbon\Carbon::createFromFormat("Y-m", $groupItem->bulan)->locale("id")->translatedFormat("M Y") }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        @else
                                                            <span class="badge badge-sm bg-gradient-info bulan-badge">
                                                                {{ \Carbon\Carbon::createFromFormat("Y-m", $item->bulan)->locale("id")->translatedFormat("M Y") }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        @if (isset($item->is_grouped) && $item->is_grouped)
                                                            <h6 class="mb-0 text-sm">Rp
                                                                {{ number_format($item->total_grouped_amount, 0, ",", ".") }}
                                                            </h6>
                                                            <p class="text-xs text-secondary mb-0">
                                                                {{ $item->grouped_items->count() }} bulan
                                                            </p>
                                                        @else
                                                            <h6 class="mb-0 text-sm">Rp
                                                                {{ number_format($item->tarif->nominal ?? 0, 0, ",", ".") }}
                                                            </h6>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        @if ($item->status == "lunas")
                                                            <span class="badge badge-sm bg-gradient-success">Lunas</span>
                                                            @if (isset($item->is_grouped) && $item->is_grouped)
                                                                <small
                                                                    class="text-muted mt-1">{{ $item->grouped_items->count() }}
                                                                    tagihan</small>
                                                            @endif
                                                        @else
                                                            <span class="badge badge-sm bg-gradient-warning">Belum
                                                                Bayar</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        @if ($item->status == "lunas")
                                                            @if (isset($item->is_grouped) && $item->is_grouped)
                                                                <a href="{{ route("tagihan-spp.wali.kwitansi-gabungan", $item->transaksi->kd_transaksi) }}"
                                                                    class="btn btn-sm btn-outline-primary mt-1">
                                                                    Kwitansi
                                                                </a>
                                                            @else
                                                                <a href="{{ route("tagihan-spp.kuitansi", $item->id) }}"
                                                                    class="btn btn-sm btn-outline-primary">Kwitansi</a>
                                                            @endif
                                                        @else
                                                            <a href="{{ route("tagihan-spp.wali.detail", $item->id) }}"
                                                                class="btn btn-sm btn-outline-success">Detail</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary Box for Selected Items -->
                        <div id="summaryBox" class="alert alert-info mx-3 mt-3" style="display: none;">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Total Tagihan Dipilih: <span id="totalSelected">0</span></strong>
                                </div>
                                <div class="col-md-6 text-end">
                                    <strong>Total Pembayaran: Rp <span id="totalAmount">0</span></strong>
                                </div>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="text-center py-5">
                        <h5 class="text-muted">Belum Ada Tagihan</h5>
                        <p class="text-muted">Saat ini tidak ada tagihan SPP</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Buat Tagihan -->
    <div class="modal fade" id="buatTagihanModal" tabindex="-1" aria-labelledby="buatTagihanModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route("tagihan-spp.wali.buat") }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="buatTagihanModalLabel">Buat Tagihan Bulanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="bulan_mulai" class="form-label">Bulan Mulai</label>
                            <input type="month" class="form-control" id="bulan_mulai" name="bulan_mulai" required>
                        </div>
                        <div class="mb-3">
                            <label for="bulan_akhir" class="form-label">Bulan Akhir</label>
                            <input type="month" class="form-control" id="bulan_akhir" name="bulan_akhir" required>
                        </div>
                        <div class="alert alert-info">
                            <small>Anda dapat membuat tagihan untuk beberapa bulan sekaligus. Sistem akan membuat tagihan
                                terpisah per bulan.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Buat Tagihan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Pembayaran -->
    <div class="modal fade" id="konfirmasiPembayaranModal" tabindex="-1"
        aria-labelledby="konfirmasiPembayaranModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="konfirmasiPembayaranModalLabel">Konfirmasi Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Anda akan membayar <strong><span id="konfirmasiJumlah">0</span></strong> tagihan dengan total
                        <strong>Rp <span id="konfirmasiTotal">0</span></strong>
                    </p>
                    <div id="detailTagihan" class="mt-3"></div>
                    <div class="alert alert-warning">
                        <small>Pembayaran akan digabung dalam satu kwitansi, namun setiap bulan akan tetap tercatat terpisah
                            dalam sistem.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="konfirmasiPembayaranBtn">Lanjut
                        Pembayaran</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing checkbox functionality...');

            const selectAllCheckbox = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.tagihan-checkbox');
            const bayarButton = document.getElementById('bayarMultipleBtn');
            const summaryBox = document.getElementById('summaryBox');
            const totalSelectedSpan = document.getElementById('totalSelected');
            const totalAmountSpan = document.getElementById('totalAmount');

            console.log('Elements found:');
            console.log('- Select all checkbox:', selectAllCheckbox);
            console.log('- Individual checkboxes:', checkboxes.length);
            console.log('- Bayar button:', bayarButton);
            console.log('- Summary box:', summaryBox);

            if (!bayarButton) {
                console.error('Bayar button not found!');
                return;
            }

            if (checkboxes.length === 0) {
                console.warn('No individual checkboxes found');
            }

            // Handle select all
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    console.log('Select all clicked:', this.checked);
                    checkboxes.forEach(checkbox => {
                        if (!checkbox.disabled) {
                            checkbox.checked = this.checked;
                        }
                    });
                    updateSummary();
                });
            }

            // Handle individual checkbox changes
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    console.log('Individual checkbox changed:', this.value, this.checked);
                    updateSelectAllStatus();
                    updateSummary();
                });
            });

            function updateSelectAllStatus() {
                const availableCheckboxes = Array.from(checkboxes).filter(cb => !cb.disabled);
                const checkedCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);

                selectAllCheckbox.checked = availableCheckboxes.length > 0 && checkedCheckboxes.length ===
                    availableCheckboxes.length;
                selectAllCheckbox.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length <
                    availableCheckboxes.length;
            }

            function updateSummary() {
                const checkedCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);
                const totalCount = checkedCheckboxes.length;
                const totalAmount = checkedCheckboxes.reduce((sum, cb) => {
                    const nominal = parseInt(cb.dataset.nominal) || 0;
                    return sum + nominal;
                }, 0);

                console.log('UpdateSummary - Count:', totalCount, 'Amount:', totalAmount);

                if (totalCount > 0) {
                    if (summaryBox) summaryBox.style.display = 'block';
                    if (bayarButton) bayarButton.disabled = false;
                    if (totalSelectedSpan) totalSelectedSpan.textContent = totalCount;
                    if (totalAmountSpan) totalAmountSpan.textContent = totalAmount.toLocaleString('id-ID');
                } else {
                    if (summaryBox) summaryBox.style.display = 'none';
                    if (bayarButton) bayarButton.disabled = true;
                }
            }

            // Handle bayar multiple button dengan fallback
            console.log('Setting up bayar button event listener...');
            bayarButton.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Bayar button clicked!');

                const checkedCheckboxes = Array.from(checkboxes).filter(cb => cb.checked);
                console.log('Checked checkboxes:', checkedCheckboxes.length);

                if (checkedCheckboxes.length === 0) {
                    alert('Silakan pilih tagihan yang akan dibayar terlebih dahulu!');
                    return;
                }

                // Show bulan details in confirmation
                const totalCount = checkedCheckboxes.length;
                const totalAmount = checkedCheckboxes.reduce((sum, cb) => {
                    const nominal = parseInt(cb.dataset.nominal) || 0;
                    return sum + nominal;
                }, 0);

                const bulanList = checkedCheckboxes.map(cb => {
                    // First try to get bulan from checkbox data attribute (most reliable)
                    let bulan = cb.dataset.bulan;
                    if (bulan) {
                        console.log('Bulan from data attribute:', bulan);
                        return bulan;
                    }

                    // Fallback to DOM traversal
                    const row = cb.closest('tr');
                    console.log('Row found:', row);
                    let bulanElement = row.querySelector('.bulan-badge');
                    if (!bulanElement) {
                        bulanElement = row.querySelector('td[data-column="bulan"] .badge');
                    }
                    if (!bulanElement) {
                        bulanElement = row.querySelector('td:nth-child(6) .badge');
                    }
                    console.log('Bulan element found:', bulanElement);
                    if (bulanElement) {
                        console.log('Bulan text:', bulanElement.textContent.trim());
                        return bulanElement.textContent.trim();
                    }
                    return 'Unknown';
                }).filter(bulan => bulan !== 'Unknown').join(', ');

                console.log('Final bulan list:', bulanList);
                const confirmMessage =
                    `Anda akan membayar ${totalCount} tagihan SPP untuk bulan:\n${bulanList}\n\nTotal: Rp ${totalAmount.toLocaleString('id-ID')}\n\nLanjutkan ke pembayaran?`;

                if (confirm(confirmMessage)) {
                    console.log('User confirmed payment, submitting form...');
                    const form = document.getElementById('pembayaranForm');
                    if (!form) {
                        console.error('Form not found!');
                        alert('Form tidak ditemukan. Silakan refresh halaman.');
                        return;
                    }
                    form.action = '{{ route("tagihan-spp.wali.bayar-multiple") }}';
                    console.log('Form action set to:', form.action);
                    form.submit();
                }
            });

            // Handle confirmation button
            document.getElementById('konfirmasiPembayaranBtn').addEventListener('click', function() {
                console.log('Confirmation button clicked, submitting form...');
                const form = document.getElementById('pembayaranForm');
                if (!form) {
                    console.error('Form not found!');
                    alert('Form tidak ditemukan. Silakan refresh halaman.');
                    return;
                }
                form.action = '{{ route("tagihan-spp.wali.bayar-multiple") }}';
                console.log('Form action set to:', form.action);
                form.submit();
            });

            // Set minimum date for bulan inputs
            const today = new Date();
            const currentMonth = today.getFullYear() + '-' + String(today.getMonth() + 1).padStart(2, '0');
            document.getElementById('bulan_mulai').min = currentMonth;
            document.getElementById('bulan_akhir').min = currentMonth;

            // Auto-update bulan_akhir when bulan_mulai changes
            document.getElementById('bulan_mulai').addEventListener('change', function() {
                const bulanAkhir = document.getElementById('bulan_akhir');
                bulanAkhir.min = this.value;
                if (bulanAkhir.value && bulanAkhir.value < this.value) {
                    bulanAkhir.value = this.value;
                }
            });
        });
    </script>

    <style>
        /* Custom checkbox styling untuk memastikan checkbox terlihat */
        .form-check-input {
            width: 1.25rem !important;
            height: 1.25rem !important;
            border: 2px solid #dee2e6 !important;
            border-radius: 0.25rem !important;
            background-color: #fff !important;
        }

        .form-check-input:checked {
            background-color: #198754 !important;
            border-color: #198754 !important;
        }

        .form-check-input:focus {
            border-color: #86b7fe !important;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25) !important;
        }

        /* Ensure table checkbox columns have proper width */
        .table th:first-child,
        .table td:first-child {
            width: 60px !important;
            text-align: center !important;
        }

        /* Summary box styling */
        #summaryBox {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Multiple badges styling */
        .badge-group {
            display: flex;
            flex-wrap: wrap;
            gap: 4px;
        }

        .badge-group .badge {
            margin: 2px;
        }
    </style>
@endsection

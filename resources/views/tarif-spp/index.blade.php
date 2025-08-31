@extends("template")
@section("title", "Tarif SPP")
@section("page", "Tarif SPP")
@section("body")
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Daftar Tarif SPP</h6>
                @can('crud-data')
                    <a href="{{ route("tarif-spp.create") }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Tarif SPP
                    </a>
                @endcan
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <!-- Filter Section -->
                <div class="row mb-3 px-3">
                    <div class="col-md-3">
                        <form method="GET" action="{{ route("tarif-spp.index") }}" id="filterForm">
                            <select name="tahun" class="form-select"
                                onchange="document.getElementById('filterForm').submit()">
                                <option value="">Semua Tahun</option>
                                @foreach ($tahunList as $tahun)
                                    <option value="{{ $tahun }}" {{ request("tahun") == $tahun ? "selected" : "" }}>
                                        {{ $tahun }}
                                    </option>
                                @endforeach
                            </select>
                    </div>
                    <div class="col-md-3">
                        <select name="tingkat_kelas" class="form-select"
                            onchange="document.getElementById('filterForm').submit()">
                            <option value="">Semua Tingkat</option>
                            @for ($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}" {{ request("tingkat_kelas") == $i ? "selected" : "" }}>
                                    Kelas {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route("tarif-spp.index") }}" class="btn btn-outline-secondary">
                            <i class="fas fa-undo"></i> Reset
                        </a>
                        </form>
                    </div>
                </div>

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

                @if ($tarifSpp->count() > 0)
                    <div class="table-responsive p-0">
                        <table class="datatable table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tahun
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tingkat
                                        Kelas</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nominal
                                    </th>
                                    @can('crud-data')
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Aksi</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tarifSpp as $index => $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $tarifSpp->firstItem() + $index }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <span
                                                        class="badge badge-sm bg-gradient-primary">{{ $item->tahun }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <span class="badge badge-sm bg-gradient-info">Kelas
                                                        {{ $item->tingkat_kelas }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">Rp
                                                        {{ number_format($item->nominal, 0, ",", ".") }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        @can('crud-data')
                                        <td class="align-middle text-center">
                                            <div class="d-flex justify-content-center">
                                                <a href="{{ route("tarif-spp.edit", $item->id) }}"
                                                    class="btn btn-outline-warning btn-sm me-1">
                                                    <i class="fas fa-edit"></i>Edit
                                                </a>
                                                <form action="{{ route("tarif-spp.destroy", $item->id) }}" method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus tarif SPP ini?')"
                                                    class="d-inline">
                                                    @csrf
                                                    @method("DELETE")
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="fas fa-trash"></i>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        @endcan
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $tarifSpp->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <img src="{{ asset("assets/img/empty-state.svg") }}" alt="No data" class="img-fluid mb-3"
                            style="max-width: 200px;">
                        <h5 class="text-muted">Belum ada data tarif SPP</h5>
                        <p class="text-muted">Klik tombol "Tambah Tarif SPP" untuk menambah data tarif baru.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@extends('template')
@section('title', 'Dashboard')
@section('page', 'Dashboard')
@section('body')
    <div class="col-12">
        <div class="row gy-3">
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card">
                    <span class="mask bg-primary opacity-10 border-radius-lg"></span>
                    <div class="card-body p-3 position-relative">
                        <div class="row">
                            <div class="col-8 text-start">
                                <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                    <i class="ni ni-circle-08 text-dark text-gradient text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                                <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                    1600
                                </h5>
                                <span class="text-white text-sm">Jumlah Siswa</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card">
                    <span class="mask bg-dark opacity-10 border-radius-lg"></span>
                    <div class="card-body p-3 position-relative">
                        <div class="row">
                            <div class="col-8 text-start">
                                <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                    <i class="ni ni-active-40 text-dark text-gradient text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                                <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                    357
                                </h5>
                                <span class="text-white text-sm">Jumlah Guru & Pegawai</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card">
                    <span class="mask bg-primary opacity-10 border-radius-lg"></span>
                    <div class="card-body p-3 position-relative">
                        <div class="row">
                            <div class="col-8 text-start">
                                <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                    <i class="ni ni-circle-08 text-dark text-gradient text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                                <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                    1600
                                </h5>
                                <span class="text-white text-sm">Transaksi Bulan Ini</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
                <div class="card">
                    <span class="mask bg-dark opacity-10 border-radius-lg"></span>
                    <div class="card-body p-3 position-relative">
                        <div class="row">
                            <div class="col-8 text-start">
                                <div class="icon icon-shape bg-white shadow text-center border-radius-2xl">
                                    <i class="ni ni-cart text-dark text-gradient text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                                <h5 class="text-white font-weight-bolder mb-0 mt-3">
                                    2300
                                </h5>
                                <span class="text-white text-sm">Belum Bayar Bulan Ini</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

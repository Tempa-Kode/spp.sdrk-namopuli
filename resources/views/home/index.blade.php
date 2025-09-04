@extends('template-home')
@section('title', 'SPP Online SD RK Namopuli')
@section('body')
<!-- hero part start-->
    <section class="banner_part" style="margin-top: -40px">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-xl-6">
                    <div class="banner_text">
                        <div class="banner_text_iner">
                            <h1>Portal Resmi Pembayaran SPP SD RK Namopuli</h1>
                            <p>Lakukan pembayaran SPP putra-putri Anda dengan cepat, aman, dan tercatat secara digital.
                            </p>
                            <a href="#" class="btn_1"><i class="ti-check-box mr-2"></i> Cek Tagihan</a>
                            <a href="#" class="btn_2"><i class="ti-info-alt mr-2"></i> Pembayaran </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- hero part start-->

    <!-- benefit start-->
    <section class="feature_part">
        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-xl-3 align-self-center">
                    <div class="single_feature_text ">
                        <h2>Mengapa Membayar SPP Secara Online?</h2>
                        <p>Kami memahami kesibukan Anda. Oleh karena itu, kami menyediakan sistem yang membuat segalanya
                            lebih mudah. </p>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="single_feature">
                        <div class="single_feature_part">
                            <span class="single_feature_icon"><i class="ti-check-box"></i></span>
                            <h4>Cepat & Praktis</h4>
                            <p>Tidak perlu lagi datang ke sekolah. Cukup beberapa klik dari ponsel atau komputer,
                                pembayaran selesai dalam hitungan menit.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="single_feature">
                        <div class="single_feature_part">
                            <span class="single_feature_icon"><i class="ti-desktop"></i></span>
                            <h4>Bisa Dari Mana Saja</h4>
                            <p>Bayar SPP dari rumah, kantor, atau bahkan saat dalam perjalanan. Akses 24 jam penuh untuk
                                kemudahan Anda.</p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="single_feature">
                        <div class="single_feature_part single_feature_part_2">
                            <span class="single_service_icon style_icon"><i class="ti-receipt"></i></span>
                            <h4>Bukti Pembayaran Digital</h4>
                            <p>Bukti pembayaran/kuitansi resmi dapat Anda unduh, tercatat rapi dan mudah dilacak.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- benefit part end-->

    <!--::FAQ start::-->
    <section class="special_cource padding_top">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-5">
                    <div class="section_tittle text-center">
                        <p>FAQ</p>
                        <h2 class="line-height-4">Pertanyaan yang Sering Diajukan</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div id="faqAccordion" class="accordion faq-accordion" role="tablist">
                        <div class="card">
                            <div class="card-header" role="tab" id="faq1">
                                <h5 class="mb-0">
                                    <a data-toggle="collapse" href="#collapseFaq1" aria-expanded="true"
                                        aria-controls="collapseFaq1">
                                        <span class="faq-question-icon">Q</span>
                                        <span class="faq-question-text">Bagaimana jika saya salah memasukkan
                                            NISN?</span>
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseFaq1" class="collapse show" role="tabpanel" aria-labelledby="faq1"
                                data-parent="#faqAccordion">
                                <div class="card-body">
                                    <strong>Jawaban:</strong> Sistem tidak akan menemukan data siswa. Mohon pastikan
                                    kembali NISN atau nama lengkap siswa yang Anda masukkan sudah benar sesuai dengan
                                    data yang terdaftar di sekolah.
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" role="tab" id="faq2">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseFaq2"
                                        aria-expanded="false" aria-controls="collapseFaq2">
                                        <span class="faq-question-icon">Q</span>
                                        <span class="faq-question-text">Apa saja pilihan bank untuk metode Virtual
                                            Account?</span>
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseFaq2" class="collapse" role="tabpanel" aria-labelledby="faq2"
                                data-parent="#faqAccordion">
                                <div class="card-body">
                                    <strong>Jawaban:</strong> Kami mendukung pembayaran melalui bank-bank besar di
                                    Indonesia seperti BCA, Mandiri, BNI, BRI, dan lainnya.
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" role="tab" id="faq3">
                                <h5 class="mb-0">
                                    <a class="collapsed" data-toggle="collapse" href="#collapseFaq3"
                                        aria-expanded="false" aria-controls="collapseFaq3">
                                        <span class="faq-question-icon">Q</span>
                                        <span class="faq-question-text">Saya sudah bayar tapi belum dapat bukti,
                                            bagaimana?</span>
                                    </a>
                                </h5>
                            </div>
                            <div id="collapseFaq3" class="collapse" role="tabpanel" aria-labelledby="faq3"
                                data-parent="#faqAccordion">
                                <div class="card-body">
                                    <strong>Jawaban:</strong> Mohon tunggu beberapa menit. Jika bukti pembayaran belum
                                    juga diterima, silakan hubungi bagian Tata Usaha kami dengan menyertakan detail
                                    transaksi Anda.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--::FAQ part end::-->
@endsection

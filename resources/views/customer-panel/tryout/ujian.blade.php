@extends('customer-panel.layout.main')
@section('title', $titlePage)
@section('content')
    <style>
        .button-question-answered {
            background-color: #49ca3f;
            border-color: #49ca3f;
            color: white;
        }

        .button-question-active {
            background-color: #0075B8 !important;
            border-color: #0075B8 !important;
            color: white !important;
        }

        .wrapper-flash-info {
            z-index: 10;
            position: absolute;
            font-size: 0.9em;
            top: 5px;
            left: 5px;
            border-left: 3px solid #99cc33;
            padding: 5px 10px;
            border-radius: 10px;
            color: #99cc33;
            animation: flash 2s ease infinite;
        }

        @keyframes flash {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }

            100% {
                opacity: 1;
            }
        }
    </style>
    <div class="main-content pt-0 hor-content">

        <div class="main-container container-fluid">
            <div class="inner-body">

                <!-- Page Header -->
                <div class="page-header">
                    <div>
                        <h2 class="main-content-title tx-24 mg-b-5">{{ $titlePage }}</h2>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Ujian</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $breadcumb }}</li>
                        </ol>
                    </div>
                    <div class="d-flex">
                        <div class="justify-content-center">
                            <h6 id="timer">
                                Waktu tersisa: <span id="time"></span>
                            </h6>
                        </div>
                    </div>
                </div>
                <!-- End Page Header -->

                <!-- Row -->
                <div class="row sidemenu-height">
                    <div class="col-lg-12">

                        <div class="card custom-card">
                            <div class="card-body" style="position: relative;">
                                <div id="wrapper-flash-info" class="d-none align-items-center gap-1 wrapper-flash-info">
                                    <i class="fa fa-info-circle"></i><span id="flash-info"></span>
                                </div>

                                <div class="" style="position: absolute; top: 10px; right: 10px;">
                                    <a href="javascript:void(0)" class="btn btn-block btn-sm btn-primary btn-web"
                                        data-bs-toggle="sidebar-right" data-bs-target=".sidebar-right">
                                        <i class="fe fe-menu header-icons"></i>
                                        Daftar Soal
                                    </a>
                                </div>

                                <div>
                                    <div style="font-size: 15px;" class="mt-4">
                                        <div class="d-flex gap-1 flex-1">
                                            <div id="question-number" data-no="">
                                                <p class="placeholder-glow"><span class="placeholder col-1"></span></p>
                                            </div>
                                            <div id="question-name" class="flex-1">
                                                <p class="placeholder-glow"><span class="placeholder col-8"></span><span
                                                        class="placeholder col-6"></span><span
                                                        class="placeholder col-7"></span></p>
                                            </div>
                                        </div>
                                        <div id="question-image">
                                            <p class="placeholder-glow"><span class="placeholder col-5"></span></p>
                                        </div>
                                        <form id="formAnswer">
                                            @csrf
                                            <input type="hidden" readonly name="ujian_id"
                                                value="{{ Crypt::encrypt($exam->id) }}">
                                            <input type="hidden" readonly name="kode_soal"
                                                value="{{ Crypt::encrypt($tryoutProduct->kode_soal) }}">
                                            <input type="hidden" readonly name="soal_ujian_id" value="">

                                            <div id="options-list" class="mt-2">
                                                <div class="placeholder-glow">
                                                    <p class="placeholder col-12"></p>
                                                    <p class="placeholder col-12"></p>
                                                    <p class="placeholder col-12"></p>
                                                    <p class="placeholder col-12"></p>
                                                    <p class="placeholder col-12"></p>
                                                </div>
                                            </div>

                                            @error('jawaban')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </form>
                                    </div>
                                    <!-- Navigation Button -->
                                    <div class="row mt-4">
                                        <div class="col mt-2">
                                            <button id="button-prev" class="btn btn-default btn-web1 btn-block" disabled>
                                                <i class="fa fa-angle-left"></i> Sebelumnya
                                            </button>
                                        </div>
                                        <div class="col mt-2">
                                            <button id="button-next" class="btn btn-default btn-web btn-block" disabled>
                                                Selanjutnya <i class="fa fa-angle-right"></i>
                                            </button>
                                        </div>
                                        <div class="col mt-2">
                                            <button id="button-finish"
                                                onclick='swal({
                                                            title: "Selesaikan Ujian",
                                                            text: "Apakah anda ingin menyelesaikan ujian sekarang ?",
                                                            type: "warning",
                                                            showCancelButton: true,
                                                            closeOnConfirm: false,
                                                            confirmButtonText: "Ya",
                                                            cancelButtonText: "Batal",
                                                            showLoaderOnConfirm: true }, function ()
                                                                {
                                                                setTimeout(function(){
                                                                    window.location.href = "{{ route('ujian.simpan-hasil', ['id' => Crypt::encrypt($exam->id)]) }}";
                                                            }, 1000); });'
                                                type="submit" class="btn btn-success btn-block" disabled>
                                                <i class="fa fa-check-circle"></i> Selesai
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Total soal terjawab dan belum terjawab-->
                                <div class="d-flex mt-4 text-normal gap-2 justify-content-between flex-1">
                                    <h6 class="d-flex gap-1">
                                        <span>Terjawab :</span>
                                        <div id="total-answered-questions">
                                            <p class="placeholder-glow"><span class="placeholder col-3"></span></p>
                                        </div>
                                    </h6>

                                    <h6 class="d-flex gap-1">
                                        <span>Belum Terjawab :</span>
                                        <div id="total-unanswered-questions">
                                            <p class="placeholder-glow"><span class="placeholder col-3"></span></p>
                                        </div>
                                    </h6>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- End Row -->
            </div>
        </div>
    </div>
    <!-- Sidebar -->
    <div class="sidebar sidebar-right sidebar-animate">
        <div class="sidebar-icon">
            <a href="#" class="text-end float-end text-dark fs-20" data-bs-toggle="sidebar-right"
                data-bs-target=".sidebar-right"><i class="fe fe-x"></i></a>
        </div>
        <div class="sidebar-body">
            <h5>Daftar Soal</h5>
            <div class="d-flex p-2">
                <div class="box-soal-container">
                    @for ($i = 1; $i <= $totalQuestion; $i++)
                        <button id="button-question-no-{{ $i }}" class="box-soal-web button-question-no"
                            onclick="goToQuestion({{ $i }})">
                            {{ $i }}
                        </button>
                    @endfor
                </div>
            </div>
        </div>
    </div>
    <!-- End Sidebar -->
    <!-- Jquery js-->
    <script src="{{ url('resources/spruha/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script>
        const questionAssetPath = "{{ asset('storage/soal/') }}";
        let questions = <?= $questions->toJson() ?>;
        let savedQuestions = <?= json_encode($savedQuestions) ?>;
        let timeoutFlashInfo = null;
        let currentQuestion = null;

        // Mengambil waktu sekarang dan waktu selesai ujian dari PHP
        const endTime = new Date('{{ $endTime }}').getTime();
        const now = new Date().getTime();

        // Update timer setiap 1 detik
        const countdown = setInterval(() => {
            const currentTime = new Date().getTime();
            const distance = endTime - currentTime;

            // Menghitung menit dan detik
            const hours = Math.floor(distance / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Tampilkan hasil di elemen dengan id="time"
            let newTime = '';
            if (hours > 0) {
                newTime += ` ${hours} jam`;
            }
            if (minutes > 0) {
                newTime += ` ${minutes} menit`;
            }
            if (seconds > 0) {
                newTime += ` ${seconds} detik`;
            }
            document.getElementById('time').textContent = String(newTime).trim();

            // Jika waktu habis, tampilkan pesan dan hentikan timer
            if (distance < 0) {
                clearInterval(countdown);
                document.getElementById('timer').textContent = 'Waktu ujian telah habis !';
                swal({
                    title: "Notifikasi",
                    text: "Waktu ujian telah habis !",
                    type: "error"
                }, function() {
                    setTimeout(function() {
                        window.location.href =
                            "{{ route('ujian.simpan-hasil', ['id' => Crypt::encrypt($exam->id)]) }}";
                    }, 100);
                });

            }
        }, 1000);

        $(document).ready(function() {
            // Check if there is no question
            if (questions.length <= 0) {
                swal({
                    title: "Notifikasi",
                    text: "Belum ada soal tersedia, silahkan tunggu pembaruan !",
                    type: "warning"
                });
            } else {
                if (Object.keys(savedQuestions).length <= 0) {
                    // Activated first question
                    goToQuestion(1);
                } else {
                    // Go to unanswered question
                    let no = 0;
                    for (const question of questions) {
                        no++;
                        if (!savedQuestions[question.id]) {
                            break;
                        }
                    }
                    sendFlashMessage(`Ini adalah soal yang belum anda jawab!`, 'success', 4000);
                    goToQuestion(no);
                }
            }
        });

        function nextChar(c) {
            return String.fromCharCode(c.charCodeAt(0) + 1);
        }

        function goToQuestion(no) {
            if (no <= 0 || no > questions.length) {
                swal({
                    title: "Notifikasi",
                    text: "Tidak ada soal nomor " + no + " !",
                    type: "warning"
                });
                return;
            }

            const indexQuestion = no - 1;
            const question = questions[indexQuestion];
            if (!question) {
                swal({
                    title: "Notifikasi",
                    text: "Soal tidak ditemukan !",
                    type: "warning"
                });
                return;
            }

            const questionNumberContent = getElement('question-number');
            const questionNameContent = getElement('question-name');
            const questionImageContent = getElement('question-image');
            const optionsListContent = getElement('options-list');

            questionNumberContent.setAttribute('data-no', no);
            questionNumberContent.innerHTML = `${no}.`;
            questionNameContent.innerHTML = question.soal;

            // Check if there is image
            let image = null
            if (question.gambar) {
                image = `<img height="300px" width="img img-thumbnail" src="` + questionAssetPath + `/` + question.gambar +
                    `" alt="Gambar Soal No ` + no + `" data-bs-target="#modalImg" data-bs-toggle="modal">`;
                image += `<div class="modal fade" id="modalImg"><div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content modal-content-demo">
                                <div class="modal-header">
                                    <h6 class="modal-title"><i class="fa fa-book"></i>
                                        Gambar Soal
                                    </h6><button aria-label="Close" class="btn-close"
                                        data-bs-dismiss="modal" type="button"></button>
                                </div>
                                <div class="modal-body">
                                    <img src="` + questionAssetPath + `/` + question.gambar + `"
                                        alt="Gambar Soal No ` + no + `">
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-sm ripple btn-danger"
                                        data-bs-dismiss="modal" type="button"><i
                                            class="fa fa-times"></i> Tutup</button>
                                </div>
                            </div>
                        </div></div>`;
            }
            questionImageContent.innerHTML = image;

            const options = [];
            if (question.jawaban_a) {
                options.push(question.jawaban_a);
            }
            if (question.jawaban_b) {
                options.push(question.jawaban_b);
            }
            if (question.jawaban_c) {
                options.push(question.jawaban_c);
            }
            if (question.jawaban_d) {
                options.push(question.jawaban_d);
            }
            if (question.jawaban_e) {
                options.push(question.jawaban_e);
            }

            const userAnswered = savedQuestions[question.id];

            optionsListContent.innerHTML = ""
            let optionName = 'A';
            options.forEach(function(option) {
                const optionDiv = document.createElement('div');
                optionDiv.classList.add('d-flex');
                optionDiv.classList.add('align-items-center');
                optionDiv.classList.add('gap-1');
                optionDiv.classList.add('p-2');

                const optionId = 'option-' + optionName;
                const optionInput = document.createElement('input');
                optionInput.name = 'jawaban'
                optionInput.type = 'radio'
                optionInput.id = optionId
                optionInput.value = optionName
                if (userAnswered === optionName) {
                    optionInput.checked = true
                }

                const optionLabel = document.createElement('label');
                optionLabel.style = "margin-bottom: 0px;"
                optionLabel.htmlFor = optionId
                optionLabel.innerHTML = `<div class="d-flex gap-1">` + String(optionName).toLowerCase() + `. ` +
                    option +
                    `</div`;

                optionDiv.appendChild(optionInput)
                optionDiv.appendChild(optionLabel)
                optionsListContent.appendChild(optionDiv)

                optionName = nextChar(optionName);
            })

            calculateAnswered();

            const questionIdElement = document.getElementsByName('soal_ujian_id')[0];
            if (questionIdElement) {
                questionIdElement.value = question.id;
            }

            // Update Event listener untuk menyimpan jawaban saat opsi radio berubah
            $("input[name='jawaban']").change(function() {
                saveAnswer();
            });

            currentQuestion = {
                no: no,
                id: question.id
            };

            // Update Button Design
            Array.from(document.getElementsByClassName("button-question-no button-question-active")).forEach(
                function(element, index, array) {
                    element.classList.remove('button-question-active');
                }
            );

            const buttonQuestionNo = getElement(`button-question-no-${no}`);
            buttonQuestionNo.classList.add('button-question-active');

            updateButtonNavigation(no);
        }

        function updateButtonNavigation(no) {
            const buttonPrev = getElement('button-prev');
            const buttonNext = getElement('button-next');
            const buttonFinish = getElement('button-finish');

            if (no > 1) {
                buttonPrev.disabled = false;
                buttonPrev.parentElement.classList.remove('d-none');
                buttonPrev.onclick = function() {
                    goToQuestion(no - 1)
                };
            } else {
                buttonPrev.disabled = true;
                buttonPrev.parentElement.classList.add('d-none');
                buttonPrev.onclick = null;
            }
            if (no < questions.length) {
                buttonNext.disabled = false;
                buttonNext.parentElement.classList.remove('d-none');
                buttonNext.onclick = function() {
                    goToQuestion(no + 1)
                };
            } else {
                buttonNext.disabled = true;
                buttonNext.parentElement.classList.add('d-none');
                buttonNext.onclick = null;
            }
            if (no === questions.length) {
                buttonFinish.disabled = false;
                buttonFinish.parentElement.classList.remove('d-none');
            } else {
                buttonFinish.disabled = true;
                buttonFinish.parentElement.classList.add('d-none');
            }
        }

        function calculateAnswered() {
            Object.keys(savedQuestions).forEach(function(questionId) {
                const index = questions.map(function(question) {
                    return String(question.id);
                }).indexOf(String(questionId));
                if (index >= 0) {
                    const buttonQuestionNo = getElement(`button-question-no-${index + 1}`);
                    buttonQuestionNo.classList.add('button-question-answered');
                }

            });

            const totalAnsweredQuestions = getElement('total-answered-questions');
            const totalUnansweredQuestions = getElement('total-unanswered-questions');

            totalAnsweredQuestions.innerHTML = Object.keys(savedQuestions).length
            totalUnansweredQuestions.innerHTML = questions.length - Object.keys(savedQuestions).length
        }

        function getElement(id, isRequired = true) {
            const element = document.getElementById(id);
            if (!element) {
                swal({
                    title: 'Notifikasi!',
                    showCancelButton: !isRequired,
                    confirmButtonText: 'Muat Ulang',
                    cancelButtonText: 'Tutup',
                    text: 'Ada masalah dengan halaman ujian.'
                }, function(confirmed) {
                    if (confirmed) {
                        window.location.reload();
                    }
                })
            }

            return element;
        }

        function saveAnswer(beforeSendCallback = null, successCallback = null) {
            // Cek apakah ada jawaban yang dipilih
            if (!$("input[name='jawaban']:checked").val()) {
                swal({
                    title: "Notifikasi",
                    text: "Pilih salah satu jawaban !",
                    type: "warning"
                });
                return;
            }

            var formData = $('#formAnswer').serialize();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "{{ route('ujian.simpan-jawaban') }}",
                data: formData,
                beforeSend: function() {
                    if (typeof beforeSendCallback === 'function') {
                        beforeSendCallback();
                    }
                },
                success: function(response) {
                    if (response.success) {
                        sendFlashMessage(`Jawaban Soal No. ${currentQuestion.no} berhasil disimpan!`, 'success',
                            4000);

                        // Update terjawab
                        const new_saved_answered = response.data.new_saved_answered
                        savedQuestions[new_saved_answered.question_id] = new_saved_answered.answer
                        calculateAnswered()

                        if (typeof successCallback === 'function') {
                            successCallback();
                        }
                    } else {
                        sendFlashMessage('Gagal menyimpan jawaban!', 'warning', 5000);
                        revertChecked(currentQuestion.id);

                        swal({
                            title: "Notifikasi",
                            text: "Gagal menyimpan jawaban !",
                            type: "error"
                        });
                    }
                },
                error: function(response) {
                    sendFlashMessage('Terjadi kesalahan response data!', 'warning', 5000);
                    revertChecked(currentQuestion.id);

                    swal({
                        title: "Notifikasi",
                        text: "Terjadi kesalahan response data !",
                        type: "error"
                    });
                }
            });
        }

        function sendFlashMessage(message, type = 'warning', durations = 4000) {
            clearTimeout(timeoutFlashInfo);
            const flashInfo = getElement('flash-info');
            flashInfo.innerHTML = message;

            flashInfo.parentElement.classList.remove('d-none');
            flashInfo.parentElement.classList.add('d-flex');

            if (type === 'warning') {
                flashInfo.parentElement.style.color = '#ffcc00';
            } else {
                flashInfo.parentElement.style.color = '#99cc33';
            }

            timeoutFlashInfo = setTimeout(function() {
                flashInfo.parentElement.classList.add('d-none');
                flashInfo.parentElement.classList.remove('d-flex');
            }, durations);
        }

        function revertChecked(currentQuestionId) {
            const previous_saved_answered = savedQuestions[currentQuestionId]
            if (previous_saved_answered) {
                document.getElementById('option-' + previous_saved_answered).checked = true;
            } else {
                document.querySelector("input[name='jawaban']:checked").checked = false;
            }
        }
    </script>
@endsection

@extends('customer-panel.layout.main', ['showSideMenu' => false])
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
            border-left: 3px solid #1ED760;
            background: #FFFFFF;
            padding: 5px 10px;
            border-radius: 10px;
            color: #99cc33;
            animation: flash 2.5s ease infinite;
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
    <div class="d-none" id="is-allowed-refresh" data-allow="false"></div>
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

                                <div class="d-flex flex-wrap gap-2 align-items-center justify-content-center">
                                    <div class="flex-1">
                                        <div class="progress"
                                            style="min-width: 200px;height: 20px !important;font-weight: bold;">
                                            <div id="progress-bar-answered"
                                                class="progress-bar progress-bar-striped progress-bar-animated"
                                                aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"
                                                style="width: 0%;height: 20px !important;">
                                                -
                                            </div>
                                        </div>
                                    </div>
                                    <a href="javascript:void(0)" class="btn btn-sm btn-primary btn-web"
                                        data-bs-toggle="sidebar-right" data-bs-target=".sidebar-right">
                                        <i class="fe fe-menu header-icons"></i>
                                        Daftar Soal
                                    </a>
                                    <button class="btn btn-sm btn-warning" onclick="reportQuestion()" title="Laporkan Soal">
                                        <i class="fe fe-alert-octagon header-icons"></i>
                                    </button>
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
                                            <button id="button-finish" onclick="confirmFinishExam()" type="submit"
                                                class="btn btn-success btn-block" disabled>
                                                <i class="fa fa-check-circle"></i> Selesai
                                            </button>
                                        </div>
                                    </div>
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
                <div id="container-questions-button" class="box-soal-container"></div>
            </div>
        </div>
    </div>
    <!-- End Sidebar -->

    <!-- Preview modal -->
    <div class="modal fade" data-bs-keyboard="false" data-bs-backdrop="static" id="modalReport">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title"><i class="fa fa-info-circle"></i>
                        Laporkan Kendala Masalah Soal !
                    </h6>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body mt-0 pt-0">
                    <div class="form-group">
                        <input type="hidden" required placeholder="ID Produk" class="form-control" name="idProduk"
                            readonly value="{{ $tryoutProduct->id }}" />
                    </div>
                    <div class="form-group">
                        <input type="hidden" required placeholder="ID Soal" class="form-control" name="idSoal"
                            readonly value="" />
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi Permasalahan <span class="text-danger">*</span></label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" autocomplete="off"
                            placeholder="Deskripsikan Permasalahan Soal Ujian" required rows="5"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="screenshot">Screenshot Soal Ujian <span class="text-danger">*</span></label>
                        <input type="file" required name="screenshot" id="screenshot" class="dropify"
                            data-height="100" accept=".jpg,.jpeg,.png" />
                        <small class="text-danger">Hanya boleh bertipe png/jpg/png. Maksimal 2MB</small>
                    </div>

                </div>
                <div class="modal-footer">
                    <button onclick="sendingReportExam()" class="btn btn-md btn-block btn-primary">
                        <i class="fa fa-send"></i> Kirim Laporan
                    </button>
                    <button class="btn btn-md btn-block btn-danger" data-bs-dismiss="modal" type="button">
                        <i class="fa fa-times"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Preview modal -->
    <!-- Jquery js-->
    <script src="{{ url('resources/spruha/assets/plugins/jquery/jquery.min.js') }}"></script>
    <script>
        const examId = '{{ $exam->id }}';
        const questionAssetPath = "{{ asset('storage/') }}";
        let timeoutFlashInfo = null;
        let currentQuestion = null;
        let countdown = null

        // Mengambil waktu sekarang dan waktu selesai ujian dari PHP
        const endTime = new Date('{{ $endTime }}').getTime();
        const now = new Date().getTime();

        $(document).ready(async function() {

            $('#modalReport').on('shown.bs.modal', function() {
                // Enabled refresh or key R on show modal
                enabledRefresh();
            })
            $('#modalReport').on('hide.bs.modal', function() {
                // Disable refresh
                disabledRefresh();
            })

            getQuestion(async function(data) {
                setQuestions(examId, data.questions)
                await mergeSavedQuestions(data.savedQuestions)
                generateButtonNoQuestion(data.totalQuestion);

                init();
            });

            disabledRefresh();

            window.onbeforeunload = function() {
                syncAnswer(null, function() {
                    swal({
                        title: "Notifikasi",
                        text: "Gagal memperbarui data jawaban anda, tidak dapat menyelesaikan Ujian.",
                        type: "error"
                    });
                });

                const isAllow = $('#is-allowed-refresh').attr('data-allow');
                if (isAllow !== 'true') {
                    return "Apakah anda yakin ingin memuat halaman?";
                }
            }

            // Update timer setiap 1 detik
            countdown = setInterval(() => {
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
                        finishExam();
                    });

                }
            }, 1000);
        });


        function enabledRefresh() {
            document.removeEventListener("keydown", preventPageRefresh);
        };

        function disabledRefresh() {
            document.addEventListener("keydown", preventPageRefresh);
        };

        function preventPageRefresh(e) {
            if ((e.which || e.keyCode) == 116 || (e.which || e.keyCode) == 82) e.preventDefault();
        };

        function clearStorageQuestion(id) {
            localStorage.removeItem("questions-" + id);
            localStorage.removeItem("saved-questions-" + id);
        }

        function getQuestions(id) {
            let data = localStorage.getItem("questions-" + id);
            if (data) {
                data = JSON.parse(data);
            }
            return data ?? [];
        }

        function setQuestions(id, data) {
            return localStorage.setItem("questions-" + id, JSON.stringify(data));
        }

        function getSavedQuestions(id) {
            let data = localStorage.getItem("saved-questions-" + id);
            if (data) {
                data = JSON.parse(data);
            }

            return data ?? [];
        }

        function setSavedQuestions(id, data) {
            return localStorage.setItem("saved-questions-" + id, JSON.stringify(data));
        }

        function generateButtonNoQuestion(total) {
            const questionNumberContent = getElement('container-questions-button');
            questionNumberContent.innerHTML = "";
            for (let no = 1; no <= total; no++) {
                const button = document.createElement('button');
                button.classList.add('box-soal-web');
                button.classList.add('button-question-no');
                button.id = `button-question-no-${no}`;
                button.onclick = function() {
                    goToQuestion(no);
                }
                button.innerHTML = no;
                questionNumberContent.appendChild(button);
            }
        }

        function getQuestion(successCallback, beforeSendCallback = null) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "{{ route('ujian.progress.get-question') }}",
                data: {
                    exam_id: '{{ Crypt::encrypt($exam->id) }}',
                    question_code: '{{ Crypt::encrypt($tryoutProduct->kode_soal) }}',
                },
                beforeSend: function() {
                    if (typeof beforeSendCallback === 'function') {
                        beforeSendCallback();
                    }
                },
                success: function(response) {
                    if (response.result === 'success') {
                        successCallback(response.data)
                    } else {
                        swal({
                            title: "Notifikasi",
                            text: response.title,
                            type: "error"
                        });
                    }
                },
                error: function(response) {
                    swal({
                        title: "Notifikasi",
                        text: response?.responseJSON?.title ?? response.statusText,
                        type: "error"
                    });
                }
            });
        }

        function init() {
            const questionsTmp = getQuestions(examId);
            // Check if there is no question
            if (questionsTmp.length <= 0) {
                swal({
                    title: "Notifikasi",
                    text: "Belum ada soal tersedia, silahkan tunggu pembaruan !",
                    type: "warning"
                });
            } else {
                const savedQuestionsTmp = getSavedQuestions(examId);
                if (savedQuestionsTmp.length <= 0) {
                    // Activated first question
                    goToQuestion(1);
                } else {
                    // Go to unanswered question
                    let no = 0;
                    for (const question of questionsTmp) {
                        no++;
                        if (savedQuestionsTmp.findIndex(e => String(e.id) === String(question.id)) === -1) {
                            break;
                        }
                    }
                    sendFlashMessage(`Ini adalah soal yang belum anda jawab!`, 'success', 4000);
                    goToQuestion(no);
                }
            }
        }

        async function mergeSavedQuestions(newData) {
            // Get Last saved question from localstorage
            const savedQuestionsTmp = getSavedQuestions(examId);
            // Get All store local answer
            for (const localData of savedQuestionsTmp) {
                if (localData.store === 'local') {
                    const index = newData.findIndex(e => String(e.id) === String(localData.id));
                    if (index === -1) {
                        newData.push(localData)
                    } else if (newData[index]?.answer != localData.answer) {
                        newData[index] = localData;
                    }
                }
            };

            setSavedQuestions(examId, newData)
        }

        function nextChar(c) {
            return String.fromCharCode(c.charCodeAt(0) + 1);
        }

        function goToQuestion(no) {
            const questions = getQuestions(examId);

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
                    `" alt="Gambar Soal No ` + no + `" title="Gambar Soal No ` + no +
                    `" data-bs-target="#modalImg" data-bs-toggle="modal" loading="eager" />`;
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
                                        alt="Gambar Soal No ` + no + `" title="Gambar Soal No ` + no + `" loading="eager" />
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-sm ripple btn-danger" data-bs-dismiss="modal" type="button">
                                        <i class="fa fa-times"></i> Tutup
                                    </button>
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

            const savedQuestionsTmp = getSavedQuestions(examId);
            const userAnswered = savedQuestionsTmp.find(e => String(e.id) === String(question.id));

            optionsListContent.innerHTML = ""
            let optionName = 'A';
            options.forEach(function(option) {
                const optionDiv = document.createElement('div');
                optionDiv.classList.add('d-flex');
                optionDiv.classList.add('align-items-start');
                optionDiv.classList.add('gap-1');
                optionDiv.classList.add('p-2');

                const optionId = 'option-' + optionName;
                const optionInput = document.createElement('input');
                optionInput.name = 'jawaban'
                optionInput.style = 'margin-top: 5px;';
                optionInput.type = 'radio'
                optionInput.id = optionId
                optionInput.value = optionName
                if (userAnswered?.answer === optionName) {
                    optionInput.checked = true
                }

                const optionLabel = document.createElement('label');
                optionLabel.style = "margin-bottom: 0px;"
                optionLabel.htmlFor = optionId
                optionLabel.innerHTML =
                    `<div class="d-flex gap-1"><div>${String(optionName).toLowerCase()}.</div> <div>${option}</div></div>`;

                optionDiv.appendChild(optionInput)
                optionDiv.appendChild(optionLabel)
                optionsListContent.appendChild(optionDiv)

                optionName = nextChar(optionName);
            })

            const questionIdElement = document.getElementsByName('soal_ujian_id')[0];
            if (questionIdElement) {
                questionIdElement.value = question.id;
            }

            // Update Event listener untuk menyimpan jawaban saat opsi radio berubah
            $("input[name='jawaban']").change(function() {
                saveAnswerLocal();
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

            calculateAnswered();
            updateButtonNavigation(no);

            clearFormReport();
        }

        function updateButtonNavigation(no) {
            const questions = getQuestions(examId);

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
            const questions = getQuestions(examId);

            const savedQuestionsTmp = getSavedQuestions(examId);
            savedQuestionsTmp.forEach(function(savedQuestion) {
                const index = questions.map(function(question) {
                    return String(question.id);
                }).indexOf(String(savedQuestion.id));
                if (index >= 0) {
                    const buttonQuestionNo = getElement(`button-question-no-${index + 1}`);
                    buttonQuestionNo.classList.add('button-question-answered');
                }

            });

            const progressBarAnswered = getElement('progress-bar-answered');
            const percentage = savedQuestionsTmp.length / questions.length * 100;
            progressBarAnswered.setAttribute('aria-valuenow', percentage.toFixed(2));
            progressBarAnswered.style = `width: ${percentage.toFixed(2)}%;height: 20px !important;`;
            progressBarAnswered.innerHTML = `${savedQuestionsTmp.length} / ${questions.length}`;
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

        function saveAnswerLocal(beforeSendCallback = null, successCallback = null) {
            const answer = $("input[name='jawaban']:checked").val();
            const questionId = $("input[name='soal_ujian_id']").val();
            // Cek apakah ada jawaban yang dipilih
            if (!answer) {
                swal({
                    title: "Notifikasi",
                    text: "Pilih salah satu jawaban !",
                    type: "warning"
                });
                return;
            }

            // Update terjawab
            const savedQuestionsTmp = getSavedQuestions(examId);
            const index = savedQuestionsTmp.findIndex(e => String(e.id) === String(questionId))
            const newData = {
                id: questionId,
                answer: answer,
                store: 'local',
            }
            if (index === -1) {
                savedQuestionsTmp.push(newData);
            } else {
                savedQuestionsTmp[index] = newData;
            }
            setSavedQuestions(examId, savedQuestionsTmp);

            calculateAnswered()
        }

        async function syncAnswer(beforeSendCallback = null, errorCallback = null, successCallback = null) {
            // Get Last saved question from localstorage
            const savedQuestionsTmp = getSavedQuestions(examId);

            const syncData = []
            // Get All store local answer
            savedQuestionsTmp.forEach(function(localData, index) {
                if (localData.store === 'local') {
                    syncData.push({
                        question_id: localData.id,
                        answer: localData.answer
                    })

                    savedQuestionsTmp[index] = {
                        ...savedQuestionsTmp[index],
                        store: 'db'
                    };
                }

            });

            if (syncData.length <= 0) {
                if (typeof successCallback === 'function') {
                    successCallback();
                }
                return;
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                url: "{{ route('ujian.progress.sync-answer') }}",
                data: {
                    exam_id: '{{ Crypt::encrypt($exam->id) }}',
                    question_code: '{{ Crypt::encrypt($tryoutProduct->kode_soal) }}',
                    anwers: syncData
                },
                beforeSend: function() {
                    if (typeof beforeSendCallback === 'function') {
                        beforeSendCallback();
                    }
                },
                success: function(response) {
                    if (response.result === 'success') {
                        setSavedQuestions(examId, savedQuestionsTmp);

                        if (typeof successCallback === 'function') {
                            successCallback();
                        }
                    } else {
                        if (typeof errorCallback === 'function') {
                            errorCallback();
                        }
                    }
                },
                error: function(response) {
                    if (typeof errorCallback === 'function') {
                        errorCallback();
                    }
                }
            });
        }

        function confirmFinishExam() {
            swal({
                title: "Selesaikan Ujian",
                text: "Apakah anda ingin menyelesaikan ujian sekarang ?",
                type: "warning",
                showCancelButton: true,
                closeOnConfirm: false,
                confirmButtonText: "Ya",
                cancelButtonText: "Batal",
                showLoaderOnConfirm: true
            }, function() {
                finishExam();
            });
        }

        function finishExam() {
            syncAnswer(null, function() {
                swal({
                    title: "Notifikasi",
                    text: "Gagal memperbarui data jawaban anda, tidak dapat menyelesaikan Ujian.",
                    type: "error"
                });
            }, function() {
                $('#is-allowed-refresh').attr('data-allow', 'true');

                // Clear Local Storage
                clearStorageQuestion(examId);

                window.location.href =
                    "{{ route('ujian.simpan-hasil', ['id' => Crypt::encrypt($exam->id)]) }}";
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
            const savedQuestionsTmp = getSavedQuestions(examId);
            const previous_saved_answered = savedQuestionsTmp.find(e => String(e.id) === String(currentQuestionId))
            if (previous_saved_answered) {
                document.getElementById('option-' + previous_saved_answered.answer).checked = true;
            } else {
                document.querySelector("input[name='jawaban']:checked").checked = false;
            }
        }

        function clearFormReport() {
            document.getElementsByName('idSoal')[0].value = null
            document.getElementsByName('deskripsi')[0].value = null
            $('[name="screenshot"]').parent().find(".dropify-clear").trigger('click');
        }

        function reportQuestion() {
            const questionIdElement = document.getElementsByName('soal_ujian_id')[0];
            const idSoal = document.getElementsByName('idSoal')[0];

            idSoal.value = questionIdElement.value;

            $('#modalReport').modal('show');
        }

        function sendingReportExam() {
            const inputs = {
                idProduk: document.getElementsByName('idProduk')[0].value,
                idSoal: document.getElementsByName('idSoal')[0].value,
                deskripsi: document.getElementsByName('deskripsi')[0].value,
                screenshot: document.getElementsByName('screenshot')[0].files[0]
            };
            if (!inputs.idProduk || !inputs.idSoal) {
                $('#modalReport').modal('hide');
                swal({
                    title: "Notifikasi",
                    text: `Ada masalah saat memuat laporan, silahkan buka kembali.`,
                    type: "info"
                });
                return;
            }

            for (const key in inputs) {
                if (!inputs[key]) {
                    swal({
                        title: "Notifikasi",
                        text: `${key} harus diisi.`,
                        type: "info"
                    });
                    return;
                }
            }

            let formData = new FormData();
            formData.append('idProduk', inputs.idProduk);
            formData.append('idSoal', inputs.idSoal);
            formData.append('deskripsi', inputs.deskripsi);
            formData.append('screenshot', inputs.screenshot);

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('report.send-exam') }}",
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.result === 'success') {
                        swal({
                            title: "Notifikasi",
                            text: response.title,
                            type: "success"
                        });

                        clearFormReport();

                        $('#modalReport').modal('hide');
                    } else {
                        swal({
                            title: "Notifikasi",
                            html: true,
                            text: response.title,
                            type: "error"
                        });
                    }
                },
                error: function(xhr) {
                    let errorMessage = "Laporan kendala gagal dikirim !";

                    const responseJson = xhr?.responseJSON
                    if (responseJson) {
                        if (responseJson.errors) {
                            errorMessage = '';
                            for (let errors of Object.values(responseJson.errors)) {
                                for (let error of errors) {
                                    errorMessage += error + '<br />'
                                }
                            }
                        } else if (responseJson.title) {
                            errorMessage = responseJson.title;
                        }
                    }

                    swal({
                        title: "Notifikasi",
                        html: true,
                        text: errorMessage,
                        type: "error"
                    });
                }
            });
        }
    </script>
@endsection

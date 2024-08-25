<div aria-multiselectable="true" class="accordion" id="accordion" role="tablist">
    {{-- A --}}
    <div class="card">
        <div class="card-header" id="JawabanA" role="tab">
            <a id="header-accordion-web" aria-controls="collapseA" aria-expanded="false" data-bs-toggle="collapse"
                href="#collapseA">Jawaban A</a>
        </div>
        <div aria-labelledby="JawabanA" class="collapse" data-bs-parent="#accordion" id="collapseA" role="tabpanel">
            <div class="card-body">
                <p style="text-align: justify">
                    {!! $jawabanA !!}
                </p>
            </div>
        </div>
    </div>
    {{-- B --}}
    <div class="card">
        <div class="card-header" id="JawabanB" role="tab">
            <a id="header-accordion-web" aria-controls="collapseB" aria-expanded="false" data-bs-toggle="collapse"
                href="#collapseB">Jawaban B</a>
        </div>
        <div aria-labelledby="JawabanB" class="collapse" data-bs-parent="#accordion" id="collapseB" role="tabpanel">
            <div class="card-body">
                <p style="text-align: justify">
                    {!! $jawabanB !!}
                </p>
            </div>
        </div>
    </div>
    {{-- C --}}
    <div class="card">
        <div class="card-header" id="JawabanC" role="tab">
            <a id="header-accordion-web" aria-controls="collapseC" aria-expanded="false" data-bs-toggle="collapse"
                href="#collapseC">Jawaban C</a>
        </div>
        <div aria-labelledby="JawabanC" class="collapse" data-bs-parent="#accordion" id="collapseC" role="tabpanel">
            <div class="card-body">
                <p style="text-align: justify">
                    {!! $jawabanC !!}
                </p>
            </div>
        </div>
    </div>
    {{-- D --}}
    <div class="card">
        <div class="card-header" id="JawabanD" role="tab">
            <a id="header-accordion-web" aria-controls="collapseD" aria-expanded="false" data-bs-toggle="collapse"
                href="#collapseD">Jawaban D</a>
        </div>
        <div aria-labelledby="JawabanD" class="collapse" data-bs-parent="#accordion" id="collapseD" role="tabpanel">
            <div class="card-body">
                <p style="text-align: justify">
                    {!! $jawabanD !!}
                </p>
            </div>
        </div>
    </div>
    {{-- Kunci --}}
    <div class="card">
        <div class="card-header" id="Kunci" role="tab">
            <a id="header-accordion-web" aria-controls="collapseKunci" aria-expanded="false" data-bs-toggle="collapse"
                href="#collapseKunci">Kunci
                Jawaban</a>
        </div>
        <div aria-labelledby="Kunci" class="collapse" data-bs-parent="#accordion" id="collapseKunci" role="tabpanel">
            <div class="card-body">
                <p style="text-align: justify">
                    {!! $kunciJawaban !!}
                </p>
            </div>
        </div>
    </div>
    {{-- ReviewPembahasan --}}
    <div class="card">
        <div class="card-header" id="ReviewPembahasan" role="tab">
            <a id="header-accordion-web" aria-controls="collapseReviewPembahasan" aria-expanded="false"
                data-bs-toggle="collapse" href="#collapseReviewPembahasan">Review
                Pembahasan </a>
        </div>
        <div aria-labelledby="ReviewPembahasan" class="collapse" data-bs-parent="#accordion"
            id="collapseReviewPembahasan" role="tabpanel">
            <div class="card-body">
                <p style="text-align: justify">
                    {!! $reviewPembahasan !!}
                </p>
            </div>
        </div>
    </div>
</div>

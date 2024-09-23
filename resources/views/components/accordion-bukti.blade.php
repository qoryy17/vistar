<div aria-multiselectable="true" class="accordion" id="accordion" role="tablist">
    {{-- Bukti Share --}}
    <div class="card">
        <div class="card-header" id="buktiShare" role="tab">
            <a id="header-accordion-web" aria-controls="collapseBuktiShare" aria-expanded="false"
                data-bs-toggle="collapse" href="#collapseBuktiShare">Bukti Share</a>
        </div>
        <div aria-labelledby="buktiShare" class="collapse" data-bs-parent="#accordion" id="collapseBuktiShare"
            role="tabpanel">
            <div class="card-body">
                <img width="400px" src="{{ asset('storage/share-follow/' . $buktiShare) }}" alt="Bukti Share"
                    title="Bukti Share" loading="lazy" />
            </div>
        </div>
    </div>
    {{-- Bukti Follow --}}
    <div class="card">
        <div class="card-header" id="buktiFollow" role="tab">
            <a id="header-accordion-web" aria-controls="collapseBuktiFollow" aria-expanded="false"
                data-bs-toggle="collapse" href="#collapseBuktiFollow">Bukti Follow</a>
        </div>
        <div aria-labelledby="buktiFollow" class="collapse" data-bs-parent="#accordion" id="collapseBuktiFollow"
            role="tabpanel">
            <div class="card-body">
                <img width="400px" src="{{ asset('storage/share-follow/' . $buktiFollow) }}" alt="Bukti Follow"
                    title="Bukti Follow" loading="lazy" />
            </div>
        </div>
    </div>
</div>

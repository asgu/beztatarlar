@php
    use Modules\Translation\Services\TranslationServiceFacade;

    $activeLang = $translateLang ?? TranslationServiceFacade::getDefaultLocale();
@endphp
<div class="form-group">
    <label>Язык:</label>
    <div class="btn-group" role="group" aria-label="Translations">
        <a class="btn btn-secondary
            {!! $activeLang === 'ru' ? 'active' : '' !!}"
            href="{{ route($route . '.edit', [$model => $id, 'translate-lang' => 'ru'])  }}"
        >Ru</a>
        <a class="btn btn-secondary
            {!! $activeLang === 'tt' ? 'active' : '' !!}"
            href="{{ route($route . '.edit', [$model => $id, 'translate-lang' => 'tt'])  }}"
        >Tt</a>
    </div>
    <hr>
</div>

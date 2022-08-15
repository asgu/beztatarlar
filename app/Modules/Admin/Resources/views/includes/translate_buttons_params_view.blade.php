@php
    use Modules\Translation\Services\TranslationServiceFacade;

    $activeLang = $translateLang ?? TranslationServiceFacade::getDefaultLocale();

    $ttParams = $ruParams = $params;
    $ttParams['translate-lang'] = 'tt';
    $ruParams['translate-lang'] = 'ru';
@endphp
<div class="form-group">
    <label>Язык:</label>
    <div class="btn-group" role="group" aria-label="Translations">
        <a class="btn btn-secondary
            {!! $activeLang === 'ru' ? 'active' : '' !!}"
           href="{{ route($route . '.view', $ruParams)  }}"
        >Ru</a>
        <a class="btn btn-secondary
            {!! $activeLang === 'tt' ? 'active' : '' !!}"
           href="{{ route($route . '.view', $ttParams)  }}"
        >Tt</a>
    </div>
    <hr>
</div>

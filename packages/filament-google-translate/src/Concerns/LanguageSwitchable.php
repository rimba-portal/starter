<?php

declare(strict_types=1);

namespace Bites\GoogleTranslate\Traits;

use Bites\GoogleTranslate\Enums\Language;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;

trait LanguageSwitchable
{
    public function bootHasGoogleTranslate(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_AFTER,
            fn (): string => Blade::render('
                <div class="flex items-center px-3">
                    <div id="google_translate_element" style="display:none;"></div>
                    
                    <x-filament::dropdown placement="bottom-end">
                        <x-slot name="trigger">
                            <x-filament::icon-button 
                                icon="heroicon-o-language" 
                                color="gray"
                                label="Switch Language"
                            />
                        </x-slot>

                        <x-filament::dropdown::list>
                            @foreach('.Language::class.'::cases() as $language)
                                <x-filament::dropdown::list.item 
                                    :icon="$language->getIcon()"
                                    tag="button"
                                    x-on:click="triggerGoogleTranslate(\'{{ $language->value }}\')"
                                >
                                    {{ $language->getLabel() }}
                                </x-filament::dropdown::list.item>
                            @endforeach
                        </x-filament::dropdown::list>
                    </x-filament::dropdown>
                </div>
            ')
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_END,
            fn (): string => '
                <script type="text/javascript">
                    function googleTranslateElementInit() {
                        new google.translate.TranslateElement({
                            pageLanguage: "en",
                            autoDisplay: false
                        }, "google_translate_element");
                    }

                    function triggerGoogleTranslate(langCode) {
                        var selectEl = document.querySelector(".goog-te-combo");
                        if (selectEl) {
                            selectEl.value = langCode;
                            selectEl.dispatchEvent(new Event("change"));
                        }
                    }
                </script>
                <script type="text/javascript" src="//://google.com"></script>
            '
        );
    }
}

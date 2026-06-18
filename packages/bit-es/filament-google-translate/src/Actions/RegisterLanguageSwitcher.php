<?php

declare(strict_types=1);

namespace Bites\GoogleTranslate\Actions;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Bites\GoogleTranslate\Enums\Language;

class RegisterLanguageSwitcher
{
    public function execute(): void
    {
        $languages = Language::googleTranslateLanguages();

        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_AFTER,
            fn() => view('bites::language-switch')
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::SIDEBAR_FOOTER,
            fn(): string => <<<HTML
            <script>
                window.googleTranslateElementInit = function () {
                    new google.translate.TranslateElement({
                        pageLanguage: 'en',
                        layout: google.translate.TranslateElement.InlineLayout.VERTICAL,
                        autoDisplay: false,
                        includedLanguages: '{$languages}'
                    }, 'google_translate_element');
                };

                window.triggerGoogleTranslate = function (langCode) {
                    const select = document.querySelector('.goog-te-combo');

                    if (! select) {
                        console.log('Google Translate select not found');
                        return;
                    }

                    select.value = langCode;
                    select.dispatchEvent(new Event('change', { bubbles: true }));

                };
            </script>

            <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
            HTML
        );
    }
}

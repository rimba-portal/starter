<?php

declare(strict_types=1);

namespace Bites\GoogleTranslate\Enums;

enum Language: string
{
    case English = 'en';
    case Korean = 'ko';
    case Malay = 'ms';
    case Japanese = 'ja';
    case Portuguese = 'pt';
    case Chinese = 'zh-CN';
    case Vietnamese = 'vi';
    case Tagalog = 'tl';

    public function getLabel(): string
    {
        return match ($this) {
            self::English => 'English',
            self::Korean => '한국어 (Korean)',
            self::Malay => 'Bahasa Melayu (Malay)',
            self::Japanese => '日本語 (Japanese)',
            self::Portuguese => 'Português (Portuguese)',
            self::Chinese => '简体中文 (Chinese)',
            self::Vietnamese => 'Tiếng Việt (Vietnamese)',
            self::Tagalog => 'Tagalog (Filipino)',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::English => 'flag-en',
            self::Korean => 'flag-ko',
            self::Malay => 'flag-ms',
            self::Japanese => 'flag-jp',
            self::Portuguese => 'flag-pt',
            self::Chinese => 'flag-zh-CN',
            self::Vietnamese => 'flag-vi',
            self::Tagalog => 'flag-tl',
        };
    }

    public static function googleTranslateLanguages(): string
    {
        return implode(
            ',',
            array_column(self::cases(), 'value')
        );
    }

}

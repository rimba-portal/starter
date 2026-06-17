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
            self::English => 'heroicon-o-globe-alt',
            self::Korean => 'heroicon-o-sparkles',
            self::Malay => 'heroicon-o-moon',
            self::Japanese => 'heroicon-o-sun',
            self::Portuguese => 'heroicon-o-shield-check',
            self::Chinese => 'heroicon-o-star',
            self::Vietnamese => 'heroicon-o-fire',
            self::Tagalog => 'heroicon-o-heart',
        };
    }
}

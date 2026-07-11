<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ContentType;
use App\Models\Menu;
use App\Models\Version;
use Illuminate\Http\RedirectResponse;

class MenuResolver
{
    public function resolve(Menu $menu): RedirectResponse|string|null
    {
        /** @var Version|null $version */
        $version = $menu->activeVersion;

        if (! $version) {
            return null;
        }

        return $this->resolveVersion($version);
    }

    public function resolveVersion(Version $version): RedirectResponse|string|null
    {
        return match (ContentType::from($version->content_type)) {

            ContentType::Route =>
                redirect()->route($version->target),

            ContentType::Url =>
                redirect()->away($version->target),

            ContentType::FilamentPage =>
                redirect()->route($version->target),

            ContentType::FilamentResource =>
                redirect()->route($version->target),

            ContentType::Dashboard =>
                redirect()->route($version->target),

            ContentType::Report =>
                redirect()->route($version->target),

            ContentType::Document,
            ContentType::Folder,
            ContentType::Markdown,
            ContentType::File,
            ContentType::Api,
            ContentType::Video,
            ContentType::Html =>
                $version->target,

        };
    }
}
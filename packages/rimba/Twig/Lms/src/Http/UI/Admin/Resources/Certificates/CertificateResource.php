<?php

namespace Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates;

use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Pages\CreateCertificate;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Pages\EditCertificate;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Pages\ListCertificates;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Pages\ViewCertificate;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Schemas\CertificateForm;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Schemas\CertificateInfolist;
use Rimba\Twig\Lms\Http\UI\Admin\Resources\Certificates\Tables\CertificatesTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Rimba\Twig\Lms\Models\Certificate;
use UnitEnum;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    
    protected static string|UnitEnum|null $navigationGroup = 'Learning';

    protected static ?string $navigationLabel = 'Certificates';

    protected static ?int $navigationSort = 69;

    public static function form(Schema $schema): Schema
    {
        return CertificateForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CertificateInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CertificatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCertificates::route('/'),
            'create' => CreateCertificate::route('/create'),
            'view' => ViewCertificate::route('/{record}'),
            'edit' => EditCertificate::route('/{record}/edit'),
        ];
    }
}

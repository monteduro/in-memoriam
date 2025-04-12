<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PageResource extends Resource
{
    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationLabel = 'Pages';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes();
    }

    public static function form(Form $form): Form
    {
        $keyTraits = config('app.key_traits');

        return $form
            ->schema([
                Section::make('Informazioni Personali')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\DatePicker::make('birth_date')
                            ->required(),
                        Forms\Components\DatePicker::make('death_date')
                            ->required(),
                        Forms\Components\TextInput::make('location')
                            ->required(),
                        Forms\Components\Textarea::make('biography')
                            ->required(),
                    ]),
                Section::make('Key Traits')
                    ->schema([
                        Forms\Components\Builder::make('key_traits')
                            ->blockNumbers(false)
                            ->addActionLabel('+ Add Key Trait')
                            ->hiddenLabel()
                            ->blocks(array_map(function ($trait) {
                                return Forms\Components\Builder\Block::make($trait['key'])
                                    ->schema([
                                        Forms\Components\Hidden::make('key')
                                            ->default($trait['key']),
                                        ...$trait['components']
                                    ])
                                    ->icon($trait['icon'])
                                    ->label($trait['label'])
                                    ->columns(1);
                            }, $keyTraits)),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('birth_date'),
                Tables\Columns\TextColumn::make('death_date'),
                Tables\Columns\TextColumn::make('location'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources\RelationManagers;

use App\Models\FlowerDonation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class FlowersRelationManager extends RelationManager
{
    protected static string $relationship = 'flowers';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('approved')
                    ->default(true),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('flower_type')
                    ->options(collect(config('app.flowers'))->pluck('icon', 'key'))
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('flower_type')
                    ->label('Flower')
                    ->tooltip(function ($state) {
                        $flower = collect(config('app.flowers'))->firstWhere('key', $state);
                        return $flower ? $flower['name'] : $state;
                    })
                    ->formatStateUsing(function ($state) {
                        $flower = collect(config('app.flowers'))->firstWhere('key', $state);
                        return $flower ? $flower['icon'] : $state;
                    }),
                Tables\Columns\TextColumn::make('name')
                    ->label('Donor'),
                Tables\Columns\IconColumn::make('approved')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Manually'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->visible(fn (FlowerDonation $record) => !$record->approved)
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function (FlowerDonation $record) {
                        $record->update(['approved' => true]);
                        }),
                Tables\Actions\Action::make('unapprove')
                    ->label('Unapprove')
                    ->visible(fn (FlowerDonation $record) => $record->approved)
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function (FlowerDonation $record) {
                        $record->update(['approved' => false]);
                    }),
                Tables\Actions\EditAction::make()
                    ->visible(fn (FlowerDonation $record) => $record->user_id === Auth::user()->id),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

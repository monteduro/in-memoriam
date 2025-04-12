<?php

namespace App\Filament\Resources\RelationManagers;

use App\Models\Comment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';
    protected static ?string $title = 'Thoughts and Memories';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('user_id')
                    ->default(Auth::user()->id),
                Forms\Components\TextInput::make('author')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('content')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('content')
            ->columns([
                Tables\Columns\TextColumn::make('author'),
                Tables\Columns\TextColumn::make('content'),
                Tables\Columns\IconColumn::make('approved')
                    ->boolean(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Manually'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->visible(fn (Comment $record) => !$record->approved)
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->action(function (Comment $record) {
                        $record->update(['approved' => true]);
                    }),
                Tables\Actions\Action::make('unapprove')
                    ->label('Unapprove')
                    ->visible(fn (Comment $record) => $record->approved)
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function (Comment $record) {
                        $record->update(['approved' => false]);
                    }),
                Tables\Actions\EditAction::make()
                    ->visible(fn (Comment $record) => $record->user_id === Auth::user()->id),
                //Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Models\MenuItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuItemResource extends Resource
{
    protected static ?string $model = MenuItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Menu';

    protected static ?string $navigationLabel = 'Menu Items';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Product Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('image')
                            ->label('Product Image')
                            ->image()
                            ->directory('menu-items')
                            ->disk('public')
                            ->maxSize(1024)
                            ->visibility('public')
                            ->columnSpanFull(),
                    ])->columns(2),
                Forms\Components\Section::make('Prices')
                    ->description('Add one or more prices. Label is optional (e.g. "12oz", "1 pc"). If no label, only the value will be shown.')
                    ->schema([
                        Forms\Components\Repeater::make('prices')
                            ->schema([
                                Forms\Components\TextInput::make('label')
                                    ->label('Type (optional)')
                                    ->placeholder('e.g. 12oz, 1 pc')
                                    ->maxLength(50),
                                Forms\Components\TextInput::make('value')
                                    ->label('Price')
                                    ->required()
                                    ->placeholder('e.g. 2.100'),
                            ])
                            ->columns(2)
                            ->defaultItems(1)
                            ->addActionLabel('Add another price'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(fn () => asset('assets/imgs/image.png')),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(40)
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('prices')
                    ->formatStateUsing(function (array|string|null $state): string {
                        $arr = is_array($state) ? $state : (is_string($state) ? json_decode($state, true) ?? [] : []);
                        return collect($arr)->map(fn ($p) => ($p['label'] ?? '') ? "{$p['label']}: {$p['value']}" : ($p['value'] ?? ''))->filter()->join(', ') ?: '—';
                    })
                    ->placeholder('—'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
